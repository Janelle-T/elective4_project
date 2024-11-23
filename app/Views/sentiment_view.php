<h1>Sentiment Analysis Results</h1>
<table>
    <thead>
        <tr>
            <th>Text</th>
            <th>Sentiment</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?php echo htmlspecialchars($result['text']); ?></td>
                <td><?php echo $result['sentiment']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>