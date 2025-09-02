<?php
require_once "../includes/config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'gerant'){
    header("location: ../index.php");
    exit;
}

$sql_categories = "SELECT id, nom FROM categories";
$categories_result = $conn->query($sql_categories);

$categorie_id = $sujet = $description = $priorite = "";
$categorie_id_err = $sujet_err = $description_err = $priorite_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["categorie_id"]))){
        $categorie_id_err = "Please select a category.";
    } else{
        $categorie_id = trim($_POST["categorie_id"]);
    }

    if(empty(trim($_POST["sujet"]))){
        $sujet_err = "Please enter a subject.";
    } else{
        $sujet = trim($_POST["sujet"]);
    }

    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";
    } else{
        $description = trim($_POST["description"]);
    }

    if(empty(trim($_POST["priorite"]))){
        $priorite_err = "Please select a priority.";
    } else{
        $priorite = trim($_POST["priorite"]);
    }

    if(empty($categorie_id_err) && empty($sujet_err) && empty($description_err) && empty($priorite_err)){
        $sql = "INSERT INTO reclamations (gerant_id, categorie_id, sujet, description, priorite) VALUES (?, ?, ?, ?, ?)";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("iisss", $param_gerant_id, $param_categorie_id, $param_sujet, $param_description, $param_priorite);
            $param_gerant_id = $_SESSION["id"];
            $param_categorie_id = $categorie_id;
            $param_sujet = $sujet;
            $param_description = $description;
            $param_priorite = $priorite;

            if($stmt->execute()){
                header("location: suivi_reclamations.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}

include '../includes/header.php';
?>

<div class="wrapper">
    <h2>Create a New Claim</h2>
    <p>Please fill this form to create a new claim.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Category</label>
            <select name="categorie_id" class="form-control <?php echo (!empty($categorie_id_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select a category</option>
                <?php
                if ($categories_result->num_rows > 0) {
                    while($row = $categories_result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['nom'] . '</option>';
                    }
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php echo $categorie_id_err; ?></span>
        </div>
        <div class="form-group">
            <label>Subject</label>
            <input type="text" name="sujet" class="form-control <?php echo (!empty($sujet_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sujet; ?>">
            <span class="invalid-feedback"><?php echo $sujet_err; ?></span>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
            <span class="invalid-feedback"><?php echo $description_err; ?></span>
        </div>
        <div class="form-group">
            <label>Priority</label>
            <select name="priorite" class="form-control <?php echo (!empty($priorite_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select a priority</option>
                <option value="basse">Low</option>
                <option value="moyenne">Medium</option>
                <option value="elevee">High</option>
            </select>
            <span class="invalid-feedback"><?php echo $priorite_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
