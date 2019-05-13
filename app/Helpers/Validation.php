<?php

namespace App\Helpers;

class Validation
{
    public static $emailRegex            = '/([\w\d\.\_\-]+)@([\w\d\.\-\_]+)\.([\w\d]{2,64})/';
    public static $nameRegex             = '/(\w{2,50})/';
    public static $otherNameRegex        = '/(\w{2,100})/';
    public static $nrcRegex              = '/(\d{6}\/\d{2}\/1)/';
    public static $dobRegex              = '/(\d{4}-\d{2}-\d{2})/';
    public static $phoneNumberRegex      = '/(\d{9})/';
    public static $passwordRegex         = '/([\w\d!@#\$%\^&\-\_]{8,}\$)/';
    public static $advancedPasswordRegex = '/(\^(?:(?=.\*[a-z])(?:(?=.\*[A-Z])(?=.\*[\d\W])|(?=.\*\W)(?=.\*\d))|(?=.\*\W)(?=.\*[A-Z])(?=.\*\d)).{8,}\$)/';
    public static $singleDigitRegex      = '/(\^\d{1})/';

    public static function validate($type, $property = null, $secondProperty = null)
    {
        // Validate first or last name or name
        if ($type == 'first name' || $type == 'last name' || $type == 'name') {
            if (empty($property)) {
                return 'Please enter your ' . $type;
            } elseif (!preg_match(self::$nameRegex, $property)) {
                return 'Please enter a valid ' . $type;
            } else {
                return null;
            }
        }

        // Validate other names
        if ($type == 'other names') {
            if (!empty($property) && !preg_match(self::$otherNameRegex, $property)) {
                return 'Please enter names that are valid';
            } else {
                return null;
            }
        }

        // Validate email address
        if ($type == 'email address') {
            if (empty($property)) {
                return 'Please enter email';
            } elseif (!preg_match(self::$emailRegex, $property)) {
                return 'Please enter a valid email address';
            } else {
                return null;
            }
        }

        // Validate NRC
        if ($type == 'nrc') {
            if (empty($property)) {
                return 'Please enter your NRC number';
            } elseif (!preg_match(self::$nrcRegex, $property)) {
                return 'Please enter a valid NRC number including the \'/\' character';
            } else {
                return null;
            }
        }
        // Validate DOB
        if ($type == 'DOB') {
            if (empty($property)) {
                return 'Please enter your date of birth';
            } elseif (!empty($property)) {
                $date = explode('-', $property);
                if (!checkdate($date[1], $date[2], $date[0])) {
                    return 'Please enter a valid date of birth';
                }
            } else {
                return null;
            }
        }

        // Validate phone number
        if ($type == 'phone number') {
            if (empty($property)) {
                return 'Please enter your phone number';
            } elseif (!empty($property) && !preg_match(self::$phoneNumberRegex, $property)) {
                return 'Please enter a valid phone number';
            } else {
                return null;
            }
        }

        // Validate password
        if ($type == 'password') {
            if (empty($property)) {
                return 'Please enter a password';
            } elseif (strlen($property) < 8) {
                return 'Please enter a password containing at least 8 characters';
            } else {
                return null;
            }
        }

        // Validate advanced password
        if ($type == 'advanced password') {
            if (empty($property)) {
                return 'Please enter a password';
            } elseif (strlen($property) < 8) {
                return 'The password is less than 8 characters';
            } elseif (!preg_match(self::$advancedPasswordRegex, $property)) {
                return 'Please ensure your password contains at least 3 of the 4 requirements i.e. lowercase letter, uppercase letter, digit and symbol';
            } else {
                return null;
            }
        }

        // Validate confirm password
        if ($type == 'confirm password') {
            if (empty($property)) {
                return 'Please confirm your password';
            } elseif ($property != $secondProperty) {
                return 'The passwords do not match!';
            } else {
                return null;
            }
        }

        // Validate department ID
        if ($type == 'department_id') {
            if ($property == 0 || $property == null) {
                return 'Please select a valid department!';
            } elseif (!($property > 0 && $property < $secondProperty + 1)) {
                $secondProperty++;
                return 'Please select a valid department!';
            } else {
                return null;
            }
        }
    }

}
