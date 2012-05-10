<!doctype html>
<html>
<head>
    <?php
    // Include the file
    session_start();
    require_once 'var/simple-forms-include.php';

    // Setup by setting path to file
    simple_forms_setup("var");
    ?>
</head>
<body style="margin-left: 10px;">
<br>

<?php
$_SESSION['options'] = $options = array(
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

$additionalOptions = array(
    'style' => 'color: red;',
    'onclick' => 'someFunction()'
);

$form = new SimpleForm('form', 'post', 'action.php');
$form->startForm();
$form->inputText('input1',"INPUT 1");
$form->inputSelect('select','SelectLabel',$options);
$form->inputSubmit("Submit Button", array('class' => 'btn btn-success'));
$form->endForm();
?>
<pre>
    <?php
    print_r($form->values);
    ?>
</pre>
<p style="color:#ff0000;"><?php $form->validate(); ?></p>
</body>
</html>