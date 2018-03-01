<?php
		function trainHistory($employeeTitle, $username, $link) {
			$employeeTitle = strtolower($employeeTitle);
			$sql = "SELECT " . $employeeTitle . "TrainHistory.trainNumber, ". $employeeTitle . "TrainHistory.startDate, " . $employeeTitle . "TrainHistory.endDate, train.days, train.departureTime, train.arrivalTime, train.destinationCity, train.departureCity FROM train, " . $employeeTitle  . "TrainHistory, " . $employeeTitle .", employee WHERE train.trainNumber = " . $employeeTitle  . "TrainHistory.trainNumber AND " . $employeeTitle . "TrainHistory.id = " . $employeeTitle  . ".id AND " . $employeeTitle  . ".id = employee.id AND employee.username = ?";
			if ($stmt = mysqli_prepare($link, $sql)) {
				$username = htmlspecialchars($_SESSION['username']);
				mysqli_stmt_bind_param($stmt, "s", $username);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				echo "<table id='tableID' style='cursor: pointer;'>";
				while ($fieldInfo = mysqli_fetch_field($result)){
				    echo "<th>" . $fieldInfo->name . "</th>";
				}

				echo "<br>";
	
				while ($row = $result->fetch_array(MYSQLI_NUM)){
				    echo "<tr>";
				    foreach($row as $value){
			        	echo "<td>  " . $value . "</td>";
				    }
				    echo "</tr>";
				}

				echo "</table>";
				mysqli_stmt_close($stmt);
			}
			else {
				echo "There was an error." . PHP_EOL;
			}	
		}
?>
