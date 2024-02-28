<?php

namespace App\Http\Controllers;

use App\Http\Enum\GroupUserRole;
use App\Http\Enum\GroupUserStatus;
use App\Http\Requests\InviteUsersRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupUser;
use App\Notifications\InvitationApproved;
use App\Notifications\InvitationInGroup;
use App\Notifications\RequestToJoinGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profile(Group $group)
    {
        $group->load('currentUserGroup');
        return Inertia::render('Group/View', [

            'success' => session('success'),
            'group' => new GroupResource($group)

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $group = Group::create($data);

        /**To Record the group users*/
        $groupUserData = [
            'status' => GroupUserStatus::APPROVED->value,
            'role'   => GroupUserRole::ADMIN->value,
             'user_id' => Auth::id(),
            'group_id' => $group->id,
            'created_by' => Auth::id()
        ];

        $groupUser = GroupUser::create($groupUserData);
        $group->status = $groupUserData['status'];
        $group->role = $groupUserData['role'];

        return response(new GroupResource($group), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }

    public   function updateImage(Request $request, Group $group)
    {
        if (!$group->isAdmin(Auth::id())){
            return  response("You don't have permission to perform this action", 403);
        }

        $data  = $request->validate([
            'cover'     => ['nullable','image'], //, 'mimes:pdf'
            'thumbnail' => ['nullable','image']
        ]);


        $thumbnail = $data['thumbnail'] ?? null;

        /** @var \Illuminate\Http\UploadedFile $cover */
        $cover  = $data['cover'] ?? null;

        $success = '';

        /**Save Cover image*/
        if ($cover){
            /**Delete */
            if ($group->cover_path){
                Storage::disk('public')->delete($group->cover_path);
            }
            /**Save new  */
            $filePath = $cover->store('group-'  .$group->id, 'public');  //'avatars/'.$user->id
            $group->update(['cover_path' => $filePath]);

            $success = 'Your cover image was updated';
            //dd($filePath);
        }

        /**Save Avatar image*/
        if ($thumbnail){
            /**Delete */
            if ($group->thumbnail_path){
                Storage::disk('public')->delete($group->thumbnail_path);
            }

            /**Save new  */
            $filePath = $thumbnail->store('group-'.$group->id, 'public');  //'avatars/'.$user->id
            $group->update(['thumbnail_path' => $filePath]);

            $success = 'Your thumbnail image was updated';
        }

        return back()->with('success', $success);

    }

    public function inviteUser(InviteUsersRequest $request, Group $group)
    {
        $data  = $request->validated();

        $user = $request->user;

        $groupUser = $request->groupUser;

        if ($groupUser){
            /*Pending*/
            $groupUser->delete();
        }

        $hours = 24;
        $token = Str::random(256);

        GroupUser::create([
            'status' => GroupUserStatus::PENDING->value,
            'role' => GroupUserRole::USER->value,
            'token' => $token,
            'token_expire_date' => Carbon::now()->addHours($hours),
            'user_id' => $user->id,
            'group_id' => $group->id,
            'created_by' => Auth::id(),
        ]);

        /**Send Email*/
        $user->notify(new InvitationInGroup($group, $hours, $token));
        return back()->with('success', 'User was invited to join to  group');
    }

    public function approveInvitation(string $token)
    {
        $groupUser = GroupUser::query()
            ->where('token', $token)
            ->first();

        $errorTitle = '';

        if (!$groupUser){
            $errorTitle = 'The link is not valid';

        }else if ($groupUser->token_used || $groupUser->status === GroupUserStatus::APPROVED->value){
            $errorTitle = 'The link is already used';
        }else if($groupUser->token_expire_date < Carbon::now()){
            $errorTitle = 'The link is expired';
        }

        if ($errorTitle){
            return \inertia('Error', compact('errorTitle'));
        }

        $groupUser->status = GroupUserStatus::APPROVED->value;
        $groupUser->token_used = Carbon::now();
        $groupUser->save();

        $adminUser = $groupUser->adminUser;

        //dd($adminUser);

        $adminUser->notify(new InvitationApproved($groupUser->group, $groupUser->user));

        return redirect(route('group.profile', $groupUser->group))
            ->with('success', 'You accepted to join to group "' . $groupUser->group->name . '"');

    }

    public function join(Group $group)
    {
        /**Getting current*/
        $user = \request()->user();

        $status  = GroupUserStatus::APPROVED->value;
        $successMessage = 'You have joined to group "' . $group->name . '"';

        if (!$group->auto_approval) {

            $status  = GroupUserStatus::PENDING->value;

            /*Send email adminUsers**/
                //dd($group->adminUsers);

            Notification::send($group->adminUsers, new RequestToJoinGroup($group, $user));
            $successMessage = 'Your request has been accepted. You will be notified once you will be approved';

        }
        GroupUser::create([
            'status' => $status,
            'role' => GroupUserRole::USER->value,
            'user_id' => $user->id,
            'group_id' => $group->id,
            'created_by' => $user->id,
        ]);

        return back()->with('success', $successMessage);
    }
}

//return \inertia('Error',[
//    'title' => 'The token is not valid'
//]);
