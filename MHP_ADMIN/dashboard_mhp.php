<?php
session_start();

// Database connection
include("connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle JSON requests for fetching the doctor's info
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
    $sql = "SELECT fname, lname, department, profile_image FROM MHP WHERE id = ?";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Dashboard</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Pusher JS (if needed for real-time) -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <style>
        .sidebar {
            transition: width 0.3s ease;
            width: 256px;
            min-width: 256px;
        }
        .main-content {
            transition: margin-left 0.3s ease;
            margin-left: 256px;
        }
        .menu-item:hover {
            background-color: #f3f4f6;
        }
        .menu-item.active {
            color: #1cabe3;
            background-color: #eff6ff;
            border-right: 4px solid #1cabe3;
        }
        .content-section {
            display: none;
        }
        .content-section.active {
            display: block;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%; 
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="sidebar fixed top-0 left-0 h-screen bg-white shadow-lg z-10">
        <!-- Logo Section -->
        <div class="flex items-center p-6 border-b">
            <div class="w-15 h-10 rounded-full flex items-center justify-center">
                <img src="uploads/Mindsoothe(2).svg" alt="Mindsoothe Logo">
            </div>
        </div>

        <!-- Menu Items -->
        <nav class="mt-6">
            <a href="#" class="menu-item active flex items-center px-6 py-3" data-section="dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3
                             m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>
            <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-600" data-section="chats">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 4h18a1 1 0 011 1v12a1 1 0 01-1 1H6l-3 3V5a1 1 0 011-1z"/>
                </svg>
                <span class="menu-text ml-3">Chats</span>
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="absolute bottom-0 w-full p-6 border-t">
            <a href="../landingpage.html" class="flex items-center text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4
                             a3 3 0 013 3v1" />
                </svg>
                <span class="ml-3">Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content min-h-screen p-8">
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="content-section active">
            <!-- Counselor Profile -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center">
                    <div class="relative">
                        <!-- The counselor image will be loaded from the server -->
                        <img id="counselor-image" src="/api/placeholder/100/100" 
                             alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                        <label for="profile-upload" 
                               class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 cursor-pointer hover:bg-blue-600">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </label>
                        <input type="file" id="profile-upload" class="hidden" accept="image/*">
                    </div>
                    <div class="ml-6">
                        <!-- We will dynamically set the counselor's name and department -->
                        <h2 id="counselor-name" class="text-2xl font-bold">[Counselor Name]</h2>
                        <p id="counselor-dept" class="text-gray-600">Department: [Dept]</p>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center mb-4">
                    <input type="text" id="student-search" placeholder="Search student..." 
                           class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button onclick="searchStudent()" 
                            class="ml-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Search
                    </button>
                </div>
            </div>

            <!-- Student Results - Initially Hidden -->
            <div id="student-results" class="hidden bg-white rounded-lg shadow-md p-6">
                <!-- Student results will be dynamically inserted here -->
            </div>
        </div>

        <!-- Chats Section -->
        <div id="chats-section" class="content-section">
            <div class="flex h-screen">
                <!-- Left sidebar for chat user list -->
                <div class="w-1/4 bg-white border-r">
                    <div class="p-4 border-b">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search students..." 
                                   class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                        </div>
                    </div>
                    <ul id="userList" class="overflow-y-auto">
                        <!-- Dynamically loaded user list will appear here -->
                    </ul>
                </div>

                <!-- Right side for actual chat messages -->
                <div class="flex-1 flex flex-col">
                    <div class="p-4 border-b bg-white flex items-center justify-between">
                        <h2 id="chat-header" class="text-lg font-semibold">Chat with Student</h2>
                    </div>
                    <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-4">
                        <!-- Chat messages will appear here -->
                    </div>
                    <div class="p-4 border-t bg-white flex items-center">
                        <input type="hidden" id="student_id">
                        <input type="text" id="message_input" placeholder="Type your message..." 
                               class="flex-1 p-3 border rounded-lg">
                        <button onclick="sendMessage()" class="ml-3 p-3 bg-blue-500 text-white rounded-lg">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Update Confirmation Modal -->
    <div id="update-modal" class="modal">
        <div class="m-auto bg-white rounded-lg p-6 max-w-sm">
            <h3 class="text-lg font-bold mb-4">Update Profile Picture</h3>
            <p class="mb-6">Are you sure you want to update your profile picture?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelProfileUpdate()" 
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button onclick="confirmProfileUpdate()" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <script>
        // ------------------------
        // Global Variables
        // ------------------------
        let selectedImage = null;
        let pusher;
        let channel;

        // ------------------------
        // On Page Load
        // ------------------------
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Initialize Pusher if needed
            pusher = new Pusher('561b69476711bf54f56f', {
                cluster: 'ap1',
                encrypted: true
            });

            // 2. Fetch Counselor Info
            fetchCounselorInfo();

            // 3. Set up Menu Navigation
            setupMenuNavigation();

            // 4. Set up Profile Upload
            setupProfileUpload();

            // 5. Set up Chat User List Search
            setupUserSearch();
        });

        // ------------------------
        // Fetch Counselor Info
        // ------------------------
        function fetchCounselorInfo() {
            fetch('?fetchDoctorName=true')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch counselor info. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    // If your DB columns are named differently, adjust accordingly
                    const { fname, lname, department, profile_image } = data;
                    document.getElementById('counselor-name').textContent = `${fname} ${lname}`;
                    document.getElementById('counselor-dept').textContent = `Department: ${department}`;

                    // If there's a stored profile_image path, display it
                    if (profile_image) {
                        document.getElementById('counselor-image').src = profile_image;
                    }
                })
                .catch(error => console.error('Error fetching counselor info:', error));
        }

        // ------------------------
        // Menu Navigation
        // ------------------------
        function setupMenuNavigation() {
            const menuItems = document.querySelectorAll('.menu-item');
            const sections = document.querySelectorAll('.content-section');

            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove 'active' from all menu items and sections
                    menuItems.forEach(mi => mi.classList.remove('active'));
                    sections.forEach(section => section.classList.remove('active'));

                    // Activate the clicked menu item and corresponding section
                    this.classList.add('active');
                    const sectionId = this.getAttribute('data-section');
                    document.getElementById(`${sectionId}-section`).classList.add('active');
                });
            });
        }

        // ------------------------
        // Profile Upload
        // ------------------------
        function setupProfileUpload() {
            const profileUpload = document.getElementById('profile-upload');
            profileUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    selectedImage = file;
                    // Show the confirmation modal
                    document.getElementById('update-modal').classList.add('active');
                }
            });
        }

        // Cancel the profile update
        function cancelProfileUpdate() {
            selectedImage = null;
            document.getElementById('profile-upload').value = '';
            document.getElementById('update-modal').classList.remove('active');
        }

        // Confirm the profile update
        function confirmProfileUpdate() {
            if (selectedImage) {
                // Example: if you want to upload it via AJAX
                const formData = new FormData();
                formData.append('profile_image', selectedImage);
                formData.append('action', 'upload_profile_image');

                // Replace 'profile_upload.php' with your actual endpoint
                fetch('profile_upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the UI to show new profile
                        document.getElementById('counselor-image').src = data.filepath;
                    } else {
                        console.error('Profile upload failed:', data.message);
                    }
                })
                .catch(error => console.error('Error uploading profile:', error));
            }

            // Hide the modal
            document.getElementById('update-modal').classList.remove('active');
        }

        // ------------------------
        // Student Search
        // ------------------------
        function searchStudent() {
            const searchInput = document.getElementById('student-search').value.trim();
            const resultsContainer = document.getElementById('student-results');

            if (searchInput === '') {
                resultsContainer.classList.add('hidden');
                return;
            }

            // Simulate or perform an actual API call to search for students
            // Demo: We'll just display a placeholder card
            resultsContainer.classList.remove('hidden');
            resultsContainer.innerHTML = `
                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <img src="/api/placeholder/64/64" alt="Student" 
                             class="w-16 h-16 rounded-full object-cover">
                        <div class="ml-4">
                            <h3 class="font-semibold">${searchInput}</h3>
                            <p class="text-sm text-gray-600">BSCS - 3rd Year</p>
                            <p class="text-sm text-gray-600">SACE Department</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Available Schedule:</h4>
                        <p class="text-sm text-gray-600">Monday, Wednesday - 2:00 PM to 4:00 PM</p>
                    </div>
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">PHQ9 Score:</h4>
                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full inline-block">
                            Moderate (Score: 15)
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button onclick="openChat('${searchInput}')" 
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Message
                        </button>
                        <button onclick="printCallSlip('${searchInput}')" 
                                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            Print Call Slip
                        </button>
                    </div>
                </div>
            `;
        }

        // Print Call Slip
        function printCallSlip(studentName) {
            // TODO: Implement call slip printing 
            console.log(`Printing call slip for ${studentName}`);
        }

        // ------------------------
        // Chat Functionality
        // ------------------------

        // Setup the user list search
        function setupUserSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase();
                // Adjust path to your actual search endpoint if needed
                fetch(`MHPSeacrch.php?fetchUsers=true&search=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => populateUserList(users))
                    .catch(error => console.error('Error fetching users:', error));
            });

            // Trigger the input event to load all users initially
            searchInput.dispatchEvent(new Event('input'));
        }

        // Populate user list in the chat sidebar
        function populateUserList(users) {
            const userList = document.getElementById('userList');
            userList.innerHTML = ''; // Clear current list

            users.forEach(user => {
                const li = document.createElement('li');
                li.className = 'p-4 flex items-center hover:bg-gray-100 cursor-pointer';
                li.innerHTML = `
                    <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="font-semibold">${user.firstName} ${user.lastName}</p>
                        <p class="text-sm text-gray-500">Latest message preview...</p>
                    </div>
                    <span class="text-sm text-gray-400">10:37 AM</span>
                `;
                // Handle click to open chat
                li.addEventListener('click', () => openChatForMHP(user.id, user.firstName + ' ' + user.lastName));
                userList.appendChild(li);
            });
        }

        // Open chat with a specific user (MHP perspective)
        function openChatForMHP(studentId, studentName) {
            document.getElementById('chat-header').innerText = 'Chat with ' + studentName;
            document.getElementById('student_id').value = studentId;
            document.getElementById('chat-box').innerHTML = ''; // Clear previous messages

            // Unsubscribe from any previous channel to avoid duplication
            if (channel) {
                pusher.unsubscribe(channel.name);
            }

            // Subscribe to Pusher channel for the selected client
            channel = pusher.subscribe('chat_' + studentId);

            // Listen for new messages
            channel.bind('new-message', function(data) {
                // Here, 'data.receiver_id' might differ depending on your server structure
                // Adjust the condition if you have a different approach
                if (data.receiver_id == studentId) {
                    document.getElementById('chat-box').innerHTML += `
                        <div class="bg-gray-100 rounded-lg p-3">
                            ${data.message}
                        </div>`;
                }
            });
        }

        // Send message to a specific user
        function sendMessage() {
            const message = document.getElementById('message_input').value.trim();
            const studentId = document.getElementById('student_id').value;

            if (!message) {
                alert('Please enter a message');
                return;
            }

            fetch('../messages_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'send_message',
                    student_id: studentId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Display the message on the sender's side
                    document.getElementById('chat-box').innerHTML += `
                        <div class="bg-blue-500 text-white rounded-lg p-3 self-end">
                            You: ${message}
                        </div>`;
                    document.getElementById('message_input').value = '';
                } else {
                    alert('Failed to send message');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Error sending message. Please try again.');
            });
        }

        // If you want the "openChat" function from the Student Results search
        // to lead to the same chat interface, you can re-use openChatForMHP.
        function openChat(studentName) {
            // This could be extended if you have a direct link between 'studentName' and 'studentId'
            alert('Opening chat with ' + studentName + '. Integrate with your real data as needed.');
        }
    </script>
</body>
</html>
