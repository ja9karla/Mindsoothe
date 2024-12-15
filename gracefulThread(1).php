<?php
        include("auth.php");

        // Text moderation function
        function moderateText($text) {
        // List of words/phrases to be flagged
        $badWords = [
            'puta', 'bwisit', 'gago', 'tanga', 'bobo', 'salot', 'pekeng', 'bakla', 'gaga', 'putangina',
            'ang kapal mo', 'mamatay ka na', 'ugok', 'wag ka makialam', 'katangahan', 'loko', 'sira ulo', 
            'haliparot', 'putangina mo', 'tangina mo', 'chismis', 'mukhang pera', 'patay gutom', 
            'mang-uuto', 'kasama sa buhay', 'hipokrito', 'unano', 'aswang', 'mangkukulam', 'mayabang', 
            'malandi', 'hudas', 'maasim ang mukha', 'sugapa', 'maka-appeal', 'bulok', 'tanga ka', 
            'fuck', 'shit', 'asshole', 'bitch', 'damn', 'cunt', 'motherfucker', 'bastard', 'dick', 
            'pussy', 'nigga', 'whore', 'slut', 'cocksucker', 'retard', 'crackhead', 'twat', 'fag', 
            'kike', 'chink', 'gook', 'spic', 'raghead', 'sandnigger', 'dirty Jew', 'wog', 'kaffir', 
            'nazi', 'towelhead', 'beaner', 'polack', 'dago', 'yid', 'wop', 'cholo', 'gypo', 'prick', 
            'cunt', 'whore', 'pikey', 'inbred', 'hillbilly', 'redneck', 'mamatay'
        ];
        
        
        // Replace flagged words with '[redacted]' and set the detection flag
        foreach ($badWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i'; // Case insensitive word boundary matching
            if (preg_match($pattern, $text)) {
                $detected = true; // Set flag if bad word is found
                $text = preg_replace($pattern, '[redacted]', $text);
            }
        }

        return $text;
    }

    // Handle post submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postText'])) {
        $content = mysqli_real_escape_string($conn, $_POST['postText']);
        
        // Moderate the post content
        $moderatedContent = moderateText($content);

        if (!empty($moderatedContent)) {
            // Insert the moderated post into the Graceful_Thread table
            $insertPost = "INSERT INTO GracefulThread (user_id, content) VALUES ('$userId', '$moderatedContent')";
            
            if (mysqli_query($conn, $insertPost)) {
                // Redirect to the same page to prevent form resubmission on page refresh
                header("Location: " . $_SERVER['PHP_SELF']);
                exit(); // Ensure no further code is executed
            } else {
                // Display error alert with the MySQL error
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            // Display alert for empty content
            echo "<script>alert('Post content cannot be empty!');</script>";
        }
    }
        // Handle like/unlike actions
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_button'])) {
            $postId = $_POST['post_id'];
        
            // Check if the user has already liked the post
            $checkLikeQuery = mysqli_query($conn, "SELECT * FROM post_likes WHERE user_id='$userId' AND post_id='$postId'");
        
            if (mysqli_num_rows($checkLikeQuery) > 0) {
                // User has already liked the post, so unlike it (delete the record)
                $deleteLikeQuery = "DELETE FROM post_likes WHERE user_id='$userId' AND post_id='$postId'";
                mysqli_query($conn, $deleteLikeQuery);
        } else {
                // User hasn't liked the post, so like it (insert a new record)
                $insertLikeQuery = "INSERT INTO post_likes (user_id, post_id) VALUES ('$userId', '$postId')";
                mysqli_query($conn, $insertLikeQuery);
            }

            // Redirect to prevent form resubmission on page refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
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

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8">
        <!-- Questionnaire Instructions -->
        <div id="instructionsContainer" class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                PHQ-9 Mental Health Screening
            </h2>

            <div class="text-center">
                <p class="text-gray-600 mb-6">
                    The Patient Health Questionnaire (PHQ-9) is a tool to help screen for depression severity.
                </p>

                <ol class="text-left list-decimal list-inside space-y-4 mb-6 text-gray-700">
                    <li>
                        <strong>Complete the 9 questions</strong> about how you've been feeling in the past two weeks
                    </li>
                    <li>
                        <strong>Rate each symptom's frequency</strong>: 
                        <ul class="list-disc list-inside ml-6">
                            <li>Not at all</li>
                            <li>Several days</li>
                            <li>More than half the days</li>
                            <li>Nearly every day</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Answer honestly</strong> to get the most accurate assessment
                    </li>
                </ol>

                <button 
                    id="startQuestionnaire" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105"
                >
                    Begin PHQ-9 Questionnaire
                </button>
            </div>
        </div>

        <!-- Questionnaire Modal -->
        <div 
            id="questionModal" 
            class="fixed inset-0 z-50 hidden items-center justify-center overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50"
        >
            <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl p-8">
                <!-- Close Button -->
                <button 
                    id="closeModal"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Modal Content -->
                <div class="text-center">
                    <h2 class="text-xl font-semibold mb-4">Patient Health Questionnaire</h2>
                    
                    <div id="questionContainer">
                        <p id="questionText" class="mb-4 text-gray-700"></p>
                        
                        <div id="answerOptions" class="space-y-2">
                            <!-- Dynamically populated radio options -->
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button 
                            id="prevQuestion" 
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
                        >
                            Previous
                        </button>
                        <button 
                            id="nextQuestion" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Modal -->
        <div 
            id="resultsModal" 
            class="fixed inset-0 z-50 hidden items-center justify-center overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50"
        >
            <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl p-8">
                <h2 class="text-2xl font-bold text-center mb-6">PHQ-9 Results</h2>
                
                <div class="text-center">
                    <p class="text-lg">
                        Total Score: <span id="totalScoreDisplay" class="font-bold text-blue-600"></span>
                    </p>
                    <p class="text-md mb-4">
                        Depression Severity: <span id="severityLevelDisplay" class="font-bold"></span>
                    </p>

                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <h3 class="font-semibold mb-2">Detailed Breakdown</h3>
                        <div id="responseBreakdown" class="space-y-1">
                            <!-- Dynamically populated response details -->
                        </div>
                    </div>

                    <button 
                        id="proceedToCompanion"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition"
                    >
                        Choose Mental Wellness Companion
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = [
                "1. Little interest or pleasure in doing things",
                "2. Feeling down, depressed, or hopeless",
                "3. Trouble falling or staying asleep, or sleeping too much",
                "4. Feeling tired or having little energy",
                "5. Poor appetite or overeating",
                "6. Feeling bad about yourself or that you are a failure",
                "7. Trouble concentrating on things",
                "8. Moving or speaking so slowly that others could have noticed",
                "9. Thoughts that you would be better off dead"
            ];

            const instructionsContainer = document.getElementById('instructionsContainer');
            const questionModal = document.getElementById('questionModal');
            const resultsModal = document.getElementById('resultsModal');
            const startButton = document.getElementById('startQuestionnaire');
            const closeModalButton = document.getElementById('closeModal');
            const prevButton = document.getElementById('prevQuestion');
            const nextButton = document.getElementById('nextQuestion');
            const questionText = document.getElementById('questionText');
            const answerOptions = document.getElementById('answerOptions');
           
            let currentQuestionIndex = 0;
            let answers = new Array(questions.length).fill(null);

            const answerLabels = [
                "Not at all",
                "Several days",
                "More than half the days", 
                "Nearly every day"
            ];

            function toggleModal(modal, show = true) {
                if (show) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

            function renderQuestion() {
                questionText.textContent = questions[currentQuestionIndex];
                
                // Clear previous options
                answerOptions.innerHTML = '';

                // Create radio options
                answerLabels.forEach((label, index) => {
                    const radioWrapper = document.createElement('div');
                    radioWrapper.classList.add('flex', 'items-center');
                
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'questionAnswer';
                    radio.value = index;
                    radio.id = `answer-${index}`;
                    radio.classList.add('mr-2');
                    radio.style.display = 'none'; // hide the radio button icon
                
                    if (answers[currentQuestionIndex] === index) {
                        radio.checked = true;
                    }
                
                    const radioLabel = document.createElement('label');
                    radioLabel.htmlFor = `answer-${index}`;
                    radioLabel.textContent = label;
                    radioLabel.classList.add('cursor-pointer', 'w-full');
                
                    // add styles to the label
                    radioLabel.style.padding = '8px 16px';
                    radioLabel.style.borderRadius = '6px';
                    radioLabel.style.transition = 'all 0.3s ease';
                
                    // add styles to the label when radio button is checked
                    radio.addEventListener('change', () => {
                        if (radio.checked) {
                            radioLabel.style.backgroundColor = '#1CABE3';
                            radioLabel.style.color = 'white';
                        } else {
                            radioLabel.style.backgroundColor = '';
                            radioLabel.style.color = '';
                        }
                    });
                
                    radioWrapper.appendChild(radio);
                    radioWrapper.appendChild(radioLabel);
                    answerOptions.appendChild(radioWrapper);
                });

                // Update navigation buttons
                prevButton.style.display = currentQuestionIndex === 0 ? 'none' : 'block';
                nextButton.textContent = currentQuestionIndex === questions.length - 1 ? 'Finish' : 'Next';
            }

            function calculateResults() {
                const total = answers.reduce((sum, ans) => sum + (ans !== null ? ans : 0), 0);
                
                let severity = 'Minimal Depression';
                if (total >= 5) severity = 'Mild Depression';
                if (total >= 10) severity = 'Moderate Depression';
                if (total >= 15) severity = 'Moderately Severe Depression';
                if (total >= 20) severity = 'Severe Depression';

                document.getElementById('totalScoreDisplay').textContent = total;
                document.getElementById('severityLevelDisplay').textContent = severity;

                // Populate response breakdown
                const breakdown = document.getElementById('responseBreakdown');
                breakdown.innerHTML = '';
                
                answerLabels.forEach((label, index) => {
                    const count = answers.filter(ans => ans === index).length;
                    const breakdownItem = document.createElement('div');
                    breakdownItem.classList.add('flex', 'justify-between');
                    breakdownItem.innerHTML = `
                        <span>${label}:</span>
                        <span class="font-bold">${count}</span>
                    `;
                    breakdown.appendChild(breakdownItem);
                });
            }

            startButton.addEventListener('click', () => {
                currentQuestionIndex = 0;
                answers = new Array(questions.length).fill(null);
                renderQuestion();
                toggleModal(questionModal);
                instructionsContainer.style.display = 'none';
            });

            closeModalButton.addEventListener('click', () => {
                // Show the instructions container again when closing the modal
                instructionsContainer.classList.remove('hidden');
                toggleModal(questionModal, false);
            });
            prevButton.addEventListener('click', () => {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    renderQuestion();
                }
            });

            nextButton.addEventListener('click', () => {
                const selectedAnswer = document.querySelector('input[name="questionAnswer"]:checked');
                
                if (!selectedAnswer) {
                    alert('Please select an answer before proceeding.');
                    return;
                }

                answers[currentQuestionIndex] = parseInt(selectedAnswer.value);

                if (currentQuestionIndex < questions.length - 1) {
                    currentQuestionIndex++;
                    renderQuestion();
                } else {
                    toggleModal(questionModal, false);
                    calculateResults();
                    toggleModal(resultsModal);
                }
            });

            document.getElementById('proceedToCompanion').addEventListener('click', () => {
                window.location.href = 'MHProfile.php';
            });
        });
    </script>
    <script src="sidebarnav.js"></script>
</body>
</html>