<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $event->getName() ?> - Results</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>
    <h1 class="text-center"><?php echo $event->getName(); ?></h1>

	<?php foreach ( $event->getResults() as $result ): ?>

        <div class="card">
            <h3><?php echo $result->getMember()->getFullName(); ?></h3>
            <p>
				<?php echo $result->getPositionAsOrdinal(); ?> place
            </p>

            <div class="card-controls">
                <a class="card-button" href="members.php?page=show&id=<?php echo $member->getId(); ?>">Edit</a>
                <a class="card-button" href="members.php?page=delete&id=<?php echo $member->getId(); ?>">Delete</a>
            </div>
        </div>
	<?php endforeach; ?>

</div>

</body>
</html>