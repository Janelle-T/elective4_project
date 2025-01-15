<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <!-- Stylesheets -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css" rel="stylesheet">
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
    <style>
        .loading-spinner {
            display: none;
            width: 30px;
            height: 30px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                    <?php if (session()->get('isLoggedIn')): ?>
                        <!-- Display logged-in user's full name -->
                        <?= session()->get('full_name') ?>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Active Status</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="logout()">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <!-- User Management Dropdown -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUserManagement" aria-expanded="false" aria-controls="collapseUserManagement">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            User Management
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseUserManagement" aria-labelledby="headingUserManagement" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('admin/list') ?>"><i class="fas fa-user-shield"></i> Admin</a>
                                <a class="nav-link" href="<?= base_url('faculty/list') ?>"><i class="fas fa-chalkboard-teacher"></i> Faculty</a>
                                <a class="nav-link" href="<?= base_url('student/list') ?>"><i class="fas fa-user-graduate"></i> Student</a>
                            </nav>
                        </div>

                        <!-- College/Department Dropdown -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCollegeDepartment" aria-expanded="false" aria-controls="collapseCollegeDepartment">
                            <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                            College/Department
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCollegeDepartment" aria-labelledby="headingCollegeDepartment" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('admin/college') ?>"><i class="fas fa-university"></i> College</a>
                                <a class="nav-link" href="<?= base_url('admin/department') ?>"><i class="fas fa-building-columns"></i> Department</a>
                            </nav>
                        </div>

                        <!-- Evaluation Management Dropdown -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEvaluationManagement" aria-expanded="false" aria-controls="collapseEvaluationManagement">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                            Evaluation Management
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseEvaluationManagement" aria-labelledby="headingEvaluationManagement" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('evaluation/academic') ?>"><i class="fas fa-book"></i> Academic</a>
                                <a class="nav-link" href="<?= base_url('evaluation/rating') ?>"><i class="fas fa-star"></i> Rating Scale</a>
                                <a class="nav-link" href="<?= base_url('evaluation/criteria') ?>"><i class="fas fa-list"></i> Criteria</a>
                                <a class="nav-link" href="<?= base_url('evaluation/evaluation_question') ?>"><i class="fas fa-question-circle"></i> Evaluation Question</a>
                                <a class="nav-link" href="<?= base_url('evaluation-dates') ?>"><i class="fas fa-question-circle"></i> Evaluation Status</a>

                            </nav>
                        </div>

                        <!-- Faculty Evaluation Dropdown -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFacultyEvaluation" aria-expanded="false" aria-controls="collapseFacultyEvaluation">
                            <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            Faculty Evaluation
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseFacultyEvaluation" aria-labelledby="headingFacultyEvaluation" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('/') ?>"><i class="fas fa-file-alt"></i> Evaluation Form</a>
                                <a class="nav-link" href="<?= base_url('/') ?>"><i class="fas fa-chart-line"></i> Evaluation Result</a>
                            </nav>
                        </div>

                        <!-- Token Link -->
                     
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    <h2 class="mb-4">Student List</h2>
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <!-- Add Import Button -->
                            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fas fa-file-import"></i> Import Data

                            </button>
                            <!-- Loading Spinner -->
                            

                            <table id="studentTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Full Name</th>
                                        <th>Phone Number</th>
                                        <th>Gender</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated by DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Import Modal -->
                <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="importModalLabel">Import Student Data</h5>
                                <div id="loadingSpinner" class="loading-spinner"></div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- File upload form -->
                                <form id="importForm" action="<?= base_url('student/import') ?>" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="importFile" class="form-label">Select File</label>
                                        <input type="file" class="form-control" id="importFile" name="importFile" accept=".csv, .xlsx, .xls" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Elective 3 System Project - Developer - Janelle Toquero</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


    <script>
        $('#studentTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url("student/getStudentList") ?>',
                type: 'POST',
                error: function(xhr, error, thrown) {
                    alert("Failed to load data: " + error);
                }
            },
            columns: [
                { data: 'counter' },
                { data: 'student_id' },
                { data: 'full_name' },
                { data: 'phone_number' },
                { data: 'gender' },
                { data: 'email' },
                { data: 'actions' }
            ]
        });

       // Handle file import form submission
        // Import form submission with loading animation and alert
        $('#importForm').on('submit', function(e) {
            e.preventDefault();  // Prevent the default form submission
            
            var formData = new FormData(this);  // Get the form data, including the file
            
            // Show loading spinner
            $('#loadingSpinner').show();
            
            $.ajax({
                url: '/student/import',  // The URL to send the POST request to
                method: 'POST',
                data: formData,
                processData: false,  // Prevent jQuery from converting the data
                contentType: false,  // Let the browser set the content type for file uploads
                success: function(response) {
                    // Hide the loading spinner
                    $('#loadingSpinner').hide();

                    // Show success alert
                    alert('Data imported successfully!');

                    // Refresh the page
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // Wait 2 seconds before refreshing the page
                },
                error: function(xhr, status, error) {
                    // Hide the loading spinner
                    $('#loadingSpinner').hide();
                    
                    // Show error alert
                    alert('Error uploading file: ' + error);
                }
            });
        });


    </script>
</body>
</html>
