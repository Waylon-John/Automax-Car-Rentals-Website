<?php
    //PROMPTS WHEN LOGIN CREDENTIALS ARE INVALID
    session_start();
    function navmenu() {

            if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
                echo '
                    <div class = "navbarmenu"><a href="index.php">Home</a></div>
                    <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                    <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                    <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                    <div class = "navbarmenu"><a href="#foot">Contact Us</a></div>
                    <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div>
                    <div class = "navbarmenu"><a href = "account.php?logout">Logout</a></div>

                ';
            } else if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Admin"){

                    echo "
                         <div class = 'navbarmenu'><a href='adminindex.php'>Home</a></div>
                        <div class = 'navbarmenu'><a href='account.php'>Profile</a></div>
                        <div class = 'navbarmenu'><a href='customerlist.php'>Customers</a></div>
                        <div class = 'navbarmenu'><a href='inbox.php'>Inbox{$notif}</a></div>
                        <div class = 'navbarmenu'><a href='about-us.php'>About Us</a></div>
                        <div class = 'navbarmenu'><a href='#foot'>Contact Us</a></div>
                        <div class = 'navbarmenu'><a href = 'account.php?logout'>Logout</a></div>
                    ";

                }

            else {
                echo '
                    <div class = "navbarmenu"><a href="index.php">Home</a></div>
                    <div class = "navbarmenu"><a href="account.php">Login</a></div>
                    <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>

                    <div class = "navbarmenu"><a href="#foot">Contact Us</a></div>
                    <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div>
                ';
            }

    }
?>



<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'registerbody'>
        <!--HEADER-->
       
         <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
               
           </div>
        </div>
        
        
        <!--BODY-->
        
        <div class ="bodycontainer">
            <div class ="bannercontainer">
                     <div class="carslogin">
                        <?php
                        session_start();
                        echo '<br>
                        <h1>INVALID CREDENTIALS<br>
                        <a style= "color:coral" href="errorlogin.php?retry">PLEASE TRY AGAIN</a></h1>';

                        if(isset($_GET['retry'])){
                        unset($_SESSION['error']);
                        header("location:account.php");
                        }
                        ?>
                    </div>
            </div>
        </div>
    </body>
</html>