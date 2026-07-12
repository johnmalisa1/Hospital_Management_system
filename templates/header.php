<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="/Hospital_Management_Starter/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Sidebar navigation -->
<div class="sidebar">
    <h3>Hospital Admin</h3>

    <a href="/Hospital_Management_Starter/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/Hospital_Management_Starter/modules/patients/view.php"><i class="fas fa-user-injured"></i> Patients</a>
    <a href="/Hospital_Management_Starter/modules/doctors/view.php"><i class="fas fa-user-md"></i> Doctors</a>
    <a href="/Hospital_Management_Starter/modules/pharmacy/view.php"><i class="fas fa-pills"></i> Pharmacy</a>
    <a href="/Hospital_Management_Starter/modules/billing/view.php"><i class="fas fa-file-invoice-dollar"></i> Billing</a>
    <a href="/Hospital_Management_Starter/modules/admissions/view.php"><i class="fas fa-procedures"></i> Admissions</a>
    <a href="/Hospital_Management_Starter/modules/appointments/view.php"><i class="fas fa-calendar-check"></i> Appointments</a>
    <a href="/Hospital_Management_Starter/modules/lab_tests/view.php"><i class="fas fa-vials"></i> Lab Tests</a>
    <a href="/Hospital_Management_Starter/modules/lab_test_results/view.php"><i class="fas fa-microscope"></i> Lab Test Results</a>
    <a href="/Hospital_Management_Starter/modules/users/view.php"><i class="fas fa-users-cog"></i> Users</a>

    <!-- Newly added modules -->
    <hr style="border-color:#555;">

    <a href="/Hospital_Management_Starter/modules/wards/view.php"><i class="fas fa-hospital"></i> Wards</a>
    <a href="/Hospital_Management_Starter/modules/payments/view.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
    <a href="/Hospital_Management_Starter/modules/insurance_providers/view.php"><i class="fas fa-handshake"></i> Insurance Providers</a>
    <a href="/Hospital_Management_Starter/modules/patient_insurance/view.php"><i class="fas fa-id-card-alt"></i> Patient Insurance</a>
    <a href="/Hospital_Management_Starter/modules/treatments/view.php"><i class="fas fa-notes-medical"></i> Treatments</a>

    <hr style="border-color:#555;">
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

    <hr style="border-color:#555;">
    <a href="/Hospital_Management_Starter/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Content wrapper -->
<div class="main-content">
