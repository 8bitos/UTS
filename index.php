<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include("db.php");

// Number of posts per page
$posts_per_page = 15;

// Get the current page from URL, default is 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting point for the query
$start_from = ($page - 1) * $posts_per_page;

// Fetch posts from the database
$sql = "SELECT * FROM post ORDER BY PublicationDate DESC LIMIT $start_from, $posts_per_page";
$result = $conn->query($sql);

// Get total number of posts to calculate total pages
$sql_count = "SELECT COUNT(PostID) FROM post";
$count_result = $conn->query($sql_count);
$total_posts = $count_result->fetch_row()[0];
$total_pages = ceil($total_posts / $posts_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Sticky footer styles */
        html, body {
            height: 100%;
        }
        .content {
            min-height: 100%;
            padding-bottom: 50px; /* space for footer */
        }
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
        }

        /* Flexbox for the navbar */
        .navbar {
            position: absolute
            display: flex;
            top: 0;
            width: 100%;
            justify-content: space-between;
            align-items: center;

            
        }
    </style>
</head>
<body>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand" href="#">My Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if ($_SESSION['role'] === 'admin') { ?>
                            <a class="dropdown-item" href="dashboard.php">Dashboard</a>
                        <?php } ?>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5 content">
    <h2 class="mb-4">Latest Posts</h2>

    <!-- Display posts in a grid with cards -->
    <div class="row">
        <?php
        $counter = 0;
        while ($post = $result->fetch_assoc()) {
            if ($counter >= 15) break; // Ensure no more than 15 posts are shown
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img class="card-img-top" src="data:image/jpeg;base64,<?php echo base64_encode($post['Thumbnail']); ?>" alt="Post Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($post['Title']); ?></h5>
                        <p class="card-text"><?php echo substr($post['Content'], 0, 100); ?>...</p>
                        <a href="view_post.php?id=<?php echo $post['PostID']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            <?php
            $counter++;
        }
        ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1) { ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>

            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php
            }
            ?>

            <?php if ($page < $total_pages) { ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 My Website. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
