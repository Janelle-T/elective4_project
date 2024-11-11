<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">  
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/form.css') ?>">
    <style>
        .input-group {
            position: relative;
            width: 100%;
        }

        .input-group input {
            width: 100%;
            padding-right: 30px; /* Adjust to make space for the icon */
        }

        .input-group .input-icon {
            position: absolute;
            right: 10px; /* Position the icon inside the input field */
            top: 50%;
            transform: translateY(-50%); /* Vertically center the icon */
            cursor: pointer; /* Change cursor to pointer to indicate it's clickable */
        }

        .input-group .input-icon i {
            font-size: 18px; /* Adjust icon size */
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .header .back-button {
            font-size: 1.2em;
            cursor: pointer;
            color: #333;
            text-decoration: none;
        }
        .title {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
     <div class="container mt-3">
        <div class="header">
            <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="title">Student Registration</div>
        </div>
        
        <!-- Success Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
       
        <div class="content">
            <form id="facultyForm" method="post" action="<?= base_url('student/register') ?>" enctype="multipart/form-data">
                <div class="user-details">
                    <!-- Full Name Input -->
                    <div class="input-box">
                        <span class="details">Full Name</span>
                        <div class="input-group">
                            <input type="text" id="full_name" name="full_name" placeholder="Enter your name" required>
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                        </div>
                    </div>
                    <!-- Faculty ID Input -->
                    <div class="input-box">
                        <span class="details">Student ID</span>
                        <div class="input-group">
                            <input type="text" id="student_id" name="student_id" placeholder="Enter your student ID" required>
                            <span class="input-icon"><i class="fas fa-id-badge"></i></span>
                        </div>
                    </div>
                    <!-- Email Input -->
                    <div class="input-box">
                        <span class="details">Email</span>
                        <div class="input-group">
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>
                    <!-- Phone Number Input -->
                    <div class="input-box">
                        <span class="details">Phone Number</span>
                        <div class="input-group">
                            <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Enter your number">
                            <span class="input-icon"><i class="fas fa-phone"></i></span>
                        </div>
                    </div>
                    <!-- Password Input -->
                    <div class="input-box">
                        <span class="details">Password</span>
                        <div class="input-group">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <span class="input-icon" id="togglePassword1"><i class="fas fa-eye-slash"></i></span>
                        </div>
                    </div>
                    <!-- Confirm Password Input -->
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <div class="input-group">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                            <span class="input-icon" id="togglePassword2"><i class="fas fa-eye-slash"></i></span>
                        </div>
                    </div>
                </div>
                <!-- Gender Selection -->
                <div class="col-md-6 mb-4">
                    <h6 class="mb-2 pb-1">Gender:</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="Female" required />
                        <label class="form-check-label" for="femaleGender">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="maleGender" value="Male" required />
                        <label class="form-check-label" for="maleGender">Male</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="button">
                    <input type="submit" value="Register">
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Fetch departments based on selected college -->
    <script>

        // Toggle password visibility for the "Password" field
        document.getElementById('togglePassword1').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');

            // Toggle the type attribute and icon
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordField.type = "password";
                
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        // Toggle password visibility for the "Confirm Password" field
        document.getElementById('togglePassword2').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm_password');
            const icon = this.querySelector('i');

            // Toggle the type attribute and icon
            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                confirmPasswordField.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                
            }
        });
    </script>
</body>
</html>
