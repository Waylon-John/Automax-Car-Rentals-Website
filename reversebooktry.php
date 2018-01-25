<?php
    //PHP FILE FOR CHECKING IF THE USER IS PERFORMING A REVERSE BOOKING OF THE CARS.
    if(isset($_POST['rent']) && isset($_POST['returndate'])) {
    $rent = $_POST['rent'];
    $return = $_POST['returndate'];
}

?>
<html>
<head>
        <script type="text/javascript">
        var rentt = new Date("<?php echo $rent ?>");
            var returnn = new Date("<?php echo $return ?>");
            
            if(rentt >= returnn) {
                document.write("ERROR IN BOOKING");
            }
             
        
    </script>
    </head>
    <body>
        <form action = 'reversebooktry.php' method = 'post'>
            <input type = 'date' name ='rent'>
             <input type = 'date' name ='returndate'>
    <input type = 'submit' onClick = 'compare()'>
            </form>
    

    </body>
</html>