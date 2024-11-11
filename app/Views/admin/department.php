<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Evaluation</title>
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="sb-nav-fixed">
    <!-- Navbar -->
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
                                <a class="nav-link" href="<?= base_url('admin/admin') ?>"><i class="fas fa-user-shield"></i> Admin</a>
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
                        <a class="nav-link" href="<?= base_url('/') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                            Token
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Main Content (Page-specific content) -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="container">
                            <h3>Manage Departments</h3>
                            <!-- Button to open modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#departmentModal">Add Department</button>
                            
                            <!-- DataTable to display departments -->
                            <table id="departmentTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Department Name</th>
                                        <th>College</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Departments will be dynamically loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal for adding department -->
                        <div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="departmentModalLabel">Add Department</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="departmentForm">
                                            <div class="form-group">
                                                <label class="form-label" for="college">Select College</label>
                                                <select id="college" name="college" class="form-control" required>
                                                    <option value="">-- Select College --</option>
                                                    <!-- Colleges will be loaded dynamically here -->
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="department_name">Department Name</label>
                                                <input type="text" id="department_name" name="department_name" class="form-control" required />
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="form-button">Save Department</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
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

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- jQuery, Bootstrap JS, DataTable JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#departmentTable').DataTable();

            // Load Colleges into the dropdown
            function loadColleges() {
                $.ajax({
                    url: '<?= base_url('admin/getColleges') ?>',
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.data) {
                            $('#college').html('<option value="">-- Select College --</option>');
                            response.data.forEach(function(college) {
                                $('#college').append(`<option value="${college.college_id}">${college.college_name}</option>`);
                            });
                        } else {
                            alert("No colleges found.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while loading the colleges.");
                    }
                });
            }

            // Load departments based on the selected college
            function loadDepartments(collegeId) {
                if (!collegeId) {
                    alert('Please select a valid college');
                    return;
                }

                $.ajax({
                    url: '<?= base_url('admin/getDepartments') ?>/' + collegeId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && Array.isArray(response.data)) {
                            // Clear the table and reload data
                            table.clear();
                            response.data.forEach(function(department) {
                                table.row.add([
                                    department.department_name,
                                    department.college_name,
                                    `<button class="btn btn-info btn-sm" onclick="editDepartment(${department.department_id})">Edit</button>
                                     <button class="btn btn-danger btn-sm" onclick="deleteDepartment(${department.department_id})">Delete</button>`
                                ]).draw();
                            });
                        } else {
                            alert("Failed to load departments.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while loading the departments.");
                    }
                });
            }

            // Function to handle form submission
            $('#departmentForm').submit(function(event) {
                event.preventDefault();

                var collegeId = $('#college').val();
                var departmentName = $('#department_name').val();

                if (!collegeId || !departmentName) {
                    alert("All fields are required.");
                    return;
                }

                $.ajax({
                    url: '<?= base_url('admin/saveDepartment') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Department added successfully');
                            $('#departmentForm')[0].reset();
                            $('#departmentModal').modal('hide');
                            loadDepartments(collegeId); // Reload the departments after adding a new one
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while adding the department.");
                    }
                });
            });

            // Load colleges on page load
            loadColleges();

            // Trigger department load when a college is selected
            $('#college').change(function() {
                var collegeId = $(this).val();
                loadDepartments(collegeId);
            });
        });
    </script>
    
    <script>
    function logout() {
        if (confirm('Are you sure you want to logout?')) {
            fetch('<?= base_url('logout') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'csrf_token_name': '<?= csrf_hash() ?>'
                })
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '<?= base_url('login') ?>';
                } else {
                    alert('Logout failed.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    </script>
    
</body>
</html>
