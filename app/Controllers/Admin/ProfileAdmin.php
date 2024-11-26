<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileAdmin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function updatePicture()
    {
        $adminId = 3; // ID Admin yang tetap

        $validation = \Config\Services::validation();
        $validation->setRules([
            'profile_picture' => 'is_image[profile_picture]|max_size[profile_picture,2048]|ext_in[profile_picture,jpg,jpeg,png]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            $newProfilePictureName = $profilePicture->getRandomName();
            $profilePicture->move('uploads/profile', $newProfilePictureName);

            $admin = $this->userModel->find($adminId);
            if (!empty($admin['profile_picture'])) {
                @unlink('uploads/profile/' . $admin['profile_picture']);
            }

            $this->userModel->update($adminId, ['profile_picture' => $newProfilePictureName]);
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function removePicture()
    {
        $adminId = 3; // ID Admin yang tetap

        $admin = $this->userModel->find($adminId);
        if (!empty($admin['profile_picture'])) {
            @unlink('uploads/profile/' . $admin['profile_picture']);
            $this->userModel->update($adminId, ['profile_picture' => null]);
        }

        return redirect()->back()->with('success', 'Profile picture removed successfully.');
    }
}
