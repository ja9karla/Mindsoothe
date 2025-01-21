<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>
            <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-600" data-section="chats">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18a1 1 0 011 1v12a1 1 0 01-1 1H6l-3 3V5a1 1 0 011-1z"/>
                </svg>
                <span class="ml-3">Chats</span>
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="absolute bottom-0 w-full p-6 border-t">
            <a href="../landingpage.html" class="flex items-center text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
                        <img id="counselor-image" src="/api/placeholder/100/100" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                        <label for="profile-upload" class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 cursor-pointer hover:bg-blue-600">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </label>
                        <input type="file" id="profile-upload" class="hidden" accept="image/*">
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold">Maloi Ricalde</h2>
                        <p class="text-gray-600">Department: SACE</p>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center mb-4">
                    <input type="text" id="student-search" placeholder="Search student..." class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button onclick="searchStudent()" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Search</button>
                </div>
            </div>

            <!-- Student Results - Initially Hidden -->
            <div id="student-results" class="hidden bg-white rounded-lg shadow-md p-6">
                <!-- Student results will be dynamically inserted here -->
            </div>
        </div>

        <!-- Chats Section -->
        <div id="chats-section" class="content-section">
            <!-- Chat interface will be added here -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Messages</h2>
                <div id="chat-container">
                    <!-- Chat messages will be dynamically inserted here -->
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
                <button onclick="cancelProfileUpdate()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
                <button onclick="confirmProfileUpdate()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let selectedImage = null;

        // Section switching functionality
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

        // Profile image upload functionality
        const profileUpload = document.getElementById('profile-upload');
        const counselorImage = document.getElementById('counselor-image');
        const updateModal = document.getElementById('update-modal');

        profileUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                selectedImage = file;
                updateModal.classList.add('active');
            }
        });

        function cancelProfileUpdate() {
            selectedImage = null;
            profileUpload.value = '';
            updateModal.classList.remove('active');
        }

        function confirmProfileUpdate() {
            if (selectedImage) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    counselorImage.src = e.target.result;
                    // Here you would typically upload the image to your server
                }
                reader.readAsDataURL(selectedImage);
            }
            updateModal.classList.remove('active');
        }

        // Student search functionality
        function searchStudent() {
            const searchInput = document.getElementById('student-search').value;
            const resultsContainer = document.getElementById('student-results');
            
            if (searchInput.trim() === '') {
                resultsContainer.classList.add('hidden');
                return;
            }

            // This is where you would normally make an API call to search for students
            // For demonstration, we'll show a sample student card
            resultsContainer.classList.remove('hidden');
            resultsContainer.innerHTML = `
                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <img src="/api/placeholder/64/64" alt="Student" class="w-16 h-16 rounded-full object-cover">
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
                        <button onclick="openChat('${searchInput}')" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Message</button>
                        <button onclick="printCallSlip('${searchInput}')" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Print Call Slip</button>
                    </div>
                </div>
            `;
        }

        // Function to open chat with specific student
        function openChat(studentName) {
            // Switch to chats section
            menuItems.forEach(mi => mi.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            document.querySelector('[data-section="chats"]').classList.add('active');
            document.getElementById('chats-section').classList.add('active');

            // You would typically load the chat history here
            document.getElementById('chat-container').innerHTML = `
                <div class="text-center text-gray-600">
                    Chat session with ${studentName}
                </div>
            `;
        }

        // Function to print call slip
        function printCallSlip(studentName) {
            // Implement call slip printing functionality
            console.log(`Printing call slip for ${studentName}`);
        }
    </script>
</body>
</html>