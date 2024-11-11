<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #508bfc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
            width: 500px; /* Fixed width */
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 2rem;
            text-align: center;
        }

        h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        /* Form Styles */
        .form-control {
            border-radius: 0.5rem;
            font-size: 1rem;
            padding: 0.75rem;
            margin-top: 0.5rem;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #508bfc;
            outline: none;
            box-shadow: 0 0 5px rgba(80, 139, 252, 0.5);
        }

        .form-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Button Styles */
        .btn {
            border-radius: 0.5rem;
            font-size: 1rem;
            padding: 0.75rem;
            transition: background-color 0.3s ease;
            width: 100%;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #3b5998;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2a437a;
        }

        /* Divider */
        hr {
            border-top: 1px solid #ddd;
            margin: 1.5rem 0;
        }

        /* Alert Styles */
        .alert {
            margin-bottom: 1rem;
            padding: 10px;
            border-radius: 5px;
            display: block; /* Show by default */
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive Styles */
        @media (max-width: 500px) {
            h3 {
                font-size: 1.5rem;
            }

            .form-control {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <form id="signUpForm" method="post" action="<?= base_url('admin/register') ?>" enctype="multipart/form-data">
        <div class="container">
            <div class="card shadow-2-strong">
                <div class="card-body p-5 text-center">

                    <!-- Alert messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert error">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <h3 class="mb-5">Admin Sign Up</h3>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="typeNameX">Full Name</label>
                        <input type="text" id="typeNameX" name="full_name" class="form-control form-control-lg" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="typeEmailX">Email</label>
                        <input type="email" id="typeEmailX" name="email" class="form-control form-control-lg" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="typePasswordX">Password</label>
                        <input type="password" id="typePasswordX" name="password" class="form-control form-control-lg" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="typePasswordConfirmX">Confirm Password</label>
                        <input type="password" id="typePasswordConfirmX" name="password_confirm" class="form-control form-control-lg" required />
                    </div>

                    <button class="btn btn-primary btn-lg" type="submit">Sign Up</button>
                    <hr class="my-4">
                    <p>Already have an account? <a href="<?= base_url('login') ?>">Log in</a></p>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('signUpForm').addEventListener('submit', function(event) {
            const password = document.getElementById('typePasswordX').value;
            const passwordConfirm = document.getElementById('typePasswordConfirmX').value;

            if (password !== passwordConfirm) {
                event.preventDefault(); // Prevent form submission
                alert("Passwords do not match. Please try again.");
            }
        });

        // Optional: Hide alerts after a few seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => alert.style.display = 'none');
        }, 5000); // Hide after 5 seconds
    </script>

</body>

</html>
