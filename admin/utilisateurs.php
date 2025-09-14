<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

// Get user counts
$sql_total = "SELECT COUNT(id) as total FROM utilisateurs";
$total_users = $conn->query($sql_total)->fetch_assoc()['total'];

$sql_admin = "SELECT COUNT(id) as total FROM utilisateurs WHERE role = 'admin'";
$admin_users = $conn->query($sql_admin)->fetch_assoc()['total'];

$sql_gerant = "SELECT COUNT(id) as total FROM utilisateurs WHERE role = 'gerant'";
$gerant_users = $conn->query($sql_gerant)->fetch_assoc()['total'];


$selected_role = isset($_GET['role']) ? $_GET['role'] : '';

$sql = "SELECT id, nom, prenom, email, role, date_creation FROM utilisateurs";
if(!empty($selected_role) && in_array($selected_role, ['admin', 'gerant'])){
    $sql .= " WHERE role = '" . $selected_role . "'";
}
$sql .= " ORDER BY date_creation DESC";

$users = [];
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $users[] = $row;
    }
}
?>

<div class="wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Manage Users</h2>
        <a href="select_role.php" class="btn btn-primary">Create New User</a>
    </div>

    <div class="user-counts" style="display: flex; justify-content: space-around; margin: 20px 0; background: #f9f9f9; padding: 10px; border-radius: 5px;">
        <p>Total Users: <strong><?php echo $total_users; ?></strong></p>
        <p>Admins: <strong><?php echo $admin_users; ?></strong></p>
        <p>Gerants: <strong><?php echo $gerant_users; ?></strong></p>
    </div>

    <form action="utilisateurs.php" method="get" class="form-group">
        <label for="role">Filter by role:</label>
        <select name="role" id="role" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="admin" <?php if($selected_role == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="gerant" <?php if($selected_role == 'gerant') echo 'selected'; ?>>Gerant</option>
        </select>
    </form>

    <?php if(empty($users)): ?>
        <p>No users found for the selected role.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date Created</th>
                    <th>Actions</th>
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
                        <td>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
