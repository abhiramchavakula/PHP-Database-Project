<?php
    session_start();
    if (!isset($_SESSION['position']) || $_SESSION['position'] != 'Administrator') {
        header("Location: login.php");
    } 
?>

<!DOCTYPE html>
<html>
<?php
    //if(!$_SESSION["username"]){
    //    header("Location: http://cs3380.rnet.missouri.edu/~********/******/XXXX"); 
    //    exit();
    //}
?>
<head>
    <title>Administrator Page</title>
</head>
<body>
    <h2>Administrator Home</h2>
    <form action="login.php" method="POST">
	    <input type="submit" name="logout" value="Log Out">
     </form>

    <form action="admin.php" method="POST">
        <a href="setPass.php">Change Password</a> <br>
        <?php
            //echo "<h2>".$_SESSION["username"]." Profile Page"."</h2>";
        ?>
        Options:
        <br><br>
        <input type="radio" name="radio" value="addNewUser" checked/>Add New User
        <br>
        <br>
        <input type="radio" name="radio" value="addNewCustomer" />Add New Customer
        <br>
        <br>
        <input type="radio" name="radio" value="editUser" />Edit User
        <br>
        <br>
        <input type="radio" name="radio" value="editCustomer" />Edit Customer
        <br>
        <br>
	<input type="radio" name="radio" value="addTrain" />Add Train
	<br>
	<br>
	<input type="radio" name="radio" value="addCar" />Add Car
	<br>
	<br>
        <input type="radio" name="radio" value="editTrain" />Edit Train
        <br>
        <br>
	<input type="radio" name="radio" value="editCar" />Edit Car
	<br>
	<br>
        <input type="submit" name="submit" value="GO">
	<input type="submit" name="submit" value="View Log">
        <?php
            //

            if( isset($_POST['submit'])){
		if( $_POST['submit'] == "GO" ){
                	$_SESSION["adminSel"] = $_POST['radio'];
                	header("Location: adminOptions.php");
        	}
		else if( $_POST['submit'] == "View Log" ){
			header("Location: log.php");
		}   
		else{
			echo "Submit Error".PHP_EOL;
		}		 
	}

        ?>



    </form>

</body>
</html>
