<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
            max-width: 400px;
            width: 100%;
            padding: 15px;
        }

        .card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 2rem;
            text-align: center;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            border: 1px solid #ddd;
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            border: none;
        }

        .btn-primary {
            background-color: #3b5998;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2a437a;
        }

        .alert {
            padding: 10px;
            background-color: #f44336;
            color: white;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        /* Modal overlay and content */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-in-out;
            z-index: 1000;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideIn 0.4s ease-out;
        }

        /* Modal animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
            }
            to {
                transform: translateY(0);
            }
        }

        /* Button styles */
        .signup-option .btn-primary {
            display: inline-block;
            width: 100%;
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: #3b5998;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .signup-option .btn-primary:hover {
            background-color: #2a437a;
        }

        .close-btn {
            margin-top: 1.5rem;
            padding: 0.5rem 1rem;
            background-color: #f44336;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .close-btn:hover {
            background-color: #d7372e;
        }

        /* Title styling */
        .modal-content h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        /* Improved spacing for buttons */
        .signup-option {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <form id="loginForm" method="post" action="<?= base_url('auth/login') ?>">
        <div class="container">
            <div class="card shadow-2-strong">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-5">Sign in</h3>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="typeEmail">Email</label>
                        <input type="email" id="typeEmail" name="email" class="form-control form-control-lg" required value="<?= old('email') ?>" />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="typePassword">Password</label>
                        <input type="password" id="typePassword" name="password" class="form-control form-control-lg" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="userType">Login as</label>
                        <select id="userType" name="user_type" class="form-control form-control-lg" required>
                            <option value="admin" <?= old('user_type') == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="student" <?= old('user_type') == 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="faculty" <?= old('user_type') == 'faculty' ? 'selected' : '' ?>>Faculty</option>
                        </select>
                    </div>

                    <button class="btn btn-primary btn-lg" type="submit">Login</button>

                    <hr class="my-4">

                    <p>Don't have an account? <a href="#" id="signupLink">Sign up</a></p>
                </div>
            </div>
        </div>
    </form>

    <!-- Sign up modal -->
    <div class="modal" id="signupModal">
        <div class="modal-content">
            <h3>Sign up as</h3>
            <div class="signup-option">
                <a href="<?= base_url('student/sign_up') ?>" class="btn btn-primary">Student</a>
            </div>
            <div class="signup-option">
                <a href="<?= base_url('faculty/sign_up') ?>" class="btn btn-primary">Faculty</a>
            </div>
            <button class="close-btn" id="closeModal">Close</button>
        </div>
    </div>

    <script>
        // Show the modal
        document.getElementById('signupLink').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('signupModal').style.display = 'flex';
        });

        // Close the modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('signupModal').style.display = 'none';
        });

        // Close modal when clicking outside modal content
        window.onclick = function(event) {
            const modal = document.getElementById('signupModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>
