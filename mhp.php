<?php
    include("auth.php"); // This will provide the $conn variable for database connection
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

        // Render the current question with options
        function renderQuestion() {
            const container = document.getElementById('questionContainer');
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
            document.getElementById('prevBtn').style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';
            const nextBtn = document.getElementById('nextBtn');
            
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
            document.getElementById('totalScore').textContent = totalScore;
            document.getElementById('depressionLevel').textContent = calculateDepressionLevel(totalScore);
            document.getElementById('notAtAllCount').textContent = counts[0];
            document.getElementById('severalDaysCount').textContent = counts[1];
            document.getElementById('halfDaysCount').textContent = counts[2];
            document.getElementById('nearlyEveryDayCount').textContent = counts[3];

            const questionModal = bootstrap.Modal.getInstance(document.getElementById('questionModal'));
            questionModal.hide();
            
            const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
            resultModal.show();
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
</html>