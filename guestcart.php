<?php
require_once './data/dbconnect.php';
include 'ultility/userultilities.php';
include 'ultility/productultilities.php';
session_start();

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    } else {
        return 0;
    }
}
function deleteProductFromCart($productId) {
    unset($_SESSION['GuestCarts'][$productId]);
}
function EmptyCart() {
    unset($_SESSION['GuestCarts']);
}

/// Get user infors
$user = '';
if (isset($_SESSION['userId'])) {
    $user = getUserById($con, $_SESSION['userId']);
}

/// Get cart's products
$cartProducts = array();
if (isset($_SESSION['GuestCarts'])) {
    $cartProducts = $_SESSION['GuestCarts'];

    if (isset($_GET['delete'])) {
        deleteProductFromCart($_GET['delete']);
    }
}

/// Add to Carts
$addProductId = '';
$addProductQuantity = 0;
if (isset($_POST['txtProductQuantity'])) {
    $addProductId = $_POST['txtProductId'];
    $addProductQuantity = $_POST['txtProductQuantity'];
    
    if (!isset($cartProducts[$addProductId])) {
        $cartProducts[$addProductId] = intval($addProductQuantity);
    } else {
        $cartProducts[$addProductId] = $cartProducts[$addProductId] + intval($addProductQuantity);
    }
    
    
    $_SESSION['GuestCarts'] = $cartProducts;
}

/// Define order's result and order's states
define('ORDER_FAIL', 3);
define('ORDER_USER_SUCCESS', 1);
define('ORDER_GUEST_SUCCESS', 2);
define('ORDER_DIDNOTORDER', -1);
define('ORDER_EMPTYCART', 4);
$orderResult = ORDER_DIDNOTORDER;
$orderId = '';

/// Handling the order
if (isset($_POST['btnSubmit'])) {
    if (getCountProducts() > 0) 
    {
        /// User order
        if (isset($_SESSION['userId'])) 
        {
            $userId = $_SESSION['userId'];
            $userAddress = $_POST['txtAddress'];
            $userPhone = $_POST['txtPhone'];
            $userRequest = $_POST['txtRequest'];

            try {
                $sql = "INSERT INTO `orders` (`CreateTime`, `Status`, `UserId`, `Address`, `Phone`, `Request`) VALUES(CURRENT_TIME(), 0, $userId, '$userAddress', '$userPhone', '$userRequest')";
                if (mysqli_query($con, $sql)) {

                    $sql = "SELECT Id FROM `orders` WHERE UserId='$userId' AND Status=0 ORDER BY ID DESC LIMIT 1";
                    $arrayResult = mysqli_fetch_array(mysqli_query($con, $sql));
                    $orderId = $arrayResult[0];

                    foreach ($cartProducts as $productId => $productQuantity) {
                        $thistoBuy = getProductById($con, $productId);
                        $thisItemsId = intval($thistoBuy['Id']);
                        $thisItemsPrice = intval($thistoBuy['Price']);
                        $thisItemsQuantity = intval($productQuantity);

                        $sql = "INSERT INTO `orderlines` (`OrderId`, `ItemsId`, `Quantity`, `Price`) VALUES ($orderId, $thisItemsId, $thisItemsQuantity, $thisItemsPrice)";
                        mysqli_query($con, $sql);
                    }
                }

                EmptyCart();
                $orderResult = ORDER_USER_SUCCESS;
                header("Location: orderdetails.php?id=$orderId");
                
            } 
            catch (Exception $ex) {  
                $orderResult = ORDER_FAIL;
            }
        } 
        /// Guest order
        else 
        {
            $guestName = $_POST['txtName'];
            $guestAddress = $_POST['txtAddress'];
            $guestPhone = $_POST['txtPhone'];
            $guestEmail = $_POST['txtEmail'];
            $guestRequest = $_POST['txtRequest'];

            try {
                $sql = "INSERT INTO `orders` (`CreateTime`, `Status`, `Name`, `Address`, `Phone`, `Email`, `Request`) VALUES(CURRENT_TIME(), 0, '$guestName', '$guestAddress', '$guestPhone', '$guestEmail', '$guestRequest')";

                if (mysqli_query($con, $sql)) {

                    $sql = "SELECT Id FROM `orders` WHERE Name='$guestName' AND Phone='$guestPhone' AND Status=0 ORDER BY Id DESC LIMIT 1";

                    $arrayResult = mysqli_fetch_array(mysqli_query($con, $sql));
                    $orderId = $arrayResult[0];
                    
                    foreach ($cartProducts as $product) {
                        $thistoBuy = getProductById($con, array_search($product, $cartProducts));
                        $thisItemsId = intval($thistoBuy['Id']);
                        $thisItemsPrice = intval($thistoBuy['Price']);
                        $thisItemsQuantity = intval($product);

                        $sql = "INSERT INTO `orderlines` (`OrderId`, `ItemsId`, `Quantity`, `Price`) VALUES ($orderId, $thisItemsId, $thisItemsQuantity, $thisItemsPrice)";

                        mysqli_query($con, $sql);
                    }
                }

                EmptyCart();
                $orderResult = ORDER_GUEST_SUCCESS;
                header("Location: orderdetails.php?id=$orderId");
                
            } 
            catch (Exception $ex) {
                $orderResult = ORDER_FAIL;
            }
        }
    } else {
        $orderResult = ORDER_EMPTYCART;
    }
}
?>

<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/guestcart.css" rel="stylesheet" type="text/css">
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
                            <p class="pPageTitle">YOUR CART</p>

                            <!-- Cart Table -->
                            <section>
                                <p>You have <?php echo getCountProducts(); ?> items in cart <a class="aContent" href="index.php">Continue shopping</a></p>
                                
                                    <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                        <!-- Top Table -->
                                        <tr id="trTop_TableCart">
                                            <th width="200px">Product</th>
                                            <th width="140px">Price</th>
                                            <th width="100px">Amount</th>
                                            <th width="140px">Payed</th>
                                            <th width="80px">Action</th>
                                        </tr>
                                        <?php
                                        $total = 0.0;

                                        if (getCountProducts() > 0) {
                                            $products = $_SESSION['GuestCarts'];
                                            foreach ($products as $productId => $productQuantity) {
                                                $thistoBuy = getProductById($con, $productId);

                                                if ($thistoBuy != '') {
                                                    $total += floatval($thistoBuy['Price']) * floatval($productQuantity);
                                                    ?>
                                                    <tr>
                                                        <td><a class="aContent" href="itemsShop.php?id=<?php echo $thistoBuy['Id']; ?>"><?php echo $thistoBuy['CodeName']; ?></a></td>
                                                        <td><?php echo number_format(floatval($thistoBuy['Price']), 0, ".", ",") . " $"; ?></td>
                                                        <td><?php echo $productQuantity; ?></td>
                                                        <td><?php echo number_format(floatval($thistoBuy['Price']) * floatval($productQuantity), 0, ".", ",") . "$"; ?></td>
                                                        <td style="text-align: center"><a class="aContent" href="guestcart.php?delete=<?php echo $thistoBuy['Id']; ?>">REMOVE</a></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <tr id="trBottom_TableCart">
                                            <td colspan="3" style="text-align: right">TOTAL: </td>
                                            <td colspan="2" style="font-weight: bold"><?php echo number_format($total, 0, ".", ","); ?> $ </td>
                                        </tr>
                                    </table>
                            </section>

                            <!-- Guest Details -->
                            <section>
                                <p class="pPageTitle">Client information</p>
                                
                                <form id="formRegister" action="" method="post">
                                <table class="tableRegister">
                                    <tr>
                                        <td width="150px">Name <span class="spanRequiredField">*</span></td>
                                        <td width="330px">
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtName" type="text" size="30" required="" />
                                                <?php
                                            } else {
                                                echo $user['Name'];
                                                ?>
                                                <input name="txtName" type="hidden" value="<?php echo $user['Name']; ?>" />
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Address <span class="spanRequiredField">*</span></td>
                                        <td>
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtAddress" type="text" size="50" required=""/>
                                                <?php
                                            } else {
                                                ?>
                                                <input name="txtAddress" type="text" size="50" value="<?php echo $user['Address']; ?>" required=""/>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Phone number <span class="spanRequiredField">*</span></td>
                                        <td>
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtPhone" type="text" size="50" required=""/>
                                                <?php
                                            } else {
                                                ?>
                                                <input name="txtPhone" type="text" size="50" value="<?php echo $user['Phone']; ?>" required=""/>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email: </td>
                                        <td>
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtEmail" type="text" size="30" />
                                                <?php
                                            } else {
                                                echo $user['Email'];
                                                ?>
                                                <input name="txtEmail" type="hidden" value="<?php echo $user['Email']; ?>" />
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td>Request: </td>
                                        <td>
                                            <textarea name="txtRequest" size="30" cols="40" rows="6" ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input class="btnOrder" name="btnSubmit" type="submit" value="ORDER"/>
                                            <input class="btnOrder" name="btnReset" type="reset" value="Retype"/>
                                        </td>
                                    </tr>
                                </table>
                                </form>
                            </section>

                            <!-- In the case, order fail -->
                            <?php
                                if ($orderResult === ORDER_FAIL) 
                                {
                            ?>
                                <p class="pResultLogin">Order failed, please try again.</p>
                            <?php
                                }
                                if ($orderResult === ORDER_EMPTYCART)
                                {
                            ?>
                                <p class="pResultLogin">Cart is now empty.</p>
                            <?php
                                }
                            ?>
                        </article>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
