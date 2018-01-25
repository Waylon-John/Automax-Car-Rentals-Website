<?php
    //PAGE WHERE ADMIN CAN VIEW MESSAGES FROM CUSTOMERS.
    session_start();

    require("database.php");
    require("messagenotif.php");

    $notify = $sign;

    function displayNew(){
        $x = 0;
        $db = $GLOBALS['db'];
        $stmt = $db->query("SELECT * FROM message WHERE status = 'UNREAD' AND recipient_id = 1 ORDER BY message_time DESC");
         if($stmt->num_rows == 0) {
                //unset($_SESSION['message']);
                echo "No new Messages";
            } else {
                //$_SESSION['message'] = 1;
                 while($new = $stmt->fetch_assoc()){
                    $users_send = $db->prepare("SELECT username FROM users WHERE id = ?"); 
                    $users_send->bind_param("i", $new['sender_id']);
                    $users_send->execute();
                    $users_send->bind_result($sendname);
                    $users_send->fetch();
                    $users_send->close();
                    echo "
                        <div class = 'messagecontents'>
                        <div class = 'usertimemsg' id = 'user'>$sendname </div>
                        <div class = 'usertimemsg' id = 'time'>{$new['message_time']}</div>
                        <div class = 'usertimemsg' id = 'msg'>{$new['content']}</div>
                        <form action='' method='post' class = 'usertimemsg' id = 'formview'>
                                <input type='submit' name='{$new['id']}' value='View Message' class = 'usertimemsg' id = 'view'/>
                            </form>

                            </div>
                            <hr>


                    ";

                       if(isset($_POST["{$new['id']}"])) {
                            $updateid = $new['sender_id'];
                            $updatestatus = $db->prepare("UPDATE message SET status = 'VIEWED' WHERE sender_id = {$new['sender_id']} AND recipient_id = 1");
                            $updatestatus->execute();
                            header("location:message.php?id={$new['sender_id']}");
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

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
          <link rel='stylesheet' href='index.css'/>
         
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'inboxbody'>
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
        <div class ="bodyinbxcontainer">
            <div class ='inboxtext'>INBOX</div>
                      <div class = "inboxmessagecontainer">
                            <?php
                                displayNew();
                            ?>
                         
            </div>
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