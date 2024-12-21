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

            <!-- Display Overall Tokenized Comments and Sentiment -->
           <div class="card">
    <h3>Overall Comments and Sentiment</h3>
    
    <p><strong>Tokenized Comments:</strong>
        <?php
            // Initialize an array to collect all tokenized comments
            $allComments = [];
            
            // Loop through the results and merge the tokenized comments
            foreach ($summaryResults as $result) {
                if (!empty($result['tokenized_comment'])) {
                    // Ensure the tokenized comment is an array before merging
                    if (is_array($result['tokenized_comment'])) {
                        $allComments = array_merge($allComments, $result['tokenized_comment']);
                    } else {
                        // If it's a string, convert it to an array (split by spaces or commas)
                        $allComments = array_merge($allComments, explode(' ', $result['tokenized_comment']));
                    }
                }
            }
            
            // Remove duplicates and display tokenized comments
            $uniqueComments = array_unique($allComments);
            $tokenizedComments = implode(', ', array_map('esc', $uniqueComments));
            echo $tokenizedComments;
        ?>
    </p>
    
    <p><strong>Sentiment:</strong>
        <?php
            // Initialize an array to collect all sentiments
            $allSentiments = [];
            
            // Loop through the results and collect the sentiments
            foreach ($summaryResults as $result) {
                if (!empty($result['sentiment'])) {
                    // Merge the sentiments
                    $allSentiments[] = $result['sentiment'];
                }
            }
            
            // Remove duplicates and display sentiments
            $uniqueSentiments = array_unique($allSentiments);
            $sentiment = implode(', ', array_map('esc', $uniqueSentiments));
            echo $sentiment;
        ?>
    </p>
</div>


        <?php endif; ?>
    </div>
</body>
</html>
