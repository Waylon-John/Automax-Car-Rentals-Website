<?php
    //PAGE WHERE ADMIN CAN VIEW, ADD OR REMOVE THE WEBSITE'S FEATURED CARS FOR RENTS.
    session_start();
    if(!isset($_SESSION['signeduser']) && $_SESSION['userlevel'] != "Admin"){
        header("location:index.php");
    }

    require("database.php");
    require("messagenotif.php");   
    $notify = $sign;
    if(isset($_POST['addbutton'])){
        if(!isset($_POST['carmodel']) && !isset($_POST['cartype']) && !isset($_POST['carunits'])&& !isset($_POST['price'])) {    echo'ERROR';
            return;
        } else {
            carAdd($_POST['carmodel'],$_POST['cartype'],$_POST['carunits'],$_POST['price']);
        }   
    }

    function carAdd($model,$type,$units,$price) {   
        $targetcar = "images/".basename($_FILES['image']['name']);
        $db = $GLOBALS['db'];

        if(isset($targetcar)){
            $image = $_FILES['image']['name'];
            if (move_uploaded_file($_FILES['image']['tmp_name'],$targetcar)){

                $sql = "INSERT INTO cars (image, Model,Type,Units,currentunits,PricePerDay) VALUES ('$image','$model','$type','$units','$units','$price')";
                mysqli_query($db,$sql);

            } else {
                echo 'ERROR IN ADDING CARS';
                return;
            }
        } else {
            echo "ERROR!";
            return;
        }           
    }

    function homecontent() {
                $db = $GLOBALS['db'];
                $stmt = $db->query("SELECT * FROM cars");
                if($stmt->num_rows == 0){
                    echo "
                        <br>
                        <h2>No Cars To View</h2>
                        <br>
                    ";
                } else {
                    while ($cars = $stmt->fetch_assoc()){
                        if($cars['image'] != null) {
                            echo "
                                <div class = 'cars2admin'>
                                    <div class='pic'>  
                                        <img class='carspicadmin' src=images/".$cars['image']." />
                                    </div>
                                    <div class='model'>
                                        <div class='details'>MODEL: {$cars['Model']}</div>
                                        <div class = 'details'>TYPE: {$cars['Type']}</div>
                                        <div class = 'details'>PRICE: {$cars['PricePerDay']} / day</div>
                                        <div class='details'>Number of unit/s: {$cars['Units']}</div>
                                        <br>
                                        <a href='successfulcarsdelete.php?id={$cars['id']}' class = 'removetxt'><div class = 'removebut'>Remove Car</div></a>
                                    </div>
                                </div>
                            ";
                        }

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
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
        
    </head>
    <body class = 'carsbody'>
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
        <div class ="bodycontainer">
            <div class ="bannercontainer">
                  
                    <div class = 'caradditioncontainer'>
                        <div class = 'add' id = 'addside'>
                            <form action='cars.php' method='post' enctype='multipart/form-data' class = 'addform'>
                                <label class = 'addtext'>ADD A CAR</label><br><br>
                                Input Details of Car:
                                <input class='addinputs' type = 'hidden' name = 'size' value = '1000000'><br>
                                <input class='addinputs' type='text' placeholder='Enter Car Model' name='carmodel'/><br>
                                <input class='addinputs' type='text' placeholder='Enter Car Type' name='cartype'/><br>
                                <input class='addinputs' type='number' placeholder='Enter Number of Units' name='carunits'/><br>
                                <input class='addinputs' type='number' placeholder='Rental Price' name='price'/><br><br>
                                Upload Car Image Here:<br><br>
                                <input class='addinputs' type = 'file' name ='image'><br><br>
                                <input type='submit' value='Add Car' name='addbutton' class='addcarbutton'/>
                            </form>
                        </div>        
                <div class ='add' id = 'carside'>
                        <?php
                            homecontent();
                        ?>
                </div>  
            </div>
        </div>
    </div>
        
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