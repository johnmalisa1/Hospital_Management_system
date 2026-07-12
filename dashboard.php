<?php
session_start();
include "config/db.php";
require_once "includes/classes/Patient.php";
require_once "includes/classes/Appointment.php";
require_once "includes/classes/Billing.php";
require_once "includes/classes/Medicine.php";
include "templates/header.php";

// Totals
$patient = new Patient($db);
$appointment = new Appointment($db);
$billing = new Billing($db);
$medicine = new Medicine($db);
$patients = $patient->countPatients();
$available_rooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE is_available = 1")->fetch_assoc()['total'];
$bills_paid = $billing->getPaidTotal();
$meds = $medicine->getTotalQuantity();
$appointments = $appointment->countAppointments();
$paid = $billing->countBillsByStatus('Paid');
$pending = $billing->countBillsByStatus('Pending');
$available = $conn->query("SELECT COUNT(*) as total FROM rooms WHERE is_available=1")->fetch_assoc()['total'];
$occupied = $conn->query("SELECT COUNT(*) as total FROM rooms WHERE is_available=0")->fetch_assoc()['total'];

// Monthly Patients
$monthly_patients = $patient->getMonthlyPatientCounts();

$labels = [];
$data = [];
while ($row = $monthly_patients->fetch_assoc()) {
    $labels[] = $row['month'];
    $data[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="sidebar-page dashboard-bg">
    <div class="main-overlay">

        <h2>🏥 Hospital Dashboard</h2>
        <p style="text-align:center;">Welcome, <strong><?= $_SESSION['username']; ?></strong>!</p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="modules/patients/add.php" class="quick-btn">+ Add Patient</a>
            <a href="modules/appointments/add.php" class="quick-btn">+ Add Appointment</a>
            <a href="modules/admissions/add.php" class="quick-btn">+ Add Admission</a>
            <a href="modules/pharmacy/add.php" class="quick-btn">+ Add Medicine</a>
            <a href="modules/lab_test/add.php" class="quick-btn">+ Add Lab Test</a>
            <a href="modules/users/add.php" class="quick-btn">+ Add User</a>
        </div>

        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>

        <div class="dashboard">
            <div class="card">
                <h3>Patients</h3>
                <p><?= $patients ?></p>
            </div>
            <div class="card">
                <h3>Appointments</h3>
                <p><?= $appointments ?></p>
            </div>
            <div class="card">
                <h3>Available Rooms</h3>
                <p><?= $available_rooms ?></p>
            </div>
            <div class="card">
                <h3>Bills Paid (Tsh)</h3>
                <p><?= number_format($bills_paid ?? 0, 2) ?></p>
            </div>
            <div class="card">
                <h3>Meds In Stock</h3>
                <p><?= $meds ?></p>
            </div>
        </div>

        <h3>📊 Charts</h3>
        <div class="charts">
            <div style="width:250px; height:200px;"><canvas id="lineChart"></canvas></div>
            <div style="width:220px; height:200px;"><canvas id="billingChart"></canvas></div>
            <div style="width:300px; height:220px;"><canvas id="monthlyPatientsChart"></canvas></div>
            <div style="width:200px; height:200px;"><canvas id="roomChart"></canvas></div>
        </div>

    </div>
</div>

<script>
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Patients', 'Appointments'],
        datasets: [{
            label: 'Total Count',
            data: [<?= $patients ?>, <?= $appointments ?>],
            borderColor: '#007bff',
            backgroundColor: '#cce5ff',
            tension: 0.3,
            fill: true
        }]
    }
});

new Chart(document.getElementById('billingChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending'],
        datasets: [{
            data: [<?= $paid ?>, <?= $pending ?>],
            backgroundColor: ['#28a745', '#ffc107']
        }]
    }
});

new Chart(document.getElementById('monthlyPatientsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Patients',
            data: <?= json_encode($data) ?>,
            backgroundColor: '#17a2b8'
        }]
    }
});

new Chart(document.getElementById('roomChart'), {
    type: 'pie',
    data: {
        labels: ['Available', 'Occupied'],
        datasets: [{
            data: [<?= $available ?>, <?= $occupied ?>],
            backgroundColor: ['#28a745', '#dc3545']
        }]
    }
});
</script>

</body>
</html>
