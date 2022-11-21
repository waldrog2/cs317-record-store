<?php

namespace App\Lib;

class Form
{

    private $fields = [];

    public function __construct($form_data,$field_data)
    {
        foreach($form_data as $field)
        {
            $fields[$field] = [
                'value' => $this->filter_input($field),
                'required' => $field_data[$field]['required'],
                'type' => $field_data[$field]['type'],
                'error_message' => $field_data[$field]['error_message']
            ];
        }
    }

    private function filter_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    public function validateForm()
    {
        $errors = [];
        foreach($this->fields as $field)
        {
            if (empty($field['value']) && $field['required'])
            {
                $errors[$field] = $field['error_message'];
            }
        }
        return $errors;
    }
    public function getFormData()
    {
        return $this->fields;
    }
}