<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Make sure to create a db_connection.php for database connection

// Fetch posts from the database
$query = "SELECT PostID, Thumbnail, Title, Content, Category FROM post ORDER BY PublicationDate DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" id="sidebar" style="width: 250px;">
            <h4 class="mb-4">Dashboard</h4>
            
            <!-- Button to toggle the menu (Manage Post) with arrow -->
            <button class="btn btn-sidebar mb-3 w-100" type="button" data-toggle="collapse" data-target="#menu" aria-expanded="false" aria-controls="menu" id="managePostBtn">
                Manage Post <i class="fas fa-chevron-down float-right"></i>
            </button>

            <!-- Collapsible Menu -->
            <div class="collapse" id="menu">
                <ul class="list-unstyled">
                    <li><a class="nav-link sidebar-link" href="add_post.php"><i class="fas fa-plus"></i> Add Post</a></li>
                    <li><a class="nav-link sidebar-link" href="index.php"><i class="fas fa-edit"></i> Homepage</a></li>
                </ul>
            </div>
            
            <!-- Profile Menu -->
            <button class="btn btn-sidebar mb-3 w-100" type="button" data-toggle="collapse" data-target="#profileMenu" aria-expanded="false" aria-controls="profileMenu" id="profileBtn">
                Username <i class="fas fa-chevron-down float-right"></i>
            </button>
            <div class="collapse" id="profileMenu">
                <ul class="list-unstyled">
                    <li><a class="nav-link sidebar-link" href="#"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a class="nav-link sidebar-link" href="#"><i class="fas fa-cogs"></i> Settings</a></li>
                    <li><a class="nav-link sidebar-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4" id="content">
            <h2 class="mb-4">Post List</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['PostID']; ?></td>
                                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['Thumbnail']); ?>" alt="Thumbnail" width="50"></td>
                                <td><?php echo htmlspecialchars($row['Title']); ?></td>
                                <td><?php echo htmlspecialchars($row['Category']); ?></td>
                                <td>
                                    <a href="edit_post.php?id=<?php echo $row['PostID']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete_post.php?id=<?php echo $row['PostID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">There's No Post Yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Add event listeners for the buttons
        const managePostBtn = document.getElementById('managePostBtn');
        const profileBtn = document.getElementById('profileBtn');

        function toggleArrowRotation(button) {
            const arrow = button.querySelector('i');
            arrow.classList.toggle('rotate-arrow');
        }

        managePostBtn.addEventListener('click', function () {
            toggleArrowRotation(managePostBtn);
        });

        profileBtn.addEventListener('click', function () {
            toggleArrowRotation(profileBtn);
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
