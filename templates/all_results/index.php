<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boardgames</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>
    <h1 class="text-center">All Results</h1>
    <table>
        <thead>
        <tr>
            <th>Event</th>
            <th>Member</th>
            <th>Position</th>
            <th>Tools</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $results as $result ): ?>
            <tr>
                <td><?php echo $result->getEvent()->getName(); ?></td>
                <td><?php echo $result->getMember()->getFullName(); ?></td>
                <td><?php echo $result->getPositionAsOrdinal(); ?></td>
                <td><a href="all_results.php?page=show&id=<?php echo $result->getId(); ?>">Edit</a> <a
                            href="all_results.php?page=delete&id=<?php echo $result->getId(); ?>">Delete</a></td>
            </tr>
		<?php endforeach; ?>
        </tbody>

    </table>
    <a href="all_results.php?page=create">Add new Result</a>

</div>


</body>
</html>