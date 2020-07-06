<?php
require_once './data/dbconnect.php';
include './ultility/userultilities.php';
include './ultility/orderultilities.php';
session_start();

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    } else {
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
        <title>Client Information</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/usercontrolGuest.css" rel="stylesheet" type="text/css">
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
            <div class="divWrapper_2" style="overflow: hidden">
                <!-- Body Wrapper First Level -->
                <div class="divWrapper_1">
                    <!-- Left Menu -->
                    <?php include './Templates/leftmenu.php'; ?>

                    <!-- Main -->
                    <div class="divMain">
                        <article class="articleContent">
                            <!-- User's infos -->
                            <p class="pPageTitle">Account Information</p>
                            <section>
                                <form id="formUpdateUserInfors" action="" method="post">

                                    <table>
                                        <tr>
                                            <td width="160px">Email: </td>
                                            <td><?php echo $user['Email']; ?></td>
                                        </tr> 
                                        <tr>
                                            <td>Name: </td>
                                            <td><?php echo $user['Name']; ?></td>
                                        </tr> 
                                        <tr>
                                            <td>Sex: </td>
                                            <td>
                                                <input type="radio" name="txtSex" value="0" <?php if (intval($user['Sex'])===0) {echo "checked=''";} ?>/> Male &nbsp;
                                                <input type="radio" name="txtSex" value="1" <?php if (intval($user['Sex'])!=0) {echo "checked=''";} ?>/> Female 
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>Birthday: </td>
                                            <td>
                                                <input type="date" name="txtDoB" value="<?php echo $user['DoB']; ?>" required="" />
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>Card ID: </td>
                                            <td>
                                                <input type="text" name="txtIdCard" value="<?php echo $user['IdCard']; ?>" size="30" required="" />
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>Address: </td>
                                            <td><input type="text" name="txtAddress" value="<?php echo $user['Address']; ?>" size="30" required="" /></td>
                                        </tr> 
                                        <tr>
                                            <td>Phone number: </td>
                                            <td><input type="text" name="txtPhone" value="<?php echo $user['Phone']; ?>" size="30" required="" /></td>
                                        </tr> 
                                        <tr>
                                            <td></td>
                                            <td><input type="submit" name="btnSubmit" value="Update" /> <input type="reset" name="btnReset" value="Retype" /></td>
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
                                    <p class="pResultLogin">Failed to update information</p>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </section>

                            <!-- User's orders -->
                            <p class="pPageTitle">My Orders</p>
                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <!-- Top Table -->
                                    <tr id="trTop_TableCart">
                                        <th width="60px">CODE</th>
                                        <th width="110px">Ordering time</th>
                                        <th width="160px">Client</th>
                                        <th width="140px">Payment</th>
                                        <th width="150px">Status</th>
                                    </tr>
                                    <!-- Orders List -->
                                    <?php
                                    /// Get user's orders
                                    if (isset($_SESSION['userId']))
                                    {
                                        $sql = "SELECT * FROM `orders` WHERE UserId = ".$user['Id']." ORDER BY Id DESC";
                                        $result = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($result) >= 1) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <a class="aContent" href="orderdetails.php?id=<?php echo $row['Id']; ?>"><?php echo $row['Id']; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $row['CreateTime']; ?>
                                        </td>
                                        <td>
                                            <?php echo $user['Name']; ?>
                                        </td>
                                        <td>
                                            <span class="spanProductPrice">
                                            <?php echo getOrderTotal($con, $row['Id'])."$"; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo getOrderStatus($con, $row['Status']); ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </table>
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
