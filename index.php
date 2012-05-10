<!doctype html>
<html>
<head>
    <?php
    // Include the file
    require_once 'var/simple-forms-include.php';
    session_start();

    // Setup by setting path to file
    simple_forms_setup("var");
    ?>
</head>
<body style="margin-left: 10px;">
<br>

<?php
$options = array(
    "Option1" => 1,
    "Option2" => 2
);

function invalid()
{
    echo 'Invalid :(';
}

function valid()
{
    echo 'Valid :)';
}

$options = array(
    'style' => 'color: red;',
    'onclick' => 'someFunction()'
);

$form = new SimpleForm('form', 'post', 'hello.php');
$form->startForm();
$form->inputText('input1',"INPUT 1");
$form->inputSubmit("Submit Button", array('class' => 'btn btn-success'));
$form->endForm();
?>
<p style="color:#ff0000;"><?php $form->validate('invalid', 'valid'); ?></p>
</body>
</html>