<?php
require_once "../includes/config.php";
include '../includes/header.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: ../index.php");
    exit;
}

// Claims per category
$sql_cat = "SELECT c.nom, COUNT(r.id) as count FROM categories c LEFT JOIN reclamations r ON c.id = r.categorie_id GROUP BY c.nom";
$claims_by_cat = [];
$result_cat = $conn->query($sql_cat);
if($result_cat->num_rows > 0){
    while($row = $result_cat->fetch_assoc()){
        $claims_by_cat[] = $row;
    }
}

// Claims per status
$sql_stat = "SELECT statut, COUNT(id) as count FROM reclamations GROUP BY statut";
$claims_by_stat = [];
$result_stat = $conn->query($sql_stat);
if($result_stat->num_rows > 0){
    while($row = $result_stat->fetch_assoc()){
        $claims_by_stat[] = $row;
    }
}

// Recently resolved claims
$sql_resolved = "SELECT r.id, c.nom as categorie, r.sujet, r.date_resolution FROM reclamations r JOIN categories c ON r.categorie_id = c.id WHERE r.statut = 'resolue' ORDER BY r.date_resolution DESC LIMIT 10";
$resolved_claims = [];
$result_resolved = $conn->query($sql_resolved);
if($result_resolved->num_rows > 0){
    while($row = $result_resolved->fetch_assoc()){
        $resolved_claims[] = $row;
    }
}
$conn->close();
?>

<div class="wrapper">
    <h2>Reports</h2>

    <div class="report-section">
        <h3>Claims by Category</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Number of Claims</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($claims_by_cat as $cat): ?>
                    <tr>
                        <td><?php echo $cat['nom']; ?></td>
                        <td><?php echo $cat['count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="report-section">
        <h3>Claims by Status</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Number of Claims</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($claims_by_stat as $stat): ?>
                    <tr>
                        <td><?php echo $stat['statut']; ?></td>
                        <td><?php echo $stat['count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="report-section">
        <h3>Recently Resolved Claims</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>Date Resolved</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($resolved_claims as $claim): ?>
                    <tr>
                        <td><?php echo $claim['id']; ?></td>
                        <td><?php echo $claim['categorie']; ?></td>
                        <td><?php echo $claim['sujet']; ?></td>
                        <td><?php echo $claim['date_resolution']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
