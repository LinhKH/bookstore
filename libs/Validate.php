<?php
class Validate
{
	
	// Error array
  private $errors = array();
	
	// Source array
  private $source = array();
	
	// Rules array
  private $rules = array();
	
	// Result array
  private $result = array();
	
	// Contrucst
  public function __construct($source)
  {
    $this->source = $source;
  }
	
	// Add rules
 /*  public function addRules($rules)
  {
    $this->rules = array_merge($rules, $this->rules);
  } */
	
	// Get error
  public function getError()
  {
    return $this->errors;
  }
	
	// Set error
  public function setError($field, $message)
  {
    $strfield = str_replace('_', ' ', $field);
    if (array_key_exists($field, $this->errors)) {
      $this->errors[$field] .= ' - ' . $message;
    } else {
      $this->errors[$field] = '<b>' . ucwords($strfield) . ':</b> ' . $message;
    }
  }
	
	// Get result
  public function getResult()
  {
    return $this->result;
  }
	
  // Add rule
  public function addRule($field, $type, $options = null, $required = true)
  {
    $this->rules[$field] = array('type' => $type, 'options' => $options, 'required' => $required);
    return $this;
  }
	
	// Run
  public function run()
  {
    foreach ($this->rules as $field => $value) {
      if ($value['required'] == true && trim(@$this->source[$field]) == null) {
        $this->setError($field, 'giá trị này không được rỗng!');
      } else {
        switch ($value['type']) {
          case 'int':
            $this->validateInt($field, $value['options']['min'], $value['options']['max']);
            break;
          case 'string':
            $this->validateString($field, $value['options']['min'], $value['options']['max']);
            break;
          case 'url':
            $this->validateUrl($field);
            break;
          case 'email':
            $this->validateEmail($field);
            break;
          case 'status':
            $this->validateStatus($field, $value['options']);
            break;
          case 'group':
            $this->validateGroupID($field);
            break;
          case 'password':
            $this->validatePassword($field, $value['options']);
            break;
          case 'date':
            $this->validateDate($field, $value['options']['start'], $value['options']['end']);
            break;
          case 'existRecord':
            $this->validateExistRecord($field, $value['options']);
            break;
          case 'notExistRecord':
            $this->validateNotExistRecord($field, $value['options']);
            break;
          case 'string-notExistRecord':
            $this->validateString($field, $value['options']['min'], $value['options']['max']);
            $this->validateNotExistRecord($field, $value['options']);
            break;
          case 'email-notExistRecord':
            $this->validateEmail($field);
            $this->validateNotExistRecord($field, $value['options']);
            break;
          case 'file':
            $this->validateFile($field, $value['options']);
            break;
        }
      }
      if (!array_key_exists($field, $this->errors)) {
        $this->result[$field] = $this->source[$field];
      }
    }
    $eleNotValidate = array_diff_key($this->source, $this->errors);
    $this->result = array_merge($this->result, $eleNotValidate);

  }
	
	// Validate Integer
  private function validateInt($field, $min = 0, $max = 0)
  {
    if ($this->source[$field] != 0 && !filter_var($this->source[$field], FILTER_VALIDATE_FLOAT, array("options" => array("min_range" => $min, "max_range" => $max)))) {
      $this->setError($field, 'is an invalid number');
    }
  }
	
	// Validate String
  private function validateString($field, $min = 0, $max = 0)
  {
    $length = strlen($this->source[$field]);
    if ($length < $min) {
      $this->setError($field, 'is too short');
    } elseif ($length > $max) {
      $this->setError($field, 'is too long');
    } elseif (!is_string($this->source[$field])) {
      $this->setError($field, 'is an invalid string');
    }
  }
	
	// Validate URL
  private function validateURL($field)
  {
    if (!filter_var($this->source[$field], FILTER_VALIDATE_URL)) {
      $this->setError($field, 'is an invalid url');
    }
  }
	
	// Validate Email
  private function validateEmail($field)
  {
    if (!filter_var($this->source[$field], FILTER_VALIDATE_EMAIL)) {
      $this->setError($field, 'is an invalid email');
    }
  }

  public function showErrors()
  {
    $xhtml = '';
    if (!empty($this->errors)) {
      $xhtml .= '<dl id="system-message"><dt class="error">Error</dt><dd class="error message"><ul>';
      foreach ($this->errors as $key => $value) {
        $xhtml .= '<li>' . $value . ' </li>';
      }
      $xhtml .= '</ul></dd></dl>';
    }
    return $xhtml;
  }

  public function showErrorsPublic()
  {
    $xhtml = '';
    if (!empty($this->errors)) {
      $xhtml .= '<ul class="error-public">';
      foreach ($this->errors as $key => $value) {
        $xhtml .= '<li>' . $value . ' </li>';
      }
      $xhtml .= '</ul>';
    }
    return $xhtml;
  }

  public function isValid()
  {
    if (count($this->errors) > 0) return false;
    return true;
  }
	
	// Validate Status
  private function validateStatus($field, $options)
  {
    if (in_array($this->source[$field], $options['deny']) == true) {
      $this->setError($field, 'Vui lòng chọn giá trị khác giá trị mặc định!');
    }
  }
	
	// Validate GroupID
  private function validateGroupID($field)
  {
    if ($this->source[$field] == 0) {
      $this->setError($field, 'Select group');
    }
  }
	
	// Validate Password
  private function validatePassword($field, $options)
  {
    if ($options['action'] == 'add' || ($options['action'] == 'edit' && $this->source[$field])) {
      $pattern = '#^(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,8}$#';	// Php4567!
      if (!preg_match($pattern, $this->source[$field])) {
        $this->setError($field, 'is an invalid password');
      };
    }

  }
	
	// Validate Date
  private function validateDate($field, $start, $end)
  {		
		// Start
    $arrDateStart = date_parse_from_format('d/m/Y', $start);
    $tsStart = mktime(0, 0, 0, $arrDateStart['month'], $arrDateStart['day'], $arrDateStart['year']);
			
		// End
    $arrDateEnd = date_parse_from_format('d/m/Y', $end);
    $tsEnd = mktime(0, 0, 0, $arrDateEnd['month'], $arrDateEnd['day'], $arrDateEnd['year']);
		
		// Current
    $arrDateCurrent = date_parse_from_format('d/m/Y', $this->source[$field]);
    $tsCurrent = mktime(0, 0, 0, $arrDateCurrent['month'], $arrDateCurrent['day'], $arrDateCurrent['year']);

    if ($tsCurrent < $tsStart || $tsCurrent > $tsEnd) {
      $this->setError($field, 'is an invalid date');
    }
  }
	
	// Validate Exist record
  private function validateExistRecord($field, $options)
  {
    $database = $options['database'];

    $query = $options['query'];	// SELECT * FROM user where id = 2    
    if ($database->isExist($query) == false) {
      $this->setError($field, 'record is not exist / giá trị này không tồn tại');
    }
  }
	
	// Validate Not Exist record
  private function validateNotExistRecord($field, $options)
  {
    $database = $options['database'];

    $query = $options['query'];	// SELECT id FROM user where username = 'admin'
    if ($database->isExist($query) == true) {
      $this->setError($field, 'giá trị này đã tồn tại');
    }
  }
	
	// Validate File
  private function validateFile($field, $options)
  {
    if ($this->source[$field]['name'] != null) {
      if (!filter_var($this->source[$field]['size'], FILTER_VALIDATE_INT, array("options" => array("min_range" => $options['min'], "max_range" => $options['max'])))) {
        $this->setError($field, 'kích thước không phù hợp');
      }

      $ext = pathinfo($this->source[$field]['name'], PATHINFO_EXTENSION);
      if (in_array($ext, $options['extension']) == false) {
        $this->setError($field, 'phần mở rộng không phù hợp');
      }
    }
  }
}