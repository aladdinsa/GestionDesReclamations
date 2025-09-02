<?php
$servername = "localhost";
$username = "reclamation_user";
$password = "password123";
$dbname = "reclamation_db";

// Connect to the new database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

if ($conn->multi_query($sql_tables)) {
    echo "Tables created successfully\n";
} else {
    echo "Error creating tables: " . $conn->error . "\n";
}

$conn->close();
?>
