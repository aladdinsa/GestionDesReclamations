<?php
$servername = "localhost";
// For this setup script, we will use the 'root' user which has privileges to create a database.
// The main application will still use the 'reclamation_user'.
$setup_username = "root";
$setup_password = ""; // Default XAMPP root password is empty. Change if you have set one.
$dbname = "reclamation_db";

// --- Step 1: Connect as root and create the database ---
$conn_setup = new mysqli($servername, $setup_username, $setup_password);

// Check connection
if ($conn_setup->connect_error) {
    die("Connection failed: " . $conn_setup->connect_error . "\n" . "Please ensure your MySQL server is running and the root user credentials are correct.");
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn_setup->query($sql_create_db) === TRUE) {
    echo "Database '$dbname' created successfully or already exists.\n";
} else {
    echo "Error creating database: " . $conn_setup->error . "\n";
    $conn_setup->close();
    exit();
}
$conn_setup->close();


// --- Step 2: Connect to the new database as the application user and create tables ---
$app_username = "reclamation_user";
$app_password = "password123";
$conn_app = new mysqli($servername, $app_username, $app_password, $dbname);

// Check connection
if ($conn_app->connect_error) {
    die("Connection to database '$dbname' failed: " . $conn_app->connect_error . "\n" . "This usually means the 'reclamation_user' has not been created or has the wrong password. Please run the user creation SQL commands.");
}

// SQL to create tables
$sql_tables = "
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('gerant','admin','technicien') NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `reclamations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gerant_id` int(11) NOT NULL,
  `technicien_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `statut` enum('en attente','en cours','resolue','rejetee','validee') NOT NULL DEFAULT 'en attente',
  `priorite` enum('basse','moyenne','elevee') NOT NULL DEFAULT 'moyenne',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_resolution` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gerant_id` (`gerant_id`),
  KEY `technicien_id` (`technicien_id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `reclamations_ibfk_1` FOREIGN KEY (`gerant_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `reclamations_ibfk_2` FOREIGN KEY (`technicien_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `reclamations_ibfk_3` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reclamation_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reclamation_id` (`reclamation_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`reclamation_id`) REFERENCES `reclamations` (`id`),
  CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn_app->multi_query($sql_tables)) {
    // This loop is necessary to clear results from multi_query before running another query
    while ($conn_app->next_result()) {
        if ($res = $conn_app->store_result()) {
            $res->free();
        }
    }
    echo "Tables created successfully.\n";
} else {
    echo "Error creating tables: " . $conn_app->error . "\n";
}

$conn_app->close();

echo "Setup complete!\n";
?>
