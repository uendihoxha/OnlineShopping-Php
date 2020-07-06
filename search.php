<?php
    session_start();
    require_once './data/dbconnect.php';
    include './ultility/userultilities.php';
    
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
        <title>SEARCH</title>
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
                            <p class="pPageTitle">Search</p>

                            <section>
                                <form id="formSearch" action="" method="post">
                                    <p>Search for product: <input type="text" id="txtWatchesName" size="40" required=""/>
                                    <input type="submit" id="btnSubmit" value="Submit"/>
                                    <input type="reset" id="btnReset" value="Reset"/></p>
                                </form>
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
