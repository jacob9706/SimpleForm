<?php
class SimpleFormData
{
    public $form;

    public function SimpleFormData($formName)
    {
        $this->form = unserialize($_SESSION[$formName]);
        $this->form = $this->form->values;
    }

    public function getValue($formElementName)
    {
        if (isset($this->form->values[$formElementName])) {
            return $this->form->values[$formElementName];
        }
        return '';
    }
}
