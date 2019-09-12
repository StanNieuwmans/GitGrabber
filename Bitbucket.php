<?php

// fill in the username of bitbucket
$usernameofbitbucket = 'StanNieuwmans';


// sort a array with dates
function date_sort( $a, $b ) {
	return strtotime( $a ) - strtotime( $b );
}

include 'APIinSQL.php';
$call = new APIinSQL();
// get repos from account and foreach the info of the repojson.
$jsonrepos = json_decode( file_get_contents( 'https://api.bitbucket.org/2.0/repositories/' . $usernameofbitbucket . '' ) );
foreach ( array_reverse( $jsonrepos->values ) as $username ) {


	// get all tags from all the repos and foreach everything
	$jsontags = json_decode( file_get_contents( 'https://api.bitbucket.org/2.0/repositories/' . $usernameofbitbucket . '/' . $username->slug . '/refs/tags' ) );
	foreach ( $jsontags->values as $key ) {
		$tagarray[] = $key->name;
	}
	$reversedtagarray = array_reverse( $tagarray );


	foreach ( $jsontags->values as $row ) {
		// check if the tag has a stable in it
		if ( strpos( $reversedtagarray[1], 'Stable' ) !== false ) {


			// put every date in array
			$array[] = $row->date;

			// sort the array with the function
			usort( $array, "date_sort" );

			// get the array the way you want it (cant explain this one (but it works))!
			$lastEl = array_reverse( array_values( array_slice( $array, - 1 ) ) )[0];

			//put the latest date from all commits
			$timeoflatestcommit = strtotime( $lastEl );

			//every date of every commit
			$timeofcommit = strtotime( $row->date );

			//checks if the date of the commit is the same/lower than as all the commits dates
			if ( $timeofcommit <= $timeoflatestcommit ) {

				//get all the data of the commits
				$jsonstabletag = json_decode( file_get_contents( 'https://api.bitbucket.org/2.0/repositories/' . $usernameofbitbucket . '/' . $username->slug . '/commits/' . $reversedtagarray[1] . '' ) );


				//and foreach all the data!
				foreach ( $jsonstabletag->values as $data ) {
					$reponamep['Name'][]  = $data->repository->name;
					$linktorepo['Link'][] = $data->repository->links->html->href;
					$img['img'][]         = $data->repository->links->avatar->href;
					$summary['summary'][] = $data->summary->raw;
					$reversedtagarray[1];
					unset( $reversedtagarray );
					break;
				}
				break;
			}
//			break;
		}
	}
}
$rat[] = array_merge( $reponamep, $linktorepo, $img, $summary );


$call->OpenConnection();
$conn  = $call->getConn();
$query = $conn->prepare( 'INSERT INTO bitbucket (`reponame`, `linktorepo`, `imglink` , `summary`) VALUES (:reponame,:linktorepo,:imglink,:summary)' );
//$deletetable = $conn->prepare( 'TRUNCATE TABLE bitbucket_api');
//$deletetable->execute();
foreach ( $rat as $reponames ) {
	$numberinarrays = count($reponames['Name']);
	for ($x = -0; $x <= $numberinarrays -1; $x++) {
	$query->bindParam( ":reponame", $reponames['Name'][$x] );
	$query->bindParam( ":linktorepo", $reponames['Link'][$x] );
	$query->bindParam( ":imglink", $reponames['img'][$x] );
	$query->bindParam( ":summary", $reponames['summary'][$x] );
//	$query->bindParam( ":tag", $reversedtagarray[$x] );
	$query->execute();
	}
}


//	$query->fetchAll( PDO::FETCH_ASSOC );


//var_dump( $linktorepo );
//var_dump( $img );
//var_dump( $summary );

https://stackoverflow.com/questions/21612933/insert-array-of-values-into-a-database-using-sql-query
?>
