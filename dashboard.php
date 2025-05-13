<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Récupérer les données selon le type
if ($user_type === 'patient') {
    $stmt = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();

    // Récupérer les rendez-vous du patient
    $stmt_rdv = $conn->prepare("SELECT a.id, a.appointment_date, a.status, d.nom AS doctor_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.patient_id = ?");
    $stmt_rdv->execute([$user_data['id']]);
    $appointments = $stmt_rdv->fetchAll();

} else {
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();

    // Récupérer les rendez-vous en attente pour le médecin
    $stmt_rdv = $conn->prepare("SELECT a.id, a.patient_id, a.appointment_date, a.status, p.nom AS patient_name FROM appointments a JOIN patients p ON a.patient_id = p.id WHERE a.doctor_id = ? AND a.status = 'pending'");
    $stmt_rdv->execute([$user_data['id']]);
    $appointments = $stmt_rdv->fetchAll();
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pFtV+Tq+3eig6PrQmJ0W2w+v7rL0+YsHkS1nHDC6v3JjCik6nFeFQj5wqN9a8XZmiI/s6lvO5M5nQfHhZLeMFQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <header class="navbar">
    <div class="logo">DocRDV</div>
    <nav>
      <ul>
        <li><a href="index.html">Accueil</a></li>
        <li><a href="rdv.php">Prendre un rendez-vous</a></li>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="logout.php">Déconnexion</a></li>
      </ul>
    </nav>
  </header>

  <div class="dashboard-container">
    <div class="sidebar">
      <h3>Bienvenue, <?= htmlspecialchars($user_data['nom'] ?? 'Utilisateur') ?></h3>
      <ul>
        <?php if ($user_type === 'doctor'): ?>
          <li><a href="appointments.html">Mes Rendez-vous</a></li>
          <li><a href="patients.html">Mes Patients</a></li>
          <li><a href="profile.html">Mon Profil</a></li>
          <li><a href="messages.html">Messages</a></li>
        <?php else: ?>
          <li><a href="rdv.php">Prendre Rendez-vous</a></li>
          <li><a href="profile.html">Mon Profil</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="dashboard-content">
      <h2>Bienvenue sur votre tableau de bord</h2>
      <p>Vous êtes connecté en tant que <strong><?= $user_type ?></strong>.</p>

      <h3>Mes Rendez-vous</h3>
      <table>
        <thead>
          <tr>
            <th>Date de Rendez-vous</th>
            <th>Nom</th>
            <th>Statut</th>
            <?php if ($user_type === 'doctor'): ?>
              <th>Action</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($appointments as $appointment): ?>
            <tr>
              <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
              <td><?= htmlspecialchars($user_type === 'patient' ? $appointment['doctor_name'] : $appointment['patient_name']) ?></td>
              <td><?= htmlspecialchars($appointment['status']) ?></td>
              <?php if ($user_type === 'doctor' && $appointment['status'] === 'pending'): ?>
                <td>
                  <form action="update_appointment.php" method="POST">
                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                    <button type="submit" name="status" value="approved">Accepter</button>
                    <button type="submit" name="status" value="rejected">Refuser</button>
                  </form>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
