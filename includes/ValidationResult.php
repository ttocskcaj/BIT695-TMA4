<?php

/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 15/08/2017
 * Time: 5:55 PM
 */
class ValidationResult {

	private $validation_results = [];

	/**
	 * ValidationResult constructor.
	 *
	 * @param array $validation_results
	 */
	public function __construct( $validation_results = [] ) {
		$this->validation_results = $validation_results;
	}

	/**
	 * Check if the results contain errors.
	 * Loops through results until a fail is found.
	 *
	 * @return bool True if errors found.
	 */
	public function hasErrors() {
		// Loop through each input's results
		return count( $this->validation_results ) > 0;
	}

	public function has( $input ) {
		return in_array( $input, $this->validation_results );
	}

	public function get( $input ) {
		return $this->validation_results[ $input ];
	}

	public function getResults() {
		return $this->validation_results;
	}
}