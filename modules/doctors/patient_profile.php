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
require_once "../../includes/classes/Vaccination.php";

$doctor_id = $_SESSION['user_id'];
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($patient_id <= 0) {
    echo "Access Denied.";
    exit();
}

$appointment = new Appointment($db);
$check = $appointment->getAppointmentsByDoctor($doctor_id);
$authorized = false;
while ($r = $check->fetch_assoc()) {
    if ($r['patient_id'] == $patient_id) {
        $authorized = true;
        break;
    }
}
if (!$authorized) {
    echo "Access Denied: Patient not assigned to you.";
    exit();
}

$patientService = new Patient($db);
$patient = $patientService->getPatientById($patient_id);

$diagnosisService = new Diagnosis($db);
$treatmentService = new Treatment($db);
$medicalHistoryService = new MedicalHistory($db);
$prescriptionService = new Prescription($db);
$labTestResultService = new LabTestResult($db);
$vaccinationService = new Vaccination($db);

$diagnoses = $diagnosisService->getDiagnosesByPatient($patient_id);
$treatments = $treatmentService->getTreatmentsByPatient($patient_id);
$history = $medicalHistoryService->getHistoryByPatient($patient_id);
$prescriptions = $prescriptionService->getPrescriptionsByPatient($patient_id);
$labResults = $labTestResultService->getResultsByPatient($patient_id);
$vaccinations = $vaccinationService->getVaccinationsByPatient($patient_id);
$allAppointments = $appointment->getAppointmentsByPatient($patient_id);
?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Profile</title>
    <style>
        .profile-section { background: var(--card); border-radius: var(--radius); padding: 20px 24px; margin: 16px auto; max-width: 900px; box-shadow: var(--shadow); border-left: 4px solid var(--primary); }
        .profile-section h3 { text-align: left; color: var(--primary-dark); margin-bottom: 12px; font-size: 1.1rem; }
        .profile-section h3 i { margin-right: 8px; }
        .profile-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; }
        .profile-info-grid p { margin: 4px 0; font-size: 14px; }
        .mini-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .mini-table th, .mini-table td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #EDF2F7; font-size: 13px; }
        .mini-table th { background: var(--primary-light); color: var(--primary-dark); font-weight: 600; }
        .section-actions { margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap; }
        .section-actions a { font-size: 12px; padding: 6px 12px; }
        @media (max-width: 576px) { .profile-info-grid { grid-template-columns: 1fr; } }
    </style>
</head>

<body class="sidebar-page">
    <div class="main-overlay">
    <h2 style="text-align:center;"><i class="fas fa-id-card"></i> Patient Profile</h2>

    <?php if ($patient): ?>
    <div class="profile-section" style="border-left-color: var(--accent);">
        <h3><i class="fas fa-user"></i> Basic Information</h3>
        <div class="profile-info-grid">
            <p><strong>Name:</strong> <?= htmlspecialchars($patient['name']) ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']) ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($patient['dob']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($patient['phone']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($patient['address']) ?></p>
        </div>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-heartbeat"></i> Medical History</h3>
        <?php if ($history->num_rows > 0): ?>
        <table class="mini-table">
            <tr><th>Condition</th><th>Treatment</th><th>Date</th><th>Recorded By</th><th>Actions</th></tr>
            <?php while ($h = $history->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($h['condition']) ?></td>
                <td><?= htmlspecialchars($h['treatment']) ?></td>
                <td><?= htmlspecialchars($h['date_recorded']) ?></td>
                <td><?= htmlspecialchars($h['recorded_by_name'] ?? 'N/A') ?></td>
                <td><a href="medical_history_edit.php?id=<?= $h['history_id'] ?>&patient_id=<?= $patient_id ?>" class="btn edit-btn" style="font-size:11px;padding:4px 8px;">Edit</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p class="no-data">No medical history found.</p>
        <?php endif; ?>
        <div class="section-actions">
            <a href="medical_history_add.php?patient_id=<?= $patient_id ?>" class="btn edit-btn"><i class="fas fa-plus"></i> Add History</a>
        </div>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-stethoscope"></i> Diagnoses</h3>
        <?php if ($diagnoses->num_rows > 0): ?>
        <table class="mini-table">
            <tr><th>Diagnosis</th><th>Date</th><th>Doctor</th><th>Actions</th></tr>
            <?php while ($d = $diagnoses->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($d['diagnosis']) ?></td>
                <td><?= htmlspecialchars($d['diagnosis_date']) ?></td>
                <td><?= htmlspecialchars($d['doctor_name']) ?></td>
                <td><a href="diagnosis_edit.php?id=<?= $d['diagnosis_id'] ?>&patient_id=<?= $patient_id ?>" class="btn edit-btn" style="font-size:11px;padding:4px 8px;">Edit</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p class="no-data">No diagnoses found.</p>
        <?php endif; ?>
        <div class="section-actions">
            <a href="diagnosis_add.php?patient_id=<?= $patient_id ?>" class="btn edit-btn"><i class="fas fa-plus"></i> Add Diagnosis</a>
        </div>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-notes-medical"></i> Treatments</h3>
        <?php if ($treatments->num_rows > 0): ?>
        <table class="mini-table">
            <tr><th>Description</th><th>Date Given</th><th>Doctor</th><th>Actions</th></tr>
            <?php while ($t = $treatments->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($t['description']) ?></td>
                <td><?= htmlspecialchars($t['date_given']) ?></td>
                <td><?= htmlspecialchars($t['doctor_name']) ?></td>
                <td><a href="treatment_edit.php?id=<?= $t['treatment_id'] ?>&patient_id=<?= $patient_id ?>" class="btn edit-btn" style="font-size:11px;padding:4px 8px;">Edit</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p class="no-data">No treatments found.</p>
        <?php endif; ?>
        <div class="section-actions">
            <a href="treatment_add.php?patient_id=<?= $patient_id ?>" class="btn edit-btn"><i class="fas fa-plus"></i> Add Treatment</a>
        </div>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-syringe"></i> Vaccinations</h3>
        <?php if ($vaccinations->num_rows > 0): ?>
        <table class="mini-table">
            <tr><th>Vaccine</th><th>Date</th><th>Dose #</th><th>Actions</th></tr>
            <?php while ($v = $vaccinations->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($v['vaccine_name']) ?></td>
                <td><?= htmlspecialchars($v['vaccination_date']) ?></td>
                <td><?= htmlspecialchars($v['dose_number']) ?></td>
                <td><a href="vaccination_edit.php?id=<?= $v['vaccination_id'] ?>&patient_id=<?= $patient_id ?>" class="btn edit-btn" style="font-size:11px;padding:4px 8px;">Edit</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p class="no-data">No vaccinations found.</p>
        <?php endif; ?>
        <div class="section-actions">
            <a href="vaccination_add.php?patient_id=<?= $patient_id ?>" class="btn edit-btn"><i class="fas fa-plus"></i> Add Vaccination</a>
        </div>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-vials"></i> Lab Tests & Results</h3>
        <?php if ($labResults->num_rows > 0): ?>
        <table class="mini-table">
            <tr><th>Test</th><th>Result</th><th>Date</th></tr>
            <?php while ($l = $labResults->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($l['test_name']) ?></td>
                <td><?= htmlspecialchars($l['result_text']) ?></td>
                <td><?= htmlspecialchars($l['result_date']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p class="no-data">No lab results found.</p>
        <?php endif; ?>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-prescription"></i> Prescriptions</h3>
        <?php if ($prescriptions->num_rows > 0): ?>
        <table class="mini-table">
            <tr><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th><th>Actions</th></tr>
            <?php while ($p = $prescriptions->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($p['medicine_name']) ?></td>
                <td><?= htmlspecialchars($p['dosage']) ?></td>
                <td><?= htmlspecialchars($p['instructions']) ?></td>
                <td><?= htmlspecialchars($p['date_issued']) ?></td>
                <td><a href="prescription_edit.php?id=<?= $p['prescription_id'] ?>&patient_id=<?= $patient_id ?>" class="btn edit-btn" style="font-size:11px;padding:4px 8px;">Edit</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p class="no-data">No prescriptions found.</p>
        <?php endif; ?>
        <div class="section-actions">
            <a href="prescription_add.php?patient_id=<?= $patient_id ?>" class="btn edit-btn"><i class="fas fa-plus"></i> Add Prescription</a>
        </div>
    </div>

    <div class="profile-section">
        <h3><i class="fas fa-calendar-check"></i> All Appointments</h3>
        <?php
        $upcoming = [];
        $previous = [];
        while ($a = $allAppointments->fetch_assoc()):
            if ($a['status'] === 'Completed' || strpos($a['status'], 'Cancelled') !== false) {
                $previous[] = $a;
            } else {
                $upcoming[] = $a;
            }
        endwhile;
        ?>
        <?php if (count($upcoming) > 0): ?>
        <h4 style="text-align:left;color:var(--accent-dark);margin:8px 0;font-size:14px;">Upcoming</h4>
        <table class="mini-table">
            <tr><th>Date</th><th>Status</th><th>Actions</th></tr>
            <?php foreach ($upcoming as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                <td><span class="badge badge-scheduled"><?= htmlspecialchars($a['status']) ?></span></td>
                <td><a href="../../appointments/reschedule.php?id=<?= $a['appointment_id'] ?>" class="btn edit-btn" style="font-size:11px;padding:4px 8px;background:var(--warning);">Reschedule</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>

        <?php if (count($previous) > 0): ?>
        <h4 style="text-align:left;color:var(--text-light);margin:12px 0 8px;font-size:14px;">Previous</h4>
        <table class="mini-table">
            <tr><th>Date</th><th>Status</th></tr>
            <?php foreach ($previous as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                <td><span class="badge badge-completed"><?= htmlspecialchars($a['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>

        <?php if (count($upcoming) === 0 && count($previous) === 0): ?>
        <p class="no-data">No appointments found.</p>
        <?php endif; ?>
    </div>

    <?php else: ?>
    <p class="no-data">Patient not found.</p>
    <?php endif; ?>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
