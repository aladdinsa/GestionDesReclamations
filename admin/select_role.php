<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $role = $_POST['role'];
    header("location: create_user.php?role=" . $role);
    exit;
}
?>

<div class="wrapper">
    <h2>Select Role for New User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="admin">Admin</option>
                <option value="gerant">Gerant</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Next">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
