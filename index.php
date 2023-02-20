<?php

// Connect to the database
$db = new SQLite3('tasks.db');

// Check for form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle add/edit/delete tasks
    if (isset($_POST['add'])) {
        // Add a new task
        $title = $_POST['title'];
        $completed = 0;
        $stmt = $db->prepare('INSERT INTO tasks (title, completed) VALUES (:title, :completed)');
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':completed', $completed);
        $stmt->execute();
    } elseif (isset($_POST['edit'])) {
        // Edit an existing task
        $id = $_POST['id'];
        $title = $_POST['title'];
        $completed = isset($_POST['completed']) ? 1 : 0;
        $stmt = $db->prepare('UPDATE tasks SET title = :title, completed = :completed WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':completed', $completed);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        // Delete a task
        $id = $_POST['id'];
        $stmt = $db->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}

// Retrieve the list of tasks
$results = $db->query('SELECT * FROM tasks ORDER BY id DESC');

?>


<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-4">
        <h1 class="mb-4">Task List</h1>

        <form method="post" class="mb-4">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add</button>
        </form>

        <ul class="list-group">
            <?php while ($row = $results->fetchArray()): ?>
                <li class="list-group-item">
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" value="<?php echo $row['title']; ?>" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="completed" id="completed<?php echo $row['id']; ?>" class="form-check-input" value="1" <?php echo $row['completed'] ? 'checked' : ''; ?>>
                            <label for="completed<?php echo $row['id']; ?>" class="form-check-label">Completed</label>
                        </div>
                        <button type="submit" name="edit" class="btn btn-success mr-2">Save</button>
                        <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
