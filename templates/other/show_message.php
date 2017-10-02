<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $message['title']; ?></title>

    <!-- Bootstrap library -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" class="css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
    <div class="message message-<?php echo $message['type']; ?>">
        <h1 class="text-center"><?php echo $message['title']; ?></h1>
        <p><?php echo $message['body']; ?></p>
		<?php if ( $message['return_url'] != null ): ?>
            <a href="<?php echo $message['return_url']; ?>">Click to return</a>
		<?php endif; ?>
    </div>
</div>


</body>
</html>