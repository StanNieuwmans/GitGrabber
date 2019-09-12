<?php
/**
 * Created by PHPStorm v. 2017.1.4
 * User: admin (Stijn Kluiters)
 * Date: 23-11-2018
 * Time: 10:56
 * Filename: APIinSQL.php
 */

include 'DBconf.php';

class APIinSQL extends DBconf {
	public function putreponameindb( $reponamearray ) {
		$this->OpenConnection();
		$conn  = $this->getConn();
		try {
			foreach ( $reponamearray as $question_name ) {
				var_dump($question_name);
				$query = $conn->prepare( 'INSERT INTO bitbucket_api (`reponame`) VALUES (:question_name)' );
				$query->bindParam( ':question_name', $question_name );
				$query->execute();
				$query->fetchAll( PDO::FETCH_ASSOC );
			}
		} catch ( PDOException $e ) {
			$this->CloseConnection();
			return $e;
		}

	}
}
