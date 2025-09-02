<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

$sql = "SELECT r.id, c.nom as categorie, u.nom as gerant_nom, u.prenom as gerant_prenom, r.sujet, r.statut, r.date_creation FROM reclamations r JOIN categories c ON r.categorie_id = c.id JOIN utilisateurs u ON r.gerant_id = u.id ORDER BY r.date_creation DESC";
$claims = [];
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $claims[] = $row;
    }
}
$conn->close();
?>

<div class="wrapper">
    <h2>Manage Claims</h2>
    <?php if(empty($claims)): ?>
        <p>No claims found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Manager</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($claims as $claim): ?>
                    <tr>
                        <td><?php echo $claim['id']; ?></td>
                        <td><?php echo $claim['categorie']; ?></td>
                        <td><?php echo $claim['gerant_prenom'] . ' ' . $claim['gerant_nom']; ?></td>
                        <td><?php echo $claim['sujet']; ?></td>
                        <td><?php echo $claim['statut']; ?></td>
                        <td><?php echo $claim['date_creation']; ?></td>
                        <td><a href="voir_reclamation.php?id=<?php echo $claim['id']; ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
