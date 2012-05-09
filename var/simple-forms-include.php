<?php
/*
* Include in head of document with
* <?php require '[your path]/simple-forms-include.php'; ?>
*
* Run setup function with your path
* simple_forms_setup("[your path]");
*/
function simple_forms_setup($pathToFolderWhereSimpleFormsIncludeIsLocated)
{
    require_once $pathToFolderWhereSimpleFormsIncludeIsLocated . '/simple-form-utilities/php/SimpleForm.php';
    echo <<<HTML
        <link rel="stylesheet" href="{$pathToFolderWhereSimpleFormsIncludeIsLocated}/simple-form-utilities/css/bootstrap.min.css">
        <script src="{$pathToFolderWhereSimpleFormsIncludeIsLocated}/simple-form-utilities/js/jquery-1.7.2.min.js"></script>
        <script src="{$pathToFolderWhereSimpleFormsIncludeIsLocated}/simple-form-utilities/js/bootstrap.min.js"></script>
        <script>$(function () {
            $('.popUp').popover();
        });</script>
HTML;
}

?>