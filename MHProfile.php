<?php
include("auth.php");


$sql = "SELECT id, firstName, lastName, specialization, experience FROM MHP WHERE status='approved'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      echo '
      <div class="overlap-2">
          <div class="frame-4">
              <img class="subs-free-card" src="image/subs-free-card-4.svg" alt="Card Background" />
              <div class="frame-5">
                  <div class="frame-6">
                      <div class="frame-7">
                          <img class="dr-pic" src="images/emily.jpg" alt="Profile Picture" />
                          <div class="frame-8">
                              <div class="text-wrapper-5">' . htmlspecialchars($row["firstName"] . ' ' . $row["lastName"]) . '</div>
                              <div class="licensed-mental">' . htmlspecialchars($row["specialization"]) . '</div>
                              <div class="text-wrapper-6">' . htmlspecialchars($row["experience"]) . ' years of experience</div>
                          </div>
                      </div>
                      <div class="frame-7">
                          <div class="frame-9"><div class="text-wrapper-7">Stress</div></div>
                          <div class="frame-9"><div class="text-wrapper-7">Anxiety</div></div>
                          <div class="frame-9"><div class="text-wrapper-8">Depression</div></div>
                      </div>
                  </div>
                  <div class="frame-10" onclick="window.location.href=\'MHProfileDetail.php?mhp_id=' . $row["id"] . '\'">
                      <div class="text-wrapper-9">View Profile</div>
                  </div>
              </div>
          </div>
      </div>';
  }
} else {
  echo "<p>No mental health professionals found.</p>";
}

$conn->close();

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
  
  .overlap-2 {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .frame-4 {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dr-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .text-wrapper-5 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .licensed-mental {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        .text-wrapper-6 {
            font-size: 14px;
            color: #555;
        }
        .frame-7 {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        .frame-9 {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .text-wrapper-9 {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
            cursor: pointer;
        }
        .text-wrapper-9:hover {
            background-color: #0056b3;
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
         <!-- <div id="sidebar" class="sidebar">
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
        </div> -->
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
          <!-- <div class="overlap-2">
            <div class="frame-4">
              <img class="subs-free-card" src="image/subs-free-card-4.svg" />
              <div class="frame-5">
                <div class="frame-6">
                  <div class="frame-7">
                    <img class="dr-pic" src="images/emily.jpg" />
                    <div class="frame-8">
                      <div class="text-wrapper-5">Emily Roberts</div>
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
                <div class="frame-10"  onclick="window.location.href='MHProfileDetail.php'"><div class="text-wrapper-9">View Profile</div></div>
              </div>
            </div>
          </div> -->
           
    <!-- External scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="sidebarnav.js"></script>


</body>
</html>