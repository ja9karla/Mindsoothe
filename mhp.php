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

    .intro {
    background-color: #fefefe;
    display: flex;
    flex-direction: row;
    justify-content: center;
    width: 100%;
  }
  
  .intro .phq-intro {
    background-color: #fefefe;
    width: 2440px;
    height: 1024px;
    position: relative;
  }
  
  .intro .ellipse {
    position: absolute;
    width: 60px;
    height: 55px;
    top: 15px;
    left: 1134px;
    object-fit: cover;
  }
  
  .intro .text-wrapper {
    position: absolute;
    top: 32px;
    left: 1201px;
    font-size: 14px;
    font-family: "Poppins", Helvetica;
    font-weight: 400;
    color: #000000;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .group {
    height: 41px;
  }
  
  .intro .overlap-group-wrapper {
    width: 386px;
    height: 41px;
  }
  
  .intro .div {
    position: absolute;
    top: 8px;
    left: 51px;
    font-family: "Poppins", Helvetica;
    font-weight: 400;
    color: #747474;
    font-size: 16px;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .mental-wellness {
    position: absolute;
    width: 772px;
    top: 125px;
    left: 382px;
    font-family: "Poppins", Helvetica;
    font-weight: 700;
    color: #1CABE3;
    font-size: 40px;
    letter-spacing: 0;
    line-height: 40px;
  }
  
  .intro .span {
    color: #000000;
  }
  
  .intro .text-wrapper-2 {
    color: #1CABE3;
  }
  
  .intro .frame {
    position: absolute;
    width: 29px;
    height: 25px;
    top: 24px;
    left: 25px;
  }
  
  .intro .mini {
    position: absolute;
    width: 76px;
    height: 121px;
    top: 153px;
    left: 1px;
  }
  
  .intro .date-picker {
    display: flex;
    flex-direction: column;
    width: 340px;
    height: 300px;
    align-items: flex-end;
    gap: 20px;
    padding: 20px;
    position: absolute;
    top: 94px;
    left: 1082px;
    background-color: #f9f9f9;
    border-radius: 8px 8px 0px 0px;
  }
  
  .intro .base-calendar {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    position: relative;
    align-self: stretch;
    width: 100%;
    flex: 0 0 auto;
    background-color: #f9f9f9;
    filter: blur(3px);
  }
  
  .intro .date-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    align-self: stretch;
    width: 100%;
    flex: 0 0 auto;
  }
  
  .intro .chevron {
    position: relative;
    width: 20px;
    height: 20px;
  }
  
  .intro .div-2 {
    display: inline-flex;
    align-items: flex-start;
    gap: 4px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .december-wrapper {
    display: inline-flex;
    align-items: flex-start;
    gap: 8px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .december {
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-weight: 500;
    color: #1CABE3;
    font-size: 16px;
    letter-spacing: 0;
    line-height: 20px;
    white-space: nowrap;
  }
  
  .intro .element {
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-weight: 500;
    color: #000000;
    font-size: 16px;
    letter-spacing: 0;
    line-height: 20px;
    white-space: nowrap;
  }
  
  .intro .frame-2 {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
    position: relative;
    align-self: stretch;
    width: 100%;
    flex: 0 0 auto;
  }
  
  .intro .frame-3 {
    display: flex;
    align-items: flex-start;
    position: relative;
    align-self: stretch;
    width: 100%;
    flex: 0 0 auto;
  }
  
  .intro .work-day {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 2px;
    position: relative;
    flex: 1;
    flex-grow: 1;
  }
  
  .intro .th {
    position: relative;
    width: 28px;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-weight: 400;
    color: #000000;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: 16px;
  }
  
  .intro .th-2 {
    color: #000000;
    position: relative;
    width: 28px;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-weight: 400;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: 16px;
  }
  
  .intro .th-3 {
    color: #000000;
    font-family: "Inter", Helvetica;
    font-weight: 400;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }

  .intro .th-4 {
    color: #000000;
    font-family: "Inter", Helvetica;
    font-weight: 400;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }

  .intro .th-5 {
    color: #000000;
    font-family: "Inter", Helvetica;
    font-weight: 400;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }

  .calendar-header th {
    color: #000000;
  }
  
  .intro .calendar-date {
    position: relative;
    flex: 1;
    flex-grow: 1;
    height: 32px;
  }
  
  .intro .calendar-date-2 {
    display: flex;
    height: 32px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    position: relative;
    flex: 1;
    flex-grow: 1;
  }
  
  .intro .text-wrapper-3 {
    font-weight: 400;
    color: #000000;
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .element-2 {
    font-weight: 400;
    color: #000000;
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .ellipse-2 {
    position: absolute;
    width: 4px;
    height: 4px;
    top: 4px;
    left: 35px;
    background-color: #1CABE3;
    border-radius: 2px;
  }
  
  .intro .element-wrapper {
    border-radius: 1000px;
    display: flex;
    height: 32px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    position: relative;
    flex: 1;
    flex-grow: 1;
  }
  
  .intro .element-3 {
    font-weight: 600;
    color: #000000;
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .calendar-date-3 {
    border-radius: 8px;
    display: flex;
    height: 32px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    position: relative;
    flex: 1;
    flex-grow: 1;
  }
  
  .intro .text-wrapper-4 {
    font-weight: 400;
    color: #000000;
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Inter", Helvetica;
    font-size: 12px;
    text-align: center;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .ellipse-3 {
    width: 6px;
    height: 6px;
    top: 3px;
    left: 34px;
    border-radius: 3px;
    border: 1px solid;
    border-color: #1CABE3;
    position: absolute;
    background-color: #1CABE3;
  }
  
  .intro .calendar-date-4 {
    border-radius: 8px 0px 0px 8px;
    display: flex;
    height: 32px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    position: relative;
    flex: 1;
    flex-grow: 1;
  }
  
  .intro .ellipse-4 {
    width: 4px;
    height: 4px;
    top: 4px;
    left: 35px;
    border-radius: 2px;
    position: absolute;
    background-color: #1CABE3;
  }
  
  .intro .div-wrapper {
    border-radius: 0px 8px 8px 0px;
    display: flex;
    height: 32px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    position: relative;
    flex: 1;
    flex-grow: 1;
  }
  
  .intro .overlap-2 {
    position: absolute;
    width: 872px;
    height: 749px;
    top: 202px;
    left: 133px;
    filter: blur(3px);
  }
  
  .intro .frame-4 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 1px;
    left: 4px;
    opacity: 0.75;
  }
  
  .intro .subs-free-card {
    position: relative;
    width: 293px;
    height: 177px;
    margin-top: -3px;
    margin-bottom: -5px;
    margin-left: -4px;
    margin-right: -4px;
  }
  
  .intro .frame-5 {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 18px;
    position: absolute;
    top: 19px;
    left: 24px;
  }
  
  .intro .frame-6 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 16px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .frame-7 {
    display: inline-flex;
    align-items: flex-start;
    gap: 16px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .dr-pic {
    position: relative;
    width: 50px;
    height: 50px;
    object-fit: cover;
  }
  
  .intro .frame-8 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .text-wrapper-5 {
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Poppins", Helvetica;
    font-weight: 400;
    color: #000000;
    font-size: 16px;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .licensed-mental {
    position: relative;
    width: fit-content;
    font-family: "Poppins", Helvetica;
    font-weight: 300;
    color: #000000;
    font-size: 10px;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .text-wrapper-6 {
    position: relative;
    width: fit-content;
    font-family: "Poppins", Helvetica;
    font-weight: 300;
    color: #000000;
    font-size: 12px;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .frame-9 {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 5px 10px;
    position: relative;
    flex: 0 0 auto;
    background-color: #1CABE3;
    border-radius: 5px;
    box-shadow: 0px 1px 4px #00000040;
  }
  
  .intro .text-wrapper-7 {
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Poppins", Helvetica;
    font-weight: 300;
    color: #000000;
    font-size: 12px;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .text-wrapper-8 {
    position: relative;
    width: 67px;
    margin-top: -1px;
    font-family: "Poppins", Helvetica;
    font-weight: 300;
    color: #000000;
    font-size: 12px;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .frame-10 {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 5px 10px;
    position: relative;
    flex: 0 0 auto;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0px 1px 4px #00000040;
  }
  
  .intro .text-wrapper-9 {
    position: relative;
    width: fit-content;
    margin-top: -1px;
    font-family: "Poppins", Helvetica;
    font-weight: 300;
    color: #1CABE3;
    font-size: 14px;
    letter-spacing: 0;
    line-height: normal;
    white-space: nowrap;
  }
  
  .intro .frame-11 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 194px;
    left: 0;
    opacity: 0.75;
  }
  
  .intro .image {
    position: relative;
    width: 58px;
    height: 58px;
    margin-top: -3px;
    margin-bottom: -5px;
    margin-left: -4px;
  }
  
  .intro .frame-12 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 387px;
    left: 2px;
    opacity: 0.75;
  }
  
  .intro .frame-13 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 580px;
    left: 4px;
    opacity: 0.75;
  }
  
  .intro .frame-14 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 0;
    left: 297px;
    opacity: 0.75;
  }
  
  .intro .frame-15 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 193px;
    left: 293px;
    opacity: 0.75;
  }
  
  .intro .frame-16 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 386px;
    left: 295px;
    opacity: 0.75;
  }
  
  .intro .frame-17 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 579px;
    left: 297px;
    opacity: 0.75;
  }
  
  .intro .frame-18 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 0;
    left: 587px;
    opacity: 0.75;
  }
  
  .intro .frame-19 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 193px;
    left: 583px;
    opacity: 0.75;
  }
  
  .intro .frame-20 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 386px;
    left: 585px;
    opacity: 0.75;
  }
  
  .intro .frame-21 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    position: absolute;
    top: 579px;
    left: 587px;
    opacity: 0.75;
  }
  
  .intro .overlap-wrapper {
    position: absolute;
    width: 720px;
    height: 600px;
    top: 74px;
    left: 78px;
  }
  
  .intro .overlap-3 {
    position: relative;
    width: 716px;
    height: 600px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0px 1px 8px #000000bf;
    left: 37%;
    top: 20%;
  }
  
  .intro .instructions-for {
    position: absolute;
    top: 93px;
    left: 80px;
    font-family: "Poppins", Helvetica;
    font-weight: 400;
    color: #000000;
    font-size: 16px;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .text-wrapper-10 {
    font-weight: 500;
  }
  
  .intro .text-wrapper-11 {
    font-weight: 300;
  }
  
  .intro .patient-health {
    position: absolute;
    top: 38px;
    left: 123px;
    font-family: "Poppins", Helvetica;
    font-weight: 600;
    color: #1CABE3;
    font-size: 32px;
    letter-spacing: 0;
    line-height: 32px;
    white-space: nowrap;
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
  
  .intro .text-wrapper-12 {
    position: relative;
    width: fit-content;
    margin-top: -6px;
    margin-bottom: -4px;
    font-family: "Poppins", Helvetica;
    font-weight: 600;
    color: #ffffff;
    font-size: 16px;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .chat {
    position: absolute;
    width: 348px;
    height: 312px;
    top: 401px;
    left: 1076px;
    filter: blur(3px);
  }
  
  .intro .friends {
    position: absolute;
    width: 340px;
    height: 300px;
    top: 715px;
    left: 1080px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0px 1px 4px #00000040;
    filter: blur(3px);
  }
  
  .intro .frame-22 {
    display: inline-flex;
    flex-direction: column;
    height: 32px;
    align-items: center;
    position: absolute;
    top: 14px;
    left: 37px;
  }
  
  .intro .frame-23 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 9px;
    position: relative;
    flex: 0 0 auto;
    margin-bottom: -0.96px;
  }
  
  .intro .text-wrapper-13 {
    position: relative;
    width: 90px;
    margin-top: -1px;
    font-family: "Poppins", Helvetica;
    font-weight: 500;
    color: #1CABE3;
    font-size: 16px;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .line {
    position: relative;
    width: 266px;
    height: 1px;
    object-fit: cover;
  }
  
  .intro .frame-wrapper {
    display: flex;
    flex-direction: column;
    width: 340px;
    align-items: center;
    gap: 1px;
    position: absolute;
    top: 59px;
    left: 0;
  }
  
  .intro .frame-24 {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .frame-25 {
    display: flex;
    width: 338px;
    align-items: center;
    gap: 15px;
    padding: 3px 35px;
    position: relative;
    flex: 0 0 auto;
  }
  
  .intro .img {
    position: relative;
    width: 40px;
    height: 40px;
    object-fit: cover;
  }
  
  .intro .text-wrapper-14 {
    position: relative;
    width: fit-content;
    font-family: "Poppins", Helvetica;
    font-weight: 400;
    color: #000000;
    font-size: 16px;
    letter-spacing: 0;
    line-height: normal;
  }
  
  .intro .eeccf-fbb-b {
    position: relative;
    width: 40px;
    height: 34.78px;
    object-fit: cover;
  }
  
  .intro .rectangle {
    position: absolute;
    width: 384px;
    height: 41px;
    top: -2718px;
    left: 891px;
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

    <div class="intro">
      <div class="phq-intro">
        <div class="overlap">
          <div class="overlap">
            <p class="mental-wellness">
              <span class="span">Mental </span> <span class="text-wrapper-2">Wellness</span> <span class="span"> Companion</span>
            </p>
            
            <div class="date-picker">
              <div class="base-calendar">
                <div class="date-header">
                  <img class="chevron" src="image/chevron-8.svg" />
                  <div class="div-2">
                    <div class="december-wrapper"><div class="december">May</div></div>
                    <div class="div-2"><div class="element">2024</div></div>
                  </div>
                  <img class="chevron" src="image/chevron-9.svg" />
                </div>
                <div class="frame-2">
                  <div class="frame-3">
                    <div class="work-day"><div class="th">Sun</div></div>
                    <div class="work-day"><div class="th-2">Mon</div></div>
                    <div class="work-day"><div class="th-2">Tue</div></div>
                    <div class="work-day"><div class="th-2">Wed</div></div>
                    <div class="work-day"><div class="th-2">Thu</div></div>
                    <div class="work-day"><div class="th-2">Fri</div></div>
                    <div class="work-day"><div class="th">Sat</div></div>
                  </div>
                  <div class="frame-2">
                    <div class="frame-3">
                      <div class="calendar-date"></div>
                      <div class="calendar-date"></div>
                      <div class="calendar-date"></div>
                      <div class="calendar-date"></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">1</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">2</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">3</div></div>
                    </div>
                    <div class="frame-3">
                      <div class="calendar-date-2"><div class="element-2">4</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">5</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">6</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">7</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">8</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">9</div></div>
                      <div class="calendar-date-2"><div class="element-2">10</div></div>
                    </div>
                    <div class="frame-3">
                      <div class="calendar-date-2"><div class="element-2">11</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">23</div></div>
                      <div class="calendar-date-2">
                        <div class="text-wrapper-3">13</div>
                        <div class="ellipse-2"></div>
                      </div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">14</div></div>
                      <div class="element-wrapper"><div class="element-3">15</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">16</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">17</div></div>
                    </div>
                    <div class="frame-3">
                      <div class="calendar-date-2"><div class="text-wrapper-3">18</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">19</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">20</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-3">21</div></div>
                      <div class="calendar-date-3">
                        <div class="text-wrapper-4">22</div>
                        <div class="ellipse-3"></div>
                      </div>
                      <div class="calendar-date-2"><div class="text-wrapper-4">23</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-4">24</div></div>
                    </div>
                    <div class="frame-3">
                      <div class="calendar-date-2"><div class="text-wrapper-4">25</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-4">26</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-4">27</div></div>
                      <div class="calendar-date-2"><div class="text-wrapper-4">28</div></div>
                      <div class="calendar-date-4">
                        <div class="text-wrapper-4">29</div>
                        <div class="ellipse-4"></div>
                      </div>
                      <div class="div-wrapper"><div class="text-wrapper-4">30</div></div>
                      <div class="calendar-date"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="overlap-2">
            <div class="frame-4">
              <img class="subs-free-card" src="image/subs-free-card-4.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="dr-pic" src="image/dr-pic-2.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Janine Karla</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-11">
              <img class="subs-free-card" src="image/subs-free-card-5.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-12">
              <img class="subs-free-card" src="image/subs-free-card-6.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-13">
              <img class="subs-free-card" src="image/subs-free-card-7.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-14">
              <img class="subs-free-card" src="image/subs-free-card-8.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="dr-pic" src="image/dr-pic-2.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Janine Karla</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-15">
              <img class="subs-free-card" src="image/subs-free-card-9.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-16">
              <img class="subs-free-card" src="image/subs-free-card-10.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="dr-pic" src="image/dr-pic-2.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Janine Karla</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-17">
              <img class="subs-free-card" src="image/subs-free-card-11.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-18">
              <img class="subs-free-card" src="image/subs-free-card-12.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-19">
              <img class="subs-free-card" src="image/subs-free-card-13.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-20">
              <img class="subs-free-card" src="image/subs-free-card-14.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            <div class="frame-21">
              <img class="subs-free-card" src="image/subs-free-card-15.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="image" src="image/image-23.png" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Jeanne Denise</div>
                      <div class="licensed-mental">Licensed Mental Health Counselor</div>
                      <div class="text-wrapper-6">9 years of experience</div>
                    </div>
                  </div>
                  <div class="frame-7">
                    <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                    <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                    <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                  </div>
                </div>
                <div class="frame-10"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
            </div>
            <div class="overlap-wrapper">
              <div class="overlap-3">
                <p class="instructions-for">
                  <br>
                  <br>
                  <span class="text-wrapper-10">Instructions for Completing the PHQ-9 Questionnaire</span>
                  <span class="text-wrapper-11">
                    <br>
                    <br>1. Answer Each Question: For each question, mark how often you have <br />experienced each issue in
                    the last two weeks:<br>• Not at all<br>• Several
                    days<br>• More than half the days<br>• Nearly every day<br /><br>2.
                    Question 10: Indicate how difficult these problems have made it to do<br />your work, take care of
                    things at home, or get along with others:<br>• Not difficult at
                    all<br>• Somewhat difficult<br>• Very
                    difficult<br>• Extremely difficult<br /><br>3. Seek Help if Needed: If you have
                    thoughts of self-harm (question 9),<br />please talk to a healthcare professional immediately.</span>
                </p>
                <p class="patient-health">
                  <span class="span">Patient </span>
                  <span class="text-wrapper-2">Health</span>
                  <span class="span">Questionnaire</span>
                </p>
                <div class="start-btn">
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#modal1">
                    Start
                  </button>
                </div>         
              </div>
            </div>
          </div>
          

        </div>
        <img class="rectangle" src="image/rectangle-60.png" />
      </div>
    </div>

    <!-- Question Modal -->
    <div class="modal" id="questionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <h2 class="text-center mb-4">Patient <span style="color: #1CABE3;">Health</span> Questionnaire</h2>
                    <div class="question-container">
                        <div class="question-header d-flex mb-4">
                            <span class="question-number me-2"></span>
                            <p class="question-text mb-0"></p>
                        </div>
                        <div class="options-container">
                            <div class="option">
                                <input type="radio" name="answer" value="0" id="option0">
                                <label class="option" for="option0">Not at all</label>
                            </div>
                            <div class="option">
                                <input type="radio" name="answer" value="1" id="option1">
                                <label class="option" for="option1">Several days</label>
                            </div>
                            <div class="option">
                                <input type="radio" name="answer" value="2" id="option2">
                                <label class="option" for="option2">More than half the days</label>
                            </div>
                            <div class="option">
                                <input type="radio" name="answer" value="3" id="option3">
                                <label class="option" for="option3">Nearly everyday</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-primary px-4" id="prevBtn">Previous</button>
                        <button class="btn btn-primary px-4" id="nextBtn">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Modal -->
    <div class="modal" id="resultsModal" tabindex="-1" aria-labelledby="resultsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <h2 class="text-center mb-4">PHQ-9 Results</h2>
                    <div class="results-container">
                        <div class="score-section mb-4">
                            <h4>Total Score: <span id="totalScore" class="text-primary"></span></h4>
                            <h5>Depression Severity: <span id="severityLevel" class="text-primary"></span></h5>
                        </div>
                        <div class="summary-section">
                            <h4 class="mb-3">Response Summary</h4>
                            <div class="summary-table">
                                <div class="summary-row">
                                    <span>Not at all:</span>
                                    <span id="count0"></span>
                                </div>
                                <div class="summary-row">
                                    <span>Several days:</span>
                                    <span id="count1"></span>
                                </div>
                                <div class="summary-row">
                                    <span>More than half the days:</span>
                                    <span id="count2"></span>
                                </div>
                                <div class="summary-row">
                                    <span>Nearly everyday:</span>
                                    <span id="count3"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary px-4" data-bs-dismiss="modal">Close</button>
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
        
        let currentQuestion = 0;
        let answers = new Array(questions.length).fill(null);
        
        function renderQuestion() {
            const questionNumber = document.querySelector('.question-number');
            const questionText = document.querySelector('.question-text');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            questionNumber.textContent = `${currentQuestion + 1}. `;
            questionText.textContent = questions[currentQuestion];
            
            // Clear previous selection
            document.querySelectorAll('input[name="answer"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Set previous answer if exists
            if (answers[currentQuestion] !== null) {
                document.querySelector(`input[value="${answers[currentQuestion]}"]`).checked = true;
            }
            
            // Update button states
            prevBtn.style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';
            nextBtn.textContent = currentQuestion === questions.length - 1 ? 'Submit' : 'Next';
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            renderQuestion();
            
            const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
            
            document.querySelector('.start-btn').addEventListener('click', () => {
                if (isLoggedIn) {
                    const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
                    currentQuestion = 0;
                    answers = new Array(questions.length).fill(null);
                    renderQuestion();
                    questionModal.show();
                    document.querySelector('.overlap-3').classList.add('hidden');
                } else {
                    window.location.href = 'login.php';
                }
            });
            
            document.getElementById('prevBtn').addEventListener('click', () => {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    renderQuestion();
                }
            });
            
            document.getElementById('nextBtn').addEventListener('click', () => {
                const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                if (!selectedAnswer) {
                    alert('Please select an answer before proceeding.');
                    return;
                }
                
                answers[currentQuestion] = selectedAnswer.value;
                
                if (currentQuestion === questions.length - 1) {
                    // Submit answers
                    submitAnswers(answers);
                } else {
                    currentQuestion++;
                    renderQuestion();
                }
            });
        });
        
        function getSeverityLevel(score) {
            if (score >= 0 && score <= 4) return "None-Minimal";
            if (score >= 5 && score <= 9) return "Mild";
            if (score >= 10 && score <= 14) return "Moderate";
            if (score >= 15 && score <= 19) return "Moderately Severe";
            return "Severe";
        }

        function submitAnswers(answers) {
            // Calculate total score
            const total = answers.reduce((sum, value) => sum + parseInt(value), 0);
            
            // Calculate response counts
            const counts = answers.reduce((acc, value) => {
                acc[value] = (acc[value] || 0) + 1;
                return acc;
            }, {});
            
            // Update results modal
            document.getElementById('totalScore').textContent = total;
            document.getElementById('severityLevel').textContent = getSeverityLevel(total);
            
            // Update response counts
            for (let i = 0; i < 4; i++) {
                document.getElementById(`count${i}`).textContent = counts[i.toString()] || 0;
            }
            
            // Hide question modal and show results modal
            const questionModal = bootstrap.Modal.getInstance(document.getElementById('questionModal'));
            questionModal.hide();
            
            const resultsModal = new bootstrap.Modal(document.getElementById('resultsModal'));
            resultsModal.show();
        }
    </script>
    
    <!-- External scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="sidebarnav.js"></script>


</body>
</html>