<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Dates</title>
        <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/scripts.js') ?>"></script>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
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
                        <a class="nav-link" href="<?= base_url('/') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                            Token
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <section>
                                <center>
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create New Evaluation Date</a>

                                    <!-- Table displaying the evaluation dates -->
                                    <table class="table mt-3">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Open Date & Time</th>
                                                <th>Close Date & Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($evaluationDates as $evaluationDate): ?>
                                            <tr>
                                                <td><?= $evaluationDate['id'] ?></td>
                                                <td><?= $evaluationDate['open_datetime'] ?></td>
                                                <td><?= $evaluationDate['close_datetime'] ?></td>
                                                <td>
                                                    <!-- Edit button triggers the Edit modal -->
                                                    <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                       data-id="<?= $evaluationDate['id'] ?>"
                                                       data-open-datetime="<?= date('Y-m-d\TH:i', strtotime($evaluationDate['open_datetime'])) ?>"
                                                       data-close-datetime="<?= date('Y-m-d\TH:i', strtotime($evaluationDate['close_datetime'])) ?>">Edit</a>
                                                    <!-- Delete button -->
                                                    <a href="/evaluation-dates/delete/<?= $evaluationDate['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </center>
                            </section>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Modal for Create -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Create Evaluation Date</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('evaluation-dates/store') ?>" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="open_datetime" class="form-label">Open Date & Time</label>
                                    <input type="datetime-local" name="open_datetime" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="close_datetime" class="form-label">Close Date & Time</label>
                                    <input type="datetime-local" name="close_datetime" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal for Edit -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Evaluation Date</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('evaluation-dates/edit') ?> method="post id="editForm">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit-id">
                                <div class="mb-3">
                                    <label for="open_datetime" class="form-label">Open Date & Time</label>
                                    <input type="datetime-local" name="open_datetime" class="form-control" id="edit-open-datetime" required>
                                </div>
                                <div class="mb-3">
                                    <label for="close_datetime" class="form-label">Close Date & Time</label>
                                    <input type="datetime-local" name="close_datetime" class="form-control" id="edit-close-datetime" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <!-- Add Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // When the Edit button is clicked, populate the Edit modal with the current data
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = document.getElementById('editModal');
                editModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var id = button.getAttribute('data-id');
                    var openDatetime = button.getAttribute('data-open-datetime');
                    var closeDatetime = button.getAttribute('data-close-datetime');

                    var form = document.getElementById('editForm');
                    form.action = '/evaluation-dates/update/' + id; // Set the form action to the correct URL

                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-open-datetime').value = openDatetime;
                    document.getElementById('edit-close-datetime').value = closeDatetime;
                });
            });
        </script>
    </body>
</html>
