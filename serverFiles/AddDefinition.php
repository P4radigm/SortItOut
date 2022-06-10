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
	$definition = mysqli_real_escape_string($con, $_POST['definition']); //care for security
	$source = mysqli_real_escape_string($con, $_POST['source']); //care for security
	$cleanSource = filter_var($source, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
	$initialRatingX = $_POST['initialRatingX'];
	$initialRatingY = $_POST['initialRatingY'];
	$location = mysqli_real_escape_string($con, $_POST['location']); //care for security
	$privacy = $_POST['privacy'];
	$gameResult = mysqli_real_escape_string($con, $_POST['gameResult']); //care for security

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
	$mainDatabase = $game . '_' . $dataKind . 'DefData';
	$ratingDatabase = $game . '_' . $dataKind . 'Ratings';

	//Add definition to main data base
	$addDefinitionQuery = "INSERT INTO $mainDatabase(definition, source, averageRatingX, averageRatingY, location, privacyCheck, result) VALUES ('" . $definition . "', '" . $cleanSource . "', $initialRatingX, $initialRatingY, '" . $location . "', $privacy, '" . $gameResult . "');";
	$addDefinitionResult = mysqli_query($con, $addDefinitionQuery) or die("4: addDefintion query failed");

	//Get ID of definition we just added to the database
	$ratingID = mysqli_insert_id($con);

	//Add initial rating to the link table
	$addInitialRatingQuery = "INSERT INTO $ratingDatabase(id, ratingX, ratingY) VALUES ($ratingID, $initialRatingX, $initialRatingY);";
	$addInitialRatingResult = mysqli_query($con, $addInitialRatingQuery) or die("5: addRating query failed");

	echo "0"; //Definition addition succesful

	mysql_close($con);
?>