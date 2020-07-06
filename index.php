<?php
    session_start();
    require_once './data/dbconnect.php';
    include 'ultility/userultilities.php';
  
    function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}
?>

<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <title>Home</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/products.css" rel="stylesheet" type="text/css">
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
                            <p class="pPageTitle">Home</p>


                            <section>
                                <div class="divProducts">
                                    <?php
                                        $sql = "SELECT * FROM `shopitems` ORDER BY Id ASC";
                                        $result = mysqli_query($con, $sql);
                                        while($product = mysqli_fetch_array($result)) {

                                    ?>
                                    <div class="divProduct">
                                        <a href="itemsShop.php?id=<?php echo $product['Id']; ?>">
                                            <img src="images/<?php echo $product['Picture']; ?>" width="156px" height="280px"/>
                                        </a>
                                        <div class="divProductDetail">
                                            <a href="itemsShop.php?id=<?php echo $product['Id']; ?>">
                                                <span class="spanProductName"><?php echo $product['Name']; ?></span> <br/> 
                                            </a>
                                                          <p class="pItemsBasicInfo">PRICE: <span class="spanProductPrice"><?php echo number_format(floatval($product['Price']), 0, ".", ","); ?> $</span> </p>
                                           
                                        </div>
                                    </div>
                                    <?php

                                        }
                                    ?>
                                </div>
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
