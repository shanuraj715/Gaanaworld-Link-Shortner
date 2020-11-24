<?php
include '../config.php';
include '../db.php';

$will_insert = true;
$array = [];
if(isset($_POST['url']) and !empty($_POST['url'])){
	$url = mysqli_real_escape_string($conn, $_POST['url']);
	if($data = removeDomainName( $url )){
		if( $shorted_url = getShortedURL( $data ) ){
			if( insertRecord( $shorted_url, $data ) ){
				$array['status'] = 'ok';
				$array['url'] = SITE_URL . 's/' . $shorted_url;
				$array['full_url'] = $url;
			}
			else{
				$array['status'] = 'failed';
				$array['error'] = 'Unable to generate Short URL. Insertion of record in database has been failed.';
			}
		}
		else{
			$array['status'] = 'failed';
			$array['error'] = 'Unable to generate short URL';
		}
	}
	else{
		$array['status'] = 'failed';
		$array['error'] = "Invaild URL. Shorten URL can only work on " . SITE_URL . " site and on Song page.";
	}
}
else{
	$array['status'] = 'failed';
	$array['error'] = 'Please enter an URL.';
}

echo json_encode($array);


function insertRecord( $shorted_url, $url ){
	global $will_insert;
	if($will_insert){
		global $conn;
		$sql = "INSERT INTO link_short(shorted, ref_page) VALUES( '$shorted_url', '$url' )";
		$query = mysqli_query($conn, $sql);
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return true;
	}
}


function getShortedURL( $url ){
	global $conn;
	$sql = "SELECT * FROM link_short WHERE ref_page = '$url'";
	$query = mysqli_query($conn, $sql);
	if($query){
		$row = mysqli_num_rows($query);
		if($row >= 1){
			$result = mysqli_fetch_assoc($query);
			$short_url = $result['shorted'];
			global $will_insert;
			$will_insert = false;
			return $short_url;
		}
		else{
			if( $short_url = generateShortURL( $url )){
				return $short_url;
			}
			else{

			}
		}
	}
	else{
		if($short_url = generateShortURL( $url )){
			return $short_url;
		}
		else{

		}
	}
}

function generateShortURL( $url ){
	global $conn;
	$characters_array = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '_'];

	$generated_url = '';
	$found = true;
	while( $found == true ){
		$generated_url = $characters_array[rand(0, 62)] . $characters_array[rand(0, 62)] . $characters_array[rand(0, 62)] . $characters_array[rand(0, 62)];

		$sql = "SELECT * FROM link_short WHERE shorted = '$generated_url'";
		$query = mysqli_query($conn, $sql);
		if($query){
			$row = mysqli_num_rows($query);
			if($row == 0){
				$found = false;
			}
			elseif($row == 1){
				$found = true;
			}
			else{
				$found = true;
			}
		}
		else{
			$found = false;
		}
	}
	return $generated_url;
}

function removeDomainName( $string ){
	$splitted = explode('/', $string);
	$flag = false;
	$url = '';
	foreach ($splitted as $key => $value) {
		if($value == 'song'){
			$flag = true;
		}
		if($flag == true){
			$url .= $value;
		}
		if($key < count($splitted) - 1 and $flag){
			$url .= '/';
		}
	}
	if($flag != true){
		return false;
	}
	else{
		return $url;
	}
}

?>