<?php
session_start();

// Database connection
include("db.php");

// Check if PostID is passed in the URL
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Query to fetch the post details based on PostID
    $sql = "SELECT * FROM post WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the post exists, fetch it
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found!";
        exit;
    }
} else {
    echo "No PostID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['Title']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <!-- Back Button -->
    <a href="index.php" class="btn btn-secondary mb-3">Back to Posts</a>

    <!-- Post Details -->
    <div class="card">
        <img class="card-img-top" src="data:image/jpeg;base64,<?php echo base64_encode($post['Thumbnail']); ?>" alt="Post Image">
        <div class="card-body">
            <h2 class="card-title"><?php echo htmlspecialchars($post['Title']); ?></h2>
            <p class="text-muted"><small>Published on <?php echo date("F j, Y, g:i a", strtotime($post['PublicationDate'])); ?></small></p>
            <p class="card-text"><?php echo nl2br(htmlspecialchars($post['Content'])); ?></p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
