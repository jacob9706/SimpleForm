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
<pre>
    <?php
    $form = unserialize($_SESSION['form']);

    print_r($form->values);
    ?>
</pre>
<?php
$form->inputText('input1', "INPUT 1");
$form->inputSelect('select', 'SelectLabel', $_SESSION['options']);

?>
</pre>
</body>
</html>