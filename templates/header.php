<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/Hospital_Management_Starter/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php if ($role === 'Admin'): ?>
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

    <a href="/Hospital_Management_Starter/dashboard.php" class="<?= $current_page === 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/Hospital_Management_Starter/modules/patients/view.php" class="<?= $current_page === 'view.php' && strpos($_SERVER['REQUEST_URI'], 'patients') !== false ? 'active' : '' ?>"><i class="fas fa-user-injured"></i> Patients</a>
    <a href="/Hospital_Management_Starter/modules/doctors/view.php"><i class="fas fa-user-md"></i> Doctors</a>
    <a href="/Hospital_Management_Starter/modules/pharmacy/view.php"><i class="fas fa-pills"></i> Pharmacy</a>
    <a href="/Hospital_Management_Starter/modules/billing/view.php"><i class="fas fa-file-invoice-dollar"></i> Billing</a>
    <a href="/Hospital_Management_Starter/modules/admissions/view.php"><i class="fas fa-procedures"></i> Admissions</a>
    <a href="/Hospital_Management_Starter/modules/appointments/view.php"><i class="fas fa-calendar-check"></i> Appointments</a>
    <a href="/Hospital_Management_Starter/modules/lab_tests/view.php"><i class="fas fa-vials"></i> Lab Tests</a>
    <a href="/Hospital_Management_Starter/modules/lab_test_results/view.php"><i class="fas fa-microscope"></i> Lab Test Results</a>
    <a href="/Hospital_Management_Starter/modules/users/view.php"><i class="fas fa-users-cog"></i> Users</a>

    <hr>
    <a href="/Hospital_Management_Starter/modules/wards/view.php"><i class="fas fa-hospital"></i> Wards</a>
    <a href="/Hospital_Management_Starter/modules/payments/view.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
    <a href="/Hospital_Management_Starter/modules/insurance_providers/view.php"><i class="fas fa-handshake"></i> Insurance</a>
    <a href="/Hospital_Management_Starter/modules/patient_insurance/view.php"><i class="fas fa-id-card-alt"></i> Patient Insurance</a>
    <a href="/Hospital_Management_Starter/modules/treatments/view.php"><i class="fas fa-notes-medical"></i> Treatments</a>

    <hr>
    <a href="/Hospital_Management_Starter/modules/medicalhistory/view.php"><i class="fas fa-heartbeat"></i> Medical History</a>
    <a href="/Hospital_Management_Starter/modules/testsamples/view.php"><i class="fas fa-vial"></i> Test Samples</a>
    <a href="/Hospital_Management_Starter/modules/schedules/view.php"><i class="fas fa-calendar-alt"></i> Schedules</a>
    <a href="/Hospital_Management_Starter/modules/vitals/view.php"><i class="fas fa-heart"></i> Vitals</a>
    <a href="/Hospital_Management_Starter/modules/equipment/view.php"><i class="fas fa-tools"></i> Equipment</a>
    <a href="/Hospital_Management_Starter/modules/specializations/view.php"><i class="fas fa-user-md"></i> Specializations</a>
    <a href="/Hospital_Management_Starter/modules/surgeries/view.php"><i class="fas fa-scalpel"></i> Surgeries</a>
    <a href="/Hospital_Management_Starter/modules/nurses/view.php"><i class="fas fa-user-nurse"></i> Nurses</a>
    <a href="/Hospital_Management_Starter/modules/vaccinations/view.php"><i class="fas fa-syringe"></i> Vaccinations</a>
    <a href="/Hospital_Management_Starter/modules/diagnoses/view.php"><i class="fas fa-notes-medical"></i> Diagnoses</a>
    <a href="/Hospital_Management_Starter/modules/ambulance_requests/view.php"><i class="fas fa-ambulance"></i> Ambulance Requests</a>
    <a href="/Hospital_Management_Starter/modules/rooms_log/view.php"><i class="fas fa-door-open"></i> Rooms Log</a>
    <a href="/Hospital_Management_Starter/modules/expenses/view.php"><i class="fas fa-wallet"></i> Expenses</a>

    <hr>
    <a href="/Hospital_Management_Starter/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<?php endif; ?>

<?php if ($role === 'Admin'): ?>
<div class="main-content">
<?php else: ?>
<div>
<?php endif; ?>
