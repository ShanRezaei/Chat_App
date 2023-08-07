<?php
	/**
	 * users Bean
	 * Class standardize our data ... to make sure that the same class is being passed everywhere
	 */
	class Users {

        // properties
        private $id;
		private $username;
		private $password;
		private $email;
		private $avatar;
		private $stat;

        // constructor
        // here we will give attribute array to the constructor, for $_post is array and also whatever we will received during reading the table will be array.
		public function __construct($id,$username,$password,$email,$avatar,$stat) {
            //With doing this for id which is autoincrement , we wont give amount during insertion but can read it
			$this->id = $id ?? null;
			$this->username = $username;
			$this->password = $password;
			$this->email = $email;
			$this->avatar = $avatar;
            //With doing this for status which has default value , we wont give amount during insertion but can read it and change it
			$this->stat = $stat ?? 0;
		}
		
        


    // getter and setter
    public function getId()
	{
		return $this->id;
	}

    /**
     * @param mixed $id
     */
    public function setId($id) : void
    {
    	$this->id = $id;

    }




    public function getUsername()
    {
        return $this->username;
    }

/**
 * @param mixed $username
 */
public function setUsername($username) : void
{
    $this->username = $username;

}

public function getPassword()
    {
        return $this->password;
    }

/**
 * @param mixed $password
 */
public function setPassword($password) : void
{
    $this->password = $password;

}


public function getEmail()
    {
        return $this->email;
    }

/**
 * @param mixed $Email
 */
public function setEmail($email) : void
{
    $this->email = $email;

}


public function getAvatar()
    {
        return $this->avatar;
    }

/**
 * @param mixed $avatar
 */
public function setAvatar($avatar) : void
{
    $this->avatar = $avatar;

}

public function getStat()
    {
    	return $this->stat;
    }

    /**
     * @param mixed $status
     */
    public function setStat($stat) : void
    {
    	$this->stat= $stat;
    }







    }
