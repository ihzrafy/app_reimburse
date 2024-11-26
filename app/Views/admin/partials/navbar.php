<?php
use App\Models\UserModel;

// Ambil data admin berdasarkan ID (3)
$userModel = new UserModel();
$admin = $userModel->find(3);
?>

<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="/admin/dashboard" class="logo d-flex align-items-center">
            <img src="/assets/img/reimbursement-logo.jpg" alt="Logo">
            <span class="d-none d-lg-block">Reimbursement</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <!-- Display Profile Picture -->
                    <img src="<?= !empty($admin['profile_picture']) 
                                ? base_url('uploads/profile/' . $admin['profile_picture']) 
                                : '/assets/img/default-pp.jpeg' ?>" 
                         alt="Profile" 
                         class="rounded-circle" 
                         style="width: 40px; height: 40px;">
                    <!-- Display Admin Name -->
                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        Admin
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header text-center">
                        <img src="<?= !empty($admin['profile_picture']) 
                                    ? base_url('uploads/profile/' . $admin['profile_picture']) 
                                    : '/assets/img/default-pp.jpeg' ?>" 
                             alt="Profile Picture" 
                             class="rounded-circle mb-2" 
                             style="width: 80px; height: 80px;">
                        <h6><?= esc($admin['full_name'] ?? 'Admin') ?></h6>
                        <span><?= esc($admin['role'] ?? 'Administrator') ?></span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <!-- Update Profile Picture -->
                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                            <i class="bi bi-person-bounding-box"></i>
                            <span>Update Profile Picture</span>
                        </a>
                    </li>
                    <li>
                        <!-- Sign Out -->
                        <a class="dropdown-item d-flex align-items-center" href="/logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<!-- Modal for Updating Profile Picture -->
<div class="modal fade" id="profilePictureModal" tabindex="-1" aria-labelledby="profilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/profile/update-picture" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="profilePictureModalLabel">Update Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <!-- Display current profile picture -->
                        <img src="<?= !empty($admin['profile_picture']) 
                                    ? base_url('uploads/profile/' . $admin['profile_picture']) 
                                    : '/assets/img/default-pp.jpeg' ?>" 
                             alt="Profile Picture" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px;">
                    </div>
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Select New Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                        <small class="text-muted">Allowed formats: jpg, jpeg, png. Max size: 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>

            <!-- Button to Remove Profile Picture -->
            <?php if (!empty($admin['profile_picture'])): ?>
                <form action="/admin/profile/remove-picture" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-sm mt-2">Remove Picture</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
