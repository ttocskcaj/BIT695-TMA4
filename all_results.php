<?php
error_reporting( E_ALL );
// Require needed files.
require_once "includes/helpers.php";
require_once "includes/Database.php";
require_once "includes/Validate.php";
require_once "includes/ValidationResult.php";
require_once "includes/BoardgameModel.php";
require_once "includes/Carbon.php";
require_once "includes/MemberModel.php";
require_once "includes/EventModel.php";
require_once "includes/ResultModel.php";


// If user is requesting the create page.
if ( $_GET['page'] == "create" ) {
	$members = MemberModel::getAll();
	$events = EventModel::getAll();
	// Require the template for result creation form.
	include "templates/all_results/create.php";
} // If user is requesting the store page.

// If the user is requesting the store page (save result to database)
elseif ( $_GET['page'] == "store" ) {
	// Validate the form data.
	$validation_results = Validate::check( $_POST, [
		"member"   => "required|number",
		"event"    => "required|number",
		"position" => "required|number"
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/all_results.php?page=create";
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		// Create a new ResultModel with the information from the form.
		$member = MemberModel::findByID( $_POST['member'] );
		$event  = EventModel::findByID( $_POST['event'] );
		$result = new ResultModel( $_POST['position'], $member, $event );
		// Try and save that result to the database.
		try {
			$result->save();
			// If the user is saved correctly, show a success message.
			showSuccessMessage( "New Result", "New result created successfully.", 'all_results.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the show page.
elseif ( $_GET['page'] == "show" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the result from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		try {
			$members = MemberModel::getAll();
			$events = EventModel::getAll();
			// Get that result from the database.
			$result = ResultModel::findByID( $_GET['id'] );

			// Include the template to show that result.
			include 'templates/all_results/show.php';
		} catch ( PDOException $e ) {
			// If there was a database error, display it.
			showErrorMessage( "Database Error", $e->getMessage() );
		} catch ( Exception $e ) {
			// If no such result was found, show a message.
			if ( $e->getCode() == ROW_NOT_FOUND ) {
				showErrorMessage( 'Result Not Found', 'That result was not found in the database.', 'all_results.php?page=index' );
			}
			else {
				// In case the exception was something else, throw it again.
				throw $e;
			}
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid result id.', 'all_results.php?page=index' );
	}
}

// If the user is requesting the update page.
elseif ( $_GET['page'] == "update" ) {

	// Validate
	$validation_results = Validate::check( $_POST, [
		"id"       => "required|number",
		"member"   => "required|number",
		"event"    => "required|number",
		"position" => "required|number"
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/all_results.php?page=show&id=" . $_POST['id'];
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		try {
			// Try and load the existing result from the database using the ID.
			$result = ResultModel::findByID( $_POST['id'] );

			// Update the results attributes from the form data.
			$result->setEvent( EventModel::findByID($_POST['event']) );
			$result->setMember( MemberModel::findByID($_POST['member']) );
			$result->setPosition( $_POST['position'] );

			// Update the database row.
			$result->update();


			// If the user is updated correctly, show a success message.
			showSuccessMessage( "Updated Result", "The results's information was updated.", 'all_results.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the delete page.
elseif ( $_GET['page'] == "delete" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the result from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		// Check if the user has confirmed the deletion
		if ( isset( $_GET['confirm'] ) ) {
			try {
				// Delete that result from the database.
				ResultModel::delete( $_GET['id'] );

				showSuccessMessage( "Deleted Result", "The result was deleted.", 'all_results.php?page=index' );

			} catch ( PDOException $e ) {
				// If there was a database error, display it.
				showErrorMessage( "Database Error", $e->getMessage() );
			} catch ( Exception $e ) {
				// If no such result was found, show a message.
				if ( $e->getCode() == ROW_NOT_FOUND ) {
					showErrorMessage( 'Result Not Found', 'That result was not found in the database.', 'all_results.php?page=index' );
				}
				else {
					// In case the exception was something else, throw it again.
					throw $e;
				}
			}
		}
		else {
			// Show a confirmation message.
			showErrorMessage( "Delete Result", 'Are you sure you want to delete this result?! <a href="all_results.php?page=delete&id=' . $_GET['id'] . '&confirm=yes">CLICK TO DELETE</a>' );
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid result id.', 'all_results.php?page=index' );
	}
}

// If the user is requesting the index page.
elseif ( $_GET['page'] == "index" ) {
	// Get array of all results from the database.
	try {
		$results = ResultModel::getAll();
	} catch ( Exception $e ) {
		// If no such result was found, show a message.
		if ( $e->getCode() == ROW_NOT_FOUND ) {
			showErrorMessage( 'No Results Found', 'There are no results in the database <a href="all_results.php?page=create">Create One</a>' );
		}
		else {
			// In case the exception was something else, throw it again.
			throw $e;
		}
	}
	// Include the template to show them.
	include 'templates/all_results/index.php';
}
else {
	// If no "page" is provided, redirect to index.
	header( "Location: /all_results.php?page=index" );
}