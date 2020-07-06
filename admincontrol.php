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

/// Get user's infors
$user = '';
if (isset($_SESSION['userId'])) 
{
    $user = getUserById($con, $_SESSION['userId']);
} 
else {
    header("Location: login.php");
}

/// Update user's infos
$result = NULL;
if (isset($_SESSION['userId'])) {
    if (isset($_POST['txtAddress'])) {
        $userId = $user['Id'];
        $userSex = $_POST['txtSex'];
        $userDoB = $_POST['txtDoB'];
        $userIdCard = $_POST['txtIdCard'];
        $userAddress = $_POST['txtAddress'];
        $userPhone = $_POST['txtPhone'];

        $sql = "UPDATE `users` SET `Sex`='$userSex', `DoB`='$userDoB', `IdCard`='$userIdCard', `Address`='$userAddress',`Phone`='$userPhone' WHERE Id='$userId'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $user = getUserById($con, $_SESSION['userId']);
        }
    }
}
?>
<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <title>Management Page</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
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
                            <p class="pPageTitle">Management</p>
                            <a class="aContent" href="ordersmanager.php">Orders</a> &nbsp;
                            <a class="aContent" href="productsmanager.php">Items.</a> &nbsp;
                            <a class="aContent" href="usersmanager.php">Account</a> &nbsp;

                            <!-- User's infos -->
                            <p class="pPageTitle">Account info</p>
                            <section>
                                <form id="formUpdateUserInfors" action="" method="post">
                                <table>
                                    <tr>
                                        <td width="160px">Email: </td>
                                        <td><?php echo $user['Email']; ?></td>
                                    </tr> 
                                    <tr>
                                        <td>Name </td>
                                        <td><?php echo $user['Name']; ?></td>
                                    </tr> 
                                    <tr>
                                        <td>Sex </td>
                                        <td>
                                            <input type="radio" name="txtSex" value="0" <?php if (intval($user['Sex'])===0) {echo "checked=''";} ?>/> Male &nbsp;
                                            <input type="radio" name="txtSex" value="1" <?php if (intval($user['Sex'])!=0) {echo "checked=''";} ?>/> Female
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>Date of birth: </td>
                                        <td>
                                            <input type="date" name="txtDoB" value="<?php echo $user['DoB']; ?>" required="" />
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>ID Card </td>
                                        <td>
                                            <input type="text" name="txtIdCard" value="<?php echo $user['IdCard']; ?>" size="30" required="" />
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>Address </td>
                                        <td><input type="text" name="txtAddress" value="<?php echo $user['Address']; ?>" size="30" required="" /></td>
                                    </tr> 
                                    <tr>
                                        <td>Phone number </td>
                                        <td><input type="text" name="txtPhone" value="<?php echo $user['Phone']; ?>" size="30" required="" /></td>
                                    </tr> 
                                    <tr>
                                        <td></td>
                                        <td><input type="submit" name="btnSubmit" value="Submit" /> <input type="reset" name="btnReset" value="Reset" /></td>
                                    </tr>
                                </table>
                                </form>

                                <?php
                                if (isset($_SESSION['userId'])) {
                                    if (isset($_POST['txtAddress'])) {
                                        if ($result) {
                                ?>
                                    <p class="pResultLogin">Information updated successfully</p>
                                <?php
                                        } else {
                                ?>
                                    <p class="pResultLogin">Information updated successfully</p>
                                <?php
                                        }
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
