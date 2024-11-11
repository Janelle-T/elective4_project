<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Approval</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 60px;
        }
        .card-header {
            background-color: #17a2b8;
            color: white;
            text-align: center;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-75">
            <div class="card-header">
                <h2 class="mb-0">Email Verified Successfully!</h2>
            </div>
            <div class="card-body text-center">
                <p class="lead">Thank you for verifying your email. Your account is now awaiting admin approval.</p>
                <p>Please be patient as the admin processes your account activation. If you have any questions, feel free to contact the admin team.</p>
                <a href="<?= base_url('login') ?>" class="btn btn-primary mt-3">Go to Login</a>
                
                <!-- Contact Admin Section -->
                <hr class="my-4">
                <h4>Contact the Admin Team</h4>
                <p>If you have urgent questions, fill out the form below to contact the admin team directly.</p>
                
                <form action="<?= base_url('contact/sendMessage') ?>" method="post" class="text-left">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
