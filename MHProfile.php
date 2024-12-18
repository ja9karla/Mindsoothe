<?php
        include("auth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Wellnesss Companion</title>
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
<body class="bg-gray-100">
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

    <!-- Main Content -->
    <?php
        $sql = "SELECT id, fname, lname, specialization, experience, profile_image 
                FROM MHP 
                WHERE status = 'approved'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<h1 class="text-2xl font-bold text-center text-gray-800 mt-6 mb-8">
                    <span class="text-[#1cabe3]">PHQ-9</span> Mental <span class="text-[#1cabe3]">Wellness</span> Companion
                </h1>';

            echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 md:px-20 justify-items-center ml-60">';

            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="bg-white rounded-lg shadow-lg w-80 overflow-hidden transition-transform transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <img class="w-14 h-14 rounded-full object-cover mr-4" 
                                src="' . htmlspecialchars($row["profile_image"]) . '" 
                                alt="Profile Picture of ' . htmlspecialchars($row["fname"]) . '" />
                            <div>
                                <div class="text-lg font-bold text-gray-800">' . htmlspecialchars($row["fname"] . ' ' . $row["lname"]) . '</div>
                                <div class="text-sm text-gray-500">' . htmlspecialchars($row["specialization"]) . '</div>
                                <div class="text-sm text-gray-500">' . htmlspecialchars($row["experience"]) . ' years of experience</div>
                            </div>
                        </div>
                        <!-- Skills / Specialization Tags -->
                        <div class="flex flex-wrap justify-center gap-2 mb-4">
                            <div class="bg-gray-200 rounded-full px-3 py-1 text-xs text-gray-600">Stress</div>
                            <div class="bg-gray-200 rounded-full px-3 py-1 text-xs text-gray-600">Anxiety</div>
                            <div class="bg-gray-200 rounded-full px-3 py-1 text-xs text-gray-600">Depression</div>
                        </div>
                        <!-- View Profile Button -->
                        <div class="flex justify-center mt-4">
                            <button class="bg-white text-[#1cabe3] border-2 border-[#1cabe3] px-4 py-1 rounded hover:bg-[#1cabe3] hover:text-white transition duration-300 ease-in-out"
                                onclick="window.location.href=\'MHProfileDetail.php?mhp_id=' . $row["id"] . '\'">
                                View Profile
                            </button>
                        </div>
                    </div>
                </div>';
            }
            echo '</div>'; // End of grid
        } else {
            echo '<div class="text-center text-gray-700 mt-10">
                    No mental health professionals found.
                </div>';
        }

        $conn->close();
    ?>

    <script>
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
</html>