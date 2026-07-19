<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/Diagnosis.php";
require_once "../../includes/classes/Treatment.php";
require_once "../../includes/classes/MedicalHistory.php";
require_once "../../includes/classes/Prescription.php";
require_once "../../includes/classes/LabTestResult.php";

$doctor_id = $_SESSION['user_id'];
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchType = isset($_GET['type']) ? $_GET['type'] : 'patients';

$patientService = new Patient($db);
$appointmentService = new Appointment($db);
$diagnosisService = new Diagnosis($db);
$treatmentService = new Treatment($db);
$medicalHistoryService = new MedicalHistory($db);
$prescriptionService = new Prescription($db);
$labTestResultService = new LabTestResult($db);

$myPatients = $patientService->getPatientsByDoctor($doctor_id);
$myPatientIds = [];
while ($mp = $myPatients->fetch_assoc()) {
    $myPatientIds[] = $mp['patient_id'];
}

$results = [];

if ($query !== '' && count($myPatientIds) > 0) {
    $placeholders = implode(',', array_fill(0, count($myPatientIds), '?'));

    if ($searchType === 'patients') {
        $sql = "SELECT * FROM patients WHERE patient_id IN ($placeholders) AND (name LIKE ? OR phone LIKE ?) ORDER BY name ASC";
        $stmt = $db->getConnection()->prepare($sql);
        $params = array_merge($myPatientIds, ['%' . $query . '%', '%' . $query . '%']);
        $types = str_repeat('i', count($myPatientIds)) . 'ss';
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $results = $stmt->get_result();
    } elseif ($searchType === 'appointments') {
        $sql = "SELECT a.*, p.name AS patient_name FROM appointments a JOIN patients p ON a.patient_id = p.patient_id WHERE a.doctor_id = ? AND (p.name LIKE ? OR a.appointment_date LIKE ? OR a.status LIKE ?) ORDER BY a.appointment_date DESC";
        $stmt = $db->getConnection()->prepare($sql);
        $likeQuery = '%' . $query . '%';
        $stmt->bind_param('isss', $doctor_id, $likeQuery, $likeQuery, $likeQuery);
        $stmt->execute();
        $results = $stmt->get_result();
    } elseif ($searchType === 'diagnoses') {
        $sql = "SELECT d.*, p.name AS patient_name FROM diagnoses d JOIN patients p ON d.patient_id = p.patient_id WHERE d.patient_id IN ($placeholders) AND (d.diagnosis LIKE ? OR p.name LIKE ?) ORDER BY d.diagnosis_date DESC";
        $stmt = $db->getConnection()->prepare($sql);
        $params = array_merge($myPatientIds, ['%' . $query . '%', '%' . $query . '%']);
        $types = str_repeat('i', count($myPatientIds)) . 'ss';
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $results = $stmt->get_result();
    } elseif ($searchType === 'treatments') {
        $sql = "SELECT t.*, p.name AS patient_name FROM treatments t JOIN patients p ON t.patient_id = p.patient_id WHERE t.patient_id IN ($placeholders) AND (t.description LIKE ? OR p.name LIKE ?) ORDER BY t.date_given DESC";
        $stmt = $db->getConnection()->prepare($sql);
        $params = array_merge($myPatientIds, ['%' . $query . '%', '%' . $query . '%']);
        $types = str_repeat('i', count($myPatientIds)) . 'ss';
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $results = $stmt->get_result();
    } elseif ($searchType === 'prescriptions') {
        $sql = "SELECT pr.*, p.name AS patient_name, m.name AS medicine_name FROM prescriptions pr JOIN patients p ON pr.patient_id = p.patient_id JOIN medicines m ON pr.medicine_id = m.medicine_id WHERE pr.patient_id IN ($placeholders) AND (m.name LIKE ? OR pr.dosage LIKE ? OR p.name LIKE ?) ORDER BY pr.date_issued DESC";
        $stmt = $db->getConnection()->prepare($sql);
        $params = array_merge($myPatientIds, ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%']);
        $types = str_repeat('i', count($myPatientIds)) . 'sss';
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}
?>

<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 1000px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-search"></i> Search</h2>

    <div class="form-container" style="margin-bottom: 30px;">
        <form method="GET" style="width: 100%; padding: 0; box-shadow: none; background: none;">
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label>Search:</label>
                    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search patients, appointments, records..." style="margin-bottom: 0;">
                </div>
                <div style="width: 180px;">
                    <label>Type:</label>
                    <select name="type" style="margin-bottom: 0;">
                        <option value="patients" <?= $searchType === 'patients' ? 'selected' : '' ?>>Patients</option>
                        <option value="appointments" <?= $searchType === 'appointments' ? 'selected' : '' ?>>Appointments</option>
                        <option value="diagnoses" <?= $searchType === 'diagnoses' ? 'selected' : '' ?>>Diagnoses</option>
                        <option value="treatments" <?= $searchType === 'treatments' ? 'selected' : '' ?>>Treatments</option>
                        <option value="prescriptions" <?= $searchType === 'prescriptions' ? 'selected' : '' ?>>Prescriptions</option>
                    </select>
                </div>
                <div>
                    <button type="submit" style="width: auto; padding: 12px 20px;"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($query !== ''): ?>
    <?php if ($results && $results->num_rows > 0): ?>

    <?php if ($searchType === 'patients'): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= $row['patient_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['gender']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><a href="patient_profile.php?patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-id-card"></i> Profile</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($searchType === 'appointments'): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                <td><span class="badge badge-scheduled"><?= htmlspecialchars($row['status']) ?></span></td>
                <td>
                    <a href="consultation.php?id=<?= $row['appointment_id'] ?>" class="btn edit-btn"><i class="fas fa-stethoscope"></i> Consult</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($searchType === 'diagnoses'): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Diagnosis</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                <td><?= htmlspecialchars($row['diagnosis_date']) ?></td>
                <td><a href="diagnosis_edit.php?id=<?= $row['diagnosis_id'] ?>&patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($searchType === 'treatments'): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['date_given']) ?></td>
                <td><a href="treatment_edit.php?id=<?= $row['treatment_id'] ?>&patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($searchType === 'prescriptions'): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= htmlspecialchars($row['dosage']) ?></td>
                <td><?= htmlspecialchars($row['date_issued']) ?></td>
                <td><a href="prescription_edit.php?id=<?= $row['prescription_id'] ?>&patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <p class="no-data">No results found for "<?= htmlspecialchars($query) ?>"</p>
    <?php endif; ?>
    <?php endif; ?>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
