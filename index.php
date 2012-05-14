<!doctype html>
<html>
<head>
    <?php
    // Include the file
    session_start();
    require_once 'SimpleForm.php';
    ?>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="margin-left: 10px;">
<br>

<?php
$_SESSION['options'] = $options = array(
    "Option1" => 1,
    "Option2" => 2,
    "Option new" => 'hello'
);

function invalid()
{
    echo 'Invalid :(';
}

function valid()
{
    echo 'Valid :)';
}

$additionalOptions = array(
    'style' => 'color: red;',
    'onclick' => 'someFunction()',
);

$form = new SimpleForm('form', 'post', 'action.php');
$form->startForm();
$form->inputText('work',"INPUT 1","work email",'email');
$form->inputText('home',"INPUT 1","home email",'email');
$form->inputSubmit("Submit Button", array('class' => 'btn btn-success'));
$form->endForm();
?>
<pre>
    <?php
    print_r(SimpleForm::$VALIDATION_TYPES);
    ?>
</pre>
<p style="color: rgb(255,0,0);"><?php $form->validate() ?></p>
</body>
</html>