<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php if ($results): ?>
        <?php foreach ($results as $faculty): ?>
            <h2><?= esc($faculty['faculty_name']) ?></h2>
            <p>Average Rating: <?= number_format($faculty['average_rating'], 2) ?></p>

            <h3>Sentiment Analysis</h3>
            <canvas id="sentimentChart_<?= $faculty['faculty_id'] ?>" width="400" height="200"></canvas>
            <script>
                new Chart(document.getElementById('sentimentChart_<?= $faculty['faculty_id'] ?>'), {
                    type: 'bar',
                    data: {
                        labels: ['Positive', 'Neutral', 'Negative'],
                        datasets: [{
                            data: [
                                <?= $faculty['sentiments']['positive'] ?>,
                                <?= $faculty['sentiments']['neutral'] ?>,
                                <?= $faculty['sentiments']['negative'] ?>
                            ],
                            backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 206, 86, 1)', 'rgba(255, 99, 132, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: { y: { beginAtZero: true } }
                    }
                });
            </script>

            <h3>Comments and Tokenized Sentiment Analysis</h3>
            <?php if (!empty($faculty['comments'])): ?>
                <ul>
                    <?php foreach ($faculty['comments'] as $comment): ?>
                        <?php 
                            // Tokenized sentiment analysis logic
                            $tokens = preg_split('/[\s,.!?;:()]+/', strtolower($comment), -1, PREG_SPLIT_NO_EMPTY);
                            $positiveWords = ['good', 'excellent', 'great', 'amazing', 'positive', 'happy', 'wonderful', 'outstanding'];
                            $negativeWords = ['bad', 'poor', 'terrible', 'negative', 'sad', 'horrible', 'awful', 'disappointing'];

                            $positiveCount = 0;
                            $negativeCount = 0;

                            foreach ($tokens as $token) {
                                if (in_array($token, $positiveWords)) {
                                    $positiveCount++;
                                }
                                if (in_array($token, $negativeWords)) {
                                    $negativeCount++;
                                }
                            }

                            $commentSentiment = 'Neutral';
                            if ($positiveCount > $negativeCount) {
                                $commentSentiment = 'Positive';
                            } elseif ($negativeCount > $positiveCount) {
                                $commentSentiment = 'Negative';
                            }
                        ?>
                        <li>
                            <?= esc($comment) ?>
                            <strong>(Sentiment: <?= $commentSentiment ?>)</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No comments available.</p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No evaluation data found.</p>
    <?php endif; ?>
</body>
</html>
