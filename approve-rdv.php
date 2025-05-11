<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id FROM doctors WHERE user_id = ?");
$stmt->execute([$user_id]);
$doctor_id = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['id'];
    $action = $_POST['action'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
        $stmt->execute([$action, $appointment_id, $doctor_id]);
    }
}

$appointments = $pdo->prepare("
    SELECT a.id, u.nom AS patient_name, a.appointment_date, a.appointment_time, a.status 
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN users u ON p.user_id = u.id
    WHERE a.doctor_id = ?
    ORDER BY a.appointment_date DESC
");
$appointments->execute([$doctor_id]);
$data = $appointments->fetchAll();
?>

<h2>Demandes de Rendez-vous</h2>
<?php foreach ($data as $app): ?>
  <div>
    <p><?= $app['patient_name'] ?> - <?= $app['appointment_date'] ?> à <?= $app['appointment_time'] ?> - <?= $app['status'] ?></p>
    <?php if ($app['status'] === 'pending'): ?>
      <form method="post">
        <input type="hidden" name="id" value="<?= $app['id'] ?>">
        <button name="action" value="approved">Approuver</button>
        <button name="action" value="rejected">Rejeter</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
