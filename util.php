<?php
//
//Generic functions
//
//
// Retrieve ID
//
function utilGetId($request)
{
   return $request->getAttribute('partner_id');
}
//
// Validate ID
//
function utilValidId($p_id)
{
	if((is_null($p_id)) or ($p_id==0) )
	{
		return false;
	}
	else
	{
		return true;
	}
}
//
// Retrieve data for record
//
function utilDataForRecord($request,&$p_document, &$p_tradingname, &$p_ownername, &$p_lat, &$p_long, &$p_coverarea)
{
	$p_document    = $request->getParsedBody()['document'];
	$p_tradingname = $request->getParsedBody()['tradingname'];
	$p_ownername   = $request->getParsedBody()['ownername'];
	$p_lat         = $request->getParsedBody()['lat'];
	$p_long        = $request->getParsedBody()['long'];
	$p_coverarea   = $request->getParsedBody()['coverarea'];
	return 1;
}
//
// Validate data for record
//
function utilValidDataForRecord($p_document, $p_tradingname, $p_ownername, $p_lat, $p_long, $p_coverarea, &$p_data)
{
	$continue = 1;
	if(!isset($p_document) or empty($p_document))
	{
		$p_data[] = "Document is requerid";
		$continue = 0;
	}
	if(!isset($p_tradingname) or empty($p_tradingname))
	{
		$p_data[] = "Trading name is requerid";
		$continue = 0;
	}
	if(!isset($p_ownername) or empty($p_ownername))
	{
		$p_data[] = "Owner name is requerid";
		$continue = 0;
	}
	if(!isset($p_lat) or empty($p_lat))
	{
		$p_data[] = "Latitud is requerid";
		$continue = 0;
	}
	if(!isset($p_long) or empty($p_long))
	{
		$p_data[] = "Longitud is requerid";
		$continue = 0;
	}
	if(!isset($p_coverarea) or empty($p_coverarea))
	{
		$p_data[] = "Cover area is requerid";
		$continue = 0;
	}
	return $continue;
}
//
// Valid document unique key 
//
function utilIdByDocument($p_connection,$p_document)
{
	$id =  0;
	$query = "SELECT id FROM pdv WHERE document = :document";
	$stmt = $p_connection->prepare($query);
	$stmt->bindParam("document",$p_document);
	$stmt->execute();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$id = $row['id'];
	}
	return $id;
}
//
// Generate next ID
//
function utilGenNextId()
{
	$max_id = 0;
	$query = "SELECT MAX(id) as MAX_ID FROM pdv";
	$stmt = $connection->prepare($query);
	$stmt->execute();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$max_id = $row['MAX_ID'];
	}
	$next_id = $max_id + 1;
	return $next_id;
}
//
// Retrieve nearest partner
//
function utilNearestPartner($request, &$p_data)
{
	$v_id = 0;
	$coord = $request->getAttribute('coord');
	if(!isset($coord) or empty($coord))
	{
		$p_data[] = "Lat/Long parameter is requerid";
		$continue = 0;
	}
	else
	{
		require_once('db.php');
		//
		// For use in other database who implement more sophisticated math funcions
		/*
		$query = "SELECT id "
	       . "     , ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( long ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS DISTANCE "
           . "  FROM pdv "
		   . "ORDER BY distance ";
		*/
		//
		// 
		$query = "SELECT id, lat, long FROM pdv ";
		$stmt = $connection->prepare($query);
		$stmt->execute();
		$v_minor_distance = 999999999;
		$v_id = 0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$v_lat  = $row['lat'];
			$v_long = $row['long'];
			$v_distance = ( 3959 * acos( cos( deg2rad(37) ) * cos( deg2rad( $v_lat ) ) * cos( deg2rad( $v_long ) - deg2rad(-122) ) + sin( deg2rad(37) ) * sin( deg2rad( $v_lat ) ) ) );
			if($v_distance < $v_minor_distance)
			{
				$v_id = $row['id'];
				$v_minor_distance = $v_distance;
			}
			var_dump($v_lat,$v_long,$v_distance,$v_minor_distance);
			
		}
		//
		if($v_id >0)
		{
			$query = "SELECT * FROM pdv WHERE id = :get_id ";
			$stmt = $connection->prepare($query);
			$stmt->bindParam("get_id",$v_id);
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p_data[] = $row;
			}
		}
		if(empty($p_data))
		{
			$p_data[] = "No data found";
		}	
	}
	return $v_id;
}
?>