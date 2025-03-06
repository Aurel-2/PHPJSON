<?php
$message = "";
require_once 'MedicalFile.php';
require_once 'FilesManager.php';
$medicalManager = new FilesManager();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On lit ce que le post nous a renvoyé
    $data = json_decode(file_get_contents('php://input'), true);
    // On regarde l'action si y en a une
    if (isset($data['action'])) {
        // Si c'est pour ajout
        if ($data['action'] == 'addDiagnosis') {
            $id = intval($data['id']);
            $date = $data['date'];
            $diag = $data['diag'];
            $docs = $medicalManager->loadDocs();
            foreach ($docs as &$doc) {
                // on ajoute le nouveau diagnostic avec l'id qui correspond
                if ($doc['id'] == $id) {
                    $doc['diagnosisList'][] = ['Date' => $date, 'Diagnostic' => $diag];
                    break;
                }
            }
            // Puis on sauvegarde le catalogue
            $medicalManager->saveDocs($docs);
            // Si c'est l'action de supprimer
        }
         if ($data['action'] == 'delete') {
            $id = $data['id'];
            $docs = $medicalManager->loadDocs();
            // On récupère une version des dossiers sans l'élément à supprimer
             $docs = array_values(array_filter($docs, function($doc) use ($id) {
                 return $doc['id'] != $id;
             }));
             print("suppression ok");
            // Sauvegarde la nouvelle version des dossiers
            $medicalManager->saveDocs($docs);
        }
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $name = htmlspecialchars($_POST["name"]);
    $age = intval($_POST["age"]);
    $date = htmlspecialchars($_POST["date"]);
    $diag = $_POST["diag"];
    if (empty($id) || empty($name) || empty($age) || empty($date)) {
        $message = "<h3 style='color: red'>Veuillez remplir tous les champs</h3>";
    }

    $data = $medicalManager->loadDocs();
    foreach ($data as $doc) {
        if ($doc["id"] == $id) {
            $message = "<h3 style='color:red'> L'ID saisi existe déjà</h3>";
        }
    }
    if (empty($message)) {
        $newDoc = new MedicalFile($name, $age, $id);
        $newDoc->addDiagnosis($date, $diag);
        $medicalManager->addMedicalToDocs($newDoc);
        $message = "<h3 style='color: green'>Le nouveau patient a été ajouté avec succès";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Gestion de dossier médical</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body onload="init()">

<h1>Gestion des dossiers médicaux</h1>
<div class="container">
    <form id="medical-form" method="post" action="GestionDossiersMedicaux.php">
        <div>
            <label for="id">ID</label>
            <input id="id" name="id" type="number">
        </div>
        <div>
            <label for="name">Nom</label>
            <input id="name" name="name" type="text">
        </div>
        <div>
            <label for="age">Age</label>
            <input id="age" name="age" type="number">
        </div>
        <div>
            <label for="date">Date</label>
            <input id="date" name="date" type="date">
        </div>
        <div>
            <label for="diag">Diagnostic</label>
            <input id="diag" name="diag" type="text">
        </div>
        <input type="submit" value="Ajouter">
        <?php echo $message; ?>

    </form>
</div>
<div id="search">
    <label for="filter">Filtrer</label>
    <input id="filter" name="filter" placeholder="Filtrer ici..." type="text">
    <div id="stats"></div>
    <div id="results"></div>
</div>
<script src="script.js" type="text/javascript"></script>
</body>
</html>

