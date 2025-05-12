<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'], $_GET['appointment_id'])) {
    header("Location: login.php");
    exit();
}

$appointment_id = $_GET['appointment_id'];
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Récupérer les messages liés à ce rendez-vous
$stmt = $conn->prepare("SELECT * FROM messages WHERE appointment_id = ? ORDER BY created_at ASC");
$stmt->execute([$appointment_id]);
$messages = $stmt->fetchAll();

// Traitement d'envoi de message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $msg = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (appointment_id, sender_id, sender_type, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$appointment_id, $user_id, $user_type, $msg]);
    header("Location: send_message.php?appointment_id=$appointment_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="chat-container">
    <h2>Messagerie</h2>
    <div class="chat-box">
        <?php foreach ($messages as $msg): ?>
            <div class="message <?= $msg['sender_type'] === $user_type ? 'sent' : 'received' ?>">
                <strong><?= htmlspecialchars($msg['sender_type']) ?>:</strong>
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <div class="timestamp"><?= $msg['created_at'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" class="message-form">
        <textarea name="message" required placeholder="Écrivez votre message..."></textarea>
        <button type="submit">Envoyer</button>
    </form>
</div>
</body>
</html>
