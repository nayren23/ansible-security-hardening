<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'entreprise';
$username = 'root';
$password = 'root';

$mysqli = new mysqli($host, $username, $password, $dbname);

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Fonction pour ajouter un employé
if (isset($_POST['submit_employe'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $departement = $_POST['departement'];
    $poste = $_POST['poste'];

    $stmt = $mysqli->prepare("INSERT INTO employes (nom, prenom, email, departement, poste) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nom, $prenom, $email, $departement, $poste);
    $stmt->execute();
    $stmt->close();
}

// Fonction pour ajouter un projet
if (isset($_POST['submit_projet'])) {
    $nom_projet = $_POST['nom_projet'];
    $description = $_POST['description'];
    $responsable = $_POST['responsable'];

    $stmt = $mysqli->prepare("INSERT INTO projets (nom_projet, description, responsable) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom_projet, $description, $responsable);
    $stmt->execute();
    $stmt->close();
}

// Suppression d'un employé
if (isset($_POST['supprimer_employe'])) {
    $id_employe = $_POST['id_employe'];
    $stmt = $mysqli->prepare("DELETE FROM employes WHERE id = ?");
    $stmt->bind_param("i", $id_employe);
    $stmt->execute();
    $stmt->close();
}

// Suppression d'un projet
if (isset($_POST['supprimer_projet'])) {
    $id_projet = $_POST['id_projet'];
    $stmt = $mysqli->prepare("DELETE FROM projets WHERE id = ?");
    $stmt->bind_param("i", $id_projet);
    $stmt->execute();
    $stmt->close();
}

// Fonction pour afficher les employés sous forme de tableau
function afficherEmployes() {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM employes");

    if ($result->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Poste</th>
                        <th>Département</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['nom'] . "</td>
                    <td>" . $row['prenom'] . "</td>
                    <td>" . $row['poste'] . "</td>
                    <td>" . $row['departement'] . "</td>
                    <td>
                        <form method='POST' action='index.php' style='display:inline;'>
                            <input type='hidden' name='id_employe' value='" . $row['id'] . "'>
                            <input type='submit' name='supprimer_employe' value='Supprimer' class='btn btn-danger' onclick='return confirm(\"Supprimer c                                                          et employé ?\")'>
                        </form>
                    </td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Aucun employé trouvé.";
    }
}

// Fonction pour afficher les projets sous forme de tableau
function afficherProjets() {
    global $mysqli;
    $result = $mysqli->query("SELECT p.id, p.nom_projet, p.description, e.prenom AS responsable_prenom, e.nom AS responsable_nom
                              FROM projets p
                              JOIN employes e ON p.responsable = e.id");

    if ($result->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du Projet</th>
                        <th>Description</th>
                        <th>Responsable</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['nom_projet'] . "</td>
                    <td>
                        <span style='cursor:pointer; color:blue;' onclick='afficherDescription(\"desc_" . $row['id'] . "\")'>
                            Voir la description
                        </span>
                        <p id='desc_" . $row['id'] . "' style='display:none;'>" . $row['description'] . "</p>
                    </td>
                    <td>" . $row['responsable_prenom'] . " " . $row['responsable_nom'] . "</td>
                    <td>
                        <form method='POST' action='index.php' style='display:inline;'>
                            <input type='hidden' name='id_projet' value='" . $row['id'] . "'>
                            <input type='submit' name='supprimer_projet' value='Supprimer' class='btn btn-danger' onclick='return confirm(\"Supprimer ce                                                           projet ?\")'>
                        </form>
                    </td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Aucun projet trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Web de l'entreprise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur le site de l'entreprise</h1>

        <!-- Formulaire pour ajouter un employé -->
        <h2>Ajouter un employé</h2>
        <form action="index.php" method="POST">
            <label for="nom">Nom : </label>
            <input type="text" id="nom" name="nom" required><br><br>
            <label for="prenom">Prénom : </label>
            <input type="text" id="prenom" name="prenom" required><br><br>
            <label for="email">Email : </label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="departement">Département : </label>
            <input type="text" id="departement" name="departement" required><br><br>
            <label for="poste">Poste : </label>
            <input type="text" id="poste" name="poste" required><br><br>
            <input type="submit" name="submit_employe" value="Ajouter l'employé">
        </form>

        <hr>

        <!-- Affichage des employés sous forme de tableau -->
        <h2>Liste des employés</h2>
        <?php afficherEmployes(); ?>

        <hr>

        <!-- Formulaire pour ajouter un projet -->
        <h2>Ajouter un projet</h2>
        <form action="index.php" method="POST">
            <label for="nom_projet">Nom du projet : </label>
            <input type="text" id="nom_projet" name="nom_projet" required><br><br>
            <label for="description">Description : </label>
            <textarea id="description" name="description" required></textarea><br><br>
            <label for="responsable">Responsable (ID employé) : </label>
            <input type="number" id="responsable" name="responsable" required><br><br>
            <input type="submit" name="submit_projet" value="Ajouter le projet">
        </form>

        <hr>

        <!-- Affichage des projets sous forme de tableau -->
        <h2>Liste des projets</h2>
        <?php afficherProjets(); ?>

    </div>

    <script>
        function afficherDescription(id) {
            var desc = document.getElementById(id);
            desc.style.display = (desc.style.display === "none") ? "block" : "none";
        }
    </script>

</body>
</html>
