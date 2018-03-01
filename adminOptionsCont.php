<?php
    session_start(); 
    if (!isset($_SESSION['position']) || $_SESSION['position'] != 'Administrator') {
        header("Location: login.php");
    } 
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="adminOptionsCont.php" method="POST">
	<?php
	include("CONFIG.php");
    $conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

    if( !$conn ){
        echo "Error: Unable to Connect to MySQL".PHP_EOL;
    }

	switch ($_SESSION['empTypeSel']) {
		case "engineer":
			echo "<h2>Additional Engineer Info</h2>";
			echo "<br><br>"."Enter Engineer Status: ";
			echo "<input type='radio' name='radio' value='active' checked>Active";
			echo "<input type='radio' name='radio' value='inactive'>Inactive <br><br>";
			echo "<input type='number' name='hours' placeholder='Hours'> <br><br>";
			echo "Rank: ";
			echo "<select name='selRank'>";
			echo "\t<option value=''>";
			echo "\t<option value='sen'>Senior";
			echo "\t<option value='jun'>Junior"; 
			echo "</select> <br><br>";
			echo "<input type='submit' name='submit' value='Add Engineer'>";
			break;
		case "conductor":
			echo "<h2>Additional Conductor Info</h2>";
			echo "<br><br>"."Enter Conductor Rank: ";
			echo "<select name='conSelect'>";
			echo "\t<option value=''>";
			echo "\t<option value='sen'>Senior";
			echo "\t<option value='jun'>Junior";
			echo "</select> <br><br>";
			echo "<input type='submit' name='submit' value='Add Conductor'>";
			break;
		case "searchUser":
			echo "<h2>Edit User</h2>User Found<br>";
			echo "<table>\n";
		    echo "\t<tr>\n";
		    if($stmt = mysqli_prepare($conn, "SELECT * FROM employee WHERE username = ?")){
				mysqli_stmt_bind_param($stmt, "s", $_SESSION['usernameSel']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				mysqli_stmt_close($stmt);
			    echo "<table>\n";
		        echo "\t<tr>\n";
		        while( $colHeaders = mysqli_fetch_field($result)){
		            echo "\t\t<th>".$colHeaders->name."</th>\n";
				}
		        echo "\t</tr>\n";
				echo "\t<tr>\n";
				foreach($row as $value){
    				echo "\t\t<td>".$value."</td>\n";
				}
				echo "<input type='submit' name='submit' value='Reset Password'> <br>";
				echo "<input type='submit' name='submit' value='Change Username'> <br>";
				echo "<input type='submit' name='submit' value='Delete User'> <br>";
				switch($_SESSION['empSel']){
					case "engineer":
						echo "<input type='submit' name='submit' value='Change Status'><br>";
						echo "<input type='submit' name='submit' value='Change Hours'><br>";
						echo "<input type='submit' name='submit' value='Change Engineer Rank'><br><br>";
						break;
					case "conductor":
						echo "<input type='submit' name='submit' value='Change Conductor Rank'><br><br>";	
						break;	
				}
			}
			break;
		default:
			echo "<br>Session Variable Error<br>";
			break;
	}

	if( isset($_POST['submit'])){
		switch ($_POST['submit']) {
			case "Add Engineer":
				$username = $_SESSION['usernameSel'];
				$password = $_SESSION['passwordSel'];
				if($_POST['radio'] == 'active'){
					$status = 1;
				}
				else{
					$status = 0;
				}
				$hours = $_POST['hours'];
				$rank = $_POST['selRank'];

				if($stmt = mysqli_prepare($conn, "INSERT INTO users (id) VALUES(DEFAULT)")){
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				if($stmt = mysqli_prepare($conn, "INSERT INTO employee VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?)")){
					mysqli_stmt_bind_param($stmt, 's', $username);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				if($stmt = mysqli_prepare($conn, "INSERT INTO engineer (id, status, hours, engineerRank) VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?, ?, ?)")){
					mysqli_stmt_bind_param($stmt, 'iis', $status, $hours, $rank);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				if($stmt = mysqli_prepare($conn, "INSERT INTO authentication (password,role,username) VALUES (?,'Engineer',?)")){
					mysqli_stmt_bind_param($stmt, 'ss', $password, $username);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				break;
			case "Add Conductor":
				$username = $_SESSION['usernameSel'];
				$password = $_SESSION['passwordSel'];
				$rank = $_POST['conSelect'];

				if($stmt = mysqli_prepare($conn, "INSERT INTO users (id) VALUES(DEFAULT)")){
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				if($stmt = mysqli_prepare($conn, "INSERT INTO employee VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?)")){
					mysqli_stmt_bind_param($stmt, 's', $username);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				if($stmt = mysqli_prepare($conn, "INSERT INTO conductor (id, conductorRank) VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?, ?, ?)")){
					mysqli_stmt_bind_param($stmt, 's', $rank);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				if($stmt = mysqli_prepare($conn, "INSERT INTO authentication (password,role,username) VALUES (?,'Conductor',?)")){
					mysqli_stmt_bind_param($stmt, 'ss', $password, $username);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
				break;
			case "Reset Password":
				break;
			case "Change Username":
				break;
			case "Delete User":
				break;
			case "Change Status":
				break;
			case "Change Hours":
				break;
			case " Change Engineer Rank":
				break;
			case "Chnage Conductor Rank":
				break;
			default:
				echo "<br>Submit Error<br>";
				break;
		}
	}

	?>
		


	</form>

</body>
</html>

