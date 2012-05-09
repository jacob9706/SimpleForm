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
$additionalAttributes = array(
    "class" => "btn btn-danger",
    "style" => "color: white;"
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

$form = new SimpleForm('form','post','hello.php');
$form->notRequired(array('phone'));
$form->startForm();
$form->inputText('text1', 'Input 1 Label', 'Input 1 Is Blank, Please Fill Enter Data','standardText');
$form->inputText('email', 'Email Label', 'Enter Valid Email', 'email');
$form->inputText('zip', "Zip Code Label", 'Enter Valid Zip Code', 'zipCode');
$form->inputText('phone', 'TEST Label', 'TEST', 'numbersOnly');
$form->inputSubmit("Submit Button", $additionalAttributes);
$form->endForm();

echo $_SESSION['formValues']['text1'];
?>
<p style="color:red;"><?php $form->validate('_showError'); ?></p>
</body>
</html>