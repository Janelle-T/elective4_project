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
        <div id="layoutSidenav_content">
          <main>
                <div class="container-fluid px-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <!-- Display Flash Data Alerts -->
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= esc(session()->getFlashdata('success')) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= esc(session()->getFlashdata('error')) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#questionModal">
                                Add Question
                            </button>

                            <!-- Modal Form -->
                            <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="questionModalLabel">Add Question</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="evaluationQuestionForm" method="post" action="<?= base_url('evaluation/saveEvaluationQuestion') ?>">
                                                <div class="mb-3">
                                                    <label for="criteriaSelect" class="form-label">Criteria:</label>
                                                    <select name="criteria_id" id="criteriaSelect" class="form-select" required>
                                                        <?php foreach ($criteria as $criterion): ?>
                                                            <option value="<?= esc($criterion['id']) ?>"><?= esc($criterion['title']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="questionText" class="form-label">Question Text:</label>
                                                    <textarea name="question_text" id="questionText" class="form-control" required></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save Evaluation Question</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DataTable -->
                            <table id="questionTable" class="table table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Criteria</th>
                                        <th>Question</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($questionRecords as $record): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= esc($record['criteria_title']) ?></td> <!-- Display criteria title -->
                                            <td><?= esc($record['question_text']) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">Edit</button>
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable for questionTable
            $('#questionTable').DataTable();
        });
    </script>
</body>
</html>
