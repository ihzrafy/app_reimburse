<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - App Reimbursement</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' fill='%2328a745' class='bi bi-cash' viewBox='0 0 16 16'><path d='M8.5 5.5a1 1 0 1 0-2 0 1 1 0 0 0 2 0z'/><path d='M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3z'/><path d='M1 6.5v-1h3v1H1zm0 1h3v1H1v-1zm3 1H1v1h3v-1zm0 1H1v1h3v-1zm9-3v-1h-3v1h3zm0 1h-3v1h3v-1zm-3 1h3v1h-3v-1zm0 1h3v1h-3v-1z'/></svg>">

    <link href="assets/css/style.css" rel="stylesheet">
    
</head>

<body>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    
                    <div class="d-flex justify-content-center py-4">
                        <a href="/" class="logo d-flex align-items-center w-auto">
                            <span class="d-none d-lg-block">App Reimbursement</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title text-center pb-0 fs-4">Create Your Account</h5>
                            <p class="text-center small">Fill the form below to register</p>

                            <form action="/auth/processRegister" method="post" class="row g-3">
                                <?= csrf_field() ?>

                                <div class="col-12">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" id="username" required>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>

                                <div class="col-12">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" id="full_name" required>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Register</button>
                                </div>
                            </form>

                            <div class="text-center mt-3">
                                <p class="small">Already have an account? 
                                    <a href="/login" class="text-primary">Login here</a>
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php if (session()->getFlashdata('success')) : ?> 
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= session()->getFlashdata('success') ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '/login'; // Redirect ke halaman login setelah OK
            });
        </script>
    <?php endif; ?>

</body>

</html>