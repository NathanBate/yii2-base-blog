<?php

namespace bb\console\controllers;

use Bb;
use bb\console\Controller;
use bb\migrations\Install;
use bb\migrations\InstallUserManagement;
use bb\migrations\InstallBlog;
use Seld\CliPrompt\CliPrompt;
use yii\console\ExitCode;
use bb\helpers\HyiiHelper;
use yii\helpers\Console;

class InstallController extends Controller
{
    public $isWorkingDbConnection;
    // public $defaultAction = 'index';

    public $username;

    public $firstName;

    public $lastName;

    public $emailAddress;

    public $password;

    /**
     * Installs bb
     *
     * @param bool $bUseBaseInstallMigration
     *
     * @return int Exit code
     */
    public function actionIndex($bUseBaseInstallMigration=true)
    {
        // check to see if the database connection is working.
        $this->getIsDbConnectionValid();

        // proceed or exit depending on whether the API has been installed yet.
        if (Bb::$app->getIsInstalled()) {
            Bb::_console("App is already installed.");
            return ExitCode::OK;
        }

        Bb::_console("Installing...");

        /**
         * Get the basic info to create a user.
         */
        $this->userManagement();
        
        $installMigration = new Install([
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'password' => $this->password,
            'admin' => 'S',
            'email' => $this->emailAddress,
            'active' => 'Y'
        ]);
        $result = $installMigration->safeUp();

        if ($result) {
            Bb::_console("Installation Done! ");
        } else {
            Bb::_console("Installation Failed!");
        }
        return ExitCode::OK;
    
    }

    /**
     * Returns whether the DB connection settings are valid
     */
    public function getIsDbConnectionValid()
    {
        try {
            Bb::$app->db->open();
            $this->isWorkingDbConnection = true;
        } catch (yii\db\Exception $e) {
            Bb::_console("There was a problem connecting to the database.");
            $this->isWorkingDbConnection = true;
        }
    }

    private function userManagement()
    {
        $this->username = $this->prompt('Username (lowercase letters, numbers and no spaces):', ['default' => 'administrator', "validator" => [$this, 'validateUsername']]);
        $this->firstName = $this->prompt("First Name:", ['required' => true, "validator" => [$this, 'validateName']]);
        $this->lastName = $this->prompt("Last Name:", ['required' => true, "validator" => [$this, 'validateName']]);
        $this->password = $this->_passwordPrompt();
        $this->emailAddress = $this->prompt("Email Address:", ['required' => true, "validator" =>[$this, 'validateEmail']]);
    }

    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validateEmail(string $value, string &$error = null): bool
    {
        if (! preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $value)) {
            $error = "Please enter a valid email!";
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validateUsername(string $value, string &$error = null): bool
    {
        if (! preg_match('/^[a-z0-9\']+$/', $value)) {
            $error = "Username must be all lowercase and no spaces!";
            return false;
        }
        return true;
    }


    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validateName(string $value, string &$error = null): bool
    {
        if (! preg_match('/^[a-zA-Z]+$/', $value)) {
            $error = "Upper or Lowercase Letters Only!";
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validatePassword(string $value, string &$error = null): bool
    {
        if (! preg_match('/^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8,}$/', $value)) {
            $error = "Please enter a valid password!";
            return false;
        }
        return true;
    }

    /**
     * I borrowed this code from CraftCMS
     *
     * @return string
     */
    public function _passwordPrompt(): string
    {
        top:

        Console::output("Password Requirements:");
        Console::output("- At least 8 characters long");
        Console::output("- At least one of the following: ! @ # $ &");
        Console::output("- At least two uppercase letters");
        Console::output("- At least three lowercase letters");
        Console::output("- At least two digits");
        $this->stdout('Password: ');
        if (($password = CliPrompt::hiddenPrompt(true)) === '') {

            $this->stdout('Invalid input.' . PHP_EOL);
            goto top;
        }
        if (!$this->validatePassword($password, $error)) {
            Console::output(PHP_EOL ."Please Enter a valid password!");
            goto top;
        }
        $this->stdout('Confirm: ');
        if (!($matched = ($password === CliPrompt::hiddenPrompt(true)))) {
            $this->stdout('Passwords didn\'t match, try again.' . PHP_EOL, Console::FG_RED);
            goto top;
        }
        return $password;
    }

}
