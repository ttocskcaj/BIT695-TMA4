<?php
error_reporting( E_ALL );
// Require needed files.
require_once "includes/helpers.php";
require_once "includes/Database.php";
require_once "includes/Validate.php";
require_once "includes/ValidationResult.php";
require_once "includes/Carbon.php"; // Third party date time class.
require_once "includes/EventModel.php";
require_once "includes/MemberModel.php";
require_once "includes/BoardgameModel.php";
require_once "includes/ResultModel.php";


// If the user is requesting the show page.
if ( $_GET['page'] == "show" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the result from database.
	if ( isset ( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
		try {
			// Get that result from the database.
			$event = EventModel::findByID( $_GET['event_id'] );

			// Include the template to show that results.
			include 'templates/event_results/show.php';
		} catch ( PDOException $e ) {
			// If there was a database error, display it.
			showErrorMessage( "Database Error", $e->getMessage() );
		} catch ( Exception $e ) {
			// If no such result was found, show a message.
			if ( $e->getCode() == ROW_NOT_FOUND ) {
				showErrorMessage( 'Event Not Found', "That event was not found in the database, there's no results listed or it hasn't happened yet.", 'event_results.php?page=index' );
			}
			else {
				// In case the exception was something else, throw it again.
				throw $e;
			}
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid event id.', 'event_results.php?page=index' );
	}
}

// If the user is requesting the index page.
elseif ( $_GET['page'] == "index" ) {
	// Get array of all result from the database.
	try {
		$events = EventModel::getPast();

		// Include the template to show them.
		include 'templates/event_results/index.php';
	} catch ( Exception $e ) {
		// If no such result was found, show a message.
		if ( $e->getCode() == ROW_NOT_FOUND ) {
			showErrorMessage( 'No Results Found', 'There are no results in the database <a href="event_results.php?page=create">Create One</a>' );
		}
		else {
			// In case the exception was something else, throw it again.
			throw $e;
		}
	}

}
else {
	// If no "page" is provided, redirect to index.
	header( "Location: /event_results.php?page=index" );
}