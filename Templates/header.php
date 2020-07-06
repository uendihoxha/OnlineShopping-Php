<header>
    <a href="#">
        <img src="styles/Banner.png" alt="shopping" height="90" id="imgBanner" />
    </a>  

    <div class="divGuestCart">
        <?php
        if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
            if (getUserById($con, $_SESSION['userId'])['Admin'] != 1) {
                ?>
                <a href="usercontrolGuest.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            } else {
                ?>
                <a href="admincontrol.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            }
            ?>

            <a href="logout.php">[LOGOUT]</a> <br>

            <?php
        } else {
            ?>
            <a href="login.php">LOGIN</a> <span style="color:#FFF">|</span>
            <a href="register.php">REGISTER</a> <br>
            <?php
        }
        ?>

        <a href="guestcart.php">Your cart: <span id="txtCountGuestCart"><?php echo getCountProducts(); ?></span> items</a>
    </div>
</header>