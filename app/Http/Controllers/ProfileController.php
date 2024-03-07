<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function  index(User $user)
    {

        $isCurrentUserFollower = false;
        if (!Auth::guest()){
            $isCurrentUserFollower = Follower::where('user_id', $user->id)->where('follower_id', Auth::id())->exists();

        }
        $followerCount = Follower::where('user_id', $user->id)->count();
        //dd($followerCount);

        return Inertia::render('Profile/View', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'success' => session('success'),
            'isCurrentUserFollower' =>$isCurrentUserFollower,
            'followerCount' =>$followerCount,
            'user' => new UserResource($user)
            //'user' => $user
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
       // dd($request->validated());
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile', $request->user())->with('success', 'Your profile details were updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public   function updateImage(Request $request)
    {
        $data  = $request->validate([
            'cover'  => ['nullable','image'], //, 'mimes:pdf'
            'avatar' => ['nullable','image']
        ]);

        $user = $request->user();

       $avatar = $data['avatar'] ?? null;

       /** @var \Illuminate\Http\UploadedFile $cover */
       $cover  = $data['cover'] ?? null;

       $success = '';

       /**Save Cover image*/
        if ($cover){
                 /**Delete */
            if ($user->cover_path){
                Storage::disk('public')->delete($user->cover_path);
            }
            /**Save new  */
            $filePath = $cover->store('user-'.$user->id, 'public');  //'avatars/'.$user->id
            $user->update(['cover_path' => $filePath]);

            $success = 'Your cover image was updated';
            //dd($filePath);
        }

        /**Save Avatar image*/
        if ($avatar){
            /**Delete */
            if ($user->avatar_path){
                Storage::disk('public')->delete($user->avatar_path);
            }
            /**Save new  */
            $filePath = $avatar->store('user-'.$user->id, 'public');  //'avatars/'.$user->id
            $user->update(['avatar_path' => $filePath]);

            $success = 'Your avatar image was updated';
        }

//        session('success', 'Cover image has been updated');

        return back()->with('success', $success);

    }
}
