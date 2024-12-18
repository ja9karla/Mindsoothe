<?php
include("auth.php");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Wellness Companion</title>
</head>
<style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

    .container {
        display: flex;
    }

    .sidebar {
        width: 250px;
        background-color: #f4f4f4;
        position: fixed; /* Make the sidebar fixed on the left */
        top: 0; /* Stick to the top */
        left: 0; /* Stick to the left */
        height: 100vh; /* Full height of the viewport */
        display: flex;
        flex-direction: column;
        border-right: 1px solid #ddd;
        overflow-y: auto; /* Add scrolling if the sidebar content overflows */
        z-index: 1000; /* Ensure it's on top of other content */
    }

  .start-btn {
    color: white !important;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    font-family: Arial, sans-serif;
    cursor: pointer;
    text-align: center;
    display: flex;
    width: 80px;
    height: 34px;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 515px;
    left: 585px;
    background-color: #1CABE3;
  }
  
  .start-btn:hover {
    background-color: #1697c8;
    color: white !important;
  }
  
  .modal-title {
      font-size: 1.5rem;
    }
    
    .modal-body h6 {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
    }

    .menu-content {
        display: flex;
        flex-direction: column;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s, padding-left 0.3s;
        font-size: 16px;
    }

    .menu-item:hover {
        background-color: #e2e2e2;
    }

    .menu-item.active {
        background-color: #d0e4f5; /* Highlight color for active item */
        border-left: 4px solid #007bff; /* A left border to indicate active section */
    }
    .clicked {
        background: rgba(217, 217, 217, 0.45);
        box-shadow: 0px 4px 5px 0px rgba(0, 0, 0, 0.25) inset;
    }

    .menu-icon {
        width: 24px;
        height: 24px;
    }

    .menu-text {
        margin-left: 15px;
        transition: opacity 0.3s, margin-left 0.3s;
    }

    .user-profile {
        display: flex;
        align-items: center;
        padding: 20px;
        background-color: #f4f4f4;
        border-top: 1px solid #ddd;
        cursor: pointer;
        text-decoration: none;
        color: #333;
    }

    .user-avatar {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin-right: 15px;
    }

    .username {
        font-size: 16px;
        transition: opacity 0.3s, margin-left 0.3s;
    }

    .Logout {
        display: block;
        padding: 15px 20px;
        color: #333;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s;
    }

    .Logout:hover {
        background-color: #e2e2e2;
    }

    .UserAcc {
        margin-top: auto;
        display: flex;
        flex-direction: column;
    }

    .logo {
        padding: 10px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }

    .logo img {
        width: 235px; /* Adjust the size of the logo */
        height: 80px;
        border-bottom: 1px solid #ddd;
    }

    .summary-table {
      width: 100%;
      margin-top: 1rem;
    }
    .summary-table th, .summary-table td {
      padding: 0.5rem;
      border-bottom: 1px solid #dee2e6;
    }
    .summary-table th {
      text-align: left;
    }

    .modal-content {
        border-radius: 15px;
        border: none;
    }
    
    .text-primary {
        color: #1CABE3 !important;
    }
    
    .btn-primary {
        background-color: #1CABE3;
        border-color: #1CABE3;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #1697c8;
        border-color: #1697c8;
        color: white;
    }
    
    .btn-outline-primary {
        color: #1CABE3;
        border-color: #1CABE3;
    }
    
    .btn-outline-primary:hover {
        background-color: #1CABE3;
        border-color: #1CABE3;
    }
    
    .option {
        
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
    }
   
    .option input[type="radio"] {
        cursor: pointer;
        margin: 0;
        display: none;
    }
    
    .option input[type="radio"]:checked + label {
        background-color: #1CABE3;
        color: white;
    }
    
    .option label {
      background-color: #f8f9fa;
        cursor: pointer;
        margin: 0;
        flex: 1;
    }

    .options-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .question-number {
        color: #000000;
        font-weight: 500;
        font-size: 1.1rem;
        min-width: 25px;
    }
    
    .question-text {
        font-size: 1.3rem;
        font-weight: 500;
        line-height: 1.5;
    }

    .question-header {
        align-items: flex-start;
    }

    .results-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }

    .score-section {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-table {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px;
        background-color: white;
        border-radius: 6px;
    }

    .summary-row span:first-child {
        font-weight: 500;
    }
    
    .score-section h4 .text-primary,
    .score-section h5 .text-primary {
        color: #1CABE3 !important;
        font-weight: 600;
    }
    .hidden {
        display: none;
    }
    .content-area {
    flex: 1;
    padding: 0 20px;
}

.profile-card {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    margin-left: 240px;
    width: 80%;
}

.profile-header {
    display: flex;
    align-items: center;
}

.profile-header img {
    width: 150px;
    height: 200px;
    border-radius: 10px;
    object-fit: cover;
    margin-right: 20px;
}

.profile-details {
    flex: 1;
}

.profile-details h2 {
    margin-bottom: 10px;
}

.profile-details p {
    margin-bottom: 10px;
    color: #555;
}

.profile-details span {
    font-weight: bold;
}

.profile-schedule-container {
    width: 70%;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 1px;
    margin-left: 240px;
    width: 80%;
}

.tabs {
    display: flex;
    justify-content: space-around;
    background-color: #1CABE3;
    border-bottom: 1px solid #ddd;
    
}

.tab {
    width: 33.33%;
    text-align: center;
    padding: 15px 0;
    cursor: pointer;
    font-weight: bold;
    color: #333;
}

.tab.active {
    background-color: #ddd;
    color: #ffffff;
    border-bottom: 3px solid #ddd;
}

.tab-content {
    padding: 20px;
    width: 80%;
}

.tab-content h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.tab-content p {
    font-size: 16px;
    color: #666;
}

.tab-content hr {
    border: none;
    border-bottom: 1px solid #ddd;
    margin: 20px 0;
}

.content {
    display: none;
}

.tab-content .content.active {
    display: block;
}
/* Success Message Animation */
.success-message {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #67DCC9;
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 9999;
    font-size: 16px;
    font-weight: 500;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s ease-in-out;
}

.success-message.show {
    transform: translateX(0);
    opacity: 1;
}

.success-message.hide {
    transform: translateX(100%);
    opacity: 0;
}

.write-review-section {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        width: 130%;
   
    }

    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 4px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 25px;
        color: #ddd;
        cursor: pointer;
    }

    .star-rating label:before {
        content: '★';
    }

    .star-rating input:checked ~ label {
        color: gold;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: gold;
    }

    .review-input {
        width: 100%;
        min-height: 100px;
        margin: 10px 0;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        resize: vertical;
    }

    .submit-review {
        background-color: #67DCC9;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .submit-review:hover {
        background-color: #50b3a3;
    }

        /* Scoped CSS for Calendar in Schedule Tab */
        #schedule-content .calendar-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
            width: 120%;
        }

        #schedule-content .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        #schedule-content .calendar-header button {
            background-color: #e0e0e0;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
        }

        #schedule-content .month-year {
            font-weight: bold;
            font-size: 18px;
        }

        #schedule-content .calendar-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #schedule-content .calendar-weekdays {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 9px;
            color: #666666;
            font-weight: bold;
        }

        #schedule-content .calendar-weekdays div {
            flex-basis: 13%;
            text-align: center;
        }

        #schedule-content .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            width: 100%;
        }

        .date {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }
        .date:hover {
            background-color: #67DCC9;
            color: white;
        }


        /* Time Selection Modal Styles */
        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 90%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
        padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto; /* Center modal horizontally */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20%; /* Move modal lower */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .time-option {
            padding: 8px; /* Reduced padding for smaller size */
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .time-option:hover {
            background-color: #67DCC9;
            color: white;
        }
       
        .time-option.booked {
            background-color: #FFA7AC;
            color: white;
            cursor: not-allowed;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #schedule-content .calendar-header button {
                font-size: 14px;
            }

            #schedule-content .calendar-weekdays div,
            #schedule-content .calendar-dates .date {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            #schedule-content .calendar-header {
                flex-direction: column;
                align-items: center;
            }

            #schedule-content .calendar-header button {
                margin-bottom: 10px;
            }
        }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<body>
    <div class="container">
         <!-- Left Sidebar -->
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
            <a href="#" class="menu-item active flex items-center px-6 py-3 text-gray-600" data-section="appointments" id="MentalWellness">
                <img src="images/Vector.svg" alt="Mental Wellness Companion" class="w-5 h-5">
                <span class="menu-text ml-3">Mental Wellness Companion</span>
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
    </div>

    <div class="content-area p-6 bg-gray-50 min-h-screen">
  <!-- Profile Card -->
  <div class="profile-card bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row gap-6">
    <img src="images/emily.jpg" alt="Emily Roberts" class="w-32 h-32 rounded-full object-cover">
    <div class="profile-details">
      <h2 class="text-2xl font-semibold text-gray-800">Emily Roberts</h2>
      <p class="text-sm text-gray-600 mt-2">
        <span class="font-medium">Licensed Mental Health Counselor</span>
      </p>
      <p class="text-sm text-gray-600">MAEd | MindCare Psychiatry Associates</p>
      <p class="text-sm text-yellow-500 mt-1">Rating: ⭐⭐⭐⭐⭐ (50 Patient Satisfaction Ratings)</p>
    </div>
  </div>

  <!-- Tabs Section -->
  <div class="profile-schedule-container mt-8 bg-white shadow-lg rounded-lg p-6">
    <!-- Tabs -->
    <div class="tabs flex space-x-4 border-b">
      <button class="tab active text-blue-500 border-b-2 border-blue-500 pb-2" id="profile-tab">Profile</button>
      <button class="tab text-gray-500 hover:text-blue-500 pb-2" id="schedule-tab">Schedule</button>
      <button class="tab text-gray-500 hover:text-blue-500 pb-2" id="reviews-tab">Rating and Reviews</button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content mt-4">
      <!-- Profile Content -->
      <div id="profile-content" class="content active">
        <h3 class="text-lg font-semibold text-gray-800">Qualifications and Experience</h3>
        <p class="text-sm text-gray-600 mt-2">Details about qualifications and experience go here...</p>
        <hr class="my-4">
        <h3 class="text-lg font-semibold text-gray-800">Education</h3>
        <p class="text-sm text-gray-600 mt-2">Details about education go here...</p>
      </div>

      <!-- Schedule Content -->
      <div id="schedule-content" class="content hidden">
        <div class="availability-bar h-2 bg-red-300 rounded-full mb-4"></div>
        <div class="calendar-container">
          <div class="calendar-header flex justify-between items-center mb-4">
            <button class="prev-month text-gray-500 hover:text-blue-500">&lt;</button>
            <div class="month-year text-lg font-semibold text-gray-800">February 2024</div>
            <button class="next-month text-gray-500 hover:text-blue-500">&gt;</button>
          </div>
          <div class="calendar-body">
            <div class="calendar-weekdays grid grid-cols-7 gap-2 text-center text-sm text-gray-500">
              <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>
            <div class="calendar-dates grid grid-cols-7 gap-2 mt-2">
              <!-- Dynamically generate dates here -->
            </div>
          </div>
        </div>
      </div>

      <!-- Reviews Content -->
      <div id="reviews-content" class="content hidden">
        <div class="write-review-section">
          <h3 class="text-lg font-semibold text-gray-800">Write a Review</h3>
          <form id="reviewForm" onsubmit="submitReview(event)" class="mt-4">
            <div class="star-rating flex space-x-2 mb-4">
              <input type="radio" id="star5" name="rating" value="5">
              <label for="star5" class="text-yellow-500">⭐</label>
              <input type="radio" id="star4" name="rating" value="4">
              <label for="star4" class="text-yellow-500">⭐</label>
              <input type="radio" id="star3" name="rating" value="3">
              <label for="star3" class="text-yellow-500">⭐</label>
              <input type="radio" id="star2" name="rating" value="2">
              <label for="star2" class="text-yellow-500">⭐</label>
              <input type="radio" id="star1" name="rating" value="1">
              <label for="star1" class="text-yellow-500">⭐</label>
            </div>
            <textarea
              class="review-input w-full border border-gray-300 rounded-lg p-4 text-sm text-gray-600"
              placeholder="Write your review here..." required></textarea>
            <button type="submit" class="submit-review bg-blue-500 text-white rounded-lg px-4 py-2 mt-4">
              Submit Review
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

   <script>
        // Tab Switching Logic
        function switchTab(clickedTab, contentId) {
            // Remove 'active' class from all tabs
            const tabs = document.querySelectorAll('.tab');
             tabs.forEach(tab => tab.classList.remove('active'));

            // Add 'active' class to the clicked tab
             clickedTab.classList.add('active');

             // Hide all content sections
             const contents = document.querySelectorAll('.content');
             contents.forEach(content => content.classList.remove('active'));

             // Show the clicked tab's content
             const contentToShow = document.getElementById(contentId);
             if (contentToShow) {
                 contentToShow.classList.add('active');
             }
         }
         
         document.addEventListener('DOMContentLoaded', function () {
        // Tab switching
        document.getElementById('profile-tab').addEventListener('click', function () {
            switchTab(this, 'profile-content');
        });

        document.getElementById('schedule-tab').addEventListener('click', function () {
            switchTab(this, 'schedule-content');
        });

        document.getElementById('reviews-tab').addEventListener('click', function () {
            switchTab(this, 'reviews-content');
        });

        function switchTab(tabElement, contentId) {
            // Deactivate all tabs and content
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.content').forEach(content => content.classList.remove('active'));

            // Activate the selected tab and content
            tabElement.classList.add('active');
            document.getElementById(contentId).classList.add('active');
        }

        // Calendar and Appointment Booking
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let selectedDay, selectedTime;
        const monthNames = [
            "January", "February", "March", "April", "May", "June", 
            "July", "August", "September", "October", "November", "December"
        ];
        let bookedTimes = {};

        function updateCalendar() {
            const monthYear = document.getElementById("monthYear");
            const calendarDates = document.getElementById("calendarDates");

            monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            calendarDates.innerHTML = '';

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const days = new Date(currentYear, currentMonth + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                calendarDates.innerHTML += `<div class="date"></div>`;
            }

            for (let day = 1; day <= days; day++) {
                calendarDates.innerHTML += `<div class="date" data-day="${day}">${day}</div>`;
            }
        }

        function openTimeModal(day) {
            selectedDay = day;
            const modal = document.getElementById("timeModal");
            const timeOptionsContainer = document.getElementById("timeSlotContainer");
            modal.style.display = "block";

            timeOptionsContainer.innerHTML = '';
            const fullDate = `${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const timeSlots = [
                "09:00AM-10:00AM", "10:00AM-11:00AM", "11:00AM-12:00PM", 
                "02:00PM-03:00PM", "03:00PM-04:00PM", "04:00PM-05:00PM"
            ];

            timeSlots.forEach(time => {
                if (!isTimeBooked(fullDate, time)) {
                    const timeOption = document.createElement('div');
                    timeOption.classList.add('time-option');
                    timeOption.setAttribute('data-time', time);
                    timeOption.textContent = time;
                    timeOption.onclick = () => confirmAppointment(time);
                    timeOptionsContainer.appendChild(timeOption);
                }
            });
        }

        function isTimeBooked(date, time) {
            return bookedTimes[date] && bookedTimes[date].includes(time);
        }

        function confirmAppointment(time) {
            selectedTime = time;
            const fullDate = `${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${selectedDay.toString().padStart(2, '0')}`;
            const confirmation = confirm(`Book appointment on ${monthNames[currentMonth]} ${selectedDay}, ${currentYear} at ${time}?`);

            if (confirmation) {
                if (!bookedTimes[fullDate]) bookedTimes[fullDate] = [];
                bookedTimes[fullDate].push(time);

                alert(`Appointment booked for ${monthNames[currentMonth]} ${selectedDay}, ${currentYear} at ${time}.`);
                closeTimeModal();
                saveAppointment(fullDate, time);
            }
        }

        function saveAppointment(date, time) {
            fetch('save_appointmentD.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ appointmentDate: date, appointmentTime: time })
            })
            .then(response => response.json())
            .then(data => {
                const successMessage = document.getElementById('successMessage');
                successMessage.style.display = 'block';
                setTimeout(() => successMessage.style.display = 'none', 3000);
            })
            .catch(error => console.error('Error saving appointment:', error));
        }

        function closeTimeModal() {
            document.getElementById("timeModal").style.display = "none";
        }

        document.getElementById('closeModal').addEventListener('click', closeTimeModal);

        // Reviews
        document.getElementById('reviewForm').addEventListener('submit', submitReview);

        function submitReview(event) {
            event.preventDefault();
            const rating = document.querySelector('input[name="rating"]:checked');
            const reviewText = document.querySelector('.review-input').value.trim();

            if (!rating || !reviewText) {
                alert('Please complete both rating and review.');
                return;
            }

            const reviewDiv = document.createElement('div');
            reviewDiv.classList.add('review');
            reviewDiv.innerHTML = `
                <h4>Anonymous</h4>
                <div class="rating">${'★'.repeat(rating.value)}</div>
                <p>${reviewText}</p>
            `;
            const reviewsSection = document.querySelector('#reviews-content');
            reviewsSection.prepend(reviewDiv);
            document.getElementById('reviewForm').reset();
            alert('Review submitted successfully!');
        }

        // Navigation
        document.querySelector('.prev-month').addEventListener('click', () => {
            if (--currentMonth < 0) { currentMonth = 11; currentYear--; }
            updateCalendar();
        });

        document.querySelector('.next-month').addEventListener('click', () => {
            if (++currentMonth > 11) { currentMonth = 0; currentYear++; }
            updateCalendar();
        });

        document.getElementById('calendarDates').addEventListener('click', function (e) {
            if (e.target.classList.contains('date') && e.target.dataset.day) {
                openTimeModal(e.target.dataset.day);
            }
        });

        // Initialize
        updateCalendar();
    });

    </script>  

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="sidebarnav.js"></script>


</body>
</html>