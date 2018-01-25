<?php
    //PROMPTS WHEN ADMIN CHOOSES TO REMOVE A FEATURED CAR FOR CONFIRMATION.
    session_start();
    $id = $_GET['id'];
    if(!isset($_SESSION['signeduser']) && $_SESSION['userlevel'] != "Admin"){
        header("location:index.php");
    }

    require("database.php");

    if(isset($_POST['confirm'])){
        carRemove();
    } else if(isset($_POST['decline'])){
        header("location:cars.php");
    }

    function carRemove(){
        $id = $GLOBALS['id'];
        $db = $GLOBALS['db'];
        $remove = $db->prepare('DELETE FROM cars WHERE id = ?');
        $remove->bind_param('i',$id);
        $remove->execute();
        $_SESSION['carsremove'] = 1;

    }

    function removeSuccess(){
        $id = $GLOBALS['id'];
        echo "
            <div class = 'confirmdelete'>CAR REMOVED SUCCESSFULLY</div><br>
            <a href='successfulcarsdelete.php?id={$id}&remove' class = 'viewcartext'><div class = 'viewcar'>VIEW CARS</div></a><h1>
        ";

        if(isset($_GET['remove'])){
            unset($_SESSION['carsremove']);
            header("location:cars.php");
        }
    }
    function RemoveForm(){
        $id = $_GET['id'];
        echo "

            <div class = 'confirmdelete'>DO YOU REALLY WANT TO REMOVE THIS CAR?</div>
                <form action='successfulcarsdelete.php?id={$id}' method='post'><br><br><br>
                <center>
                    <input type='submit' value='Yes' name='confirm' class='choicebut'/><br>
                    <input type='submit' value='No' name='decline' class='choicebut'/>
                    </center>
                </form></h1>
        ";
    }
?>



<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'msgbody'>
        <!--HEADER-->
       
   <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
               
           </div>
        </div>
        <hr>
        
        
        <!--BODY-->
        
        
                     <div class="deletecontainer">
                        <?php
                            if(!isset($_SESSION['carsremove'])){
                                RemoveForm();
                            } else {
                                removeSuccess();
                            }
                            
                         ?>
                    </div>
          
    </body>
</html>