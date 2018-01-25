<?php
    //PAGE WHERE USER CAN VIEW HIS/HER UPLOADED RECEIPT FOR THE DOWNPAYMENT MADE.
    session_start();
    if (!isset($_SESSION['signeduser']) && $_SESSION['userlevel'] != "Customer"){
        header("location:index.php");
    }
    $tid = $_GET['id'];
    
    require("database.php");
    require("messagenotif.php");
    $notify = $sign;


    if (isset($_POST['deletereceipt'])){
        $delete = $db->prepare("DELETE FROM receipt WHERE transaction_id = ?");
        $delete->bind_param('s',$tid);
        $delete->execute();
    }

function homecontent(){
    $tid = $GLOBALS['tid'];
    $db = $GLOBALS['db'];
    $display = $db->prepare("SELECT image FROM receipt WHERE transaction_id = ?");
    $display->bind_param('s',$tid);
    $display->execute();
    $display->bind_result($image);
    $display->store_result();
    
    if($display->num_rows == 0){
        echo "
            <br>
            <br>
            <h2 class = 'h22'>NO RECEIPT IMAGE TO VIEW<h2>
        ";
    }
    
    while($display->fetch()){
            echo "<div class = 'recreserve' id = 'cus'>
                <img class='receipt' alt='IMAGE OF RECEIPT' src=/images/".$image." />
            
           
            ";
        
            echo "
                <form action='viewreceipt.php?id={$tid}' method='post'>
                <input  class = 'deletereceiptbutton' type='submit' name='deletereceipt' value = 'Delete'/><br>     
                </form>
            ";
        }
    
    echo "
       <a class='backtoreservations' href='reservations.php'>
        <div class = 'back'>
        Back to My Reservations</div></a>
        </div>
    ";
    }

function action() {
        $tid = $GLOBALS['tid'];
        if($_SESSION['userlevel'] == "Customer") {
            echo "<div class = 'recheader'>Receipt of Transaction No. {$tid} </div>";
        }
    }

function navmenu() {
      
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="index.php#foot">Contact Us</a></div>
                <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div><br>
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
    
    $delete = $db->prepare("DELETE FROM receipt WHERE user_id = ? AND transaction_id = ?");
    $delete->bind_param('ss',$aid,$tid);
    $delete->execute();
                  

?>


<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='cars.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'reservebody'>
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
        
         <div class="reccontainer">
            <?php
                 action();
            ?>

            <?php
                homecontent();
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