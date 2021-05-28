<?php

	$inData = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("localhost", "Group15Admin", "ByVivec", "COP4331");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT '*' FROM Contacts WHERE FirstName LIKE ?  AND LastName LIKE  ?  AND UserID = ? ");
		$stmt->bind_param("ssi", $FirstName, $LastName, $inData["userID"]);
		$FirstName = "'%" . $inData["searchFirst"] . "%'";
		$LastName = "'%" . $inData["searchLast"] . "%'";
		$stmt->execute();

		if($result = $stmt->get_result())
		{
			while($row = $result->fetch_assoc())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
				}
				$searchCount++;
				$searchResults .= '"' . $row["FirstName"]. ' ' . $row["LastName"]. ' ' . $row["ID"] . '"';
			}

			if( $searchCount == 0 )
			{
				$debugger = $FirstName . $LastName . $inData["userID"];
				returnWithError( $debugger);
			}
			else
			{
				returnWithInfo( $searchResults );
			}
		}

		else returnWithError( $stmt->error );

		

		$stmt->close();
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"ID":0,"FirstName":"","LastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results": ['.$searchResults.'] ,"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
