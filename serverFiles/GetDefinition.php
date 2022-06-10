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
	$blacklistA = $_POST['blacklistA']; //returns -1 if not in use
	$blacklistB = $_POST['blacklistB']; //returns -1 if not in use


	//Set correct database string
	$dataKind = 'player';
	if($isPersonal == 0){
		$dataKind = 'official';
	}	
	$mainDatabase = $game . '_' . $dataKind . 'DefData';
	$ratingDatabase = $game . '_' . $dataKind . 'Ratings';

	//Run an algorithm that finds a definition that would be worth showing? (Just random for now)
	//Exclude blacklisted IDs from form
	$randomIDquery = "SELECT * FROM $mainDatabase WHERE id <> $blacklistA AND id <> $blacklistB ORDER BY RAND() LIMIT 1;";

	//Check if the algorithm picked a definition that exists
	$idResult = mysqli_query($con, $randomIDquery) or die("2: Did not get random ID"); //error code #2 random id query failed

	//extract info from query
	$existingInfo = mysqli_fetch_assoc($idResult);
	$index = $existingInfo["id"];
	$definition = $existingInfo["definition"];
	$source = $existingInfo["source"];
	$averageRatingX = $existingInfo["averageRatingX"];
	$averageRatingY = $existingInfo["averageRatingY"];
	$location = $existingInfo["location"];
	$privacyCheck = $existingInfo["privacyCheck"];
	$result = $existingInfo["result"];

	// --- (Re)calculate average rating ---
	$getRatingsQuery = "SELECT ratingX, ratingY FROM $ratingDatabase WHERE id=$index;";

	//Check if the query returns any ratings
	$ratingResult = mysqli_query($con, $getRatingsQuery) or die("3: ratingQuery for this definition ID: ~ " . $index . " ~ failed"); //error code #3 rating query for this id failed
	if(mysqli_num_rows($ratingResult) == 0){
		echo "7: No ratings found for this definition ID: " . $index; //error code #7 this id has no ratings in the database
		exit();
	}

	$totalRatingsX = 0;
	$totalRatingsY = 0;

	//add all existing ratings together from query
	while ($row = mysqli_fetch_assoc($ratingResult)) {
		$totalRatingsX += $row["RatingX"];
		$totalRatingsY += $row["RatingY"];
	}

	//divide by number of ratings (rows)
	$averageRatingX = $totalRatingsX / mysqli_num_rows($ratingResult);
	$averageRatingY = $totalRatingsY / mysqli_num_rows($ratingResult);

	//Spit info
	echo "0" . "\t" . $index . "\t" . $definition . "\t" . $source . "\t" . $averageRatingX . "\t" . $averageRatingY . "\t" . $location . "\t" . $privacyCheck . "\t" . $result;

	mysql_close($con);
?>