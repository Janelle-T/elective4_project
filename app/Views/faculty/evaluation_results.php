<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3 {
            color: black;
        }
        form {
            margin-bottom: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        form label {
            font-weight: bold;
        }
        select {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            border: none;
            background-color: #0056b3;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004494;
        }
        .card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f9;
            color: #0056b3;
        }
        canvas {
            display: block;
            margin: 0 auto;
            max-width: 300px;
        }

        /* Print-specific styles */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="no-print">Evaluation Results</h1>

        <!-- Form to Select Academic Semester -->
        <form method="post" action="<?= base_url('evaluation/results') ?>" class="no-print">
            <label for="academic_id">Select Academic Year and Semester:</label>
            <select name="academic_id" id="academic_id" required>
                <option value="" disabled selected>Choose...</option>
                <?php foreach ($academicOptions as $academic): ?>
                    <option value="<?= esc($academic['id']) ?>" <?= isset($selectedAcademic) && $selectedAcademic['id'] == $academic['id'] ? 'selected' : '' ?>
                    ><?= esc($academic['school_year']) ?> - <?= esc($academic['semester'] == 1 ? '1st Semester' : '2nd Semester') ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="no-print">View Results</button>
            <!-- Print Button -->
        <button onclick="window.print();" class="no-print">Print Results</button>
        </form>

        

        <?php if (isset($errorMessage)): ?>
            <div class="card">
                <p><?= esc($errorMessage) ?></p>
            </div>
        <?php endif; ?>

        <!-- Display Summarized Evaluation Results if available -->
        <?php if (isset($summaryResults) && !empty($summaryResults)): ?>
            <div class="card">
                <h2 class="no-print">Summary of Ratings for Academic Year: <?= esc($selectedAcademic['school_year']) ?> - 
                    <?= esc($selectedAcademic['semester'] == 1 ? '1st Semester' : '2nd Semester') ?>
                </h2>

                <?php if (session()->get('isLoggedIn')): ?>
                    <h3>Name: <?= esc(session()->get('full_name')) ?></h3>
                <?php endif; ?>

                <h3>Total Number of Responses: <?= esc($summaryResults[0]['total_evaluations']) ?> </h3>

                <?php
                $totalRating = 0;
                $totalCount = count($summaryResults);
                foreach ($summaryResults as $result) {
                    $totalRating += $result['average_rating'];
                }
                $totalAverageRating = ($totalCount > 0) ? $totalRating / $totalCount : 0;
                ?>

                <h3>Total Average Rating: <?= number_format($totalAverageRating, 2) ?></h3>

                <?php
                $descriptiveRating = '';
                if ($totalAverageRating >= 5) {
                    $descriptiveRating = 'Outstanding';
                } elseif ($totalAverageRating >= 4) {
                    $descriptiveRating = 'Very Satisfactory';
                } elseif ($totalAverageRating >= 3) {
                    $descriptiveRating = 'Satisfactory';
                } elseif ($totalAverageRating >= 2) {
                    $descriptiveRating = 'Fair';
                } else {
                    $descriptiveRating = 'Poor';
                }
                ?>

                <h3>Descriptive Rating: <?= esc($descriptiveRating) ?></h3>
            </div>

            <div class="card">
                <h3>Evaluation Details</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Average Rating</th>
                            <th>Chart</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($summaryResults as $index => $result): ?>
                            <tr>
                                <td><?= esc($result['question_text']) ?></td>
                                <td><?= number_format($result['average_rating'], 2) ?></td>
                                <td><canvas id="chart_<?= $index ?>"></canvas></td>
                            </tr>

                            <script>
                                var ctx = document.getElementById('chart_<?= $index ?>').getContext('2d');
                                var individualRatings = <?= json_encode($result['individual_ratings']) ?>;
                                var averageRating = <?= esc($result['average_rating']) ?>;

                                var ratingColors = {
                                    1: 'rgba(255, 99, 132, 0.2)',
                                    2: 'rgba(255, 159, 64, 0.2)',
                                    3: 'rgba(255, 205, 86, 0.2)',
                                    4: 'rgba(75, 192, 192, 0.2)',
                                    5: 'rgba(54, 162, 235, 0.2)'
                                };

                                var individualData = [0, 0, 0, 0, 0];
                                var individualRatingColors = [];

                                individualRatings.forEach(function(rating) {
                                    if (rating.rate >= 1 && rating.rate <= 5) {
                                        individualData[rating.rate - 1]++;
                                        individualRatingColors.push(ratingColors[rating.rate]);
                                    }
                                });

                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['1', '2', '3', '4', '5'],
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
                                                data: individualData,
                                                backgroundColor: individualRatingColors,
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
            </div>

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
