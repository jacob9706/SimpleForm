<!doctype html>
<html>
<head>
    <?php
    // Include the file
    require_once 'var/simple-forms-include.php';

    // Setup by setting path to file
    simple_forms_setup("var");
    ?>
</head>
<body style="margin-left: 10px;">
<br>

<?php
$additionalAttributes = array(
    "class" => "btn",
    "style" => "color: red;"
);
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

$form = new SimpleForm('form');
$form->startForm();
$form->inputText('text1', 'Input 1 Label','Test Error Message');
$form->inputSubmit("Submit Button");
$form->endForm();
$form->validate('invalid','valid');
?>

</body>
</html>