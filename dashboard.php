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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="sidebar-page dashboard-bg">
    <div class="main-overlay">

        <div class="page-header">
            <div>
                <h2><i class="fas fa-hospital"></i> Hospital Dashboard</h2>
                <p style="text-align:left; color: var(--text-light); margin-top: 4px;">Welcome back, <strong style="color: var(--primary-dark);"><?= htmlspecialchars($_SESSION['username']); ?></strong></p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="modules/patients/add.php" class="quick-btn"><i class="fas fa-user-plus"></i> Add Patient</a>
            <a href="modules/appointments/add.php" class="quick-btn"><i class="fas fa-calendar-plus"></i> Add Appointment</a>
            <a href="modules/admissions/add.php" class="quick-btn"><i class="fas fa-procedures"></i> Add Admission</a>
            <a href="modules/pharmacy/add.php" class="quick-btn"><i class="fas fa-pills"></i> Add Medicine</a>
            <a href="modules/lab_tests/add.php" class="quick-btn"><i class="fas fa-vials"></i> Add Lab Test</a>
            <a href="modules/users/add.php" class="quick-btn"><i class="fas fa-user-cog"></i> Add User</a>
        </div>

        <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print Report</button>

        <div class="dashboard">
            <div class="card">
                <div class="card-icon"><i class="fas fa-user-injured"></i></div>
                <h3>Patients</h3>
                <p><?= $patients ?></p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
                <h3>Appointments</h3>
                <p><?= $appointments ?></p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-door-open"></i></div>
                <h3>Available Rooms</h3>
                <p><?= $available_rooms ?></p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h3>Bills Paid (Tsh)</h3>
                <p><?= number_format($bills_paid ?? 0, 2) ?></p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-pills"></i></div>
                <h3>Meds In Stock</h3>
                <p><?= $meds ?></p>
            </div>
        </div>

        <h3><i class="fas fa-chart-bar"></i> Charts</h3>
        <div class="charts">
            <div class="chart-card"><canvas id="lineChart"></canvas></div>
            <div class="chart-card"><canvas id="billingChart"></canvas></div>
            <div class="chart-card"><canvas id="monthlyPatientsChart"></canvas></div>
            <div class="chart-card"><canvas id="roomChart"></canvas></div>
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
            borderColor: '#4A90D9',
            backgroundColor: 'rgba(74, 144, 217, 0.1)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#4A90D9',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { font: { family: "'Segoe UI', system-ui, sans-serif" } } } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('billingChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending'],
        datasets: [{
            data: [<?= $paid ?>, <?= $pending ?>],
            backgroundColor: ['#5BB5A2', '#F5A623'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { font: { family: "'Segoe UI', system-ui, sans-serif" } } } }
    }
});

new Chart(document.getElementById('monthlyPatientsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Patients',
            data: <?= json_encode($data) ?>,
            backgroundColor: 'rgba(74, 144, 217, 0.7)',
            borderColor: '#4A90D9',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { font: { family: "'Segoe UI', system-ui, sans-serif" } } } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('roomChart'), {
    type: 'pie',
    data: {
        labels: ['Available', 'Occupied'],
        datasets: [{
            data: [<?= $available ?>, <?= $occupied ?>],
            backgroundColor: ['#5BB5A2', '#E8636F'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { font: { family: "'Segoe UI', system-ui, sans-serif" } } } }
    }
});
</script>

</body>
</html>
