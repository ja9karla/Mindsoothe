create database _Mindsoothe;
use _Mindsoothe;

CREATE TABLE Users (
id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
firstName VARCHAR(50) NOT NULL,
lastName VARCHAR(50) NOT NULL,
email VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL,
profile_image VARCHAR(255) DEFAULT 'images/blueuser.svg',
status TINYINT(1) NOT NULL DEFAULT 0,
otp VARCHAR(6) DEFAULT NULL,
is_patient BOOLEAN DEFAULT FALSE
);
 
CREATE TABLE GracefulThread (
id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT(10) NOT NULL,
content TEXT NOT NULL,
likes INT (10) DEFAULT 0,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE post_likes (
    id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) NOT NULL,
    post_id INT(10) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE phq9_responses (
    id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) NOT NULL,
    question_1 VARCHAR(50) NOT NULL,
    question_2 VARCHAR(50) NOT NULL,
    question_3 VARCHAR(50) NOT NULL,
    question_4 VARCHAR(50) NOT NULL,
    question_5 VARCHAR(50) NOT NULL,
    question_6 VARCHAR(50) NOT NULL,
    question_7 VARCHAR(50) NOT NULL,
    question_8 VARCHAR(50) NOT NULL,
    question_9 VARCHAR(50) NOT NULL,
    question_10 VARCHAR(50) NOT NULL,
    response_score INT(10) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table MHP (
	id INT AUTO_INCREMENT PRIMARY KEY,
	fname VARCHAR(50) NOT NULL,
	lname VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
	specialization VARCHAR(255) NOT NULL,
	experience INT NOT NULL,
	license_front VARCHAR(255) NOT NULL,
	license_back VARCHAR(255) NOT NULL,
	qualifications text(500) not null,
	education text(500) not null,
	password VARCHAR(255),  -- To store the hashed password
	status ENUM('pending', 'approved', 'declined') DEFAULT 'pending',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);	
ALTER TABLE MHP
ADD COLUMN profile_image VARCHAR(255) DEFAULT 'images/blueuser.svg' AFTER status;
DESCRIBE MHP;
drop table MHP;

--walang pang appointment wait lang

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL, -- Links to MHP
    patient_id INT NOT NULL, -- Links to Users who become patients
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Scheduled', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES MHP(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES Users(id) ON DELETE CASCADE
);
drop table appointments;
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
drop table patients;
CREATE TABLE MHP_sched (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    date DATE NOT NULL,
    time_slot VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES MHP(id) ON DELETE CASCADE,
    UNIQUE KEY unique_schedule (doctor_id, date, time_slot)
);
drop table MHP_sched;