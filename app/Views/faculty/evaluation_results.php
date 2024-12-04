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
                    <option value="<?= esc($academic['id']) ?>">
                        <?= esc($academic['school_year']) ?> - <?= esc($academic['semester'] == 1 ? 'First Semester' : 'Second Semester') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">View Results</button>
        </form>

        <!-- Display Evaluation Results -->
        <?php if (!empty($evaluations)): ?>
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
                    foreach ($evaluations as $evaluation): 
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
                                    <li><?= esc($evaluation['question']) ?>: <?= esc($evaluation['rating_value']) ?></li>
                    <?php 
                        if (next($evaluations)['evaluation_id'] !== $currentEvaluationId): 
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
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No evaluations found for the selected semester.</p>
        <?php endif; ?>
    </div>
</body>
</html>
