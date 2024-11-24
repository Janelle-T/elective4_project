<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis Results</title>
</head>
<body>
    <h1>Sentiment Analysis Results</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Error: <?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Text</th>
                    <th>Negative</th>
                    <th>Neutral</th>
                    <th>Positive</th>
                    <th>Predicted Label</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?= htmlspecialchars($result['text']) ?></td>
                        <td><?= htmlspecialchars($result['Negative']) ?></td>
                        <td><?= htmlspecialchars($result['Neutral']) ?></td>
                        <td><?= htmlspecialchars($result['Positive']) ?></td>
                        <td><?= htmlspecialchars($result['predicted_label']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>