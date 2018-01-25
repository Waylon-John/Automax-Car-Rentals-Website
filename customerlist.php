<?php
    //PAGE WHERE ADMIN CAN VIEW CURRENT REGISTERED USERS TO THE WEBSITE.
    session_start();
    if(!isset($_SESSION['signeduser']) && $_SESSION['userlevel'] != "Admin"){
        header("location:index.php");
    }
    require("database.php");
    require("messagenotif.php");
    $notify = $sign;
    function customers(){
        $db = $GLOBALS['db'];
        $stmt = $db->prepare("SELECT id,username FROM users WHERE UserLevel = 'Customer'");
        $stmt->execute();
        $stmt->bind_result($cid,$customers);
        while ($stmt->fetch()){
            echo "
                <a class='messagetext' href=message.php?id={$cid}><div class = 'messagebutton'>{$customers}</div></a>
                <hr>
            ";

        }


    }
    function search() {
        echo " <form action = 'customerlist.php' method='post' class = 'search'>
                <input type ='text' name = 'search'>
                <input class='searchbutton' type = 'submit' name = 'submit' value ='Search User'>
                </form>";
    }
    function dosearch($namesearch) {

        $db = $GLOBALS['db'];
        $stmt = $db->prepare("SELECT id,username FROM users WHERE UserLevel = 'Customer'");

        $stmt->execute();
        $stmt->bind_result($cid,$username);
        $stmt->store_result();
         while ($stmt->fetch()){
                if($namesearch == $username) {
                  $_SESSION['success'] = 1;

                    echo "
                <form action = 'customerlist.php' method = 'post' class = 'customerform'>
                 <a class='messagetext' href=message?id={$cid}><div class = 'messagebutton'>{$namesearch}</div></a><br>
                <center><input  type = 'submit' id = 'back' name = 'back' 
                value = 'Return to Customer List'></center></form>

                <hr>
            ";


                }
         }





         }


    function navmenu() {
          $notif = $GLOBALS['notify'];
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
                        <div class = 'navbarmenu'><a href='about-us.php#abtfoot'>Contact Us</a></div>
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
    if(isset($_GET['logout'])) {
            unset($_SESSION['user']);
            unset($_SESSION['signeduser']);
            unset($_SESSION['signedid']);
            unset($_SESSION['userlevel']);
        }

    if(isset($_POST['back'])) {
       unset($_SESSION['success']);
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='account.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class ='customerlistbody'>
        <!--HEADER-->

           
       
       <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
           </div>
        </div>
        <hr>
        
        
        <!--BODY-->
        
        <!--SIDENAV-->
       <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
                <?php
                    navmenu();
                ?>
        </div>
        <!--SIDENAV-->
      <div class = 'customerlist'>CUSTOMER LIST</div>
        <div class ="clbodycontainer">
             
                <?php
                    search();
                    echo "<hr>";
                    if(isset($_POST['search'])) {
                        dosearch($_POST['search']);   
                    }
                               
                    if(!isset($_SESSION['success'])) {
                        customers();
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