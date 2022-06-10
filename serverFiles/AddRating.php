<?PHP

	$con = mysqli_connect('localhost', 'leonvanoldenborg', 'aizi5vaC4e', 'leonvanoldenborg');

	//check if we can reach the database
	if(mysqli_connect_errno()){
		echo "1: Connection failed"; //error code #1 = connection failed
		exit();
	}

	//Get all info from unity form
	$game = mysqli_real_escape_string($con, $_POST['game']);
	$isPersonal = $_POST['isPersonal']; //0 = official, 1 = personal
	$index = $_POST['index']; 
	$ratingX = $_POST['ratingX']; 
	$ratingY = $_POST['ratingY']; 
	$privacy = $_POST['privacy'];

	//Make sure we are allowed to upload the data
	if($privacy == 0){
		echo "6: privacy check not passed";
		exit();
	}

	//Set correct database string
	$dataKind = 'player';
	if($isPersonal == 0){
		$dataKind = 'official';
	}	
	$ratingDatabase = $game . '_' . $dataKind . 'Ratings';

	//Add rating to correct link table
	$addRatingQuery = "INSERT INTO $ratingDatabase(id, ratingX, ratingY) VALUES ($index, $ratingX, $ratingY);";
	$addRatingResult = mysqli_query($con, $addRatingQuery) or die("5: addRating query failed");

	echo("0"); //Rating addition succesful

	mysql_close($con);
?>