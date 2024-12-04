<!-- evaluation_results.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Results</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Add your styles -->
</head>
<body>
    <div class="container">
        <h1>Evaluation Results</h1>
        
        <?php if (count($evaluations) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Comment</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evaluations as $evaluation): ?>
                        <tr>
                            <td><?= esc($evaluation->student_id) ?></td>
                            <td><?= esc($evaluation->comment) ?></td>
                            <td><?= esc($evaluation->created_at) ?></td>
                            <td><?= esc($evaluation->updated_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No evaluations found for this faculty in the selected semester.</p>
        <?php endif; ?>
    </div>
</body>
</html>
