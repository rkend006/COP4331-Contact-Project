
<?php
  $inData = getRequestInfo();

  $FirstName = $inData["FirstName"];
  $lastName = $inData["LastName"];
  $email = $inData["Email"];
  $phoneNumber = $inData["PhoneNumber"];
  $address = $inData["Address"];
  $userID = $inData["UserID"];


  $conn = new mysqli("localhost", "Group15Admin", "ByVivec", "COP4331");
  if( $conn->connect_error )
  {
    returnWithError( $conn->connect_error );
  }
  else
  {
    $stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Email, PhoneNumber, Address, UserID) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $FirstName, $LastName, $Email, $PhoneNumber, $Address, $UserID);
    $stmt->execute();
    $lastID = $conn->insert_id;

    if( $row = $insert_id->fetch_assoc() )
		{
			returnWithInfo( $row['ID'], $row['FirstName'], $row['LastName'], $row['Email'],
       $row['PhoneNumber'],  $row['Address'] );
		}
		else
		{
			returnWithError("Bad Input Syntax");
		}

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
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson( $retValue );
  }

  function returnWithInfo( $ID, $FirstName, $LastName, $Email, $PhoneNumber, $Address )
	{
		$retValue = '{"ID":' . $ID . ',"FirstName":"' . $FirstName . '","LastName":"' .
      $LastName . '","Email":"' . $Email . '","PhoneNumber":"' . $PhoneNumber .
      '","Address":"' . $Address . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
