<?php
/**
 * Some constants used for error messages
 */
const ROW_NOT_FOUND = 1001;

/**
 * Class Database
 * This class manages the database connection and transactions.
 */
class Database {
	protected static $pdo;
	protected static $instance;

	/**
	 * Creates a PDO object if it's not already created and returns it.
	 *
	 * @return PDO
	 */
	public static function getPdo(): PDO {
		if ( ! isset( static::$pdo ) ) {
			$config = require_once( "config.php" );
			$dsn    = "mysql:host={$config['database']['host']}:{$config['database']['port']};dbname={$config['database']['database']};charset={$config['database']['charset']}";

			try {
				static::$pdo = new PDO( $dsn, $config['database']['user'], $config['database']['password'], [
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => false,
				] );
			} catch ( PDOException $e ) {
				die( "Could not connect to database: " . $e->getMessage() );
			}
		}

		return static::$pdo;
	}

}