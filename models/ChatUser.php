<?php
require_once '../_config/config.php';
require_once 'Database.php';
class Users extends Database
{
    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_profile;
    private $user_status;
    private $user_created_on;
    private $user_verification_code;
    private $user_login_status;
    private $user_token;
    private $user_connection_id;
    public $connect;
    private $usersTable = "user";

    /**
     * @param $user_id
     * @return void
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param $user_name
     * @return void
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param $user_email
     * @return void
     */
    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * @param $user_password
     * @return void
     */
    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * @param $user_profile
     * @return void
     */
    public function setUserProfile($user_profile)
    {
        $this->user_profile = $user_profile;
    }

    /**
     * @return mixed
     */
    public function getUserProfile()
    {
        return $this->user_profile;
    }

    /**
     * @param $user_status
     * @return void
     */
    public function setUserStatus($user_status)
    {
        $this->user_status = $user_status;
    }

    /**
     * @return mixed
     */
    public function getUserStatus()
    {
        return $this->user_status;
    }

    /**
     * @param $user_created_on
     * @return void
     */
    public function setUserCreatedOn($user_created_on)
    {
        $this->user_created_on = $user_created_on;
    }

    /**
     * @return mixed
     */
    public function getUserCreatedOn()
    {
        return $this->user_created_on;
    }

    /**
     * @param $user_verification_code
     * @return void
     */
    public function setUserVerificationCode($user_verification_code)
    {
        $this->user_verification_code = $user_verification_code;
    }

    /**
     * @return mixed
     */
    public function getUserVerificationCode()
    {
        return $this->user_verification_code;
    }

    /**
     * @param $user_login_status
     * @return void
     */
    public function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }

    /**
     * @return mixed
     */
    public function getUserLoginStatus()
    {
        return $this->user_login_status;
    }

    /**
     * @param $user_token
     * @return void
     */
    public function setUserToken($user_token)
    {
        $this->user_token = $user_token;
    }

    /**
     * @return mixed
     */
    public function getUserToken()
    {
        return $this->user_token;
    }

    /**
     * @param $user_connection_id
     * @return void
     */
    public function setUserConnectionId($user_connection_id)
    {
        $this->user_connection_id = $user_connection_id;
    }

    /**
     * @return mixed
     */
    public function getUserConnectionId()
    {
        return $this->user_connection_id;
    }


    public function get_user_all_data_with_status_count()
    {
        $this->query("SELECT user_id, user_name, user_profile, user_login_status, 
       (SELECT COUNT(*) FROM chat_message WHERE to_user_id = :user_id AND from_user_id = chat_user_table.user_id AND status = 'No') AS count_status FROM chat_user_table
		");
        $this->bind(':user_id', $this->user_id);
        $data = $this->resultSet();
        return $data;
    }

    /**
     * @return bool
     */
    function is_valid_email_verification_code()
    {
        $this->query( "SELECT * FROM chat_user_table 
		WHERE user_verification_code = :user_verification_code");
        $this->bind(':user_verification_code', $this->user_verification_code);

        $this->execute();
        if ($this->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function enable_user_account()
    {
        $this->query("UPDATE chat_user_table 
		SET user_status = :user_status 
		WHERE user_verification_code = :user_verification_code");
        $this->bind(':user_status', $this->user_status);
        $this->bind(':user_verification_code', $this->user_verification_code);
        if ($this->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update_user_login_data()
    {
        $this->query("UPDATE chat_user_table 
		SET user_login_status = :user_login_status, user_token = :user_token  
		WHERE user_id = :user_id");
        $this->bind(':user_login_status', $this->user_login_status);
        $this->bind(':user_token', $this->user_token);
        $this->bind(':user_id', $this->user_id);
        if ($this->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function upload_image($user_profile)
    {
        $extension = explode('.', $user_profile['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = 'images/' . $new_name;
        move_uploaded_file($user_profile['tmp_name'], $destination);
        return $destination;
    }

    public function update_data()
    {
        $this->query("UPDATE chat_user_table 
		SET user_name = :user_name, 
		user_email = :user_email, 
		user_password = :user_password, 
		user_profile = :user_profile  
		WHERE user_id = :user_id");
        $this->bind(':user_name', $this->user_name);
        $this->bind(':user_email', $this->user_email);
        $this->bind(':user_password', $this->user_password);
        $this->bind(':user_profile', $this->user_profile);
        $this->bind(':user_id', $this->user_id);
        if ($this->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update_user_connection_id()
    {
        $this->query("UPDATE chat_user_table 
		SET user_connection_id = :user_connection_id 
		WHERE user_token = :user_token");
        $this->bind(':user_connection_id', $this->user_connection_id);
        $this->bind(':user_token', $this->user_token);
        $this->execute();
    }

    function get_user_id_from_token()
    {
        $this->query("SELECT user_id FROM chat_user_table 
		WHERE user_token = :user_token");
        $this->bindParam(':user_token', $this->user_token);
        $user_id = $this->single();
        return $user_id;
    }

    /** Login USER:
     * @param $email
     * @param $password
     * @return false|array
     */
    public function login($email, $password){
        $this->query("SELECT * FROM $this->usersTable WHERE email = :email");
        $this->bind(':email', $email);

        $row = $this->single();

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)){
            return $row;
        } else {
            return false;
        }
    }


    /** List All USERS:
     * @return mixed
     */
    public function listAllUsers() {
        $this->query("SELECT * From  $this->usersTable");
        $this->execute();
        $users = $this->resultSet();
        if ($users !== null) {
        }
        return $users;
    }

    /** List
     * @param $colName
     * @param $colValue
     * @return void
     * @throws Exception
     */
    public function listUser($colName, $colValue) {
        // Check Param Validation
        if($this->checkParam($colName)) {
            $this->query("SELECT * From $this->usersTable WHERE $this->$colName = :colValue");
            $this->bind(':colValue', $colValue);
            $this->execute();
            $users = $this->single();
            if ($users !== null) {
            }
            return $users;
        }
    }

    /** ADD New User:
     * @param string $email
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @return void
     * @throws Exception
     */
    public function addUser($email, $password, $firstName, $lastName) {
        $hashedPassword = hash('sha256', $password);
        $this->query("INSERT INTO $this->usersTable (email, password, firstName, lastName) VALUES (:email, :password, :firstName, :lastName);");
        $this->bind(':email', $email);
        $this->bind(':password', $hashedPassword);
        $this->bind(':firstName', $firstName);
        $this->bind(':lastName', $lastName);
        $this->execute();
    }

    public function updateUser($valueCol, $value, $identifierCol, $identifier) {
        $this->allowedColumns = ['email', 'firstName', 'lastName', 'password', 'id'];
        $paramValidation = $this->checkParam($valueCol, $identifierCol);
        if($paramValidation) {
            $this->query("UPDATE $this->usersTable
                        SET $valueCol = :value 
                        WHERE $identifierCol = :identifier");
            $this->bind(':value', $value);
            $this->bind(':identifier', $identifier);
            $this->execute();
        }
    }

    public function deleteUser($identifierCol, $identifier) {
        $this->allowedColumns = ['email', 'firstName', 'lastName', 'password', 'id'];
        $paramValidation = $this->checkParam($identifierCol);
        if($paramValidation) {
            $this->query("UPDATE $this->usersTable
                                SET deleted = 1 
                                WHERE $identifierCol = :identifier");
            $this->bind(':identifier', $identifier);
            $this->execute();
        }
    }

    /** Check If Email Already Exists:
     * @param string $email
     * @return mixed
     */
    public function emailExistance($email) {
        $this->query("SELECT * FROM $this->usersTable WHERE email = :email");
        $this->bind(':email', $email);
        $this->execute();
        return $this->single();
    }

    /** Email Validate:
     * @param string $email
     * @return bool
     */
    public function emailValidate($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            return false;
        } else {
            return true;
        }
    }

    /** Validate Name:
     * @param string $name
     * @return bool
     */
    public function nameValidate($name) {
        if(!preg_match("/^[a-zA-Z]{3,12}$/", $name) || strlen($name) < 3 || strlen($name) > 12) {
            return false;
        } else {
            return true;
        }
    }

    /** Validate Password:
     * @param string $password
     * @return bool
     */
    public function passwordValidate($password) {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/';
        if(!preg_match($pattern, $password) || strlen($password) < 8) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $password
     * @param $confirmPassword
     * @return bool
     */
    public function passwordMatch($password, $confirmPassword) {
        if($password === $confirmPassword) {
            return true;
        } else {
            return false;
        }
    }
}
$user = new Users();





