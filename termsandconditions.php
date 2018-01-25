<?php
    
     session_start();
    require("database.php");
    require("messagenotif.php");
   
    $notify = $sign;
    
    function action() {
        
        if(!isset($_SESSION['signeduser'])) {
                  echo "
              <div class='topbanner'>
           
            <div class ='topbannertext'>
            <h2 id ='hh2'>WE GOT YOU COVERED.</h2>
            <h3 class = 'hh3'>DON'T HAVE A CAR? WANNA GO TRAVELLING?</h3>
            <h3 class = 'hh3'>RESERVE THE CAR THAT YOU LIKE</h3>
          
            
        
            </div>
              
            <div class='topbanner2'><a href='reservations.php' id = 'myreserv'>LOGIN NOW</a></div>
           
            </div>
           
            
       
           
            <div class='indexbanner'>
           
            </div>
            ";
        } else if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo "
              <div class='topbanner'>
           
            <div class ='topbannertext'>
            <h2 id ='hh2'>WE GOT YOU COVERED.</h2>
            <h3 class = 'hh3'>DON'T HAVE A CAR? TRAVELING SOMEWHERE? WANNA GO TRAVELLING?</h3>
            <h3 class = 'hh3'>RESERVE THE CAR THAT YOU LIKE</h3>
          
            
        
            </div>
              
            <div class='topbanner2'><a href='reservations.php' id = 'myreserv'>MY RESERVATIONS</a></div>
           
            </div>
           
            
       
           
            <div class='indexbanner'>
           
            </div>
            ";
        }
    }

    function homecontent() {
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel']=="Admin"){
            echo ' <div class ="adminhome">ADMIN OPTIONS</div>
                <div class = "admincontent">
                  
                    <h3><a href = "cars.php" class=adminbutton>Cars for rent</a></h3>
                    <br><br>
                    <h3><a href = "reservations.php" class=adminbutton>Customer Reservations</a></h3>
                    <br><br>
                   <h4><a href = "account.php" class=adminbutton>Back</a></h4>
                </div>
            ';
            
        } else {
            $db = $GLOBALS['db'];
            $stmt = $db->prepare("SELECT Model,image,id FROM cars");
            $stmt->execute();   
            $stmt->bind_result($model,$image,$id);
            while ($stmt->fetch()){
                
                echo "
                
                
                <div class = 'cars2'>
                    <br><br>
                    <span class='pic'>
                        <img class='carspic' src='/automaxdes/images/{$image}' />
                    </span>
                    <div class = 'indexcardetails'>
                     <label class = 'carmodel'>$model</label>
                     <br>
                     <a class='indexbookbutton' href=book.php?id={$id}>
                     <div class ='bookingbutton'>BOOK</div></a>
                    
                    <a class='indexviewreservebutton' href=car-reservations.php?id={$id}>
                     <div class ='viewreservationsbutton'> VIEW CURRENT RESERVATIONS</div></a>
                    </div>
               
                </div>
                ";
            }
        }
    }

      function navmenu() {
      
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="#abtfoot">Contact Us</a></div>
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
                    <div class = 'navbarmenu'><a href='#abtfoot'>Contact Us</a></div>
                    <div class = 'navbarmenu'><a href = 'account.php?logout'>Logout</a></div>
                ";
                    
            }
            
        else {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Login</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                
                <div class = "navbarmenu"><a href="#abtfoot">Contact Us</a></div>
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






?>


<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
         <link rel='stylesheet' href='cars.css'/>
        <link rel='stylesheet' href='index.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = "indexbody">
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

                      
        <div class = 'termsbody'>
        <h2 class = 'h22'>REMINDER</h2>
        <ul>
            <li>Customers who will drive must be:</li>
            <ul>
                <li>At least 18 years old.</li>
                <li>Has a valid Driver's License</li>
                 <li>Physically and mentally fit to operate a motor vehicle.</li>
                <li>Able to read and write in Filipino or English.</li>
            </ul><br>
            <li>Each rented car must be handled with care.</li>
            <li>Reservations made can be viewed by a customer in "My Reservations"</li>
            <li>Cars rented shall be picked up at Automax Car Rental</li>
            <li>Payment for rent shall be done upon receiving the rented car.</li>
            <br><br>
        </ul>
            <hr><br>
        <h2 class = 'h22'>TERMS AND CONDITIONS</h2>
        <ul>
            <li>Each reservation has its own downpayment deadline which is a day after booking it.</li>
            <li>If downpayment is not made once the deadline has paased, Admin will revoke reservation made.</li>
            <li>Reservations will be finalized once downpayment has been made.</li>
            <li>Finalized reservations cannot be edited or changed by the customer.</li>
            <li>Once the return date has passed and the rented car is not yet returned, <br>the customer must pay an additional 250 PHP with an interest of 40% daily when car is finally returned.</li>
        </ul><br><hr><br>
        <h2 class = 'h22'>INSTRUCTIONS ON DOWNPAYMENT TRANSACTION</h2>
            <ul>
                <li>Pay the downpayment fee of 400 PHP using Fund Transfer (BDO) or Bank Deposit (BPI) to the following accounts:</li>
                    <ul>
                        <li>BDO: XXXX-XXXX-XXXX</li>
                        <li>BPI: XXXX-XXXX-XX</li>
                    </ul>
                <li>
                    Upload picture of transaction receipt in "My Reservations" for that particular reservation.
                </li>
                <li>
                    Wait for reservation to be finalized by the Admin.
                </li>
            </ul>
        </div>
        
        
        
        
        <footer id = 'abtfoot'>
               <div class = 'footdiv'>
         
            <div id ='footercontent'><label class= 'footh'>Address</label>
                <br><br>B17 L15 Royal South Village Talon V / L15 B17 Royal South St. Royal South Townhomes Talon V, Las Pinas, Metro Manila
            </div>

            <div id = 'footercontent'>
                <label class = 'footh'>Social Media</label><br><br>
                <label id = 'email'>automaxrentals@gmail.com</label>
                <label id = 'email'>www.facebook.com/pages/automaxrentals</label>
            </div>
            <div id = 'footercontent'>
                <label class = 'footh'>Contact</label><br><br>
                
                <label>+63 2 330 0479</label><br>
            </div>
            
      
            </div>
           
        </footer>
         
        <script>
            /* Set the width of the side navigation to 250px */
           function openNav() {
                document.getElementById("sidenav").style.width = "200px";
               
                document.getElementById("sidenav").style.visibility = "visible"; document.getElementById("sidenav").style.padding = "20,20,20,20";
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