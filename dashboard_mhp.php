<?php
session_start();

// Include the database connection
include("connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authentication Check
if (!isset($_SESSION['doctor_id'])) {
    header('Location: login.php');
    exit();
}
$mhp_id = (int)$_SESSION['doctor_id'];  // The counselor's ID in your MHP table

// AJAX Request Handling
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // 1) Fetch Doctor Name (from MHP table)
    if (isset($_GET['fetchDoctorName'])) {
        header('Content-Type: application/json');

        $sql = "SELECT fname, lname, department, profile_image FROM MHP WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $mhp_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $doctor = $result->fetch_assoc();
            echo json_encode($doctor);
        } else {
            echo json_encode(['error' => 'Doctor not found']);
        }

        $stmt->close();
        $conn->close();
        exit();
    }

    // 2) Fetch messages for a specific student and this MHP
    if (isset($_GET['fetchMessages']) && isset($_GET['student_id'])) {
        header('Content-Type: application/json');

        $student_id = (int) $_GET['student_id'];

        // We want all messages where (student_id = X AND mhp_id = Y).
        // "sender_type" and "receiver_type" just describe who sent each row,
        // but the conversation is always the same pair.
        $query = "
            SELECT *
            FROM Messages
            WHERE student_id = ? 
              AND mhp_id = ?
            ORDER BY timestamp ASC
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $student_id, $mhp_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        echo json_encode($messages);

        $stmt->close();
        $conn->close();
        exit();
    }
}

// POST Request Handling for sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: The MHP (doctor) is sending a message to a student
    if (isset($_POST['receiver_id']) && isset($_POST['message'])) {
        header('Content-Type: application/json');

        $student_id = (int) $_POST['receiver_id'];
        $message    = trim($_POST['message']);

        if (empty($student_id) || empty($message)) {
            echo json_encode(["error" => "Invalid input"]);
            exit();
        }

        // For an MHP sending to a student:
        $sender_type   = 'MHP';
        $receiver_type = 'student';

        $query = "
            INSERT INTO Messages (
                student_id,
                mhp_id,
                sender_type,
                receiver_type,
                message
            ) VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);
        // We have 5 placeholders: (i, i, s, s, s)
        $stmt->bind_param("iisss", 
            $student_id,       // the student's ID from "Users"
            $mhp_id,           // the MHP's ID from "MHP"
            $sender_type,      // 'MHP'
            $receiver_type,    // 'student'
            $message
        );

        if ($stmt->execute()) {
            echo json_encode(["success" => "Message sent successfully"]);
        } else {
            echo json_encode(["error" => "Failed to send message: " . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        exit();
    }
}

// --------------------------------------------
// Removed the bulk "Fetch received messages" 
// that automatically loops in the HTML below.
// --------------------------------------------
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
    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>

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
                <img src="images/Mindsoothe(2).svg" alt="Mindsoothe Logo">
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
    <div class="main-content min-h-screen p-5">
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="content-section active">
            <!-- Counselor Profile -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center">
                    <div class="relative">
                        <img id="counselor-image" src="" 
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
        <div id="chats-section" class="content-section h-screen flex">
            <div class="flex h-screen bg-gray-100">
                <!-- Left sidebar for chat user list -->
                <div class="w-1/4 bg-white border-r shadow-md flex flex-col">
                    <div class="p-4 border-b bg-gray-50">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search students..." 
                                class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                        </div>
                    </div>
                    <ul id="userList" class="overflow-y-auto flex-grow">
                        <!-- Dynamically loaded user list will appear here -->
                        
                    </ul>
                </div>

                <!-- Right side for actual chat messages -->
                <div class="flex flex-col flex-grow bg-white shadow-md rounded-lg">
                    <div class="p-4 border-b bg-gray-50 flex items-center justify-between">
                        <h2 id="chat-header" class="text-xl font-semibold text-gray-800">Chat with Student</h2>
                    </div>

                    <div id="chatMessages" class="flex-grow overflow-y-auto p-6 space-y-4 bg-gray-50 max-h-[calc(100vh-10rem)]">
                        <!-- Messages from the selected student will appear here dynamically -->
                    </div>

                    <div class="p-4 border-t bg-gray-50 flex items-center">
                        <input type="hidden" id="student_id">
                        <input type="text" id="message_input" placeholder="Type your message..." 
                            class="flex-1 p-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <button onclick="sendMessage()" 
                                class="ml-3 p-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition-all">
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
            // 1. Initialize Pusher
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

                    menuItems.forEach(mi => mi.classList.remove('active'));
                    sections.forEach(section => section.classList.remove('active'));

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
                const formData = new FormData();
                formData.append('profile_image', selectedImage);
                formData.append('action', 'upload_profile_image');

                fetch('profile_upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('counselor-image').src = data.filepath;
                    } else {
                        console.error('Profile upload failed:', data.message);
                    }
                })
                .catch(error => console.error('Error uploading profile:', error));
            }
            document.getElementById('update-modal').classList.remove('active');
        }

        // ------------------------
        // Student Search (Dashboard)
        // ------------------------
        function searchStudent() {
            const searchInput = document.getElementById('student-search').value.trim();
            const resultsContainer = document.getElementById('student-results');

            if (searchInput === '') {
                resultsContainer.classList.add('hidden');
                return;
            }

            // Demo for searching a student. Replace with real API call if needed.
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

        function printCallSlip(studentName) {
            console.log(`Printing call slip for ${studentName}...`);
        }

        // Reuse this if needed
        function openChat(studentName) {
            alert('Opening chat with ' + studentName + ' (add your real logic here).');
        }

        // ------------------------
        // Chat Functionality
        // ------------------------
        function setupUserSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase();

                // Replace with your actual endpoint if needed
                // Example: MHPSeacrch.php?fetchUsers=true&search=...
                fetch(`MHPSeacrch.php?fetchUsers=true&search=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => populateUserList(users))
                    .catch(error => console.error('Error fetching users:', error));
            });

            // Load all users initially
            searchInput.dispatchEvent(new Event('input'));
        }

        function populateUserList(users) {
            const userList = document.getElementById('userList');
            userList.innerHTML = ''; 

            users.forEach(user => {
                const li = document.createElement('li');
                li.className = 'p-4 flex items-center cursor-pointer hover:bg-gray-100 border-b';
                li.innerHTML = `
                    <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="font-semibold">${user.firstName} ${user.lastName}</p>
                        <p class="text-sm text-gray-500">Latest message preview...</p>
                    </div>
                    <span class="text-sm text-gray-400">10:37 AM</span>
                `;
                // Open chat on click
                li.addEventListener('click', () => openChatForMHP(user.id, user.firstName + ' ' + user.lastName));
                userList.appendChild(li);
            });
        }

        // Open chat with a specific user
        function openChatForMHP(studentId, studentName) {
            // 1) Set chat header
            document.getElementById('chat-header').innerText = 'Chat with ' + studentName;
            // 2) Store student_id in a hidden input
            document.getElementById('student_id').value = studentId;
            // 3) Clear chatMessages
            const chatMessagesDiv = document.getElementById('chatMessages');
            chatMessagesDiv.innerHTML = '';

            // 4) Unsubscribe from previous channel if any
            if (channel) {
                pusher.unsubscribe(channel.name);
            }

            // 5) Subscribe to new channel for that student
            channel = pusher.subscribe('chat_' + studentId);
            channel.bind('new-message', function(data) {
                // You can check data.sender_id, data.receiver_id, etc.
                // e.g., if data.sender_type !== 'MHP'
                if (data.sender_type !== 'MHP') {
                    appendMessageToUI(data);
                }
            });

            // 6) Fetch existing chat from the server for this conversation
            fetch(`?fetchMessages=true&student_id=${studentId}`)
                .then(response => response.json())
                .then(messages => {
                    messages.forEach(msg => appendMessageToUI(msg));
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Create a message bubble
        function createMessageElement(message, type) {
            const div = document.createElement('div');
            const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            div.className = `mb-4 ${type === 'sent' ? 'flex justify-end' : 'flex justify-start'}`;

            const messageContainer = document.createElement('div');
            messageContainer.className = `max-w-[70%] flex flex-col ${type === 'sent' ? 'items-end' : 'items-start'}`;

            const messageContent = document.createElement('div');
            messageContent.className = `px-4 py-2 rounded-lg break-words`;
            messageContent.style.backgroundColor = type === 'sent' ? '#e6e6e6' : '#1cabe3';
            messageContent.style.color = type === 'sent' ? '#333' : '#fff';
            messageContent.textContent = message;

            const timeStampElem = document.createElement('div');
            timeStampElem.className = 'text-xs text-gray-500 mt-1';
            timeStampElem.textContent = timestamp;

            messageContainer.appendChild(messageContent);
            messageContainer.appendChild(timeStampElem);
            div.appendChild(messageContainer);

            return div;
        }

        function appendMessageToUI(msg) {
            const chatMessagesDiv = document.getElementById('chatMessages');

            const messageElement = createMessageElement(msg.message, msg.sender_type === 'student' ? 'received' : 'sent');
            chatMessagesDiv.appendChild(messageElement);

            // Scroll to the bottom of the chat
            chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
        }

        // Send message to the currently open student
        function sendMessage() {
            const message = document.getElementById('message_input').value.trim();
            const studentId = document.getElementById('student_id').value;

            if (!message) {
                alert('Please enter a message.');
                return;
            }

            fetch('messages_handler_mhp.php', {  // or messages_handler_mhp.php if you prefer
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `receiver_id=${studentId}&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Append to UI as counselor's message
                    const newMsg = {
                        message: message,
                        sender_type: 'MHP',
                        timestamp: new Date().toISOString()
                    };
                //  appendMessageToUI(newMsg);
                    document.getElementById('message_input').value = '';
                } else {
                    alert(data.error || 'Failed to send message');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Error sending message. Please try again.');
            });
        }

        // Add "Enter key" event for sending message
        const message_input = document.getElementById('message_input');
        if (message_input) {
            message_input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }
    </script>
</body>
</html>