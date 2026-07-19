CREATE DATABASE IF NOT EXISTS hospital_db;
USE hospital_db;

-- Users table (Admin, Doctor, Receptionist accounts)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- Departments
CREATE TABLE IF NOT EXISTS departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Doctors
CREATE TABLE IF NOT EXISTS doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Patients (has own login credentials)
CREATE TABLE IF NOT EXISTS patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    dob DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointments
CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Scheduled',
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Rooms
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1
);

-- Wards
CREATE TABLE IF NOT EXISTS wards (
    ward_id INT AUTO_INCREMENT PRIMARY KEY,
    ward_name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Beds
CREATE TABLE IF NOT EXISTS beds (
    bed_id INT AUTO_INCREMENT PRIMARY KEY,
    ward_id INT NOT NULL,
    bed_number VARCHAR(20) NOT NULL,
    is_occupied TINYINT(1) NOT NULL DEFAULT 0,
    patient_id INT,
    FOREIGN KEY (ward_id) REFERENCES wards(ward_id),
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- Admissions
CREATE TABLE IF NOT EXISTS admissions (
    admission_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    room_id INT NOT NULL,
    admission_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- Discharges
CREATE TABLE IF NOT EXISTS discharges (
    discharge_id INT AUTO_INCREMENT PRIMARY KEY,
    admission_id INT NOT NULL,
    doctor_id INT NOT NULL,
    discharge_date DATE NOT NULL,
    summary TEXT NOT NULL,
    notes TEXT,
    FOREIGN KEY (admission_id) REFERENCES admissions(admission_id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

-- Billing
CREATE TABLE IF NOT EXISTS billing (
    billing_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- Payments
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    billing_id INT NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    method VARCHAR(50) NOT NULL,
    FOREIGN KEY (billing_id) REFERENCES billing(billing_id)
);

-- Medicines
CREATE TABLE IF NOT EXISTS medicines (
    medicine_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- Prescriptions
CREATE TABLE IF NOT EXISTS prescriptions (
    prescription_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    medicine_id INT NOT NULL,
    dosage VARCHAR(50) NOT NULL,
    instructions TEXT,
    date_issued DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- Diagnoses
CREATE TABLE IF NOT EXISTS diagnoses (
    diagnosis_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    diagnosis TEXT NOT NULL,
    diagnosis_date DATE NOT NULL,
    doctor_id INT NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Treatments
CREATE TABLE IF NOT EXISTS treatments (
    treatment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    description TEXT NOT NULL,
    date_given DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Medical History
CREATE TABLE IF NOT EXISTS medical_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    `condition` TEXT NOT NULL,
    treatment TEXT NOT NULL,
    date_recorded DATE NOT NULL,
    recorded_by INT NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Lab Tests
CREATE TABLE IF NOT EXISTS lab_tests (
    test_id INT AUTO_INCREMENT PRIMARY KEY,
    test_name VARCHAR(100) NOT NULL,
    cost DECIMAL(10,2) NOT NULL
);

-- Lab Test Results
CREATE TABLE IF NOT EXISTS lab_test_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    test_id INT NOT NULL,
    doctor_id INT NOT NULL,
    result_text TEXT NOT NULL,
    result_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (test_id) REFERENCES lab_tests(test_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Test Samples
CREATE TABLE IF NOT EXISTS test_samples (
    sample_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    lab_test_id INT NOT NULL,
    sample_type VARCHAR(50) NOT NULL,
    collected_date DATE NOT NULL,
    collected_by INT NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (lab_test_id) REFERENCES lab_tests(test_id),
    FOREIGN KEY (collected_by) REFERENCES users(id)
);

-- Vaccinations
CREATE TABLE IF NOT EXISTS vaccinations (
    vaccination_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    vaccine_name VARCHAR(100) NOT NULL,
    vaccination_date DATE NOT NULL,
    dose_number INT NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- Surgeries
CREATE TABLE IF NOT EXISTS surgeries (
    surgery_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    surgery_type VARCHAR(100) NOT NULL,
    surgery_date DATE NOT NULL,
    notes TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Ambulances
CREATE TABLE IF NOT EXISTS ambulances (
    ambulance_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_number VARCHAR(50) NOT NULL,
    driver_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    availability VARCHAR(20) NOT NULL
);

-- Ambulance Requests
CREATE TABLE IF NOT EXISTS ambulance_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    request_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    notes TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- Blood Bank
CREATE TABLE IF NOT EXISTS blood_bank (
    unit_id INT AUTO_INCREMENT PRIMARY KEY,
    blood_type VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    donor_name VARCHAR(100) NOT NULL,
    date_donated DATE NOT NULL,
    expiry_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL
);

-- Equipment
CREATE TABLE IF NOT EXISTS equipment (
    equipment_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    purchase_date DATE NOT NULL
);

-- Expenses
CREATE TABLE IF NOT EXISTS expenses (
    expense_id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    expense_date DATE NOT NULL,
    notes TEXT
);

-- Insurance Providers
CREATE TABLE IF NOT EXISTS insurance_providers (
    provider_id INT AUTO_INCREMENT PRIMARY KEY,
    provider_name VARCHAR(100) NOT NULL,
    contact VARCHAR(100) NOT NULL
);

-- Patient Insurance
CREATE TABLE IF NOT EXISTS patient_insurance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    provider_id INT NOT NULL,
    policy_number VARCHAR(50) NOT NULL,
    valid_until DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (provider_id) REFERENCES insurance_providers(provider_id)
);

-- Nurses
CREATE TABLE IF NOT EXISTS nurses (
    nurse_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    assigned_ward INT NOT NULL,
    shift_time VARCHAR(50) NOT NULL,
    FOREIGN KEY (assigned_ward) REFERENCES wards(ward_id)
);

-- Schedules
CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    day VARCHAR(20) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

-- Rooms Log
CREATE TABLE IF NOT EXISTS rooms_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL,
    patient_id INT NOT NULL,
    admission_date DATE NOT NULL,
    discharge_date DATE,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- Vitals
CREATE TABLE IF NOT EXISTS vitals (
    vital_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    blood_pressure VARCHAR(20) NOT NULL,
    pulse INT NOT NULL,
    temperature DECIMAL(5,2) NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    date_recorded DATE NOT NULL,
    recorded_by INT NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Specializations
CREATE TABLE IF NOT EXISTS specializations (
    specialization_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Seed data
INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$wHqxRYYOZysOnZPKVXvEtu7htf7cDBq2rZgOBgiTRn5F2kPzBO1CG', 'Admin');
