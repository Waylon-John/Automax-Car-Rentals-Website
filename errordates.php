<?php
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





<html class = 'msgbody'>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body>
        <!--HEADER-->
          <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
               
           </div>
        </div>
        <hr>
             <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
                <?php
                    navmenu();
                ?>
        </div>
        
        <!--BODY-->
             <div class="errorcont">
                <?php

                 $id=$_GET['id'];
                echo "<br>
                <div class = 'errordates'>DATES HAVE ALREADY BEEN RESERVED<br><br><br>
                <a href='errordates.php?retry&id={$id}' class = 'errordatetext'>PLEASE TRY ANOTHER ONE</a></div>";
                if(isset($_GET['retry'])){
                unset($_SESSION['error']);
                header("location:book.php?id={$id}");
                }
                ?>
            </div>
        
          <script>
            /* Set the width of the side navigation to 250px */
            function openNav() {
                document.getElementById("sidenav").style.width = "15%";
                document.getElementById("sidenav").style.minWidth="200px";
                document.getElementById("sidenav").style.visibility = "visible"; document.getElementById("sidenav").style.padding = "20,20,20,20"; document.getElementById("buttonlogin").style.width = "25%";
            }

            /* Set the width of the side na vigation to 0 */
            function closeNav() {
                
                document.getElementById("sidenav").style.width = "0";
                document.getElementById("sidenav").style.minWidth="0";
                document.getElementById("sidenav").style.visibility = "hidden";
                document.getElementsById("buttonlogin").style.width = "0";
                document.getElementById("sidenav").style.padding = "0,0,0,0";
                
            }
            
            
        </script>
      
    </body>
</html>