<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
    <h1 class="text-center">Events</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Event Name</th>
            <th>Location</th>
            <th>Date & Time</th>
            <th>Tools</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $events as $event ): ?>
            <tr>
                <td><?php echo $event->getId(); ?></td>
                <td><?php echo $event->getName(); ?></td>
                <td><?php echo $event->getLocation(); ?></td>
                <td><?php echo $event->getCarbon()->format("F jS g:ia"); ?></td>
                <td><a href="events.php?page=show&id=<?php echo $event->getId(); ?>">Edit</a> <a
                            href="events.php?page=delete&id=<?php echo $event->getId(); ?>">Delete</a></td>
            </tr>
		<?php endforeach; ?>
        </tbody>

    </table>
    <a href="events.php?page=create">Create new event</a>

</div>


</body>
</html>