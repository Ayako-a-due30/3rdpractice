<?php

require('../function/function.php');
debug('///////掲示板//////////');
debugLogStart();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
</head>
<body>
    <p id="js-show-msg" style="display:none;" class="msg-slide">
<?php echo getSessionFlash('msg_success'); ?></p>
</body>
<?php require('footer.php'); ?>
</html>