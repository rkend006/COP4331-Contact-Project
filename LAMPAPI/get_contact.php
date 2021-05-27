
<?php
  $inData = getRequestInfo();

  $ID = $inData["ID"];
  $firstName = "";
  $lastName = "";
  $email = "";
  $phoneNumber = "";
  $address = "";

  $conn = new mysqli("localhost", "Group15Admin", "ByVivec", "COP4331");
  if( $conn->connect_error )
  {
    returnWithError( $conn->connect_error );
  }
  else
  {
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE ID = ?");
    $stmt->bind_param("s", $ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if( $row = $result->fetch_assoc() )
		{
			returnWithInfo( $row['ID'], $row['firstName'], $row['lastName'], $row['email'],
       $row['phoneNumber'],  $row['address'] );
		}
		else
		{
			returnWithError("No Records Found");
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

  function returnWithInfo( $ID, $firstName, $lastName, $email, $phoneNumber, $address )
	{
		$retValue = '{"id":' . $ID . ',"firstName":"' . $firstName . '","lastName":"' .
      $lastName . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber .
      '","address":"' . $address . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
