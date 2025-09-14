<?php
require_once "../includes/config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

if(isset($_GET['id']) && !empty($_GET['id'])){
    $id_to_delete = $_GET['id'];

    if($id_to_delete == $_SESSION['id']){
        // Optional: Add a message to the session to inform the admin why deletion failed.
        $_SESSION['delete_error'] = "You cannot delete your own account.";
        header("location: utilisateurs.php");
        exit;
    }

    $sql = "DELETE FROM utilisateurs WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id_to_delete);
        if($stmt->execute()){
            header("location: utilisateurs.php");
            exit;
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}
$conn->close();
?>
