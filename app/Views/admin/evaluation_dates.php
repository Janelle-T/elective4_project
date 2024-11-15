<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Dates</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>Evaluation Dates</h1>

<!-- Button to trigger Create modal -->
<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create New Evaluation Date</a>

<!-- Table displaying the evaluation dates -->
<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Open Date & Time</th>
            <th>Close Date & Time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($evaluationDates as $evaluationDate): ?>
        <tr>
            <td><?= $evaluationDate['id'] ?></td>
            <td><?= $evaluationDate['open_datetime'] ?></td>
            <td><?= $evaluationDate['close_datetime'] ?></td>
            <td>
                <!-- Edit button triggers the Edit modal -->
                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" 
                   data-id="<?= $evaluationDate['id'] ?>"
                   data-open-datetime="<?= date('Y-m-d\TH:i', strtotime($evaluationDate['open_datetime'])) ?>"
                   data-close-datetime="<?= date('Y-m-d\TH:i', strtotime($evaluationDate['close_datetime'])) ?>">Edit</a>
                <!-- Delete button -->
                <a href="/evaluation-dates/delete/<?= $evaluationDate['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal for Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Evaluation Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('evaluation-dates/store') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="open_datetime" class="form-label">Open Date & Time</label>
                        <input type="datetime-local" name="open_datetime" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="close_datetime" class="form-label">Close Date & Time</label>
                        <input type="datetime-local" name="close_datetime" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Evaluation Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('evaluation-dates/edit') ?> method="post" id="editForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label for="open_datetime" class="form-label">Open Date & Time</label>
                        <input type="datetime-local" name="open_datetime" class="form-control" id="edit-open-datetime" required>
                    </div>
                    <div class="mb-3">
                        <label for="close_datetime" class="form-label">Close Date & Time</label>
                        <input type="datetime-local" name="close_datetime" class="form-control" id="edit-close-datetime" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // When the Edit button is clicked, populate the Edit modal with the current data
    document.addEventListener('DOMContentLoaded', function() {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var id = button.getAttribute('data-id');
            var openDatetime = button.getAttribute('data-open-datetime');
            var closeDatetime = button.getAttribute('data-close-datetime');

            var form = document.getElementById('editForm');
            form.action = '/evaluation-dates/update/' + id; // Set the form action to the correct URL

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-open-datetime').value = openDatetime;
            document.getElementById('edit-close-datetime').value = closeDatetime;
        });
    });
</script>
</body>
</html>
