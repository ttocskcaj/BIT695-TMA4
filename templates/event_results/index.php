<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Results</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>
    <h1 class="text-center">Event Results</h1>
    <ul>
		<?php foreach ( $events as $event ): /* @var $event EventModel */ ?>
            <li>
                <a href="event_results.php?page=show&event_id=<?php echo $event->getId() ?>"><?php echo $event->getName(); ?>
                    (<?php echo $event->getBoardgame()->getName(); ?>)
                    - <?php echo $event->getCarbon()->toDayDateTimeString(); ?></a>
            </li>
		<?php endforeach; ?>
    </ul>

    <div>
    </div>
    <a href="all_results.php?page=index">View/edit all results</a>
</div>


</body>
</html>