<?php

class Validate {
	/**
	 * Validate constructor.
	 *
	 * @param array $inputs The inputs to test.
	 * @param array $rules The rules to test the inputs against.
	 *        Key, value pairs where the key is the name of the input and the value is a | separated list of rules.
	 *        E.G. "username" => "required|alphanumeric|max:15"
	 *
	 * @return ValidationResult Object containing information on the outcomes of validation.
	 * @throws Exception Thrown if there is an unhandled exception with validation such as a rule not being defined.
	 */
	public static function check( Array $inputs, Array $rules ) {
		$validation_results = [];

		/*
		 * Step 1: Check if all the required inputs are present.
		 */
		foreach ( $rules as $inputName => $rule_string ) {
			// Explode the rule string into an array.
			$input_rules = explode( "|", $rule_string );
			// If one of the rules is "required" AND (the key doesn't exist in the array of inputs OR the value is empty)
			if ( in_array( "required", $input_rules ) && ( ! array_key_exists( $inputName, $inputs ) || $inputs[ $inputName ] == "" ) ) {
				$validation_results[ $inputName ]['required'] = 'is required.';
			}
		}

		/*
		 * Step 2: Loop through the other specified rules for each input and test each one.
		 */
		foreach ( $inputs as $inputName => $inputValue ) {
			// If there is a rule(s) defined for that input:
			if ( array_key_exists( $inputName, $rules ) ) {
				// Explode the rules string for this input.
				$input_rules = explode( "|", $rules[ $inputName ] );
				// Loop through the rules and test each one.
				foreach ( $input_rules as $rule ) {
					$result = self::testInputAgainstRule( $rule, $inputValue );
					if ( ! $result['passed'] ) {
						$validation_results[ $inputName ][ $rule ] = $result['message'];
					}
				}
			}
		}

		// Return a new ValidationResults object which will hold all the errors (if any).
		return new ValidationResult( $validation_results );
	}

	/**
	 * @param $rule String The rule to test with.
	 * @param $inputValue String The value to test.
	 *
	 * @return array Information on the results.
	 * @throws Exception if the rule isn't known.
	 */
	private static function testInputAgainstRule( String $rule, String $inputValue ) {
		// Rules can have 2 parts. E.G. "max:4"
		$rule_parts = explode( ":", $rule );
		$return     = [ "passed" => true ];
		switch ( $rule_parts[0] ) {
			case "min":
				if ( strlen( $inputValue ) < $rule_parts[1] ) {
					$return['passed']  = false;
					$return['message'] = "must have at least {$rule_parts[1]} characters.";
				}
				break;
			case "max":
				if ( strlen( $inputValue ) > $rule_parts[1] ) {
					$return['passed']  = false;
					$return['message'] = "can't have more than {$rule_parts[1]} characters.";
				}
				break;
			case "required":
				$return['rule'] = 'required';
				// Step 1 already checked if it existed. Check it's not blank or a lot of spaces.
				if ( trim( $inputValue ) == "" ) {
					$return['passed']  = false;
					$return['message'] = "is required.";
				} else {
					$return['passed']  = true;
					$return['message'] = 'pass';
				}
				break;
			case "email":
				if ( ! filter_var( $inputValue, FILTER_VALIDATE_EMAIL ) ) {
					$return['message'] = "must be a valid email address.";
					$return['passed']  = false;
				}
				break;
			case "number":
				if ( ! is_numeric( $inputValue ) ) {
					$return['passed']  = false;
					$return['message'] = "must be a number.";
				}
				break;
			case "datetime":
				// Checks if carbon can parse the string.
				try {
					$carbon = \Carbon\Carbon::parse($inputValue);
				} catch (InvalidArgumentException $e){
					echo $inputValue;
					$return['message'] = "must be a valid date and time.";
					$return['passed']  = false;
				}
				break;
			default:
				$return['rule'] = 'unknown';
				throw new Exception( 'Unknown rule: ' . $rule_parts[0] );

		}

		return $return;

	}
}
