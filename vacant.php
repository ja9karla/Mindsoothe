<?php
        include("auth.php");
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Availability</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed top-0 left-0 h-screen bg-white shadow-lg z-10">
        <!-- Logo Section -->
        <div class="flex items-center p-6 border-b">
            <div class="w-15 h-10 rounded-full flex items-center justify-center">
                <a href="#"><img src="images/Mindsoothe(2).svg" alt="Mindsoothe Logo"></a>
            </div>
        </div>

        <!-- Menu Items -->
        <nav class="mt-6">
            <a href="#" class="menu-item flex items-center px-6 py-3" data-section="dashboard" id="gracefulThreadItem">
                <img src="images/gracefulThread.svg" alt="Graceful Thread" class="w-5 h-5">
                <span class="menu-text ml-3">Graceful Thread</span>
            </a>
            <a href="#" class="menu-item flex items-center px-6 py-3 text-gray-600" data-section="appointments" id="MentalWellness">
                <img src="images/Vector.svg" alt="Mental Wellness Companion" class="w-5 h-5">
                <span class="menu-text ml-3">Mental Wellness Companion</span>
            </a>
            <a href="#" class="menu-item active flex items-center px-6 py-3 text-gray-600" data-section="profile" id="ProfileItem">
                <img src="images/Vector.svg" alt="Mental Wellness Companion" class="w-5 h-5">
                <span class="menu-text ml-3">Profile</span>
            </a>
        </nav>

        <!-- User Profile and Logout Section -->
        <div class="absolute bottom-0 w-full border-t">
            <!-- User Profile -->
            <a href="#" class="menu-item flex items-center px-6 py-4 text-gray-600">
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="w-8 h-8 rounded-full">
                <span class="menu-text ml-3"><?php echo htmlspecialchars($fullName); ?></span>
            </a>

            <!-- Logout -->
            <a href="landingpage.html" class="menu-item flex items-center px-6 py-4 text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="menu-text ml-3">Logout</span>
            </a>  
        </div>
    </div>
    <div class="pl-64">
    <div class="p-8 ml-4">
        <!-- Header -->
        <div class="mb-6 ">
            <h1 class="text-2xl font-bold text-gray-800">My Available Time</h1>
            <p class="text-gray-600">Set your vacant time slots for counseling</p>
        </div>

      <!-- Add New Time Slot Form -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form id="addTimeForm" class="flex flex-wrap gap-4">
        <div class="w-full md:w-auto">
            <select name="day" required class="w-full md:w-48 p-2 border rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <input type="time" name="start_time" required 
                   min="07:30" max="17:00"
                   class="w-full md:w-40 p-2 border rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="w-full md:w-auto">
            <input type="time" name="end_time" required 
                   min="07:30" max="17:00"
                   class="w-full md:w-40 p-2 border rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="w-full md:w-auto">
            <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Add Time Slot
            </button>
        </div>
    </form>
</div>

        <!-- Time Slots Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <!-- Time slots will be inserted here -->
        </tbody>
    </table>
    <div id="emptyState" class="hidden p-4 text-center text-gray-500">
        No time slots available
    </div>
</div>

    <script>    // Section switching functionality
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addTimeForm');
    const startTimeInput = form.querySelector('[name="start_time"]');
    const endTimeInput = form.querySelector('[name="end_time"]');
    
     // Function to validate time range
     function validateTimeRange(startTime, endTime) {
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        
        // Check if end time is after start time
        if (end <= start) {
            return {
                isValid: false,
                message: 'End time must be later than start time'
            };
        }

         // Check if times are within allowed range (7:30 AM - 5:00 PM)
         const minTime = new Date(`2000-01-01T07:30:00`);
        const maxTime = new Date(`2000-01-01T17:00:00`);

        if (start < minTime || end > maxTime) {
            return {
                isValid: false,
                message: 'Time must be between 7:30 AM and 5:00 PM'
            };
        }

        return {
            isValid: true
        };
    }

    // Add input event listeners for real-time validation
    [startTimeInput, endTimeInput].forEach(input => {
        input.addEventListener('change', function() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;

            if (startTime && endTime) {
                const validation = validateTimeRange(startTime, endTime);
                if (!validation.isValid) {
                    alert(validation.message);
                    input.value = ''; // Clear the invalid input
                }
            }
        });
    });

// Form submission validation
form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
            day: form.querySelector('[name="day"]').value,
            start_time: startTimeInput.value,
            end_time: endTimeInput.value,
            user_id: 1
        };

        const validation = validateTimeRange(formData.start_time, formData.end_time);
        
        if (!validation.isValid) {
            alert(validation.message);
            return;
        }

        try {
            const response = await fetch('get_vacant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            
            if(result.success) {
                alert('Time slot saved successfully!');
                loadTimeSlots();
                form.reset();
            } else {
                alert('Error: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to save time slot: ' + error.message);
        }
    });
});

// Function to load time slots
// Function to load time slots
async function loadTimeSlots() {
    try {
        const response = await fetch('get_vacant.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const timeSlots = await response.json();
        console.log('Loaded time slots:', timeSlots);

        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = ''; // Clear existing content

        // Handle empty state
        if (!Array.isArray(timeSlots) || timeSlots.length === 0) {
            document.getElementById('emptyState').classList.remove('hidden');
            return;
        }

        document.getElementById('emptyState').classList.add('hidden');

        // Render time slots
        timeSlots.forEach(slot => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.day_of_week}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.start_time}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.end_time}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="deleteTimeSlot(${slot.id})" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    } catch (error) {
        console.error('Error loading time slots:', error);
        // Show error message to user
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-600 p-4';
        errorDiv.textContent = 'Failed to load time slots. Please try again later.';
        document.querySelector('table').before(errorDiv);
    }
}

// Function to delete time slot
async function deleteTimeSlot(id) {
    if (!confirm('Are you sure you want to delete this time slot?')) {
        return;
    }

    try {
        const response = await fetch(`get_vacant.php?id=${id}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            loadTimeSlots(); // Reload the table
        } else {
            alert(result.error || 'Failed to delete time slot');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete time slot. Please try again later.');
    }
}

// Load time slots when page loads
document.addEventListener('DOMContentLoaded', loadTimeSlots);
 // Section switching functionality
 const menuItems = document.querySelectorAll('.menu-item');
            const sections = document.querySelectorAll('.section');
            
            menuItems.forEach(item => {
              item.addEventListener('click', function(e) {
                if (this.getAttribute('data-section')) {
                  e.preventDefault();
                  
                  menuItems.forEach(mi => mi.classList.remove('active'));
                  sections.forEach(section => section.classList.remove('active'));
                  
                  this.classList.add('active');
                  
                  const sectionId = this.getAttribute('data-section');
                  document.getElementById(`${sectionId}-section`).classList.add('active');
                }
              });
            });
    </script>
    <script src="sidebarnav.js"></script>
    </div>
    </div>
</body>
</html>
