<?php
class SimpleFormData
{
    public $values;

    public function SimpleFormData($formName)
    {
        $this->values = unserialize($_SESSION[$formName]);
        $this->values = $this->values->values;
    }

    public function getValue($formElementName)
    {
        if (isset($this->values[$formElementName])) {
            return $this->values[$formElementName];
        }
        return '';
    }
}