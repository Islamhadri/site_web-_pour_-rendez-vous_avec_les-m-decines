<?php
include('db.php');

if (isset($_GET['specialty'])) {
    $specialty = $_GET['specialty'];

    // Récupérer les médecins selon le spécialité
    $stmt = $conn->prepare("SELECT id, nom, specialty FROM doctors WHERE specialty = ?");
    $stmt->execute([$specialty]);
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les données en format JSON
    echo json_encode($doctors);
}
?>
