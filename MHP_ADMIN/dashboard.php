<?php
    session_start();
    
    // Database connection
    include("connect.php");
    
    // Handle JSON requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetchDoctorName'])) {
        header('Content-Type: application/json');
    
        // Check if doctor is logged in
        if (!isset($_SESSION['doctor_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
    
        // Get doctor's complete information
        $doctor_id = $_SESSION['doctor_id'];
    
        // Fetch doctor details
        $sql = "SELECT fname, lname,department, profile_image FROM MHP WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
    
        echo json_encode($doctor);
        exit(); // End script to prevent further output
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <style>
        body {
            background-color: #f4f7f6;
        }
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .sidebar {
            transition: width 0.3s ease;
            width: 256px;
            min-width: 256px;
        }
        .sidebar.collapsed {
            width: 80px;
            min-width: 80px;
        }
        .main-content {
            transition: margin-left 0.3s ease;
            margin-left: 256px;
        }
        .main-content.expanded {
            margin-left: 80px;
        }
        .menu-item {
            transition: all 0.3s ease;
        }
        .menu-item:hover {
            background-color: #f3f4f6;
        }
        .menu-item.active {
            color: #1cabe3;
            background-color: #eff6ff;
            border-right: 4px solid #1cabe3;
        }
        .menu-text {
            transition: opacity 0.3s ease;
        }
        .sidebar.collapsed .menu-text {
            opacity: 0;
            display: none;
        }
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
        .content-section {
            display: none;
        }
        
        .content-section.active {
            display: block;
        }
        
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed top-0 left-0 h-screen bg-white shadow-lg z-10">
        <!-- Logo Section -->
        <div class="flex items-center p-6 border-b">
            <div class="w-15 h-10 rounded-full flex items-center justify-center">
                <a href="#"><img src="uploads/Mindsoothe(2).svg" alt="Mindsoothe Logo"></a>
            </div>
        </div>
         <!-- Menu Items -->
         <nav class="mt-6">
            <a href="#" class="menu-item active flex items-center px-6 py-3" data-section="dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="menu-text ml-3">Dashboard</span>
            </a>
            <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-600" data-section="appointments">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="menu-text ml-3">Appointments</span>
            </a>
            <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-600" data-section="schedule">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="menu-text ml-3">Profile</span>
            </a>
            <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-600" data-section="chats">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18a1 1 0 011 1v12a1 1 0 01-1 1H6l-3 3V5a1 1 0 011-1z"/>
                </svg>
                <span class="menu-text ml-3">Chats</span>
            </a>
                
        </nav>

        <!-- Logout Button -->
        <div class="absolute bottom-0 w-full p-6 border-t">
            <a href="../landingpage.html" class="flex items-center text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="menu-text ml-3">Logout</span>
            </a>
        </div>
    </div>

    <div id="mainContent" class="main-content px-4 py-8">
        <div class="container mx-auto">
        <div id="dashboard-section" class="section active">
        <!-- Doctor Profile Section -->
        <div class="shared-content">
            <div id="dashboard-doctor-profile" class="bg-white shadow-md rounded-lg p-6 mb-6 flex items-center space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center">
                    <img  class="h-24 w-24 rounded-full" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
                    </div>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center space-x-2">
                        <h2 id="dashboardDoctorFirstName" class="text-2xl font-bold text-gray-800">Loading...</h2>
                        <h2 id="dashboardDoctorLastName" class="text-2xl font-bold text-gray-800">Loading...</h2>
                    </div>
                    <p id="dashboardDepartment" class="text-gray-600">Loading...</p>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 011 1v1a1 1 0 11-2 0V8a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span id="dashboardMemberSince">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="dashboard-card bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-gray-600 mb-2">Today's Appointments</h3>
                <div id="todayAppointments" class="text-3xl font-bold text-blue-600">0</div>
            </div>
            <div class="dashboard-card bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-gray-600 mb-2">Total Patients</h3>
                <div id="totalPatients" class="text-3xl font-bold text-green-600">0</div>
            </div>
            <div class="dashboard-card bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-gray-600 mb-2">Consultation Hours</h3>
                <div id="consultationHours" class="text-3xl font-bold text-purple-600">0</div>
            </div>
        </div>
        <!-- Upcoming Appointments -->
        <div id="upcomingAppointmentsContainer" class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Upcoming Appointments</h3>
            <ul id="upcomingAppointmentsList" class="divide-y divide-gray-200">
                <li class="py-4 text-center text-gray-500">Loading appointments...</li>
            </ul>
        </div>
        </div>

        <div id="chats-section" class="section">
            <div class="flex h-screen">
                <!-- Sidebar for Contacts -->
                <div class="w-1/4 bg-white border-r">
                    <div class="p-4 border-b">
                    <!-- search bar for students or user -->
                        <div class="relative">
                            <input type="text" placeholder="Search students..." class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                            <svg class="absolute top-2 right-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a4 4 0 11-8 0 4 4 0 018 0zM21 21l-4.35-4.35"></path>
                            </svg>
                        </div>
                    </div>
                    <ul id="userList" class="overflow-y-auto">
                        <li class="p-4 flex items-center hover:bg-gray-100 cursor-pointer">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="font-semibold">Student Name</p>
                                <p class="text-sm text-gray-500">Latest message preview...</p>
                            </div>
                            <span class="text-sm text-gray-400">10:37 AM</span>
                        </li>
                    </ul>
                </div>
                <!-- Chat Content -->
                <div class="flex-1 flex flex-col">
                    <div class="p-4 border-b bg-white flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Chat with User</h2>
                        <button class="text-gray-500">...</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div class="flex">
                            <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                            <div class="bg-gray-100 rounded-lg p-3">
                                <p>Sample message text.</p>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div class="bg-blue-500 text-white rounded-lg p-3">
                                <p>Response message text.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border-t bg-white flex items-center">
                        <input type="text" placeholder="Type your message..." class="flex-1 p-3 border rounded-lg">
                        <button class="ml-3 p-3 bg-blue-500 text-white rounded-lg">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        // Section switching functionality
        const menuItems = document.querySelectorAll('.menu-item');
        const sections = document.querySelectorAll('.section');

        menuItems.forEach(item => {
             item.addEventListener('click', function(e) {
                e.preventDefault();
                
                menuItems.forEach(mi => mi.classList.remove('active'));
                sections.forEach(section => section.classList.remove('active'));
                            
                this.classList.add('active');
                            
                 const sectionId = this.getAttribute('data-section');
                document.getElementById(`${sectionId}-section`).classList.add('active');
                        });
        });

        function fetchDoctorName() {
            fetch('dashboard.php?fetchDoctorName=true')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.fname && data.lname) {
                        document.getElementById('dashboardDoctorFirstName').textContent = data.fname;
                        document.getElementById('dashboardDoctorLastName').textContent = data.lname;
                        document.getElementById('dashboardDepartment').textContent = data.department;
                        document.getElementById('dashboardprofileImage').src = data.profile_image;
                    } else {
                        console.error('Error: Doctor name not found in response');
                    }
                })
                .catch(error => {
                    console.error('Error fetching doctor name:', error);
                });
        }
           
        document.addEventListener('DOMContentLoaded', fetchDoctorName);
       
    </script>
</body>
</html>