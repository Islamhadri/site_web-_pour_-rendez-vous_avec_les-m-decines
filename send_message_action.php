<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'doctor' && $_SESSION['user_type'] !== 'patient')) {
    exit("Accès non autorisé.");
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$appointment_id = $_POST['appointment_id'];
$message = $_POST['message'];

// Vérifier l'accès au rdv bro
if ($user_type === 'doctor') {
    $stmt = $conn->prepare("SELECT d.id FROM doctors d
                          JOIN appointments a ON a.doctor_id = d.id
                          WHERE d.user_id = ? AND a.id = ?");
} else {
    $stmt = $conn->prepare("SELECT p.id FROM patients p
                          JOIN appointments a ON a.patient_id = p.id
                          WHERE p.user_id = ? AND a.id = ?");
}

$stmt->execute([$user_id, $appointment_id]);
$valid = $stmt->fetch();

if (!$valid) exit("Accès non autorisé.");

// Déterminer le destinataire
$stmt = $conn->prepare("SELECT doctor_id, patient_id FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$app = $stmt->fetch();

$receiver_id = ($user_type === 'doctor') ? $app['patient_id'] : $app['doctor_id'];
$receiver_type = ($user_type === 'doctor') ? 'patient' : 'doctor';

// Insérer le message
$stmt = $conn->prepare("
    INSERT INTO messages 
    (appointment_id, sender_id, sender_type, receiver_id, receiver_type, message)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $appointment_id,
    $valid['id'],
    $user_type,
    $receiver_id,
    $receiver_type,
    $message
]);

header("Location: chat.php?appointment_id=$appointment_id");
exit();
