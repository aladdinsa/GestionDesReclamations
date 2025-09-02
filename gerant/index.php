<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'gerant'){
    header("location: ../index.php");
    exit;
}
?>

<div class="wrapper">
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["email"]); ?></b>. Welcome to the Manager Dashboard.</h1>
    </div>
    <div class="dashboard-menu">
        <a href="creer_reclamation.php">Create a new Claim</a>
        <a href="suivi_reclamations.php">Track Claims</a>
        <a href="historique_reclamations.php">Claims History</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
