<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'gerant'){
    header("location: ../index.php");
    exit;
}

$sql = "SELECT r.id, c.nom as categorie, r.sujet, r.statut, r.date_creation FROM reclamations r JOIN categories c ON r.categorie_id = c.id WHERE r.gerant_id = ? AND r.statut NOT IN ('resolue', 'rejetee') ORDER BY r.date_creation DESC";
$claims = [];
if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $param_gerant_id);
    $param_gerant_id = $_SESSION["id"];
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $claims[] = $row;
            }
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt->close();
}
$conn->close();
?>

<div class="wrapper">
    <h2>Track Claims</h2>
    <?php if(empty($claims)): ?>
        <p>You have no active claims.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($claims as $claim): ?>
                    <tr>
                        <td><?php echo $claim['id']; ?></td>
                        <td><?php echo $claim['categorie']; ?></td>
                        <td><?php echo $claim['sujet']; ?></td>
                        <td><?php echo $claim['statut']; ?></td>
                        <td><?php echo $claim['date_creation']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
