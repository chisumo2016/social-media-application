<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function  index(User $user)
    {
        return Inertia::render('Profile/View', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
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

        return Redirect::route('profile.edit');
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

       /**Save */
        if ($cover){

            $filePath = $cover->store('avatars/'.$user->id, 'public');
            $user->update(['cover_path' => $filePath]);
            //dd($filePath);
        }

        session('success', 'Cover image has been updated');

        return back()->with('status', 'cover-image-update');

    }
}
