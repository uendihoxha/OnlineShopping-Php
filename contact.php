<?php

require_once './data/dbconnect.php';
include 'ultility/userultilities.php';
session_start();

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}

$name = "null";
$email = "null";
$title = "null";
$details = "null";

if (isset($_POST['txtName'])) {
    $name = $_POST['txtName'];
    $email = $_POST['txtEmail'];
    $title = $_POST['txtTitle'];
    $details = $_POST['txtDetails'];

    $sql = "INSERT INTO feedbacks (`UserId`, `Title`, `Details`, `CreateTime`) VALUES (1, '$title', '$details', CURRENT_TIME())";
}
?>

<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <title>CONTACT</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/contact.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <!-- Body HTML -->
    <body>
        <div class="divContainer">
            <!-- Header -->
            <?php include './Templates/header.php'; ?>

            <!-- Top Menu -->
            <?php include './Templates/topmenu.php'; ?>

            <!-- Body Wrapper Second Level -->
            <div class="divWrapper_2">
                <!-- Body Wrapper First Level -->
                <div class="divWrapper_1">
                    <!-- Left Menu -->
                    <?php include './Templates/leftmenu.php'; ?>

                    <!-- Main -->
                    <div class="divMain">
                        <article class="articleContent">
                            <p class="pPageTitle">CONTACT</p>

                            <!-- Contact -->
                            <section id="Contact">
                                <p>Editor: <span id="CompanyName">SHOP</span> </p>
                                <p>Social Media: <span id="Address"></span></p>
                                <p>Contact number: <span id="PhoneNumber"></span></p>
                                <p>Email: <span id="Email"></span></p>

                                <script language="javascript">
                                    ShowContact();
                                </script>
                            </section>

                            <!-- Feedback -->
                            <section id="sectionFeedback">
                                <p class="pPageTitle">FEEDBACK & REQUESTS</p>

                                <?php
                                    if (!isset($_POST['txtName'])) {
                                ?>

                                    <form id="form1" name="formFeedback" method="post" action="#">
                                        <table class="tableFeedback" cellpadding="5" cellspacing="5">
                                            <tr>
                                                <th width="106" style="text-align: left" scope="row">Your name</th>
                                                <td width="459" style="text-align: left"><input name="txtName" type="text" id="txtName" size="40" required></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left" scope="row">Email:</th>
                                                <td style="text-align: left">
                                                    <input name="txtEmail" type="text" id="txtEmail" size="40" required></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left" scope="row">Subject</th>
                                                <td style="text-align: left">
                                                    <input name="txtTitle" type="text" id="txtTitle" size="60" required></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left" scope="row" valign="top">Write your feedback/request</th>
                                                <td style="text-align: left">
                                                    <textarea name="txtDetails" cols="60" rows="6" id="txtDetails" required></textarea></td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td>
                                                    <input type="submit" value="Send Feedback"/>
                                                    &nbsp; 
                                                    <input type="reset" value="Reset"/>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>

                                <?php
                                    } else {
                                        if (mysqli_query($con, $sql)) {
                                ?>
                                    <p class="pResultFeedback">Feedback/request send successfully.</p>		
                                <?php
                                        } else {
                                ?>
                                    <p class="pResultFeedback">Failed to send feedback/request.</p>	
                                <?php
                                    }
                                }
                                ?>
                            </section>

                        </article>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
