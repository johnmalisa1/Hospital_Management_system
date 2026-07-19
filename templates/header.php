<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$current_page = basename($_SERVER['PHP_SELF']);

$base = '/Hospital_Management_Starter';
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/Hospital_Management_Starter/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php if ($role === 'Admin' || $role === 'Doctor'): ?>
<button class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
<?php endif; ?>

<?php if ($role === 'Admin'): ?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-hospital"></i>
        <span>Praise Hospital</span>
    </div>

    <a href="<?= $base ?>/dashboard.php" class="<?= $current_page === 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="<?= $base ?>/modules/patients/view.php" class="<?= $current_page === 'view.php' && strpos($_SERVER['REQUEST_URI'], 'patients') !== false ? 'active' : '' ?>"><i class="fas fa-user-injured"></i> Patients</a>
    <a href="<?= $base ?>/modules/doctors/view.php"><i class="fas fa-user-md"></i> Doctors</a>
    <a href="<?= $base ?>/modules/pharmacy/view.php"><i class="fas fa-pills"></i> Pharmacy</a>
    <a href="<?= $base ?>/modules/billing/view.php"><i class="fas fa-file-invoice-dollar"></i> Billing</a>
    <a href="<?= $base ?>/modules/admissions/view.php"><i class="fas fa-procedures"></i> Admissions</a>
    <a href="<?= $base ?>/modules/appointments/view.php"><i class="fas fa-calendar-check"></i> Appointments</a>
    <a href="<?= $base ?>/modules/lab_tests/view.php"><i class="fas fa-vials"></i> Lab Tests</a>
    <a href="<?= $base ?>/modules/lab_test_results/view.php"><i class="fas fa-microscope"></i> Lab Test Results</a>
    <a href="<?= $base ?>/modules/users/view.php"><i class="fas fa-users-cog"></i> Users</a>

    <hr>
    <a href="<?= $base ?>/modules/wards/view.php"><i class="fas fa-hospital"></i> Wards</a>
    <a href="<?= $base ?>/modules/payments/view.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
    <a href="<?= $base ?>/modules/insurance_providers/view.php"><i class="fas fa-handshake"></i> Insurance</a>
    <a href="<?= $base ?>/modules/patient_insurance/view.php"><i class="fas fa-id-card-alt"></i> Patient Insurance</a>
    <a href="<?= $base ?>/modules/treatments/view.php"><i class="fas fa-notes-medical"></i> Treatments</a>

    <hr>
    <a href="<?= $base ?>/modules/medicalhistory/view.php"><i class="fas fa-heartbeat"></i> Medical History</a>
    <a href="<?= $base ?>/modules/testsamples/view.php"><i class="fas fa-vial"></i> Test Samples</a>
    <a href="<?= $base ?>/modules/schedules/view.php"><i class="fas fa-calendar-alt"></i> Schedules</a>
    <a href="<?= $base ?>/modules/vitals/view.php"><i class="fas fa-heart"></i> Vitals</a>
    <a href="<?= $base ?>/modules/equipment/view.php"><i class="fas fa-tools"></i> Equipment</a>
    <a href="<?= $base ?>/modules/specializations/view.php"><i class="fas fa-user-md"></i> Specializations</a>
    <a href="<?= $base ?>/modules/surgeries/view.php"><i class="fas fa-scalpel"></i> Surgeries</a>
    <a href="<?= $base ?>/modules/nurses/view.php"><i class="fas fa-user-nurse"></i> Nurses</a>
    <a href="<?= $base ?>/modules/vaccinations/view.php"><i class="fas fa-syringe"></i> Vaccinations</a>
    <a href="<?= $base ?>/modules/diagnoses/view.php"><i class="fas fa-notes-medical"></i> Diagnoses</a>
    <a href="<?= $base ?>/modules/ambulance_requests/view.php"><i class="fas fa-ambulance"></i> Ambulance Requests</a>
    <a href="<?= $base ?>/modules/rooms_log/view.php"><i class="fas fa-door-open"></i> Rooms Log</a>
    <a href="<?= $base ?>/modules/expenses/view.php"><i class="fas fa-wallet"></i> Expenses</a>

    <hr>
    <a href="<?= $base ?>/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<?php endif; ?>

<?php if ($role === 'Doctor'): ?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-hospital"></i>
        <span>Praise Hospital</span>
    </div>

    <a href="<?= $base ?>/doctor_dashboard.php" class="<?= $current_page === 'doctor_dashboard.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="<?= $base ?>/modules/doctors/my_patients.php" class="<?= $current_page === 'my_patients.php' ? 'active' : '' ?>"><i class="fas fa-user-friends"></i> My Patients</a>
    <a href="<?= $base ?>/modules/appointments/doctor_view.php" class="<?= $current_page === 'doctor_view.php' && strpos($_SERVER['REQUEST_URI'], 'appointments') !== false ? 'active' : '' ?>"><i class="fas fa-calendar-check"></i> My Appointments</a>
    <a href="<?= $base ?>/modules/doctors/diagnosis_history.php" class="<?= $current_page === 'diagnosis_history.php' ? 'active' : '' ?>"><i class="fas fa-stethoscope"></i> Diagnoses</a>
    <a href="<?= $base ?>/modules/doctors/treatment_view.php" class="<?= $current_page === 'treatment_view.php' ? 'active' : '' ?>"><i class="fas fa-notes-medical"></i> Treatments</a>
    <a href="<?= $base ?>/modules/doctors/medical_history_view.php" class="<?= $current_page === 'medical_history_view.php' ? 'active' : '' ?>"><i class="fas fa-heartbeat"></i> Medical History</a>
    <a href="<?= $base ?>/modules/doctors/prescription_history.php" class="<?= $current_page === 'prescription_history.php' ? 'active' : '' ?>"><i class="fas fa-prescription"></i> Prescriptions</a>

    <hr>
    <a href="<?= $base ?>/modules/doctors/lab_request.php" class="<?= $current_page === 'lab_request.php' ? 'active' : '' ?>"><i class="fas fa-vials"></i> Laboratory</a>
    <a href="<?= $base ?>/modules/doctors/lab_results.php" class="<?= $current_page === 'lab_results.php' ? 'active' : '' ?>"><i class="fas fa-microscope"></i> Lab Results</a>
    <a href="<?= $base ?>/modules/doctors/vaccination_view.php" class="<?= $current_page === 'vaccination_view.php' ? 'active' : '' ?>"><i class="fas fa-syringe"></i> Vaccinations</a>
    <a href="<?= $base ?>/modules/doctors/search.php" class="<?= $current_page === 'search.php' ? 'active' : '' ?>"><i class="fas fa-search"></i> Search</a>
    <a href="<?= $base ?>/modules/notifications/doctor_view.php" class="<?= $current_page === 'doctor_view.php' && strpos($_SERVER['REQUEST_URI'], 'notifications') !== false ? 'active' : '' ?>" style="position: relative;">
        <i class="fas fa-bell"></i> Notifications
    </a>

    <hr>
    <a href="<?= $base ?>/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<?php endif; ?>

<?php if ($role === 'Admin' || $role === 'Doctor'): ?>
<div class="main-content">
<?php else: ?>
<div>
<?php endif; ?>
