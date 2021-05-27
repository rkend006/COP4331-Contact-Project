
<?php
  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $email = $inData["email"];
  $phoneNumber = $inData["phoneNumber"];
  $address = $inData["address"];
  $userID = $inData["userID"];


  $conn = new mysqli("localhost", "Group15Admin", "ByVivec", "COP4331");
  if( $conn->connect_error )
  {
    returnWithError( $conn->connect_error );
  }
  else
  {
    $stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Email, PhoneNumber, Address, UserID) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phoneNumber, $address, $userID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    returnWithError("");
    echo "Contact created successfully.";
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
?>
