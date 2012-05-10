<!doctype html>
<html>
<head>
    <?php
    // Include the file
    session_start();
    require_once 'var/simple-forms-include.php';
    require_once 'var/simple-form-utilities/php/SimpleFormData.php';

    // Setup by setting path to file
    simple_forms_setup("var");
    ?>
</head>
<body style="margin-left: 10px;">
<br>
<pre>
    <?php
    //$form = unserialize($_SESSION['form']);
    $form = new SimpleFormData('form');

    print_r($form->form);
    ?>
</pre>
<?php

?>
</pre>
</body>
</html>