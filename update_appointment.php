<?php
session_start();
include('db.php');

// التحقق من أن المستخدم متصل
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// تحقق من إذا كانت البيانات متاحة في الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // تحديث حالة الموعد في قاعدة البيانات
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $appointment_id]);

    // إعادة التوجيه إلى لوحة التحكم بعد التحديث
    header("Location: dashboard.php");
    exit();
}

// إذا كان المستخدم مريضًا، يعرض مواعيده فقط
if ($user_type === 'patient') {
    $stmt_rdv = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ?");
    $stmt_rdv->execute([$user_id]);
    $appointments = $stmt_rdv->fetchAll();
} 

// إذا كان المستخدم طبيبًا، يعرض مواعيده المعلقة
else {
    $stmt_rdv = $pdo->prepare("SELECT * FROM appointments WHERE doctor_id = ? AND status = 'pending'");
    $stmt_rdv->execute([$user_id]);
    $appointments = $stmt_rdv->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Rendez-vous</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <h2>Gestion des Rendez-vous</h2>
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
                            <td>
                                <?php
                                // إذا كان المستخدم مريضًا، عرض اسم الطبيب
                                if ($user_type === 'patient') {
                                    $stmt_doctor = $pdo->prepare("SELECT nom FROM doctors WHERE id = ?");
                                    $stmt_doctor->execute([$appointment['doctor_id']]);
                                    $doctor = $stmt_doctor->fetch();
                                    echo htmlspecialchars($doctor['nom']);
                                } 
                                // إذا كان المستخدم طبيبًا، عرض اسم المريض
                                else {
                                    $stmt_patient = $pdo->prepare("SELECT nom FROM patients WHERE id = ?");
                                    $stmt_patient->execute([$appointment['patient_id']]);
                                    $patient = $stmt_patient->fetch();
                                    echo htmlspecialchars($patient['nom']);
                                }
                                ?>
                            </td>
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
