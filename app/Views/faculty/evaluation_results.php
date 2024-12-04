<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Results</title>
</head>
<body>
    <div class="container">
        <h1>Evaluation Results</h1>

        <!-- Form to Select Academic Semester -->
        <form method="post" action="<?= base_url('evaluation/results') ?>">
            <label for="academic_id">Select Academic Year and Semester:</label>
            <select name="academic_id" id="academic_id" required>
                <option value="" disabled selected>Choose...</option>
                <?php foreach ($academicOptions as $academic): ?>
                    <option value="<?= esc($academic['id']) ?>" <?= isset($selectedAcademic) && $selectedAcademic['id'] == $academic['id'] ? 'selected' : '' ?>>
                        <?= esc($academic['school_year']) ?> - <?= esc($academic['semester'] == 1 ? '1st Semester' : '2nd Semester') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">View Results</button>
        </form>

        <?php if (isset($errorMessage)): ?>
            <p><?= esc($errorMessage) ?></p>
        <?php endif; ?>

        <!-- Display Evaluation Results if available -->
        <?php if (isset($evaluations) && !empty($evaluations)): ?>
            <h2>Evaluation Results for Academic Year: <?= esc($selectedAcademic['school_year']) ?> - 
                <?= esc($selectedAcademic['semester'] == 1 ? '1st Semester' : '2nd Semester') ?></h2>
            
            <table border="1">
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Questions and Ratings</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentEvaluationId = null;
                    $totalEvaluations = count($evaluations);
                    foreach ($evaluations as $index => $evaluation):
                        if ($currentEvaluationId !== $evaluation['evaluation_id']):
                            $currentEvaluationId = $evaluation['evaluation_id'];
                    ?>
                        <tr>
                            <td><?= esc($evaluation['comment']) ?></td>
                            <td><?= esc($evaluation['school_year']) ?></td>
                            <td><?= esc($evaluation['semester'] == 1 ? 'First Semester' : 'Second Semester') ?></td>
                            <td>
                                <ul>
                    <?php endif; ?>
                                <li><?= esc($evaluation['question_text']) ?>: <?= esc($evaluation['rating_rate']) ?></li>
                    <?php 
                        // Check if the current evaluation is the last one for this evaluation_id
                        if ($index + 1 == $totalEvaluations || $evaluations[$index + 1]['evaluation_id'] !== $currentEvaluationId):
                    ?>
                                </ul>
                            </td>
                            <td><?= esc(date('F d, Y', strtotime($evaluation['created_at']))) ?></td>
                        </tr>
                    <?php 
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
