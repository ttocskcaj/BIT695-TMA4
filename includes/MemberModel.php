<?php

class MemberModel {

	private $id;
	private $first_name;
	private $family_name;
	private $email;
	private $phone;
	private $boardgames = null;

	/**
	 * MemberModel constructor.
	 * Creates a new MemberModel with the defined attributes.
	 *
	 * @param $first_name String - The members first name.
	 * @param $family_name String - The members family/last name.
	 * @param $email String - The members email address.
	 * @param $phone String - The members phone number.
	 * @param $id int - The members ID.
	 */
	function __construct( $first_name, $family_name, $email, $phone, $id = null ) {
		$this->first_name  = $first_name;
		$this->family_name = $family_name;
		$this->email       = $email;
		$this->phone       = $phone;
		if ( $id != null ) {
			$this->id = $id;
		}
	}

	/**
	 * Saves (inserts) the user into the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function save() {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the member.
		$statement = $pdo->prepare( "INSERT INTO members (first_name, family_name, email, phone) VALUES (:first_name, :family_name, :email, :phone)" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":first_name", $this->first_name );
		$statement->bindParam( ":family_name", $this->family_name );
		$statement->bindParam( ":email", $this->email );
		$statement->bindParam( ":phone", $this->phone );

		// Execute the statement to insert the member.
		$success  = $statement->execute();
		$this->id = $pdo->lastInsertId();

		return $success;
	}

	/**
	 * Deletes the user from the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public static function delete( $id ) {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the member.
		$statement = $pdo->prepare( "DELETE FROM members WHERE id=?" );

		// Execute the statement to insert the member.
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

		// Create a prepared statement for the query to insert the member.
		$statement = $pdo->prepare( "UPDATE members SET first_name = :first_name, family_name = :family_name, email = :email, phone = :phone WHERE id = :id" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":first_name", $this->first_name );
		$statement->bindParam( ":family_name", $this->family_name );
		$statement->bindParam( ":email", $this->email );
		$statement->bindParam( ":phone", $this->phone );
		$statement->bindParam( ":id", $this->id );

		// Execute the statement to insert the member.
		return $statement->execute();
	}

	/**
	 * Finds a member in the database by matching their ID.
	 *
	 * @param $id String - The Members ID number
	 */
	public static function findByID( $id ) {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, first_name, family_name, email, phone FROM members  WHERE id=?" );
		$statement->execute( [ $id ] );
		if ( $statement->rowCount() > 0 ) {
			$member_data = $statement->fetch();

			return new MemberModel( $member_data['first_name'], $member_data['family_name'], $member_data['email'], $member_data['phone'], $member_data['id'] );
		}
		else {
			throw new Exception( "Member not found", ROW_NOT_FOUND );
		}
	}

	public static function getAll() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, first_name, family_name, email, phone FROM members" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$member_results = $statement->fetchAll();
			$members        = [];
			foreach ( $member_results as $member ) {
				$members[] = new MemberModel( $member['first_name'], $member['family_name'], $member['email'], $member['phone'], $member['id'] );
			}

			return $members;
		}
		else {
			throw new Exception( "No Members Found", ROW_NOT_FOUND );
		}
	}

	// Lazy load the joined boardgames.
	private function loadBoardgames() {
		$pdo       = Database::getPdo();
		$statement = $pdo->prepare( "SELECT boardgames.id, boardgames.name, boardgames.description FROM members_boardgames JOIN boardgames ON members_boardgames.boardgame_id = boardgames.id WHERE members_boardgames.member_id=?" );
		$statement->execute( [ $this->id ] );
		if ( $statement->rowCount() > 0 ) {
			$results = $statement->fetchAll();
			foreach ( $results as $result ) {
				$this->boardgames[] = new BoardgameModel( $result['name'], $result['description'], $result['id'] );
			}
		}

	}

	public function syncBoardgames( $boardgames ) {
		$pdo = Database::getPdo();

		try {
			// Delete existing boardgame links.
			$statement = $pdo->prepare( "DELETE FROM members_boardgames WHERE member_id = ?" );
			$statement->execute( [ $this->id ] );

			$statement = $pdo->prepare( "INSERT INTO members_boardgames (member_id, boardgame_id) VALUES (:member_id, :boardgame_id)" );
			$pdo->beginTransaction();
			foreach ( $boardgames as $boardgame ) {
				$statement->bindParam( ":member_id", $this->id );
				$statement->bindParam( ":boardgame_id", $boardgame );
				$statement->execute();
			}
			$pdo->commit();
		} catch ( PDOException $e ) {
			// If anything failed, rollback the changes.
			$pdo->rollBack();
			// Throw the exception again to be caught somewhere else.
			throw $e;
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
	 * @return String
	 */
	public function getFirstName(): String {
		return $this->first_name;
	}

	/**
	 * @param String $first_name
	 */
	public function setFirstName( String $first_name ) {
		$this->first_name = $first_name;
	}

	/**
	 * @return String
	 */
	public function getFamilyName(): String {
		return $this->family_name;
	}

	/**
	 * @param String $family_name
	 */
	public function setFamilyName( String $family_name ) {
		$this->family_name = $family_name;
	}

	public function getFullName() {
		return $this->first_name . " " . $this->family_name;
	}

	/**
	 * @return String
	 */
	public function getEmail(): String {
		return $this->email;
	}

	/**
	 * @param String $email
	 */
	public function setEmail( String $email ) {
		$this->email = $email;
	}

	/**
	 * @return String
	 */
	public function getPhone(): String {
		return $this->phone;
	}

	/**
	 * @param String $phone
	 */
	public function setPhone( String $phone ) {
		$this->phone = $phone;
	}

	public function getBoardgames() {
		if ( $this->boardgames == null ) {
			$this->loadBoardgames();
		}

		return $this->boardgames;
	}


}