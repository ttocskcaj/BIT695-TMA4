<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $event->getName(); ?></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
    <h1 class="text-center"><?php echo $event->getName(); ?></h1>
    <p>Edit the event and click "update" to save changes.</p>
    <form action="/events.php?page=update" method="post" id="form">
        <!-- A hidden input to hold the events ID for updating the DB row -->
        <input type="hidden" name="id" id="id" value="<?php echo $event->getId(); ?>">
        <div class="form-group">
            <label for="firstName">Event Name: </label>
            <!-- Input for First Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="eventName" name="eventName" class="form-input"
                   placeholder="Event Name" required maxlength="35"
                   value="<?php echo $event->getName(); ?>">
        </div>
        <div class="form-group">
            <label for="location">Location: </label>
            <!-- Input for Last Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="location" name="location" class="form-input"
                   placeholder="Location" required maxlength="90"
                   value="<?php echo $event->getLocation(); ?>">
        </div>
        <div class="form-group">
            <label for="dateTime">Date & Time: </label>
            <!-- Input for Email. Using HTML5 validation, must be an email address and is required -->
            <input type="datetime-local" id="dateTime" name="dateTime" class="form-input"
                   placeholder="Email Address" required
                   value="<?php echo $event->getCarbon()->format('Y-m-d\TH:i:s'); ?>">
        </div>

        <button type="submit">Update</button>
        <button type="reset">Clear Form</button>
    </form>
</div>

<script type="text/javascript">

    /**
     * Checks if the eventName field is valid. If not, sets an appropriate message.
     * @param eventName The input object
     */
    var eventNameValidation = function (eventName) {
        if (eventName.validity.valueMissing) {
            eventName.setCustomValidity("A name for the event is required.");
        } else if (eventName.validity.tooLong) {
            eventName.setCustomValidity("The event name can't be more than 35 characters long.")
        } else {
            eventName.setCustomValidity("");
        }
    };

    /**
     * Checks if the location field is valid. If not, sets an appropriate message.
     * @param location The input object
     */
    var locationValidation = function (location) {
        if (location.validity.valueMissing) {
            location.setCustomValidity("A location for the event is required.");
        } else if (location.validity.tooLong) {
            location.setCustomValidity("The location can't be more than 90 characters long.")
        } else {
            location.setCustomValidity("");
        }
    };

    /**
     * Checks if the dateTime field is valid. If not, sets an appropriate message.
     * @param dateTime The input object
     */
    var dateTimeValidation = function (dateTime) {
        if (dateTime.validity.valueMissing) {
            dateTime.setCustomValidity("A date and time is required.");
        } else {
            dateTime.setCustomValidity("");
        }
    };



    // When the page loads,
    window.onload = function () {

        // Gets each object from the DOM.
        var form = document.getElementById("form");
        var eventName = document.getElementById("eventName");
        var location = document.getElementById("location");
        var dateTime = document.getElementById("dateTime");

        // When the page is loaded, call each of the methods to set the validation messages for each input.
        // This is in case a user submits without entering anything.
        eventNameValidation(eventName);
        locationValidation(location);
        dateTimeValidation(dateTime);

        // Each time there is an "input" event, update each of the validation messages.
        form.addEventListener("input", function () {
            eventNameValidation(eventName);
            locationValidation(location);
            dateTimeValidation(dateTime);
        });

    };
</script>

</body>
</html>