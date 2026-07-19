<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login_doctor.php");
    exit();
}

include "config/db.php";
require_once "includes/classes/Appointment.php";
require_once "includes/classes/Notification.php";

$doctor_id = $_SESSION['user_id'];
$appointment = new Appointment($db);
$notification = new Notification($db);

$todayTotal = $appointment->countTodayByDoctor($doctor_id);
$todayCompleted = $appointment->countTodayByDoctorAndStatus($doctor_id, 'Completed');
$todayScheduled = $appointment->countTodayByDoctorAndStatus($doctor_id, 'Scheduled');
$todayRescheduled = $appointment->countTodayByDoctorAndStatus($doctor_id, 'Rescheduled');
$patientsSeen = $appointment->countTodayPatientsSeen($doctor_id);
$cancelledCount = $appointment->countTodayCancelledByDoctor($doctor_id);
$unreadCount = $notification->countUnreadByUser($doctor_id);

include "templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
</head>

<body class="sidebar-page dashboard-bg">
    <div class="main-overlay">

        <div class="page-header">
            <div>
                <h2><i class="fas fa-user-md"></i> Doctor Dashboard</h2>
                <p style="text-align:left; color: var(--text-light); margin-top: 4px;">Welcome, <strong style="color: var(--primary-dark);">Dr. <?= htmlspecialchars($_SESSION['username']) ?></strong></p>
            </div>
        </div>

        <div class="dashboard">
            <div class="card">
                <div class="card-icon"><i class="fas fa-calendar-day"></i></div>
                <h3>Today's Appointments</h3>
                <p><?= $todayTotal ?></p>
            </div>
            <div class="card">
                <div class="card-icon" style="color: var(--accent);"><i class="fas fa-check-circle"></i></div>
                <h3>Completed Today</h3>
                <p><?= $todayCompleted ?></p>
            </div>
            <div class="card">
                <div class="card-icon" style="color: var(--warning);"><i class="fas fa-clock"></i></div>
                <h3>Pending</h3>
                <p><?= $todayScheduled ?></p>
            </div>
            <div class="card">
                <div class="card-icon" style="color: var(--danger);"><i class="fas fa-times-circle"></i></div>
                <h3>Cancelled</h3>
                <p><?= $cancelledCount ?></p>
            </div>
            <div class="card">
                <div class="card-icon" style="color: var(--primary);"><i class="fas fa-exchange-alt"></i></div>
                <h3>Rescheduled</h3>
                <p><?= $todayRescheduled ?></p>
            </div>
            <div class="card">
                <div class="card-icon" style="color: var(--accent);"><i class="fas fa-user-check"></i></div>
                <h3>Patients Seen Today</h3>
                <p><?= $patientsSeen ?></p>
            </div>
        </div>

    </div>

<?php include "templates/footer.php"; ?>
