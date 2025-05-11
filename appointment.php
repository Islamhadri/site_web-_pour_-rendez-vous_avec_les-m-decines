<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer la liste des médecins disponibles
$stmt = $pdo->prepare("SELECT * FROM doctors");
$stmt->execute();
$doctors = $stmt->fetchAll();

// Traitement de la prise de rendez-vous
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $patient_id = $user_id; // Patient actuel

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES (:patient_id, :doctor_id, :appointment_date)");
    $stmt->execute([
        'patient_id' => $patient_id,
        'doctor_id' => $doctor_id,
        'appointment_date' => $appointment_date
    ]);

    $message = "Rendez-vous pris avec succès!";
}
?>

<form method="POST" action="appointment.php">
    <label for="doctor_id">Choisir un médecin</label>
    <select name="doctor_id">
        <?php foreach ($doctors as $doctor): ?>
            <option value="<?php echo $doctor['id']; ?>"><?php echo $doctor['name']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="appointment_date">Date et heure du rendez-vous</label>
    <input type="datetime-local" name="appointment_date" required>

    <button type="submit">Prendre rendez-vous</button>
    
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
</form>
