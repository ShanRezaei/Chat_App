<?php
/**
	 * Class DbManager
	 * Handles all users and posts queries CRUD (Create ReadOne ReadAll Update Delete)
	 */
class DbManager extends DbConnector {
   public static $error=0;

    // method to add new user
    public function addUser(Users $user) {
        $query=$this->db->prepare("INSERT INTO users (username,password,email,avatar) VALUES (?,?,?,?)");
        $query->execute(array(
             $user->getUsername(),
             $user->getPassword(),
             $user->getEmail(),
             $user->getAvatar()
        ));
        
        $result = $query->fetchAll();
        return $result;
    }

    // GET USER BY USERNAME to check unique username
    public function getUserByUsername( $username) {
        $user_obj = array();
        $query =$this->db->prepare("SELECT * FROM users where username=?;");
        $query->execute(array($username));
        $singleuser = $query->fetchAll( PDO::FETCH_ASSOC );//if we want to get the info of the query we can use this line
        
        // $row=$query->rowCount();
        // if($row>0){
            foreach ( $singleuser as $user ) {
               return $user_obj[] =new Users($user['id'],$user['username'],$user['password'],$user['email'],$user['avatar'],$user['stat']);
            }
        // }else{
        //     return $row;
        // }
        
    }

    // // GET USER BY email to check unique email
    public function getUserByEmail(string $email) {
        $query3 = $this->db->prepare("SELECT * FROM users where email=?;");
        $query3->execute(array($email));
        $query3->fetch( PDO::FETCH_ASSOC );//if we want to get the info of the query we can use this line
        return $query3->rowCount();
    }


    //send email (activation and confirmation) just we need to create msg_body in advance and in controller
    public function sendEmail(string $email, string $subject, string $msg_body,$header ) {
        try {
            mail($email, $subject, $msg_body, $header);
           return "1";
        
        }catch (Exception $e) {
            return "0";
        }
        
        
    }


    //write post
    public function sendPost(Posts $post){
        $query=$this->db->prepare("INSERT INTO posts (username,avatar,comment) VALUES (?,?,?)");
        $query->execute([$post->getUsername(),$post->getAvatar(), $post->getComment()]);
        $result1 = $query->fetchAll();
        return $result1;

    }

    //get all posts
    public function getPost(){
        $comment_obj = array();
        // query to get all users comments from posts db
        $query = $this->db->query("SELECT `id`,`username`, `avatar`, `comment` FROM `posts` ORDER BY `id` DESC;");
        $user_comment = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ( $user_comment as $comment ) {
            $comment_obj[] = new Posts( $comment["id"],$comment["username"],$comment["avatar"],$comment["comment"] );
        }

        return $comment_obj;
       

    }

    //log in process
    public function verifyLogin(string $username,string $password){
        $query = $this->db->prepare("SELECT id,username,password,email,avatar,stat FROM users where username=? AND password=? ;");
        $query->execute(array($username,$password));
         return $query->rowCount();

    }


    //activate user
    public function activateUser(string $username){
        //update status of the user in db to 1
     $query1 = $this->db->prepare("UPDATE users SET stat=1 WHERE username=? ;");
     $query1->execute(array($username));
    //  then send another email to ask to log in
    //send email for activation
      $result2 = $query1->fetchAll();
      return $result2;

    }
    



}

?>