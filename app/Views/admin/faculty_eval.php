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
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .section-title, .rating-title {
            text-align: center;
        }
        .header div {
            margin-bottom: 5px;
        }
        .section-title {
            margin-top: 15px;
            font-size: 1.2em;
        }
        .evaluation-table {
            width: 75%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .evaluation-table td, .evaluation-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .evaluation-table td {
            width: 75%;
        }
        .evaluation-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .rating-title {
            margin-top: 15px;
            font-size: 1.2em;
        }
        .rating-table {
            width: 75%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .width-20 {
            width: 20%;
        }
        .card-width-25 {
            width: 25%;
        }
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
          padding: 15px;
          text-align: left;
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
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
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
                        <a class="nav-link" href="<?= base_url('/dashboard') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Evaluation Form
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseAdmin" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('/faculty') ?>">Faculty Evaluation</a>
                                <a class="nav-link" href="<?= base_url('/student') ?>">Student Evaluation</a>
                                <a class="nav-link" href="<?= base_url('/course') ?>">Course Evaluation</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudent" aria-expanded="false" aria-controls="collapseStudent">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Evaluation Results
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseStudent" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('/faculty/result') ?>">Faculty Evaluation Results</a>
                                <a class="nav-link" href="<?= base_url('/student/result') ?>">Student Evaluation Results</a>
                                <a class="nav-link" href="<?= base_url('/course/result') ?>">Course Evaluation Results</a>
                            </nav>
                        </div>
                       
                        <a class="nav-link" href="<?= base_url('') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
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
                                    <div class="header">
                                        <div><font size="5"><b>University of Science and Technology of Southern Philippines</b></font></div>
                                        <div><font size="3"><i>Alubijid | Balubal | Cagayan de Oro | Claveria | Jasaan | Oroquieta | Panaon | Villanueva</i></font></div>
                                        <div><font size="4"><b>PERFORMANCE EVALUATION AS RATED BY STUDENTS</b></font></div>
                                        <div><font size="4"><b>FIRST SEMESTER AY 2022-2023</b></font></div>
                                        <div><font size="4"><b>CLAVERIA CAMPUS</b></font></div>
                                    </div>
                                </center>
                            </section>
                        </div>
                    </div>
                    <div class="container-fluid px-4">
                        <div class="card card-width-25 mb-4">
                            <div class="card-body">
                                <h5>Faculty</h5>

                                <!-- Input field to display selected faculty -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="selectedFaculty" placeholder="Select a faculty member" readonly>
                                </div>

                                <!-- Faculty List Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="facultyListDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Faculty
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="facultyListDropdown">
                                        <li><a class="dropdown-item" href="#" onclick="selectFaculty('Prof. Alphany Aragua')">Prof. Alphany Aragua</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="selectFaculty('Ms. Caroline Zaportiza')">Ms. Caroline Zaportiza</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="selectFaculty('Ms. Aubrey Lavarez')">Ms. Aubrey Lavarez</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="selectFaculty('Mr. Leslie Sungkit')">Mr. Leslie Sungkit</a></li>
                                        <!-- Add more faculty members as needed -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container-fluid px-4">
                        <div class="card card-width-27 mb-4">
                            <div class="card-body">
                                <div class="rating-title">Instruction: Please evaluate the faculty using the scale below.</div>              
                                <!-- Scale Table -->
                                <table class="rating-table">
                                    <tr>
                                        <th>Scale</th>
                                        <th>Descriptive Rating</th>
                                        <th>Qualitative Description</th>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Outstanding</td>
                                        <td>The Performance almost always exceeds the job requirements. The faculty is an exceptional role model</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Very Satisfactory</td>
                                        <td>The performance meets and often exceeds the job requirements</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Satisfactory</td>
                                        <td>The performance meets job requirements</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Fair</td>
                                        <td>The performance needs some development to meet job requirements</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Poor</td>
                                        <td>The faculty fails to meet job requirements</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container-fluid px-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <section>

                                    <!-- Commitment Section -->
                                    <div class="section-title">COMMITMENT</div>
                                    <table class="evaluation-table">
                                        <tr>
                                            <th>Criteria</th>
                                            <th>Rating (1-5)</th>
                                        </tr>
                                        <tr>
                                            <td>Demonstrates sensitivity to students' ability to absorb content information</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="commitment1" value="1"> 1</label>
                                                    <label><input type="radio" name="commitment1" value="2"> 2</label>
                                                    <label><input type="radio" name="commitment1" value="3"> 3</label>
                                                    <label><input type="radio" name="commitment1" value="4"> 4</label>
                                                    <label><input type="radio" name="commitment1" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Makes self-available to students beyond official time.</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="commitment2" value="1"> 1</label>
                                                    <label><input type="radio" name="commitment2" value="2"> 2</label>
                                                    <label><input type="radio" name="commitment2" value="3"> 3</label>
                                                    <label><input type="radio" name="commitment2" value="4"> 4</label>
                                                    <label><input type="radio" name="commitment2" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Keeps accurate records of students' performance and prompt submission of same</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="commitment3" value="1"> 1</label>
                                                    <label><input type="radio" name="commitment3" value="2"> 2</label>
                                                    <label><input type="radio" name="commitment3" value="3"> 3</label>
                                                    <label><input type="radio" name="commitment3" value="4"> 4</label>
                                                    <label><input type="radio" name="commitment3" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Knowledge of Subject Matter Section -->
                                    <div class="section-title">KNOWLEDGE OF SUBJECT MATTER</div>
                                    <table class="evaluation-table">
                                        <tr>
                                            <th>Criteria</th>
                                            <th>Rating (1-5)</th>
                                        </tr>
                                        <tr>
                                            <td>Demonstrates mastery of the subject matter.</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="knowledge1" value="1"> 1</label>
                                                    <label><input type="radio" name="knowledge1" value="2"> 2</label>
                                                    <label><input type="radio" name="knowledge1" value="3"> 3</label>
                                                    <label><input type="radio" name="knowledge1" value="4"> 4</label>
                                                    <label><input type="radio" name="knowledge1" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Draws and shares information on the state-of-the-art theory and practice in their discipline</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="knowledge2" value="1"> 1</label>
                                                    <label><input type="radio" name="knowledge2" value="2"> 2</label>
                                                    <label><input type="radio" name="knowledge2" value="3"> 3</label>
                                                    <label><input type="radio" name="knowledge2" value="4"> 4</label>
                                                    <label><input type="radio" name="knowledge2" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Integrates subject to practical circumstances and learning intents of students.</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="knowledge3" value="1"> 1</label>
                                                    <label><input type="radio" name="knowledge3" value="2"> 2</label>
                                                    <label><input type="radio" name="knowledge3" value="3"> 3</label>
                                                    <label><input type="radio" name="knowledge3" value="4"> 4</label>
                                                    <label><input type="radio" name="knowledge3" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Demonstrates up-to-date knowledge and/or awareness on current trends and issues of the subject.</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="knowledge4" value="1"> 1</label>
                                                    <label><input type="radio" name="knowledge4" value="2"> 2</label>
                                                    <label><input type="radio" name="knowledge4" value="3"> 3</label>
                                                    <label><input type="radio" name="knowledge4" value="4"> 4</label>
                                                    <label><input type="radio" name="knowledge4" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Teaching for Independent Learning Section -->
                                    <div class="section-title">TEACHING FOR INDEPENDENT LEARNING</div>
                                    <table class="evaluation-table">
                                        <tr>
                                            <th>Criteria</th>
                                            <th>Rating (1-5)</th>
                                        </tr>
                                        <tr>
                                            <td>Encourages students to take responsibility for their learning.</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="teaching1" value="1"> 1</label>
                                                    <label><input type="radio" name="teaching1" value="2"> 2</label>
                                                    <label><input type="radio" name="teaching1" value="3"> 3</label>
                                                    <label><input type="radio" name="teaching1" value="4"> 4</label>
                                                    <label><input type="radio" name="teaching1" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Facilitates critical thinking and independent learning skills.</td>
                                            <td>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="teaching2" value="1"> 1</label>
                                                    <label><input type="radio" name="teaching2" value="2"> 2</label>
                                                    <label><input type="radio" name="teaching2" value="3"> 3</label>
                                                    <label><input type="radio" name="teaching2" value="4"> 4</label>
                                                    <label><input type="radio" name="teaching2" value="5"> 5</label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Comments Section -->
                                    <div class="section-title">Comments</div>
                                    <div style="text-align: center;">
                                        <textarea rows="4" cols="50" placeholder="Please leave your comments here..." style="width: 75%;"></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div style="text-align: center; margin-top: 20px;">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </section>
                            </div>
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
    <!-- JavaScript function to update input field -->
<script>
    function selectFaculty(facultyName) {
        // Set the selected faculty name in the input field
        document.getElementById('selectedFaculty').value = facultyName;
    }
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
                    window.location.href = '<?= base_url('admin') ?>';
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
