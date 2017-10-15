<?php

class ResultModel {

	private $id;
	private $position;
	private $member;
	private $event;

	/**
	 * ResultModel constructor.
	 * Creates a new ResultModel with the defined attributes.
	 *
	 * @param position - The position the player came.
	 * @param $member - The member this result is for.
	 * @param $event - The event this result is for.
	 * @param $boardgame - The boardgame this result is for.
	 * @param $id int - The results ID.
	 */
	function __construct( int $position, MemberModel $member, EventModel $event, int $id = null ) {
		$this->position = $position;
		$this->member   = $member;
		$this->event    = $event;
		if ( $id != null ) {
			$this->id = $id;
		}
	}


	/**
	 * Saves (inserts) the result into the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function save() {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the result.
		$statement = $pdo->prepare( "INSERT INTO results (position, member_id, event_id) VALUES (:position, :member_id, :event_id)" );
		// Bind the named parameters to the statement.
		$statement->bindValue( ":position", $this->position );
		$statement->bindValue( ":member_id", $this->member->getId() );
		$statement->bindValue( ":event_id", $this->event->getId() );


		// Execute the statement to insert the result.
		return $statement->execute();
	}

	/**
	 * Deletes the result from the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public static function delete( $id ) {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the result.
		$statement = $pdo->prepare( "DELETE FROM results WHERE id=?" );

		// Execute the statement to insert the result.
		return $statement->execute( [ $id ] );
	}

	/**
	 * Updates an existing row in the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function update() {
		// Get PDO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the result.
		$statement = $pdo->prepare( "UPDATE results SET position = :position, member_id = :member_id, event_id = :event_id WHERE id = :id" );
		// Bind the named parameters to the statement.
		$statement->bindValue( ":position", $this->position );
		$statement->bindValue( ":member_id", $this->member->getId() );
		$statement->bindValue( ":event_id", $this->event->getId() );
		$statement->bindValue( ":id", $this->id );

		// Execute the statement to insert the result.
		return $statement->execute();
	}

	/**
	 * Finds a result in the database by matching their ID.
	 *
	 * @param $id integer - The Results ID number
	 */
	public static function findByID( $id ) {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "
		SELECT
			results.id,
			results.position, 
		    members.id AS member_id, 
		    members.first_name,
		    members.family_name,
		    members.email,
		    members.phone,
		    boardgames.id AS boardgame_id,
		    boardgames.name AS boardgame_name,
		    boardgames.description AS boardgame_description,
		    events.id AS event_id,
		    events.name AS event_name,
		    events.location,
		    events.datetime
		    FROM results 
		JOIN members ON members.id = results.member_id
		JOIN events ON events.id = results.event_id
		JOIN boardgames ON boardgames.id = events.boardgame_id
		WHERE results.id=?
		" );
		$statement->execute( [ $id ] );
		if ( $statement->rowCount() > 0 ) {
			$row      = $statement->fetch();
			$member    = new MemberModel( $row['first_name'], $row['family_name'], $row['email'], $row['phone'], $row['member_id'] );
			$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
			$event     = new EventModel( $row['event_name'], $row['location'], $row['datetime'], $boardgame, $row['event_id'] );

			return new ResultModel( $row['position'], $member, $event, $row['id'] );
		}
		else {
			throw new Exception( "Result not found", ROW_NOT_FOUND );
		}
	}

	public static function getAll() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "
		SELECT
			results.id,
			results.position, 
		    members.id AS member_id, 
		    members.first_name,
		    members.family_name,
		    members.email,
		    members.phone,
		    boardgames.id AS boardgame_id,
		    boardgames.name AS boardgame_name,
		    boardgames.description AS boardgame_description,
		    events.id AS event_id,
		    events.name AS event_name,
		    events.location,
		    events.datetime
		    FROM results 
		JOIN members ON members.id = results.member_id
		JOIN events ON events.id = results.event_id
		JOIN boardgames ON boardgames.id = events.boardgame_id
		" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$rows    = $statement->fetchAll();
			$results = [];
			foreach ( $rows as $row ) {
				$data      = $statement->fetch();
				$member    = new MemberModel( $row['first_name'], $row['family_name'], $row['email'], $row['phone'], $row['member_id'] );
				$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
				$event     = new EventModel( $row['event_name'], $row['location'], $row['datetime'], $boardgame, $row['event_id'] );

				$results[] = new ResultModel( $row['position'], $member, $event, $row['id'] );
			}

			return $results;
		}
		else {
			throw new Exception( "No Results Found", ROW_NOT_FOUND );
		}
	}


	public static function getResultsForEvent( int $event_id ) {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "
		SELECT
			results.id,
			results.position, 
		    members.id AS member_id, 
		    members.first_name,
		    members.family_name,
		    members.email,
		    members.phone,
		    boardgames.id AS boardgame_id,
		    boardgames.name AS boardgame_name,
		    boardgames.description AS boardgame_description,
		    events.id AS event_id,
		    events.name AS event_name,
		    events.location,
		    events.datetime
		    FROM results 
		JOIN members ON members.id = results.member_id
		JOIN events ON events.id = results.event_id
		JOIN boardgames ON boardgames.id = events.boardgame_id
		WHERE event_id = ?
		" );
		$statement->execute( [ $event_id ] );
		if ( $statement->rowCount() > 0 ) {
			$rows    = $statement->fetchAll();
			$results = [];
			foreach ( $rows as $row ) {
				$member    = new MemberModel( $row['first_name'], $row['family_name'], $row['email'], $row['phone'], $row['member_id'] );
				$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
				$event     = new EventModel( $row['event_name'], $row['location'], $row['datetime'], $boardgame, $row['event_id'] );

				$results[] = new ResultModel( $row['position'], $member, $event, $row['id'] );
			}

			return $results;
		}
		else {
			throw new Exception( "No Results Found", ROW_NOT_FOUND );
		}
	}

	/**
	 * @return int|null
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int|null $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}

	public function getPositionAsOrdinal(): string {
		$ends = array( 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' );
		if ( ( ( $this->position % 100 ) >= 11 ) && ( ( $this->position % 100 ) <= 13 ) ) {
			return $this->position . 'th';
		}
		else {
			return $this->position . $ends[ $this->position % 10 ];
		}
	}

	/**
	 * @param int $position
	 */
	public function setPosition( int $position ) {
		$this->position = $position;
	}

	/**
	 * @return MemberModel
	 */
	public function getMember(): MemberModel {
		return $this->member;
	}

	/**
	 * @param MemberModel $member
	 */
	public function setMember( MemberModel $member ) {
		$this->member = $member;
	}

	/**
	 * @return EventModel
	 */
	public function getEvent(): EventModel {
		return $this->event;
	}

	/**
	 * @param EventModel $event
	 */
	public function setEvent( EventModel $event ) {
		$this->event = $event;
	}


}