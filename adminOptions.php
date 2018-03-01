<?php
    session_start(); 
    if (!isset($_SESSION['position']) || $_SESSION['position'] != 'Administrator') {
        header("Location: login.php");
    } 
?>


<!DOCTYPE html>
<html>
<head>
	<title>Administrator's Options</title>
</head>
<body>
	<h2>Administrator Options</h2>
	<form action="adminOptions.php" method="POST">
		<?php
			include("CONFIG.php");
            $conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

            if( !$conn ){
                echo "Error: Unable to Connect to MySQL".PHP_EOL;
            }
			
			switch($_SESSION["adminSel"]){
				case "addNewUser":
					echo "<h3>New User</h3>";
					echo "Enter the Following Information"."<br><br>";
					echo "<input type='text' name='username' placeholder='Employee Username' required><br>";
					echo "<input type='password' name='password' placeholder='Password'><br><br>";
					echo "Employee Role"."<br>";
					echo "<input type='radio' name='radio' value='admin' checked>Administrator <br>";
					echo "<input type='radio' name='radio' value='conductor'>Conductor <br>";
					echo "<input type='radio' name='radio' value='engineer'>Engineer <br><br>";
					echo "<input type='submit' name='submit' value='Add User'>";
					break;
				case "addNewCustomer":
					echo "<h3>New Customer</h3>";
					echo "Enter the Following Information"."<br><br>";
					echo "<input type='text' name='companyID' placeholder='Company ID' required> <br><br>";
					echo "<input type='submit' name='submit' value='Add Customer'>";
					break;
				case "editUser":
					echo "<h3>Search User to Edit</h3>";
					echo "Username: ";
					echo "<input type='text' name='username' placeholder='Username'> <br><br>";
					echo "Employee Type: "."<select required name='empSelect'>";
					echo "\t<option value=''> </option>";
					echo "\t<option value='admin'>Administrator </option>";
					echo "\t<option value='engineer'>Engineer </option>";
					echo "\t<option value='conductor'>Conductor </option>";
					echo "</select> <br><br>"; 
					echo "<input type='submit' name='submit' value='Search User'>";
					break;
				case "editCustomer":
					echo "<h3>Search Customer to Edit</h3>";
					echo "Customer ID: ";
					echo "<input type='text' name='customerID' placeholder='Customer ID'> <br><br>";
					echo "<input type='submit' name='submit' value='Search Customer'>";
					break;
				case "addTrain":
					echo "<h3>Add Train</h3>";
					echo "<input type='number' name='trainNum' placeholder='Train Number'> <br><br>";
					echo "Days Running"."<br>";
					echo "<input type='checkbox' name='daysRun[]' value='mon'>Monday <br>";
					echo "<input type='checkbox' name='daysRun[]' value='tue'>Tuesday <br>";
					echo "<input type='checkbox' name='daysRun[]' value='wed'>Wednesday <br>";
					echo "<input type='checkbox' name='daysRun[]' value='thu'>Thursday <br>";
					echo "<input type='checkbox' name='daysRun[]' value='fri'>Friday <br>";
					echo "<input type='checkbox' name='daysRun[]' value='sat'>Saturday <br>";
					echo "<input type='checkbox' name='daysRun[]' value='sum'>Sunday <br><br>";
					echo "Depart Time: <input type='time' name='departTime'> <br><br>";
					echo "Arrival Time: <input type='time' name='arriveTime'> <br><br>";
					echo "Destination City: ";
					echo "<select name='destCity'>";
					echo "<option value=''> </option>";
					$sql = "SELECT cityName FROM cities;";
                        		$result = mysqli_query($conn, $sql);
		                        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
               		   	        	echo "<option value=\""  . $row[0] . "\"";
                                		//if (isset($_POST['submit']) && isset($_POST[$field]) && $_POST[$field] == $row[0]) { // keep user selections
                                        	//	echo " selected=\"selected\"";
                                		//}
		                                echo ">" . $row[0] . "</option>" . PHP_EOL;
                		        }
					echo "</select>";
					echo "<br><br> Departure City:";
					echo "<select name='departCity'>";
                                        echo "<option value=''> </option>";
                                        $sql = "SELECT cityName FROM cities;";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                                                echo "<option value=\""  . $row[0] . "\"";
                                                //if (isset($_POST['submit']) && isset($_POST[$field]) && $_POST[$field] == $row[0]) { // keep user selections
                                                //      echo " selected=\"selected\"";
                                                //}
                                                echo ">" . $row[0] . "</option>" . PHP_EOL;
                                        }
                                        echo "</select>"; 
					echo "<br><br> <input type='submit' name='submit' value='Add Train'>";
					break;
				case "addCar":
					header("Location: reserve.php");
					// echo "Add Car"."<br><br>";
					// echo "<input type='number' name='carSerial' placeholder='Car Serial Number'><br><br>";
					// echo "<input type='number' name='loadCapacity' placeholder='Load Capacity'><br><br>";
					// echo "Type of Car: <br><br>";
					// echo "Manufacturer:  <br><br>";
					// echo "Price: $<input type='number' min='0.01' step='0.01' name='carPrice'> <br><br>";
					// echo "Type of Cargo: <br><br>";
					// echo "Location: <br><br>";
					// echo "Train Number: <br><br>";
					// echo "Customer: <br><br>";
					// echo "Departrue Date: <input type='date' name='departDate'> <br><br>";
					// echo "Arrival Date: <input type='date' name='arriveDate'> <br><br>";
					// echo "<input type='submit' name='submit' value='Add Car'>";
					break;					
				case "editTrain":
					echo "<h3>Search Train to Edit</h3>";
					echo "Train ID: ";
					echo "<input type='text' name='trainNum' placeholder='Train Number'> <br><br>";
					echo "<input type='submit' name='submit' value='Search Train'>";
					break;
				case "editCar":
					echo "<h3>Edit Car</h3>";
					echo "<input type='text' name='carNum' placeholder='Car Serial Number'>";
					echo "<input type='submit' name='submit' value='Search Car'>";
					break;
				default:
					echo "Radio Button Failure".PHP_EOL;
					break;
			}
			
			if( isset($_POST['submit'] )){
				switch($_POST['submit']){
					case "Add User":
						$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
						$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
						if($stmt = mysqli_prepare($conn, "SELECT * FROM employee WHERE username = ?")){
							mysqli_stmt_bind_param($stmt, "s", $username);
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);
							$row = mysqli_fetch_array($result, MYSQLI_NUM);
							mysqli_stmt_close($stmt);
							if( $row > 0 ){
								echo "<br><br>Username Already Exists".PHP_EOL;
							}
							else{
								switch($_POST['radio']){
										case "admin":
											if($stmt = mysqli_prepare($conn, "INSERT INTO users (id) VALUES(DEFAULT)")){
												mysqli_stmt_execute($stmt);
												mysqli_stmt_close($stmt);
											}
											if($stmt = mysqli_prepare($conn, "INSERT INTO employee VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?)")){
												mysqli_stmt_bind_param($stmt, 's', $username);
												mysqli_stmt_execute($stmt);
												mysqli_stmt_close($stmt);
											}
											if($stmt = mysqli_prepare($conn, "INSERT INTO administrator (id) VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1))")){
												mysqli_stmt_execute($stmt);
												mysqli_stmt_close($stmt);
											}
											if($stmt = mysqli_prepare($conn, "INSERT INTO authentication (password,role,username) VALUES (?,'Administrator',?)")){
												mysqli_stmt_bind_param($stmt, 'ss', $password, $username);
												mysqli_stmt_execute($stmt);
												mysqli_stmt_close($stmt);
											}
											break;
										case "engineer":
											$_SESSION['empTypeSel'] = "engineer";
											$_SESSION['usernameSel'] = $username;
											$_SESSION['passwordSel'] = $password;
											header("Location: adminOptionsCont.php");
											break;
										case "conductor":
											$_SESSION['empTypeSel'] = "conductor";
											$_SESSION['usernameSel'] = $username;
											$_SESSION['passwordSel'] = $password;
											header("Location: adminOptionsCont.php");
											break;
									}
								}
							}
						break;
					case "Add Customer":
						$username = htmlspecialchars($_POST['companyID'], ENT_QUOTES);
						if($stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE companyID = ?")){
							mysqli_stmt_bind_param($stmt, 's', $username);
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);
							$row = mysqli_fetch_array($result, MYSQLI_NUM);
							mysqli_stmt_close($stmt);
							if( $row > 0 ){
								echo "<br><br>Company ID Already Exists".PHP_EOL;
							}
							else{
								if($stmt = mysqli_prepare($conn, "INSERT INTO users (id) VALUES(DEFAULT)")){
									mysqli_stmt_execute($stmt);
									mysqli_stmt_close($stmt);
								}
								else{
									echo "Did NOT Run".PHP_EOL;
								}
								if($stmt = mysqli_prepare($conn, "INSERT INTO customer (id, companyID) VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?)")){
									mysqli_stmt_bind_param($stmt, 's', $username);
									mysqli_stmt_execute($stmt);
									mysqli_stmt_close($stmt);
								}
								else{
									echo "Did NOT Run 2".PHP_EOL;
								}
							}
						}
						
						break;
					case "Search User":
						if($stmt = mysqli_prepare($conn, "SELECT * FROM employee WHERE username = ?")){
							mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);
							$row = mysqli_fetch_array($result, MYSQLI_NUM);
							mysqli_stmt_close($stmt);
							if( $row > 0 ){
								$_SESSION['empTypeSel'] = "searchUser";
								$_SESSION['empSel'] = $_POST['empSelect'];
								$_SESSION['usernameSel'] = $_POST['username'];
								$_SESSION['sqlResult'] = $result;
								header("Location: adminOptionsCont.php");
							// 	echo "User Found: <br><br>"
								// echo "<table>\n";
						  //       	echo "\t<tr>\n";
						  //       	while( $colHeaders = mysqli_fetch_field($result)){
						  //           		echo "\t\t<th>".$colHeaders->name."</th>\n";
        //        							}
						  //       	echo "\t</tr>\n";
								// echo "\t<tr>\n";
        //     					foreach($row as $value){
        //             					echo "\t\t<td>".$value."</td>\n";
        //     					}
							// 	echo "<br>What to Edit?"."<br>";
							// 	echo "<input type='submit' name='submit2' value='Reset Password'> <br>";
							// 	echo "<input type='submit' name='submit2' value='Change Username'> <br>";
							// 	echo "<input type='submit' name='submit2' value='Delete User'> <br>";
							// 	switch($_POST['empSelect']){
							// 		case "engineer":
							// 			echo "<input type='checkbox' name='userEditList' value='userStatus'>Status <br>";
							// 			echo "<input type='checkbox' name='userEditList' value='userHours'>Hours <br>";
							// 			echo "<input type='checkbox' name='userEditList' value='userRank'>Rank <br><br>";
							// 			break;
							// 		case "conductor":
							// 			echo "<input type='checkbox' name='userEditList' value='userRank'>Rank <br><br>";	
							// 			break;	
							// 	}
							// 	echo "<input type='submit' name='submit2' value='Make User Changes'>";
							}
							else{
								echo "<br><br>Username NOT Found".PHP_EOL;
							}
						}	
						break;
					case "Search Customer":
						if($stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE companyID = ?")){
							mysqli_stmt_bind_param($stmt, "s", $_POST['customerID']);
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);
							$row = mysqli_fetch_array($result, MYSQLI_NUM);
							mysqli_stmt_close($stmt);
							if( $row > 0 ){
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
				                                echo "Edit Customer ID?"."<br>"; 
								echo "<input type='submit' name='submit' value='Yes'> <input type='submit' name='submit' value='No'> <br><br>";
							}
							else{
								echo "Customer ID NOT Found".PHP_EOL;
							}
						}
						break;
					case "Add Train":
						$trainNum = htmlspecialchars($_POST['trainNum'], ENT_QUOTES);
						$daysRun = '';
						if( !empty($_POST['daysRun'])){
							foreach($_POST['daysRun'] as $value){
								switch($value){
									case "mon":
										$daysRun = $daysRun."Mo";
										break;
									case "tue":
										$daysRun = $daysRun."Tu";
										break;
									case "wed":
										$daysRun = $daysRun."We";
										break;
									case "thr":
										$daysRun = $daysRun."Th";
										break;
									case "fri":
										$daysRun = $daysRun."Fr";
										break;
									case "sat":
										$daysRun = $daysRun."Sa";
										break;
									case "sun":
										$daysRun = $daysRun."Su";
										break;
									default:
										echo "Checkbox Error!";
										break;
								}
									
							}
						}
						$departTime = $_POST['departTime'];
						$arriveTime = $_POST['arriveTime'];
						$destCity = $_POST['destCity'];
						$departCity = $_POST['departCity'];
                        if($stmt = mysqli_prepare($conn, "SELECT * FROM train WHERE trainNumber = ?")){
                            mysqli_stmt_bind_param($stmt, 's', $trainNum);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_array($result, MYSQLI_NUM);
                            mysqli_stmt_close($stmt);
                            if( $row > 0 ){
                                    echo "<br><br>Train Number Already Exists".PHP_EOL;
                            }
                            else{
                                if($stmt = mysqli_prepare($conn, "INSERT INTO train VALUES(?,?,?,?,?,?)")){
                                        mysqli_stmt_bind_param($stmt, "isssss", $trainNum, $daysRun, $departTime, $arriveTime, $destCity, $departCity);
										mysqli_stmt_execute($stmt);
                                        mysqli_stmt_close($stmt);
                                }
                                else{
                                        echo "Did NOT Run 2".PHP_EOL;
                                }
                        	}
                        }
						break;
					case "Add Car":
						// redirect to add page
						break;
					case "Search Train":
						if($stmt = mysqli_prepare($conn, "SELECT * FROM train WHERE trainNumber = ?")){
							mysqli_stmt_bind_param($stmt, "s", $_POST['trainNum']);
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);
							$row = mysqli_fetch_array($result, MYSQLI_NUM);
							mysqli_stmt_close($stmt);
							if( $row > 0 ){
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
                                echo "</table>";
                                echo "<br><br>What to Edit?"."<br>"; 
								echo "<input type='checkbox' name='trainEditList' value='trainNum'>Train Number <br>";
                                echo "<input type='checkbox' name='trainEditList' value='runDays'>Days <br>";
                                echo "<input type='checkbox' name='trainEditList' value='departTime'>Departure Time <br>";
                                echo "<input type='checkbox' name='trainEditList' value='arriveTime'>Arrival Time <br>";
								echo "<input type='checkbox' name='trainEditList' value='departCity'>Departure City <br>";
								echo "<input type='checkbox' name='trainEditList' value='destCity'>Destination City <br>";
                                echo "<input type='submit' name='submit2' value='Make Train Changes'>";	
							}
							else{
								echo "<br><br>Train ID NOT Found".PHP_EOL;	
							}
						}
						break;
					case "Search Car";
						if($stmt = mysqli_prepare($conn, "SELECT * FROM equipment WHERE equipmentID = ?")){
							mysqli_stmt_bind_param($stmt, "s", $_POST['carNum']);
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);
							$row = mysqli_fetch_array($result, MYSQLI_NUM);
							mysqli_stmt_close($stmt);
							if( $row > 0 ){
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
                                                                echo "What to Edit?"."<br>"; 
								echo "<input type='checkbox' name='carEditList' value='serialNum'>Serial Number <br>";
								echo "<input type='checkbox' name='carEditList' value='loadCap'>Load Capacity <br>";
								echo "<input type='checkbox' name='carEditList' value='carType'>Type of Car <br>";
								echo "<input type='checkbox' name='carEditList' value='manufac'>Manufacturer <br>";
								echo "<input type='checkbox' name='carEditList' value='carPrice'>Price <br>";
								echo "<input type='checkbox' name='carEditList' value='cargoType'>Type of Cargo <br>";
								echo "<input type='checkbox' name='carEditList' value='location'>Location <br>";
								echo "<input type='checkbox' name='carEditList' value='trainNumber'>Train Number <br>";
								echo "<input type='checkbox' name='carEditList' value='customer'>Customer <br>";
								echo "<input type='checkbox' name='carEditList' value='departDate'>Departure Date <br>";
								echo "<input type='checkbox' name='carEditList' value='arrivalDate'>Arrival Date  <br>";
								echo "<input type='submit' name='submit2' value='Make Car Changes'>";
							}
							else{
								echo "Car Serial Number NOT Found".PHP_EOL;	
							}
						}
						break;
				}
			
			}

			if( isset($_POST['submit2'])){

				switch($_POST['submit2']){
					// case "Add Engineer":
					// 	$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
					// 	$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
					// 	if($_POST['radio'] == 'active'){
					// 		$status = 1;
					// 	}
					// 	else{
					// 		$status = 0;
					// 	}
					// 	$hours = $_POST['hours'];
					// 	$rank = $_POST['selRank'];

					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO users (id) VALUES(DEFAULT)")){
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO employee VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?)")){
					// 		mysqli_stmt_bind_param($stmt, 's', $username);
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO engineer (id, status, hours, engineerRank) VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?, ?, ?)")){
					// 		mysqli_stmt_bind_param($stmt, 'iis', $status, $hours, $rank);
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO authentication (password,role,username) VALUES (?,'Engineer',?)")){
					// 		mysqli_stmt_bind_param($stmt, 'ss', $password, $username);
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	break;
					// case "Add Conductor":
					// 	$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
					// 	$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
					// 	$rank = $_POST['conSelect'];

					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO users (id) VALUES(DEFAULT)")){
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO employee VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?)")){
					// 		mysqli_stmt_bind_param($stmt, 's', $username);
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO conductor (id, conductorRank) VALUES((SELECT * FROM users ORDER BY id DESC LIMIT 1), ?, ?, ?)")){
					// 		mysqli_stmt_bind_param($stmt, 's', $rank);
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	if($stmt = mysqli_prepare($conn, "INSERT INTO authentication (password,role,username) VALUES (?,'Conductor',?)")){
					// 		mysqli_stmt_bind_param($stmt, 'ss', $password, $username);
					// 		mysqli_stmt_execute($stmt);
					// 		mysqli_stmt_close($stmt);
					// 	}
					// 	break;
					case "Make User Changes":
						break;
					case "Reset Password":
						break;
					case "Change Username":
						echo "Enter New Username: ";
						echo "<input type='text' name='newUsername' placeholder='New Username' required> <br><br>";
						echo "<input type='submit' name='submit3' value='Change Username'>";
						break;
					case "Delete User":
						break;
				}
			}

			if( isset($_POST['submit3'])){
				switch($_POST['submit3']){
					case "Change Username":
						if($stmt = mysqli_prepare($conn, "UPDATE employee SET username = ? WHERE username = ?")){
							mysqli_stmt_bind_param($stmt, "ss", $_POST['newUsername'], $_POST['username']);
							mysqli_stmt_execute($stmt);

						}
						else{
							echo "Change User Name Failed";
						}
						break;
				}
			}


			
		?>
		<br><br><a href="admin.php">Admin Home</a> <br>
	</form>

</body>
</html>
