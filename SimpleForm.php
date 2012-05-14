<?php
/*
 * I hate to do this but I can not create a class variable to be accessed within
 * a function nested within a method
 */

$GLOBALS['phoneFormats'] = array(
    'xxx-xxx-xxxx',
    'xxx xxx xxxx',
    '(xxx)xxx xxxx',
    '(xxx) xxx xxxx',
    '(xxx)xxx-xxxx',
);

/**
 * SimpleForms is a class that is designed to:
 *   - Make creating forms simple and intuitive
 *   - Make validation easy
 *   - Provide everyone with an easy to use form system
 *
 * YOU MUST START A SESSION ABOVE WHERE YOU USE THIS CLASS. I LEFT OUT AUTO STARTING A
 * SESSION IN THE CLASS DUE TO SCHOOL SERVER ISSUES.
 *
 * All data is stored in the session under $_SESSION[$_formName . "Values"]
 *
 * If in the constructor you set $nameAttributeValue = "contactForm" and you create an input called
 * input1, your data can be accessed by $_SESSION['contactFormValues']['input1'] or you can
 * use getValue('input1') to avoid errors if value has yet to be set.
 */
class SimpleForm
{

    // Things about form
    private $_formName;
    private $_formMethod;
    private $_formAction;
    private $_isValid;

    // Error messages
    private $_errorMessages = array();

    // Data about form
    private $_formElementList = array();
    private $_notRequired = array();

    /**
     * @var string
     *   Compiled Error Message
     */
    public $error = "";

    /**
     * @var array
     *   Array of user input
     */
    public $values = array();

    /**
     * @var array
     *   List of available validation types
     */
    public static $VALIDATION_TYPES = array(
        'standardText',
        'numbersOnly',
        'textOnly',
        'tagsAllowed',
        'phoneNumber',
        'email',
        'zipCode',
        'radio',
        'select',
        'submit'
    );

    /**
     * Constructor used to set values of form
     *
     * @param $formName
     *   The name of your form. Used to store information about form to be accessed latter.
     * @param $formMethod
     *   The method of handling data.
     *   Options are: "post" or "get".
     * @param $formAction
     *   The action of the form. Used to redirect to new page after everything is valid.
     *   Defaults to a blank string so it stays on the same page.
     */
    function SimpleForm($formName, $formMethod = "post", $formAction = "")
    {
        $this->_formName = $formName;
        $this->_formMethod = $formMethod;
        $this->_formAction = $formAction;
        $this->_isValid = true;
    }

    /**
     * Initiates new form on page and sets method to $formMethod. Also store get or post values in session.
     *
     * @see SimpleForm
     */
    function  startForm()
    {
        if ($this->_formMethod == "get") {
            $this->values = $_GET;
        } else {
            $this->values = $_POST;
        }
        echo "<form action='' method='{$this->_formMethod}'>";
    }

    /**
     * Placed at end of form to : close HTML tags, store form data
     */
    function endForm()
    {
        echo '</form>';

        // Store form element list for later use
        $_SESSION[$this->_formName] = serialize($this);
    }

    /**
     * Double quotes are used for all attributes ie. name="someName" or onclick="someFunction('foo')" (notice the
     * single quotes when passing in the function parameters.)
     *
     * @param $nameAttributeValue
     *   Assigned to name="$nameAttributeValue"
     * @param $label
     *   Label tags are placed before the input tag
     * @param $errorMessage
     *   Error message to be displayed if invalid
     * @param $typeOfValidation
     *   The type of validation that will be done to the element.
     * @param $additionalAttributes
     *   Array of additional attributes formatted like so.. array("onclick" => "someFunction();",
     *   "class" => "class1 class2")
     *
     * @see validationTypes
     */
    function inputText($nameAttributeValue, $label, $errorMessage = "Enter Text", $typeOfValidation = "standardText", $additionalAttributes = array())
    {
        // Add input to list of form elements for validation.
        $this->_formElementList[$nameAttributeValue] = $typeOfValidation;

        // Add error to list of form errors
        $this->_errorMessages[$nameAttributeValue] = $errorMessage;

        echo <<<HTML
            <label>{$label}</label>
            <input type="text" name="{$nameAttributeValue}" value="
HTML;
        if (isset($this->values[$nameAttributeValue])) {
            echo $this->values[$nameAttributeValue];
        }
        echo '"';
        foreach ($additionalAttributes as $attribute => $value) {
            echo ' ' . $attribute . '="' . $value . '"';
        }

        echo '>';
    }

    /**
     * Double quotes are used for all attributes ie. name="someName" or onclick="someFunction('foo')" (notice the
     * single quotes when passing in the function parameters.)
     *
     * @param $nameAttributeValue
     *   Assigned to name="$nameAttributeValue"
     * @param $label
     *   Label tags are placed before the input tag
     * @param $errorMessage
     *   Error message to be displayed if invalid
     * @param string $typeOfValidation
     *   The type of validation that will be done to the element.
     * @param array $additionalAttributes
     *   Array of additional attributes formatted like so.. array("onclick" => "someFunction();",
     *   "class" => "class1 class2")
     */
    function inputTextArea($nameAttributeValue, $label, $errorMessage = "Enter Text", $typeOfValidation = "standardText", $additionalAttributes = array())
    {
        // Add input to list of form elements for validation.
        $this->_formElementList[$nameAttributeValue] = $typeOfValidation;

        // Add error to list of form errors
        $this->_errorMessages[$nameAttributeValue] = $errorMessage;

        echo <<<HTML
            <label>{$label}</label>
            <textarea type="text" name="{$nameAttributeValue}"
HTML;
        foreach ($additionalAttributes as $attribute => $value) {
            echo ' ' . $attribute . '="' . $value . '"';
        }

        echo '>';

        if (isset($this->values[$nameAttributeValue])) {
            echo $this->values[$nameAttributeValue];
        }

        echo '</textarea>';
    }

    /**
     * Double quotes are used for all attributes ie. name="someName" or onclick="someFunction('foo')" (notice the
     * single quotes when passing in the function parameters.)
     *
     * @param $nameAttributeValue
     *   Assigned to name="$nameAttributeValue"
     * @param $label
     *   Label tags are placed before the input tag
     * @param $optionsArray
     *   array("Label 1" => 1, "Label2" => "value2")
     * @param bool $eachItemOnOwnLine
     *   Determines whether to break after each option or not
     * @param array $additionalAttributes
     *   Array of additional attributes formatted like so.. array("onclick" => "someFunction();",
     *   "class" => "class1 class2")
     * @param $errorMessage
     *   Error message to be displayed if invalid
     */
    function inputRadio($nameAttributeValue, $label, $optionsArray, $eachItemOnOwnLine = false,
                        $additionalAttributes = array(), $errorMessage = "Select Radio")
    {
        $set = false;

        // Add input to list of form elements for validation.
        $this->_formElementList[$nameAttributeValue] = 'radio';

        // Add error to list of form errors
        $this->_errorMessages[$nameAttributeValue] = $errorMessage;

        echo <<<HTML
            <label>{$label}</label>
HTML;
        foreach ($optionsArray as $label => $value) {
            echo <<<HTML
                <input type="radio" name="{$nameAttributeValue}" value="{$value}"
HTML;
            foreach ($additionalAttributes as $attribute => $attributeValue) {
                echo ' ' . $attribute . '="' . $attributeValue . '"';
            }
            if (isset($this->values[$nameAttributeValue]) && !$set) {
                if ($this->values[$nameAttributeValue] == $value) {
                    echo ' checked="checked"';
                    $set = true;
                }
            } else {
                if (!$set) {
                    echo ' checked="checked"';
                    $set = true;
                }
            }
            echo <<<HTML
                >{$label}
HTML;
            if ($eachItemOnOwnLine) {
                echo '<br>';
            }
        }
    }

    /**
     * Double quotes are used for all attributes ie. name="someName" or onclick="someFunction('foo')" (notice the
     * single quotes when passing in the function parameters.)
     *
     * @param $nameAttributeValue
     *   Assigned to name="$nameAttributeValue"
     * @param $label
     *   Label tags are placed before the input tag
     * @param $optionsArray
     *   array("Label 1" => 1, "Label2" => "value2")
     * @param array $additionalAttributes
     *   Array of additional attributes formatted like so.. array("onclick" => "someFunction();",
     *   "class" => "class1 class2")
     * @param $errorMessage
     *   Error message to be displayed if invalid
     */
    function inputSelect($nameAttributeValue, $label, $optionsArray, $additionalAttributes = array(),
                         $errorMessage = "Select Dropdown")
    {
        $set = false;

        // Add input to list of form elements for validation.
        $this->_formElementList[$nameAttributeValue] = 'select';

        // Add error to list of form errors
        $this->_errorMessages[$nameAttributeValue] = $errorMessage;

        echo <<<HTML
            <label>{$label}</label>
            <select name="{$nameAttributeValue}"
HTML;
        foreach ($additionalAttributes as $attribute => $attributeValue) {
            echo ' ' . $attribute . '="' . $attributeValue . '"';
        }
        echo '>';

        foreach ($optionsArray as $label => $value) {
            echo <<<HTML
                <option value="{$value}"
HTML;
            if (isset($this->values[$nameAttributeValue]) && !$set) {
                if ($this->values[$nameAttributeValue] == $value) {
                    echo ' selected="selected"';
                    $set = true;
                }
            } else {
                if (!$set) {
                    echo ' selected="selected"';
                    $set = true;
                }
            }
            echo <<<HTML
                >{$label}</option>
HTML;
        }
        echo '</select>';
    }

    /**
     * @param $label
     *   Label is what the button says
     * @param array $additionalAttributes
     *   Array of additional attributes formatted like so.. array("onclick" => "someFunction();",
     *   "class" => "class1 class2")
     */
    function inputSubmit($label, $additionalAttributes = array())
    {
        // Add input to list of form elements for validation.
        $this->_formElementList['submit'] = 'submit';

        echo <<<HTML
            <br>
            <input type="submit" name="submit" value="{$label}"
HTML;
        foreach ($additionalAttributes as $attribute => $value) {
            echo ' ' . $attribute . '="' . $value . '"';
        }

        echo '>';
    }


    //============================================= Validation ====================================================/

    /**
     * @param $arrayOfNotRequiredInputNames
     *   An array of the names of elements that are not required.
     */
    public function notRequired($arrayOfNotRequiredInputNames)
    {
        (array)$this->_notRequired = (array)$arrayOfNotRequiredInputNames;
    }

    /**
     * @param $arrayOfPhoneFormats
     *   An array of phone formats. Format strings are composed of x's. ie: array('xxx-xxx-xxxx', 'xxxxxxxxxx')
     * @param $keepStandardFormats
     *   Merge with defaults or replace with only your values
     */
    public function setPhoneFormats($arrayOfPhoneFormats, $keepStandardFormats = true)
    {
        if ($keepStandardFormats) {
            (array)$GLOBALS['phoneFormats'] = array_merge((array)$GLOBALS['phoneFormats'], (array)$arrayOfPhoneFormats);
            (array)$GLOBALS['phoneFormats'] = array_unique((array)$GLOBALS['phoneFormats']);
        } else {
            (array)$GLOBALS['phoneFormats'] = (array)$arrayOfPhoneFormats;
        }
    }

    /**
     * @return array
     *  Returns an array of allowed phone formats.
     */
    public function getPhoneFormats()
    {
        settype($GLOBALS['phoneFormats'], 'array');
        return $GLOBALS['phoneFormats'];
    }

    /**
     * @param string $functionToBeCalledIfInvalid
     *   Function to be executed if form is not valid
     * @param string $functionToBeCalledIfValid
     *   Function to be executed if form is valid
     *
     * Should come after endForm
     *
     * @see endForm
     */
    public function validate($functionToBeCalledIfInvalid = "_showError", $functionToBeCalledIfValid = "_redirect")
    {
        function _showError($errorMessage)
        {
            echo $errorMessage;
        }

        function _redirect($where)
        {
            header("Location: " . $where);
        }

        function standardText($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);
            $value = strip_tags($value);

            if (empty($value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function numbersOnly($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);
            $value = strip_tags($value);

            if (empty($value) || !isset($value) || preg_match("/([^0-9])+/", $value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function textOnly($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);
            $value = strip_tags($value);

            if (empty($value) || !isset($value) || is_numeric($value) || preg_match("^[0-9]^", $value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function tagsAllowed($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);

            if (empty($value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function phoneNumber($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);
            $value = strip_tags($value);

            $formatted = preg_replace("^[0-9]^", "x", $value);
            if (!in_array($formatted, $GLOBALS['phoneFormats'])) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            } else {
                // Remove everything except numbers
                $value = preg_replace('/[^\d]/', '', $value);
                // If like 555-5555 (seven characters) or 555-555-5555 (ten characters) format
                if (strlen($value) == 7) {
                    $value = substr($value, 0, 3) . '-' . substr($value, 3);
                } else if (strlen($value) == 10) {
                    $value = substr($value, 0, 3) . '-' . substr($value, 3, 3) . '-' . substr($value, 6);
                }
            }
        }

        function email($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);
            $value = strip_tags($value);

            if (!preg_match("/^([a-zA-Z0-9])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function zipCode($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);
            $value = preg_replace("[^0-9]", "", $value);

            if (strlen($value) != 5) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function radio($name, &$value, $errorMessages, &$error, &$isValid)
        {
            if (!isset($value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function select($name, &$value, $errorMessages, &$error, &$isValid)
        {
            if (!isset($value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function submit($name, &$value, $errorMessages, &$error, &$isValid)
        {
            if (!isset($value)) {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        // If form has been submitted
        if (isset($this->values['submit'])) {
            // Go through each form element
            foreach ($this->_formElementList as $name => $formElementType) {
                // Go through each type of element
                if (in_array($name, $this->_notRequired) && empty($this->values[$name])) {
                    continue;
                }
                foreach (SimpleForm::$VALIDATION_TYPES as $validationType) {
                    // If the current element is a real type
                    if ($validationType == $formElementType) {
                        // Check if element exists
                        if (isset($this->values[$name])) {
                            // Else validate
                            $validationType($name, $this->values[$name], $this->_errorMessages,
                                $this->error, $this->_isValid); //The function for validation is called based upon the type the
                            //User passed in
                        }
                    }
                }
            }
            if ($this->_isValid) {
                if ($functionToBeCalledIfValid == "_redirect") {
                    $functionToBeCalledIfValid($this->_formAction);
                } else {
                    $functionToBeCalledIfValid();
                }
            } else {
                if ($functionToBeCalledIfInvalid == "_showError") {
                    $functionToBeCalledIfInvalid($this->error);
                } else {
                    $functionToBeCalledIfInvalid();
                }
            }
        }
    }

    /**
     * @param $formElementName
     *   Name of form element you are trying to access
     * @return mixed
     *   Value of form element
     */
    public function getValue($formElementName)
    {
        if (isset($this->values[$formElementName])) {
            return $this->values[$formElementName];
        }
        return '';
    }


    private function is_assoc_array($array)
    {
        if (is_array($array) && !is_numeric(array_shift(array_keys($array)))) {
            return true;
        }
        return false;
    }
}
//TODO: Figure out why the formatted data is not being displayed back in the text fields
//TODO: Add more options for validation
//TODO: Find a way to do checkboxes efficiently