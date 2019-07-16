<?php 
require 'vendor/autoload.php';
$app = new Slim\App();
require_once('util.php');
//
//Retrieve all data
//
$app->get('/partner', function() 
{
	require_once('db.php');
	$query = "select * from pdv order by id";
	$result = $connection->query($query);
	$data = array();	
	while ($row = $result->fetch(PDO::FETCH_ASSOC))
	{
		$data[] = $row;
	}
	if(empty($data))
	{
		$data[] = "No data found";
	}
	echo json_encode($data);	
});
//
// Retrieve a specific partner by id
//
$app->get('/partner/{partner_id}', function($request)
{
	$continue = 1;
	$get_id = utilGetId($request);
	if(!utilValidId($get_id))
	{
		$data[] = "Partner_id is required.";
		$continue = 0;
	}
	else
	{
		require_once('db.php');
		$query = "SELECT * FROM pdv WHERE id = :get_id ";
		$stmt = $connection->prepare($query);
		$stmt->bindParam("get_id",$get_id);
		$stmt->execute();
		$data = array();	
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$data[] = $row;
		}
		if(empty($data))
		{
			$data[] = "No data found";
		}
	}
	echo json_encode($data);
});
	
//
// Create PDV
//
$app->post('/partner', function($request)
{
	$continue = 1;
	//
	$continue = utilDataForRecord($request,$document, $tradingname, $ownername, $lat, $long, $coverarea);
	$data = array();
	$continue = utilValidDataForRecord($document, $tradingname, $ownername, $lat, $long, $coverarea, $data);
	//
	if($continue==1)
	{
		require_once('db.php');
		//
		$id = utilIdByDocument($connection,$document);
		//
		if($id > 0)
		{
			$data[] = "Document is alredy registered for id = " . $id;
			$continue = 0;
		}
		else
		{
			$next_id = utilGenNextId();
			//
			$query = "INSERT INTO pdv (id, document, tradingname, ownername, lat, long, coverarea) VALUES (:next_id,:document,:tradingname,:ownername,:lat,:long,:coverarea)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam("next_id",$next_id);
			$stmt->bindParam("document",$document);
			$stmt->bindParam("tradingname",$tradingname);
			$stmt->bindParam("ownername",$ownername);
			$stmt->bindParam("lat",$lat);
			$stmt->bindParam("long",$long);
			$stmt->bindParam("coverarea",$coverarea);
			$stmt->execute();
			$data[] = "Sucess, record added";
		}
	}
	echo json_encode($data);
});
//
// Update PDV
//
$app->put('/partner/{partner_id}', function($request)
{
	$continue = 1;
	$get_id = utilGetId($request);
	if(!utilValidId($get_id))
	{
		$data[] = "Partner_id is required.";
		$continue = 0;
	}
	else
	{
		//
		$document    = "";
		$tradingname = "";
		$ownername   = "";
		$lat         = "";
		$long        = "";
		$coverarea   = "";
		//
		$continue = utilDataForRecord($request,$document, $tradingname, $ownername, $lat, $long, $coverarea);
		$data = array();
		$continue = utilValidDataForRecord($document, $tradingname, $ownername, $lat, $long, $coverarea, $data);
		//
		require_once('db.php');
		
		$query = "UPDATE pdv "
			   . "   SET document = :document "
			   . "     , tradingname = :tradingname "
			   . "     , ownername   = :ownername "
			   . "     , lat = :lat "
			   . "     , long = :long "
			   . "     , coverarea = :coverarea "
			   . " WHERE id = :get_id ";
		$stmt = $connection->prepare($query);
		$stmt->bindParam("get_id",$get_id);
		$stmt->bindParam("document",$document);
		$stmt->bindParam("tradingname",$tradingname);
		$stmt->bindParam("ownername",$ownername);
		$stmt->bindParam("lat",$lat);
		$stmt->bindParam("long",$long);
		$stmt->bindParam("coverarea",$coverarea);
		$stmt->execute();
		$data[] = "Sucess, record updated.";
	}
	//
	echo json_encode($data);
});	
//
// Retrieve a nearest partner
//
$app->get('/nearest/{coord}', function($request)
{
    $data = array();
	$nearest_id = utilNearestPartner($request, $data);
	echo json_encode($data);
});



$app->delete('/books/{book_id}', function($request){
 require_once('db.php');
 $get_id = $request->getAttribute('book_id');
 $query = "DELETE from library WHERE book_id = $get_id";
 $result = $connection->query($query);
});


//
$app->run();
//
?>