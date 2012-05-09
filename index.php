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
$form = new SimpleForm('form', 'post', 'hello.php');
$form->setPhoneFormats(array('xxx-xxxx'));
print_r($form->getPhoneFormats());
$form->startForm();
$form->inputText('phone', 'Phone', 'Enter Phone', 'phoneNumber');
$form->inputSubmit("Submit Button", $additionalAttributes);
$form->endForm();
?>
<p style="color:red;"><?php $form->validate('_showError', 'valid'); ?></p>
<?php
echo $form->getValue('phone');
?>
</body>
</html>