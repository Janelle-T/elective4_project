<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Display Summarized Evaluation Results if available -->
        <?php if (isset($summaryResults) && !empty($summaryResults)): ?>
            <h2>Summary of Ratings for Academic Year: <?= esc($selectedAcademic['school_year']) ?> - 
                <?= esc($selectedAcademic['semester'] == 1 ? '1st Semester' : '2nd Semester') ?></h2>
            
            <table border="1">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Average Rating</th>
                        <th>Total Evaluations</th>
                        <th>Chart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summaryResults as $index => $result): ?>
                        <tr>
                            <td><?= esc($result['question_text']) ?></td>
                            <td><?= number_format($result['average_rating'], 2) ?></td>
                            <td><?= esc($result['total_evaluations']) ?></td>
                            <td><canvas id="chart_<?= $index ?>"></canvas></td>
                        </tr>

                        <!-- Create a chart for each result -->
                        <script>
                            var ctx = document.getElementById('chart_<?= $index ?>').getContext('2d');

                            var individualRatings = <?= json_encode($result['individual_ratings']) ?>; // Assuming this is an array of individual ratings
                            var averageRating = <?= esc($result['average_rating']) ?>;

                            // Color mapping for individual ratings
                            var ratingColors = {
                                1: 'rgba(255, 99, 132, 0.2)', // Red
                                2: 'rgba(255, 159, 64, 0.2)', // Orange
                                3: 'rgba(255, 205, 86, 0.2)', // Yellow
                                4: 'rgba(75, 192, 192, 0.2)', // Green
                                5: 'rgba(54, 162, 235, 0.2)'  // Blue
                            };

                            // Prepare individual ratings data and colors
                            var individualData = [0, 0, 0, 0, 0]; // Array to hold counts for ratings 1-5
                            var individualRatingColors = []; // Array to hold colors

                            individualRatings.forEach(function(rating) {
                                if (rating.rate >= 1 && rating.rate <= 5) {
                                    individualData[rating.rate - 1]++; // Increment the count for the corresponding rating
                                    individualRatingColors.push(ratingColors[rating.rate]); // Store the color
                                }
                            });

                            var chart = new Chart(ctx, {
                                type: 'bar', // Change type if needed
                                data: {
                                    labels: ['1', '2', '3', '4', '5'], // Ratings 1-5
                                    datasets: [
                                        {
                                            label: 'Average Rating',
                                            data: [averageRating],
                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Individual Ratings',
                                            data: individualData, // Counts for each rating (1-5)
                                            backgroundColor: individualRatingColors, // Color array
                                            borderColor: 'rgba(0, 0, 0, 0.1)',
                                            borderWidth: 1
                                        }
                                    ]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        </script>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
