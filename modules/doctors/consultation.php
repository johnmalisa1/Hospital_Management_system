<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/Diagnosis.php";
require_once "../../includes/classes/Treatment.php";
require_once "../../includes/classes/MedicalHistory.php";
require_once "../../includes/classes/Prescription.php";
require_once "../../includes/classes/LabTestResult.php";
require_once "../../includes/classes/LabTest.php";
require_once "../../includes/classes/Vaccination.php";

$doctor_id = $_SESSION['user_id'];
$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointment_id <= 0) { echo "Access Denied."; exit(); }

$appointmentService = new Appointment($db);
$appt = $appointmentService->getAppointmentById($appointment_id);

if (!$appt || $appt['doctor_id'] != $doctor_id) {
    echo "Access Denied: This appointment is not assigned to you.";
    exit();
}

$patient_id = $appt['patient_id'];

$patientService = new Patient($db);
$patient = $patientService->getPatientById($patient_id);

$diagnosisService = new Diagnosis($db);
$treatmentService = new Treatment($db);
$medicalHistoryService = new MedicalHistory($db);
$prescriptionService = new Prescription($db);
$labTestResultService = new LabTestResult($db);
$labTestService = new LabTest($db);
$vaccinationService = new Vaccination($db);

$diagnoses = $diagnosisService->getDiagnosesByPatient($patient_id);
$treatments = $treatmentService->getTreatmentsByPatient($patient_id);
$history = $medicalHistoryService->getHistoryByPatient($patient_id);
$prescriptions = $prescriptionService->getPrescriptionsByPatient($patient_id);
$labResults = $labTestResultService->getResultsByPatient($patient_id);
$vaccinations = $vaccinationService->getVaccinationsByPatient($patient_id);
$labTests = $labTestService->getAllLabTests();
$medicines = $prescriptionService->getMedicines();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['form_action'] ?? '';
    $success = false;

    if ($action === 'diagnosis') {
        $diag = trim($_POST['diagnosis'] ?? '');
        $date = trim($_POST['date'] ?? '');
        if ($diag !== '' && $date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $success = $diagnosisService->addDiagnosis($patient_id, $diag, $date, $doctor_id);
        }
    } elseif ($action === 'treatment') {
        $desc = trim($_POST['description'] ?? '');
        $date = trim($_POST['date_given'] ?? '');
        if ($desc !== '' && $date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $success = $treatmentService->addTreatment($patient_id, $doctor_id, $desc, $date);
        }
    } elseif ($action === 'prescription') {
        $medId = intval($_POST['medicine_id'] ?? 0);
        $dosage = trim($_POST['dosage'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $date = trim($_POST['date_issued'] ?? '');
        if ($medId > 0 && $dosage !== '' && $date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $success = $prescriptionService->addPrescription($patient_id, $doctor_id, $medId, $dosage, $instructions, $date);
        }
    } elseif ($action === 'lab_request') {
        $testId = intval($_POST['test_id'] ?? 0);
        if ($testId > 0) {
            $success = $labTestResultService->requestLabTest($patient_id, $testId, $doctor_id);
        }
    } elseif ($action === 'medical_history') {
        $condition = trim($_POST['condition'] ?? '');
        $treatment = trim($_POST['treatment_detail'] ?? '');
        $date = trim($_POST['date_recorded'] ?? '');
        if ($condition !== '' && $date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $success = $medicalHistoryService->addHistory($patient_id, $condition, $treatment, $date, $doctor_id);
        }
    } elseif ($action === 'vaccination') {
        $vaccine = trim($_POST['vaccine_name'] ?? '');
        $date = trim($_POST['date_administered'] ?? '');
        $dose = intval($_POST['dose_number'] ?? 0);
        if ($vaccine !== '' && $date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && $dose > 0) {
            $success = $vaccinationService->addVaccination($patient_id, $vaccine, $date, $dose);
        }
    }

    header("Location: consultation.php?id=" . $appointment_id . ($success ? '' : '&error=1'));
    exit();
}

?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultation</title>
    <style>
        .consult-section { background: var(--card); border-radius: var(--radius); padding: 20px 24px; margin: 16px auto; max-width: 950px; box-shadow: var(--shadow); border-left: 4px solid var(--primary); }
        .consult-section h3 { text-align: left; color: var(--primary-dark); margin-bottom: 12px; font-size: 1.05rem; display: flex; align-items: center; gap: 8px; }
        .consult-section h3 i { color: var(--primary); }
        .consult-form { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; }
        .consult-form .full-width { grid-column: 1 / -1; }
        .consult-form label { font-size: 13px; margin-bottom: 2px; }
        .consult-form input, .consult-form select, .consult-form textarea { margin-bottom: 8px; padding: 10px 12px; font-size: 13px; }
        .consult-form button { grid-column: 1 / -1; width: auto; padding: 10px 24px; font-size: 14px; }
        .mini-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .mini-table th, .mini-table td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #EDF2F7; font-size: 13px; }
        .mini-table th { background: var(--primary-light); color: var(--primary-dark); font-weight: 600; }
        .tab-buttons { display: flex; gap: 8px; flex-wrap: wrap; margin: 16px auto; max-width: 950px; justify-content: center; }
        .tab-btn { padding: 8px 16px; border-radius: var(--radius-sm); background: var(--card); border: 2px solid #E2E8F0; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text-light); transition: var(--transition); }
        .tab-btn:hover, .tab-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        @media (max-width: 600px) { .consult-form { grid-template-columns: 1fr; } }
    </style>
</head>

<body class="sidebar-page">
    <div class="main-overlay">
    <h2 style="text-align:center;"><i class="fas fa-stethoscope"></i> Consultation</h2>

    <?php if ($patient): ?>
    <div class="consult-section" style="border-left-color: var(--accent);">
        <h3><i class="fas fa-user"></i> Patient Details</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px 24px;">
            <p style="font-size:14px;"><strong>Name:</strong> <?= htmlspecialchars($patient['name']) ?></p>
            <p style="font-size:14px;"><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']) ?></p>
            <p style="font-size:14px;"><strong>DOB:</strong> <?= htmlspecialchars($patient['dob']) ?></p>
            <p style="font-size:14px;"><strong>Phone:</strong> <?= htmlspecialchars($patient['phone']) ?></p>
            <p style="font-size:14px;"><strong>Address:</strong> <?= htmlspecialchars($patient['address']) ?></p>
            <p style="font-size:14px;"><strong>Appointment:</strong> <?= htmlspecialchars($appt['appointment_date']) ?> | <span class="badge badge-scheduled"><?= htmlspecialchars($appt['status']) ?></span></p>
        </div>
    </div>

    <div class="tab-buttons">
        <button class="tab-btn active" onclick="showTab('medical', this)">Medical History</button>
        <button class="tab-btn" onclick="showTab('diagnosis', this)">Diagnosis</button>
        <button class="tab-btn" onclick="showTab('treatment', this)">Treatment</button>
        <button class="tab-btn" onclick="showTab('prescription', this)">Prescription</button>
        <button class="tab-btn" onclick="showTab('lab', this)">Lab Tests</button>
        <button class="tab-btn" onclick="showTab('vaccination', this)">Vaccinations</button>
        <button class="tab-btn" onclick="showTab('notes', this)">Notes</button>
    </div>

    <!-- Medical History -->
    <div id="tab-medical" class="tab-content active">
        <div class="consult-section">
            <h3><i class="fas fa-heartbeat"></i> Medical History</h3>
            <?php if ($history->num_rows > 0): ?>
            <table class="mini-table">
                <tr><th>Condition</th><th>Treatment</th><th>Date</th></tr>
                <?php while ($h = $history->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($h['condition']) ?></td>
                    <td><?= htmlspecialchars($h['treatment']) ?></td>
                    <td><?= htmlspecialchars($h['date_recorded']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No medical history found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 20px;"><i class="fas fa-plus-circle"></i> Add Medical History</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="medical_history">
                <div>
                    <label>Condition:</label>
                    <input type="text" name="condition" required placeholder="Condition">
                </div>
                <div>
                    <label>Date:</label>
                    <input type="date" name="date_recorded" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="full-width">
                    <label>Treatment:</label>
                    <textarea name="treatment_detail" rows="2" placeholder="Treatment details"></textarea>
                </div>
                <button type="submit">Add History</button>
            </form>
        </div>
    </div>

    <!-- Diagnosis -->
    <div id="tab-diagnosis" class="tab-content">
        <div class="consult-section">
            <h3><i class="fas fa-stethoscope"></i> Diagnosis</h3>
            <?php if ($diagnoses->num_rows > 0): ?>
            <table class="mini-table">
                <tr><th>Diagnosis</th><th>Date</th></tr>
                <?php while ($d = $diagnoses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($d['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($d['diagnosis_date']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No diagnoses found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 20px;"><i class="fas fa-plus-circle"></i> Add Diagnosis</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="diagnosis">
                <div class="full-width">
                    <label>Diagnosis:</label>
                    <input type="text" name="diagnosis" required placeholder="Enter diagnosis">
                </div>
                <div>
                    <label>Date:</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div></div>
                <button type="submit">Add Diagnosis</button>
            </form>
        </div>
    </div>

    <!-- Treatment -->
    <div id="tab-treatment" class="tab-content">
        <div class="consult-section">
            <h3><i class="fas fa-notes-medical"></i> Treatment</h3>
            <?php if ($treatments->num_rows > 0): ?>
            <table class="mini-table">
                <tr><th>Description</th><th>Date Given</th></tr>
                <?php while ($t = $treatments->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($t['description']) ?></td>
                    <td><?= htmlspecialchars($t['date_given']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No treatments found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 20px;"><i class="fas fa-plus-circle"></i> Add Treatment</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="treatment">
                <div class="full-width">
                    <label>Description:</label>
                    <textarea name="description" rows="3" required placeholder="Treatment description"></textarea>
                </div>
                <div>
                    <label>Date Given:</label>
                    <input type="date" name="date_given" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div></div>
                <button type="submit">Add Treatment</button>
            </form>
        </div>
    </div>

    <!-- Prescription -->
    <div id="tab-prescription" class="tab-content">
        <div class="consult-section">
            <h3><i class="fas fa-prescription"></i> Prescriptions</h3>
            <?php if ($prescriptions->num_rows > 0): ?>
            <table class="mini-table">
                <tr><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th></tr>
                <?php while ($p = $prescriptions->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['medicine_name']) ?></td>
                    <td><?= htmlspecialchars($p['dosage']) ?></td>
                    <td><?= htmlspecialchars($p['instructions']) ?></td>
                    <td><?= htmlspecialchars($p['date_issued']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No prescriptions found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 20px;"><i class="fas fa-plus-circle"></i> Add Prescription</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="prescription">
                <div>
                    <label>Medicine:</label>
                    <select name="medicine_id" required>
                        <option value="">-- Select --</option>
                        <?php while ($m = $medicines->fetch_assoc()): ?>
                            <option value="<?= $m['medicine_id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label>Dosage:</label>
                    <input type="text" name="dosage" required placeholder="e.g., 500mg twice daily">
                </div>
                <div class="full-width">
                    <label>Instructions:</label>
                    <textarea name="instructions" rows="2" placeholder="Special instructions"></textarea>
                </div>
                <div>
                    <label>Date Issued:</label>
                    <input type="date" name="date_issued" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div></div>
                <button type="submit">Add Prescription</button>
            </form>
        </div>
    </div>

    <!-- Lab Tests -->
    <div id="tab-lab" class="tab-content">
        <div class="consult-section">
            <h3><i class="fas fa-vials"></i> Lab Results</h3>
            <?php if ($labResults->num_rows > 0): ?>
            <table class="mini-table">
                <tr><th>Test</th><th>Result</th><th>Date</th></tr>
                <?php while ($l = $labResults->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($l['test_name']) ?></td>
                    <td>
                        <?php if ($l['result_text'] === 'Pending'): ?>
                            <span class="badge badge-pending">Pending</span>
                        <?php else: ?>
                            <?= htmlspecialchars($l['result_text']) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($l['result_date']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No lab results found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 20px;"><i class="fas fa-plus-circle"></i> Request Lab Test</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="lab_request">
                <div>
                    <label>Lab Test:</label>
                    <select name="test_id" required>
                        <option value="">-- Select Test --</option>
                        <?php while ($t = $labTests->fetch_assoc()): ?>
                            <option value="<?= $t['test_id'] ?>"><?= htmlspecialchars($t['test_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div></div>
                <button type="submit">Request Test</button>
            </form>
        </div>
    </div>

    <!-- Vaccinations -->
    <div id="tab-vaccination" class="tab-content">
        <div class="consult-section">
            <h3><i class="fas fa-syringe"></i> Vaccinations</h3>
            <?php if ($vaccinations->num_rows > 0): ?>
            <table class="mini-table">
                <tr><th>Vaccine</th><th>Date</th><th>Dose #</th></tr>
                <?php while ($v = $vaccinations->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($v['vaccine_name']) ?></td>
                    <td><?= htmlspecialchars($v['vaccination_date']) ?></td>
                    <td><?= htmlspecialchars($v['dose_number']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No vaccinations found.</p>
            <?php endif; ?>

            <h3 style="margin-top: 20px;"><i class="fas fa-plus-circle"></i> Add Vaccination</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="vaccination">
                <div>
                    <label>Vaccine Name:</label>
                    <input type="text" name="vaccine_name" required placeholder="Vaccine name">
                </div>
                <div>
                    <label>Date Administered:</label>
                    <input type="date" name="date_administered" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="full-width">
                    <label>Dose Number:</label>
                    <input type="number" name="dose_number" value="1" min="1" required>
                </div>
                <button type="submit">Add Vaccination</button>
            </form>
        </div>
    </div>

    <!-- Notes -->
    <div id="tab-notes" class="tab-content">
        <div class="consult-section">
            <h3><i class="fas fa-clipboard"></i> Consultation Notes</h3>
            <form method="POST" class="consult-form">
                <input type="hidden" name="form_action" value="medical_history">
                <div class="full-width">
                    <label>Note:</label>
                    <textarea name="condition" rows="4" required placeholder="Enter consultation notes..."></textarea>
                </div>
                <div class="full-width">
                    <label>Follow-up Plan:</label>
                    <textarea name="treatment_detail" rows="3" placeholder="Follow-up instructions..."></textarea>
                </div>
                <div>
                    <label>Date:</label>
                    <input type="date" name="date_recorded" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div></div>
                <button type="submit">Save Notes</button>
            </form>
        </div>
    </div>

    <?php else: ?>
    <p class="no-data">Appointment not found.</p>
    <?php endif; ?>
    <script>
    function showTab(tabName, btn) {
        document.querySelectorAll('.tab-content').forEach(function(el) { el.classList.remove('active'); });
        document.querySelectorAll('.tab-btn').forEach(function(el) { el.classList.remove('active'); });
        document.getElementById('tab-' + tabName).classList.add('active');
        btn.classList.add('active');
    }
    </script>

    </div>

<?php include "../../templates/footer.php"; ?>
