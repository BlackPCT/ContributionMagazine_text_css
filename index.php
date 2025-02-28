<?php
session_start();

// Include necessary files(ads)

require_once 'functions.php';

// Check if the user is logged in
if (!is_logged_in()) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit;
}

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Destroy session and redirect to login page if session has expired
    session_unset();
    session_destroy();
    header('Location: login.php?expired=true');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Display welcome message
$user = get_user($_SESSION['user']['username']);

// $user_id = $_SESSION['user']['id'];

// Fetch user's faculty information from the database
$stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE username = ?");
$stmt->execute([$user['username']]);
$user_faculty = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch submitted articles from the database
$stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 0 AND is_published = 0 AND faculty_name = ?");
$stmt->execute([$user_faculty['faculty_name']]);
$newArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Magazine</title>

        <!-- Bootstrap link -->
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/index.css">

</head>
<body>
    <div class="header">
        <nav class="navbar ">
            <marquee behavior="" direction="">Welcome To University Magazine, <?php echo $user['username']; ?>!</marquee>

            <div class="navbar-notification">
                <?php if (has_role('coordinator')) : ?>
                    <div class="notification-container">
        
                        <!-- Notification Bell -->
                        <div class="notification-bell" id="notificationBell">
                            <i class="fas fa-bell"></i>
                            <span class="badge" id="notificationCount"><?php echo count($newArticles); ?></span>
                        </div>
                        <!-- End Notification Bell -->
                    
                        <!-- Display new article submissions -->
                        <div class="notification-box" id="notificationBox">
                            <?php if (empty($newArticles)): ?>
                                <p>No new article submissions</p>
                            <?php else: ?>
                                <ul>
                                    <?php foreach ($newArticles as $article): ?>
                                        <li>
                                            <a href="student/view_article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a>
                                            <!-- Display other details of the article as needed -->
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <!-- End Display new article submissions -->
                    </div>
                <?php endif; ?>
            </div>
            <div class="navbar-header">
                <h2>Dashboard</h2>
                <a href="logout.php" class="logout">Logout</a>
            </div>
            <div>
                <ul>
                    <?php if (has_role('admin')) : ?>
                        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <?php if (has_role('admin')) : ?>
                        <li><a href="administrator/manage_user.php">Manage User</a></li>
                    <?php endif; ?>
                    <?php if (has_role('admin')) : ?>
                        <li><a href="administrator/manage_closure_dates.php">Manage Closure Dates</a></li>
                    <?php endif; ?>
                    <?php if (has_role('admin')) : ?>
                        <li><a href="administrator/manage_faculty.php">Manage Faculty</a></li>
                    <?php endif; ?>
                    <?php if (has_role('admin')) : ?>
                        <li><a href="administrator/report.php">Manage Report</a></li>
                    <?php endif; ?>
                    <?php if (has_role('coordinator')) : ?>
                        <li><a href="marketing_coordinator/coordinator_dashboard.php">Coordinator Dashboard</a></li>
                    <?php endif; ?>
                    <?php if (has_role('coordinator')) : ?>
                        <li><a href="administrator/report.php">Report</a></li>
                    <?php endif; ?>
                    <?php if (has_role('manager')) : ?>
                        <li><a href="marketing_manager/manager.php">Manager Dashboard</a></li>
                    <?php endif; ?>
                    <?php if (has_role('manager')) : ?>
                        <li><a href="news_feed.php">News Feed</a></li>
                    <?php endif; ?>
                    <?php if (has_role('manager')) : ?>
                        <li><a href="administrator/report.php">Report</a></li>
                    <?php endif; ?>
                    <?php if (has_role('student')) : ?>
                        <a href="student_dashboard.php">Student Dashboard</a>
                    <?php endif; ?>
                    <?php if (has_role('student')) : ?>
                        <li><a href="student/write_article.php">Write your article</a></li>
                    <?php endif; ?>
                    <?php if (has_role('student')) : ?>
                        <li><a href="student/manage_article.php">Manage your article</a></li>
                    <?php endif; ?>
                    <?php if (has_role('student')) : ?>
                        <li><a href="news_feed.php">News Feed</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <!-- Bell icon and notification count -->
    </div>



    <script>
        // JavaScript to toggle the visibility of new article submissions when clicking on the bell icon
        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notificationBell');
            const box = document.getElementById('notificationBox');

            bell.addEventListener('click', function() {
                box.classList.toggle('open');
            });
        });

        
    </script>

</body>
</html>
