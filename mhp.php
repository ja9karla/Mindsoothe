<<<<<<< HEAD
<?php
include("auth.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Validate input
    if (!isset($_POST['responses']) || !isset($_POST['userId'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }

    $responses = json_decode($_POST['responses'], true);
    $userId = intval($_POST['userId']); // Use the posted userId

    if (count($responses) !== 10) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid number of responses']);
        exit;
    }

    // Calculate total score
    $totalScore = array_sum($responses);

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO phq9_responses 
              (user_id, question_1, question_2, question_3, question_4, question_5, 
               question_6, question_7, question_8, question_9, question_10, total_score) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param(
        'iiiiiiiiiiii',
        $userId,
        $responses[0],
        $responses[1],
        $responses[2],
        $responses[3],
        $responses[4],
        $responses[5],
        $responses[6],
        $responses[7],
        $responses[8],
        $responses[9],
        $totalScore
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Response saved successfully', 'totalScore' => $totalScore]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save response: ' . $stmt->error]);
    }
    exit;
}

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
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<body>
    <div class="container">
         <!-- Left Sidebar -->
         <div id="sidebar" class="sidebar">
            <div class="logo">
                <img src="image/Mindsoothe (1).svg" alt="Logo" srcset="">
            </div>
            <div class="menu-content">
                <a href="#" class="menu-item" id="gracefulThreadItem">
                    <img src="images/gracefulThread.svg" alt="Graceful Thread" class="menu-icon">
                    <span class="menu-text">Graceful-thread</span> 
                </a>
                <a href="#" class="menu-item" id="MentalWellness">
                    <img src="images/Vector.svg" alt="Mental Wellness Companion" class="menu-icon">
                    <span class="menu-text">Mental Wellness Companion</span>  
                </a>
            </div>
            <div class="UserAcc">
                <a href="#" class="user-profile">
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="user-avatar">
                    <span class="username"><?php echo htmlspecialchars($fullName); ?></span>
                </a>
                <a href="logout.php" class="Logout">Logout</a>
            </div>
        </div>
    </div>

    <!-- Question Modal -->
    <div class="modal" id="questionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="modalLabel"><strong>Patient <span class="text-wrapper-15">Health</span> Questionnaire</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="phqForm" onsubmit="return showResults(event)">
                <div id="questionContainer" class="mb-4"></div>
                <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="navigate(-1)">Previous</button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="navigate(1)">Next</button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Results Modal -->
    <div class="modal" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="resultModalLabel">Results Summary</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <p class="h6">Total Score: <span id="totalScore" class="fw-bold"></span></p>
            <p class="h6">Depression Level: <span id="depressionLevel" class="fw-bold"></span></p>
            
            <div class="result-summary mt-4">
                <h6>Summary of Responses:</h6>
                <table class="summary-table">
                <tr>
                    <th>Not at all</th>
                    <td id="notAtAllCount"></td>
                </tr>
                <tr>
                    <th>Several days</th>
                    <td id="severalDaysCount"></td>
                </tr>
                <tr>
                    <th>More than half the days</th>
                    <td id="halfDaysCount"></td>
                </tr>
                <tr>
                    <th>Nearly every day</th>
                    <td id="nearlyEveryDayCount"></td>
                </tr>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
    
    <script>
        const questions = [
            "Little interest or pleasure in doing things",
            "Feeling down, depressed, or hopeless",
            "Trouble falling or staying asleep, or sleeping too much",
            "Feeling tired or having little energy",
            "Poor appetite or overeating",
            "Feeling bad about yourself or that you are a failure or have let yourself or your family down",
            "Trouble concentrating on things, such as reading the newspaper or watching television",
            "Moving or speaking so slowly that other people could have noticed. Or the opposite being so fidgety or restless that you have been moving around a lot more than usual",
            "Thoughts that you would be better off dead, or of hurting yourself",
            "If you checked off any problems, how difficult have these problems made it for you to do your work, take care of things at home, or get along with other people?"
        ];

        const options = ["Not at all", "Several days", "More than half the day", "Nearly every day"];
        let currentQuestion = 0;
        let responses = Array(questions.length).fill(null);  // Stores responses per question

        function submitResponses() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'mhp.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(`Success! Total Score: ${response.totalScore}`);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Parsing error:', e);
                        alert('Unexpected server response: ' + xhr.responseText);
                    }
                }
            };

            const data = `responses=${encodeURIComponent(JSON.stringify(responses))}&userId=${<?php echo $userId; ?>}`;
            xhr.send(data);
        }


        // Render the current question with options
        function renderQuestion() {
            const container = document.getElementById('questionContainer');
            if (!container) {
                console.error('Could not find element with id questionContainer');
                return;
            }

            container.innerHTML = `
                <h6>${currentQuestion + 1}. ${questions[currentQuestion]}</h6>
                ${options.map((option, i) => `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="response_${currentQuestion}"
                            id="option${currentQuestion}_${i}" value="${i}" ${responses[currentQuestion] === i ? 'checked' : ''} required>
                        <label class="form-check-label" for="option${currentQuestion}_${i}">
                            ${option}
                        </label>
                    </div>
                `).join('')}
            `;

            // Update button states
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (!prevBtn || !nextBtn) {
                console.error('Could not find elements with ids prevBtn and nextBtn');
                return;
            }

            prevBtn.style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';

            if (currentQuestion === questions.length - 1) {
                nextBtn.textContent = 'Submit';
                nextBtn.type = 'submit';
                nextBtn.onclick = showResults;
            } else {
                nextBtn.textContent = 'Next';
                nextBtn.type = 'button';
                nextBtn.onclick = () => navigate(1);
            }
        }

        // Navigate between questions
        function navigate(direction) {
            const selectedOption = document.querySelector(`input[name="response_${currentQuestion}"]:checked`);
            
            if (direction > 0 && !selectedOption) {
                alert('Please select an option before proceeding.');
                return;
            }

            if (selectedOption) {
                responses[currentQuestion] = parseInt(selectedOption.value);
            }

            currentQuestion = Math.max(0, Math.min(questions.length - 1, currentQuestion + direction));
            renderQuestion();
        }

        // Calculate depression level based on total score
        function calculateDepressionLevel(score) {
            if (score === 0) return "Negative";
            if (score <= 4) return "Minimal depression";
            if (score <= 9) return "Mild depression";
            if (score <= 14) return "Moderate depression";
            if (score <= 19) return "Moderately Severe depression";
            return "Severe";
        }

        // Show results in modal
        function showResults(event) {
            event.preventDefault();

            const counts = [0, 0, 0, 0];
            let totalScore = 0;

            responses.forEach(response => {
                if (response !== null) {
                    counts[response]++;
                    totalScore += response;
                }
            });

            // Update results in the modal
            const resultModal = document.getElementById('resultModal');
            if (!resultModal) {
                console.error('Could not find element with id resultModal');
                return;
            }

            document.getElementById('totalScore').textContent = totalScore;
            document.getElementById('depressionLevel').textContent = calculateDepressionLevel(totalScore);
            document.getElementById('notAtAllCount').textContent = counts[0];
            document.getElementById('severalDaysCount').textContent = counts[1];
            document.getElementById('halfDaysCount').textContent = counts[2];
            document.getElementById('nearlyEveryDayCount').textContent = counts[3];

            const questionModal = bootstrap.Modal.getInstance(document.getElementById('questionModal'));
            questionModal.hide();
            
            const resultModalInstance = new bootstrap.Modal(resultModal);
            resultModalInstance.show();
        }

        // Initialize question rendering on DOM content load
        document.addEventListener('DOMContentLoaded', () => {
            renderQuestion();

            const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
            if (isLoggedIn) {
                const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
                questionModal.show();
            }
        });

    </script>

    <!-- External scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="sidebarnav.js"></script>

</body>
=======
<?php
include("auth.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Validate input
    if (!isset($_POST['responses']) || !isset($_POST['userId'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }

    $responses = json_decode($_POST['responses'], true);
    $userId = intval($_POST['userId']); // Use the posted userId

    if (count($responses) !== 10) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid number of responses']);
        exit;
    }

    // Calculate total score
    $totalScore = array_sum($responses);

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO phq9_responses 
              (user_id, question_1, question_2, question_3, question_4, question_5, 
               question_6, question_7, question_8, question_9, question_10, total_score) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param(
        'iiiiiiiiiiii',
        $userId,
        $responses[0],
        $responses[1],
        $responses[2],
        $responses[3],
        $responses[4],
        $responses[5],
        $responses[6],
        $responses[7],
        $responses[8],
        $responses[9],
        $totalScore
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Response saved successfully', 'totalScore' => $totalScore]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save response: ' . $stmt->error]);
    }
    exit;
}

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
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<body>
    <div class="container">
         <!-- Left Sidebar -->
         <div id="sidebar" class="sidebar">
            <div class="logo">
                <img src="image/Mindsoothe (1).svg" alt="Logo" srcset="">
            </div>
            <div class="menu-content">
                <a href="#" class="menu-item" id="gracefulThreadItem">
                    <img src="images/gracefulThread.svg" alt="Graceful Thread" class="menu-icon">
                    <span class="menu-text">Graceful-thread</span> 
                </a>
                <a href="#" class="menu-item" id="MentalWellness">
                    <img src="images/Vector.svg" alt="Mental Wellness Companion" class="menu-icon">
                    <span class="menu-text">Mental Wellness Companion</span>  
                </a>
            </div>
            <div class="UserAcc">
                <a href="#" class="user-profile">
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="user-avatar">
                    <span class="username"><?php echo htmlspecialchars($fullName); ?></span>
                </a>
                <a href="logout.php" class="Logout">Logout</a>
            </div>
        </div>
    </div>

    <!-- Question Modal -->
    <div class="modal" id="questionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="modalLabel"><strong>Patient <span class="text-wrapper-15">Health</span> Questionnaire</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="phqForm" onsubmit="return showResults(event)">
                <div id="questionContainer" class="mb-4"></div>
                <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="navigate(-1)">Previous</button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="navigate(1)">Next</button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Results Modal -->
    <div class="modal" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="resultModalLabel">Results Summary</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <p class="h6">Total Score: <span id="totalScore" class="fw-bold"></span></p>
            <p class="h6">Depression Level: <span id="depressionLevel" class="fw-bold"></span></p>
            
            <div class="result-summary mt-4">
                <h6>Summary of Responses:</h6>
                <table class="summary-table">
                <tr>
                    <th>Not at all</th>
                    <td id="notAtAllCount"></td>
                </tr>
                <tr>
                    <th>Several days</th>
                    <td id="severalDaysCount"></td>
                </tr>
                <tr>
                    <th>More than half the days</th>
                    <td id="halfDaysCount"></td>
                </tr>
                <tr>
                    <th>Nearly every day</th>
                    <td id="nearlyEveryDayCount"></td>
                </tr>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
    
    <script>
        const questions = [
            "Little interest or pleasure in doing things",
            "Feeling down, depressed, or hopeless",
            "Trouble falling or staying asleep, or sleeping too much",
            "Feeling tired or having little energy",
            "Poor appetite or overeating",
            "Feeling bad about yourself or that you are a failure or have let yourself or your family down",
            "Trouble concentrating on things, such as reading the newspaper or watching television",
            "Moving or speaking so slowly that other people could have noticed. Or the opposite being so fidgety or restless that you have been moving around a lot more than usual",
            "Thoughts that you would be better off dead, or of hurting yourself",
            "If you checked off any problems, how difficult have these problems made it for you to do your work, take care of things at home, or get along with other people?"
        ];

        const options = ["Not at all", "Several days", "More than half the day", "Nearly every day"];
        let currentQuestion = 0;
        let responses = Array(questions.length).fill(null);  // Stores responses per question

        function submitResponses() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'mhp.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(`Success! Total Score: ${response.totalScore}`);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Parsing error:', e);
                        alert('Unexpected server response: ' + xhr.responseText);
                    }
                }
            };

            const data = `responses=${encodeURIComponent(JSON.stringify(responses))}&userId=${<?php echo $userId; ?>}`;
            xhr.send(data);
        }


        // Render the current question with options
        function renderQuestion() {
            const container = document.getElementById('questionContainer');
            if (!container) {
                console.error('Could not find element with id questionContainer');
                return;
            }

            container.innerHTML = `
                <h6>${currentQuestion + 1}. ${questions[currentQuestion]}</h6>
                ${options.map((option, i) => `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="response_${currentQuestion}"
                            id="option${currentQuestion}_${i}" value="${i}" ${responses[currentQuestion] === i ? 'checked' : ''} required>
                        <label class="form-check-label" for="option${currentQuestion}_${i}">
                            ${option}
                        </label>
                    </div>
                `).join('')}
            `;

            // Update button states
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (!prevBtn || !nextBtn) {
                console.error('Could not find elements with ids prevBtn and nextBtn');
                return;
            }

            prevBtn.style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';

            if (currentQuestion === questions.length - 1) {
                nextBtn.textContent = 'Submit';
                nextBtn.type = 'submit';
                nextBtn.onclick = showResults;
            } else {
                nextBtn.textContent = 'Next';
                nextBtn.type = 'button';
                nextBtn.onclick = () => navigate(1);
            }
        }

        // Navigate between questions
        function navigate(direction) {
            const selectedOption = document.querySelector(`input[name="response_${currentQuestion}"]:checked`);
            
            if (direction > 0 && !selectedOption) {
                alert('Please select an option before proceeding.');
                return;
            }

            if (selectedOption) {
                responses[currentQuestion] = parseInt(selectedOption.value);
            }

            currentQuestion = Math.max(0, Math.min(questions.length - 1, currentQuestion + direction));
            renderQuestion();
        }

        // Calculate depression level based on total score
        function calculateDepressionLevel(score) {
            if (score === 0) return "Negative";
            if (score <= 4) return "Minimal depression";
            if (score <= 9) return "Mild depression";
            if (score <= 14) return "Moderate depression";
            if (score <= 19) return "Moderately Severe depression";
            return "Severe";
        }

        // Show results in modal
        function showResults(event) {
            event.preventDefault();

            const counts = [0, 0, 0, 0];
            let totalScore = 0;

            responses.forEach(response => {
                if (response !== null) {
                    counts[response]++;
                    totalScore += response;
                }
            });

            // Update results in the modal
            const resultModal = document.getElementById('resultModal');
            if (!resultModal) {
                console.error('Could not find element with id resultModal');
                return;
            }

            document.getElementById('totalScore').textContent = totalScore;
            document.getElementById('depressionLevel').textContent = calculateDepressionLevel(totalScore);
            document.getElementById('notAtAllCount').textContent = counts[0];
            document.getElementById('severalDaysCount').textContent = counts[1];
            document.getElementById('halfDaysCount').textContent = counts[2];
            document.getElementById('nearlyEveryDayCount').textContent = counts[3];

            const questionModal = bootstrap.Modal.getInstance(document.getElementById('questionModal'));
            questionModal.hide();
            
            const resultModalInstance = new bootstrap.Modal(resultModal);
            resultModalInstance.show();
        }

        // Initialize question rendering on DOM content load
        document.addEventListener('DOMContentLoaded', () => {
            renderQuestion();

            const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
            if (isLoggedIn) {
                const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
                questionModal.show();
            }
        });

    </script>

    <!-- External scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="sidebarnav.js"></script>

</body>
>>>>>>> origin/main
</html>