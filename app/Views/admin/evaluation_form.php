<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 30px;
        }

        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .evaluation-table td, .evaluation-table th {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .evaluation-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .radio-group {
            display: flex;
            align-items: center;
        }

        .radio-group label {
            margin-right: 10px;
        }

        .criteria-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Faculty Evaluation Form</h1>

        <!-- Display validation errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display general error message if evaluation already submitted -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')); ?>
            </div>
        <?php endif; ?>

        <!-- Evaluation Form -->
        <form method="post" action="<?= base_url('evaluation/submit'); ?>">

            <?php 
            $studentId = session('studentId'); 
            $academicId = session('academicId');
            if (!$academicId) {
                $academicId = model('App\Models\AcademicModel')->where('status', 1)->first()['id'];
                session()->set('academicId', $academicId);
            }
            ?>

            <!-- Hidden Inputs -->
            <input type="hidden" name="student_id" value="<?= $studentId; ?>"> 
            <input type="hidden" name="academic_id" value="<?= $academicId; ?>"> 

            <!-- Faculty Dropdown -->
            <div class="mb-3">
                <label for="faculty_id" class="form-label">Select Faculty</label>
                <select class="form-select" id="faculty_id" name="faculty_id" required>
                    <option value="">Select Faculty</option> <!-- Default empty option -->
                    <?php foreach ($facultyList as $faculty) : ?>
                        <option value="<?= $faculty['id']; ?>"><?= htmlspecialchars($faculty['full_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Rating Scale -->
            <div class="mb-3">
                <table class="evaluation-table">
                    <thead>
                        <tr>
                            <th>Rate</th>
                            <th>Descriptive Rating</th>
                            <th>Qualitative Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $ratings = model('App\Models\RatingModel')->findAll();
                        foreach ($ratings as $rating) : 
                        ?>
                            <tr>
                                <td><?= $rating['rate']; ?></td>
                                <td><?= $rating['descriptive_rating']; ?></td>
                                <td><?= $rating['qualitative_description']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Evaluation Questions Table -->
            <table class="evaluation-table">
                <thead>
                    <tr>
                        <th>Criteria & Questions</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $criteria = model('App\Models\CriteriaModel')->findAll();
                    foreach ($criteria as $criterion) : 
                    ?>
                        <tr>
                            <td colspan="2" class="criteria-title"><?= $criterion['title']; ?></td>
                        </tr>
                        <?php 
                        $questions = model('App\Models\EvaluationQuestionModel')->where('criteria_id', $criterion['id'])->findAll(); 
                        foreach ($questions as $question) : 
                        ?>
                            <tr>
                                <td><?= $question['question_text']; ?></td>
                                <td>
                                    <div class="radio-group">
                                        <?php 
                                        foreach ($ratings as $rating) : 
                                        ?>
                                            <label>
                                                <input type="radio" name="question_<?= $question['id']; ?>" value="<?= $rating['id']; ?>"> <?= $rating['rate']; ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; endforeach; ?>
                    <tr>
                        <td colspan="2">
                            <label for="comment" class="form-label fw-bold">Overall Comments (Minimum 10 words in English)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="6" required minlength="10"></textarea>
                        </td>
                    </tr>


                </tbody>
            </table>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit Evaluation</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
