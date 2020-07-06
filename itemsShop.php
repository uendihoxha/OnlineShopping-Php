<?php
    require_once './data/dbconnect.php';
    include './ultility/userultilities.php';
    session_start();
    
function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}
    
    $itemsCodeN = '';
    $itemsTID = '';
    if (isset($_GET['id'])) {
        $itemsTID = $_GET['id'];
    }
    
    if ($itemsTID === '') {
        header("Location: 404.php");
    }
    else {
        $sql = "SELECT * FROM `shopitems` WHERE Id = '$itemsTID'";
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $shopitemsN = mysqli_fetch_array($result);
            $itemsCodeN = $shopitemsN['CodeName'];
        }
        else {
            header("Location: 404.php");
        }

    }
?>
<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <title><?php echo $shopitemsN['Name']; ?></title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/itemss.css" rel="stylesheet" type="text/css">
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
                            <div class="divItemsPicture">
                                <img class="imgItemsPicture" src="images/<?php echo $shopitemsN['Picture']; ?>"/>
                            </div>
                            
                            <div class="divItemsRight">
                                <p id="pProductName"><?php echo $shopitemsN['Name']; ?></p>
                          
                                <p class="pItemsBasicInfo">Product Code: <?php echo $shopitemsN['CodeName']; ?> </p>
                                <p class="pItemsBasicInfo">Category: <?php echo $shopitemsN['Type']; ?> </p>
                                <p class="pItemsBasicInfo">Origin: <?php echo $shopitemsN['Origin']; ?> </p>
                                <p class="pItemsBasicInfo">Amount(ml): <?php echo $shopitemsN['Year']; ?></p>
                               <p class="pItemsBasicInfo">PRICE: <span class="spanProductPrice"><?php echo number_format(floatval($shopitemsN['Price']), 0, ".", ","); ?> $</span> </p>

                                
                                <form id="AddCart" action="guestcart.php" method="post">
                                <div class="divAddCart">
                                    <input type="hidden" name="txtProductId" value="<?php echo $itemsTID; ?>"/>
                                    Amount: <input type="number" value="1" min="1" name="txtProductQuantity" id="inputQuantity"/> <input type="submit" value="ORDER" name="btnButton" id="btnSubmitAddCart"/>
                                </div>
                                </form>
                            </div>

                            <div class="divItemsDetails">
                                <div class="divProdutDetailTitle">
                                    <p class="pProductDetailTitle">My REVIEW </p>
                                </div>
                                <div class="divProductDetails">
                                    <pre class="preWatchesDetails"><?php echo $shopitemsN['Details']; ?></pre>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
