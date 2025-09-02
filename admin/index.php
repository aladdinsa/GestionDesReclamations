<?php
require_once "../includes/config.php";
include '../includes/header.php';

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}
?>

<div class="wrapper">
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["email"]); ?></b>. Welcome to the Admin Dashboard.</h1>
    </div>
    <div class="dashboard-menu">
        <a href="reclamations.php">Manage Claims</a>
        <a href="utilisateurs.php">Manage Users</a>
        <a href="rapports.php">View Reports</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
