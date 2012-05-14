<!doctype html>
<html>
<head>
    <?php
    // Include the file
    session_start();
	require_once 'SimpleForm.php';
    require_once 'SimpleFormData.php';
    ?>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="margin-left: 10px;">
<br>
<pre>
    <?php
    $form = new SimpleFormData('form');
    print_r($form->values);
    ?>
</pre>

<?php echo $form->getValue('input1'); ?>
</body>
</html>