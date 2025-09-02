<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

$sql = "SELECT id, nom, prenom, email, role, date_creation FROM utilisateurs ORDER BY date_creation DESC";
$users = [];
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $users[] = $row;
    }
}
$conn->close();
?>

<div class="wrapper">
    <h2>Manage Users</h2>
    <?php if(empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['prenom'] . ' ' . $user['nom']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo $user['date_creation']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
