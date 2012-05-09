<?php
/**
 * SimpleForms is a class that is designed to:
 *   - Make creating forms
 *     - simple and intuitive
 *     - look good
 *   - Make validation easy
 *   - Provide everyone with an easy to use form system
 *
 * All data is stored in the session under $_SESSION[$_formName . "Data"]
 *
 * If in the constructor you set $_formName = "contactForm" and you create an input called
 * input1, your data can be accessed by $_SESSION['contactFormData']['input1']
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
     *   List of available validation types
     */
    public static $validationTypes = array(
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
            $_SESSION[$this->_formName . 'Values'] = $_GET;
        } else {
            $_SESSION[$this->_formName . 'Values'] = $_POST;
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
        $_SESSION[$this->_formName . 'ElementList'] = $this->_formElementList;
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
        if (isset($_SESSION[$this->_formName . 'Values'][$nameAttributeValue])){
            echo $_SESSION[$this->_formName . 'Values'][$nameAttributeValue];
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
            <textarea type="text" name="{$nameAttributeValue}" value="
HTML;
        if (isset($_SESSION[$this->_formName . 'Values'][$nameAttributeValue])){
            echo $_SESSION[$this->_formName . 'Values'][$nameAttributeValue];
        }
        echo '"';
        foreach ($additionalAttributes as $attribute => $value) {
            echo ' ' . $attribute . '="' . $value . '"';
        }

        echo '></textarea>';
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
            if (isset($_SESSION[$this->_formName . 'Values'][$nameAttributeValue]) && !$set) {
                if ($_SESSION[$this->_formName . 'Values'][$nameAttributeValue] == $value) {
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
            <select
HTML;
        foreach ($additionalAttributes as $attribute => $value) {
            echo ' ' . $attribute . '="' . $value . '"';
        }
        echo '>';

        foreach ($optionsArray as $label => $value) {
            echo <<<HTML
                <option value="{$value}"
HTML;
            if (isset($_SESSION[$this->_formName . 'Values'][$nameAttributeValue]) && !$set) {
                if ($_SESSION[$this->_formName . 'Values'][$nameAttributeValue] == $value) {
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

    public function notRequired($arrayOfNotRequiredInputNames)
    {
        $this->_notRequired = $arrayOfNotRequiredInputNames;
    }

    /**
     * @param string $functionToBeCalledIfInvalid
     *   Function to be executed if form is not valid
     * @param string $functionToBeCalledIfValid
     *   Function to be executed if form is valid
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

            $formatted = preg_replace("^[0-9]^", "#", $value);
            if ($formatted != "###-###-####") {
                $error .= $errorMessages[$name] . '<br>';
                $isValid = false;
            }
        }

        function email($name, &$value, $errorMessages, &$error, &$isValid)
        {
            $value = trim($value);

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

        if (isset($_SESSION[$this->_formName . 'Values']['submit'])) {
            foreach ($_SESSION[$this->_formName . 'ElementList'] as $name => $formElementType) {
                foreach (SimpleForm::$validationTypes as $validationType) {
                    if ($validationType == $formElementType) {
                        if (isset($_SESSION[$this->_formName . 'Values'][$name])) {
                            if(in_array($name,$this->_notRequired) && empty($_SESSION[$this->_formName . 'Values'][$name])) {
                                continue 2;
                            }
                            $validationType($name, $_SESSION[$this->_formName . 'Values'][$name], $this->_errorMessages,
                                $this->error, $this->_isValid);
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
}