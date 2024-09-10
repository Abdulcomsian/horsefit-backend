<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Jobs\UserRegisterEmailToUserJob;
use App\Jobs\UserRegisterEmailToAdmin;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Imagick\Driver;
class UserService
{
    public function store(array $userData)
    {
        try {
            DB::beginTransaction();
            $password = Str::random(8);
            $user = User::create(array_merge($userData, ['password' => Hash::make($password)]));
            $user->assignRole($userData['role_id']);
            DB::commit();

            $emailTemplateKeyUser = 'New user registration user';
            if ($emailTemplateKeyUser) {
                $emailTemplate = EmailTemplate::where('key', $emailTemplateKeyUser)->first();
                if ($emailTemplate && $emailTemplate->status) {
                    UserRegisterEmailToUserJob::dispatch($user, $emailTemplate, $password)->delay(now()->addSeconds(10));
                }
            }
            return new UserResource($user);

        }
        catch (Exception $e) {

            DB::rollBack();
            throw $e;
        }  

    } 

    public function update(array $userData, User $user)
    {
        try {
    
            DB::beginTransaction();
                $user->update($userData);
                $user->syncRoles($userData['role_id']);
            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    public function updateProfile(array $userData, User $user)
    {
        try {
            DB::beginTransaction();
                $user->update($userData);

                if (isset($userData['photo']) && is_a($userData['photo'], \Illuminate\Http\UploadedFile::class)) {
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read(file_get_contents($userData['photo']));
                    $image->resize(200, 200);
                    $image->save('img/user_photo.png');
                    $imagePath = public_path('img/user_photo.png');
                    if (file_exists($imagePath)) {
                        $imageName = Str::slug($user->name) .'_' . time() . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
                        $storedImagePath = Storage::disk('public')->putFileAs('profile-photos', $imagePath, $imageName);
                        unlink($imagePath);
                        $user->update(['profile_photo_path' => $storedImagePath]);
                    }
                    //! This method can also be used. both will do same
                    // $user->updateProfilePhoto($userData['photo']);
                }
            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(User $user)
    {
        try {

            DB::beginTransaction();
              User::find($user->id)->delete();
            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            throw $e;
        }

    }
}
