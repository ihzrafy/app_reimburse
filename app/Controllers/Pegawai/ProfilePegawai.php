<?php
namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;

class ProfilePegawai extends BaseController
{
    protected $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    public function updatePicture()
    {
        $employeeId = session()->get('employee_id');

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

            $employee = $this->employeeModel->find($employeeId);
            if (!empty($employee['profile_picture'])) {
                @unlink('uploads/profile/' . $employee['profile_picture']);
            }

            $this->employeeModel->update($employeeId, ['profile_picture' => $newProfilePictureName]);
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function removePicture()
    {
        $employeeId = session()->get('employee_id');

        $employee = $this->employeeModel->find($employeeId);
        if (!empty($employee['profile_picture'])) {
            @unlink('uploads/profile/' . $employee['profile_picture']);
            $this->employeeModel->update($employeeId, ['profile_picture' => null]);
        }

        return redirect()->back()->with('success', 'Profile picture removed successfully.');
    }
}
