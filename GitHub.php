<?php

// fill in the username of bitbucket
$usernameofbitbucket = 'StanNieuwmans';


// sort a array with dates
function date_sort( $a, $b ) {
    return strtotime( $a ) - strtotime( $b );
}
include 'APIinSQL.php';
$call = new APIinSQL();
$call->OpenConnection();
$conn  = $call->getConn();
$query = $conn->prepare( 'INSERT INTO github (`reponame`, `linktorepo` , `summary`) VALUES (:reponame,:linktorepo,:summary)' );




//$deletetable = $conn->prepare( 'TRUNCATE TABLE bitbucket_api');
//$deletetable->execute();

//Adding header to request
$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
$context = stream_context_create($opts);

// get repos
$jsonrepos = json_decode(file_get_contents('http://api.github.com/users/' . $usernameofbitbucket .'/repos',false,$context),true);

foreach ($jsonrepos as $repo ) {



        $query->bindParam( ":reponame", $repo['full_name'] );
        $query->bindParam( ":linktorepo", $repo['url'] );
        $query->bindParam( ":summary", $repo['description'] );
        $query->execute();
}

?>
