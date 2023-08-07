<?php
	/**
	 * posts Bean
	 * Class standardize our data ... to make sure that the same class is being passed everywhere
	 */
	class Posts  {

        // properties
        private $id1;
		private $comment;
        private $username;
        private $avatar;

        // constructor
        // here we will give attribute array to the constructor, for $_post is array and also whatever we will received during reading the table will be array.
		public function __construct($id1,$username,$avatar,$comment) {
            //With doing this for id which is autoincrement , we wont give amount during insertion but can read it
			$this->id1 = $id1 ?? null;
			$this->comment = $comment;
			$this->avatar=$avatar;
            $this->username=$username;


		}


        // getter and setter
    public function getId()
	{
		return $this->id1;
	}

    /**
     * @param mixed $id
     */
    public function setId($id1) : void
    {
    	$this->id1 = $id1;

    }




    public function getComment()
    {
        return $this->comment;
    }

/**
 * @param mixed $comment
 */
public function setComment($comment) : void
{
    $this->comment = $comment;

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









    }

    ?>