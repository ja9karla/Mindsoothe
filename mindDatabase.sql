create database _Mindsoothe;
use _Mindsoothe;
drop Table Users;
CREATE TABLE Users (
id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
Student_id INT(7) NOT NULL,
firstName VARCHAR(50) NOT NULL,
lastName VARCHAR(50) NOT NULL,
email VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL,
profile_image VARCHAR(255) DEFAULT 'images/blueuser.svg',
status TINYINT(1) NOT NULL DEFAULT 0,
otp VARCHAR(6) DEFAULT NULL,
is_patient BOOLEAN DEFAULT FALSE
);
-- default users pw is 123
INSERT INTO Users (Student_id, firstName, lastName, email, password) 
VALUES ('2102446' ,'Janine', 'Pablo', '2102446@usl.edu.ph', '202cb962ac59075b964b07152d234b70');

select * from Users;
INSERT INTO Users (Student_id, firstName, lastName, email, password) 
VALUES ('2102445', 'Lim', 'mikha', 'mikha@usl.edu.ph', '202cb962ac59075b964b07152d234b70');
INSERT INTO Users (Student_id, firstName, lastName, email, password) 
VALUES ('1234567', 'Sevilleja','stacey', 'stacey@usl.edu.ph', '202cb962ac59075b964b07152d234b70');
INSERT INTO Users (Student_id, firstName, lastName, email, password) 
VALUES ('1234566', 'Robles', 'jhoana','jhoanna@usl.edu.ph', '202cb962ac59075b964b07152d234b70');

CREATE TABLE GracefulThread (
id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT(10) NOT NULL,
content TEXT NOT NULL,
likes INT (10) DEFAULT 0,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
drop table GracefulThread;
CREATE TABLE post_likes (
    id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) NOT NULL,
    post_id INT(10) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
drop table post_likes;
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
    response_score INT(10) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    response_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
select * from phq9_responses;
create table MHP (
	id INT AUTO_INCREMENT PRIMARY KEY,
	fname VARCHAR(50) NOT NULL,
	lname VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
	department VARCHAR(255) NOT NULL,
	password VARCHAR(255),  -- To store the hashed password
    profile_image VARCHAR(255) DEFAULT 'images/blueuser.svg',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);	

insert into mhp (fname, lname, email, department, password, profile_image, created_at)
values     ('Maloi', 'Ricalde', 'maloi.ricalde@usl.edu.ph', 'SABH', '202cb962ac59075b964b07152d234b70', 'images/blueuser.svg', CURRENT_TIMESTAMP);

insert into mhp (fname, lname, email, department, password, profile_image, created_at)
values     ('Aiah', 'Arceta', 'aiah@usl.edu.ph', 'SACE', '123', 'images/blueuser.svg', CURRENT_TIMESTAMP);

insert into mhp (fname, lname, email, department, password, profile_image, created_at)
values     ('Aiah', 'Arceta', 'aiah.arceta@usl.edu.ph', 'SACE', '202cb962ac59075b964b07152d234b70', 'images/blueuser.svg', CURRENT_TIMESTAMP);
drop table MHP;

select * from MHP;

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
select * from MHP;
drop table MHP_sched;

CREATE TABLE Messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    sender_type ENUM('student', 'MHP') NOT NULL,
    receiver_id INT NOT NULL,
    receiver_type ENUM('student', 'MHP') NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('sent', 'read') DEFAULT 'sent'
);


CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
