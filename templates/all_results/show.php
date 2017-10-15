<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Result</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">


</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>

    <h1 class="text-center">Edit Result</h1>
    <p>Fill out the form and click "Submit" to create a new result.</p>
    <form action="/all_results.php?page=update" method="post" id="form">
        <input type="hidden" name="id" id="id" value="<?php echo $player_result->getId(); ?>">

        <div class="form-group">
            <label for="member">Member: </label>
            <select id="member" name="member" class="form-input">
				<?php foreach ( $members as $member ): ?>
                    <option value="<?php echo $member->getId() ?>" <?php if ( $player_result->getMember()->getId() == $member->getId() )
						echo "selected"; ?>><?php echo $member->getFullName() ?></option>
				<?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="event">Event: </label>
            <select id="event" name="event" class="form-input">
				<?php foreach ( $events as $event ): ?>
                    <option value="<?php echo $event->getId() ?>" <?php if ( $player_result->getEvent()->getId() == $event->getId() )
	                    echo "selected"; ?>><?php echo $event->getName() ?></option>
				<?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="position">Position: </label>
            <input type="number" id="position" name="position" class="form-input" required value="<?php echo $player_result->getPosition(); ?>">
        </div>

        <div class="form-group">
            <button type="submit">Submit</button>
            <button type="reset">Clear Form</button>
        </div>

    </form>
</div>

<script type="text/javascript">
    /**
     * Checks if the member field is valid. If not, sets an appropriate message.
     * @param member The input object
     */
    var memberValidation = function (member) {
        if (member.validity.valueMissing) {
            member.setCustomValidity("Please select a member.");
        } else {
            member.setCustomValidity("");
        }
    };

    /**
     * Checks if the event field is valid. If not, sets an appropriate message.
     * @param event The input object
     */
    var eventValidation = function (event) {
        if (event.validity.valueMissing) {
            event.setCustomValidity("Please select an event.");
        } else {
            event.setCustomValidity("");
        }
    };

    /**
     * Checks if the position field is valid. If not, sets an appropriate message.
     * @param position The input object
     */
    var positionValidation = function (position) {
        if (event.validity.valueMissing) {
            event.setCustomValidity("Please enter a position.");
        } else {
            event.setCustomValidity("");
        }
    };

    // When the page loads,
    window.onload = function () {

        // Gets each object from the DOM.
        var form = document.getElementById("form");
        var member = document.getElementById("member");
        var event = document.getElementById("event");
        var position = document.getElementById("position");

        // When the page is loaded, call each of the methods to set the validation messages for each input.
        // This is in case a user submits without entering anything.
        memberValidation(member);
        eventValidation(event);
        positionValidation(position);


        // Each time there is an "input" event, update each of the validation messages.
        form.addEventListener("input", function () {
            memberValidation(member);
            eventValidation(event);
            positionValidation(position);
        });

    };
</script>

</body>
</html>