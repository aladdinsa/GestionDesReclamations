<?php
// This is a basic header
// It includes the session start and the basic html structure
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SNDP Claims Management</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo">SNDP Claims</h1>
            <nav>
                <ul>
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <?php if($_SESSION["role"] === 'admin'): ?>
                            <li><a href="/admin/index.php">Dashboard</a></li>
                            <li><a href="/admin/reclamations.php">Claims</a></li>
                            <li><a href="/admin/utilisateurs.php">Users</a></li>
                            <li><a href="/admin/rapports.php">Reports</a></li>
                        <?php elseif($_SESSION["role"] === 'gerant'): ?>
                            <li><a href="/gerant/index.php">Dashboard</a></li>
                            <li><a href="/gerant/creer_reclamation.php">New Claim</a></li>
                            <li><a href="/gerant/suivi_reclamations.php">Track Claims</a></li>
                            <li><a href="/gerant/historique_reclamations.php">History</a></li>
                        <?php endif; ?>
                        <li><a href="/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/index.php">Login</a></li>
                        <li><a href="/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
