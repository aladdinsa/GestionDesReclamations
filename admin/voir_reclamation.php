<?php
require_once "../includes/config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

if(!isset($_GET["id"]) || empty(trim($_GET["id"]))){
    header("location: reclamations.php");
    exit;
}
$claim_id = trim($_GET["id"]);

$sql_claim = "SELECT r.*, c.nom as categorie, u.nom as gerant_nom, u.prenom as gerant_prenom FROM reclamations r JOIN categories c ON r.categorie_id = c.id JOIN utilisateurs u ON r.gerant_id = u.id WHERE r.id = ?";
$claim = null;
if($stmt = $conn->prepare($sql_claim)){
    $stmt->bind_param("i", $claim_id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $claim = $result->fetch_assoc();
        } else {
            header("location: reclamations.php");
            exit;
        }
    }
    $stmt->close();
}

$sql_technicians = "SELECT id, nom, prenom FROM utilisateurs WHERE role = 'technicien'";
$technicians_result = $conn->query($sql_technicians);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $technicien_id = trim($_POST["technicien_id"]);
    $statut = trim($_POST["statut"]);

    $sql_update = "UPDATE reclamations SET technicien_id = ?, statut = ? WHERE id = ?";
    if($stmt = $conn->prepare($sql_update)){
        $stmt->bind_param("isi", $technicien_id, $statut, $claim_id);
        if($stmt->execute()){
            header("location: reclamations.php");
            exit;
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}
$conn->close();

include '../includes/header.php';
?>

<div class="wrapper">
    <h2>Claim #<?php echo $claim['id']; ?></h2>

    <div class="claim-details">
        <p><strong>Manager:</strong> <?php echo $claim['gerant_prenom'] . ' ' . $claim['gerant_nom']; ?></p>
        <p><strong>Category:</strong> <?php echo $claim['categorie']; ?></p>
        <p><strong>Subject:</strong> <?php echo $claim['sujet']; ?></p>
        <p><strong>Description:</strong> <?php echo $claim['description']; ?></p>
        <p><strong>Priority:</strong> <?php echo $claim['priorite']; ?></p>
        <p><strong>Status:</strong> <?php echo $claim['statut']; ?></p>
        <p><strong>Date Created:</strong> <?php echo $claim['date_creation']; ?></p>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $claim_id; ?>" method="post">
        <div class="form-group">
            <label>Assign Technician</label>
            <select name="technicien_id" class="form-control">
                <option value="">Select a technician</option>
                <?php
                if ($technicians_result->num_rows > 0) {
                    while($row = $technicians_result->fetch_assoc()) {
                        $selected = ($row['id'] == $claim['technicien_id']) ? 'selected' : '';
                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['prenom'] . ' ' . $row['nom'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Change Status</label>
            <select name="statut" class="form-control">
                <option value="en attente" <?php echo ($claim['statut'] == 'en attente') ? 'selected' : ''; ?>>en attente</option>
                <option value="en cours" <?php echo ($claim['statut'] == 'en cours') ? 'selected' : ''; ?>>en cours</option>
                <option value="resolue" <?php echo ($claim['statut'] == 'resolue') ? 'selected' : ''; ?>>resolue</option>
                <option value="rejetee" <?php echo ($claim['statut'] == 'rejetee') ? 'selected' : ''; ?>>rejetee</option>
                <option value="validee" <?php echo ($claim['statut'] == 'validee') ? 'selected' : ''; ?>>validee</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Update Claim">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
