<?PHP
//******************************************//
//* This copyright notice must not be removed
//* under any circumstances.
//* It must stay intact in all the files.

//* Samforum
//* Version 1.0
//* Script created by Samiuddin Samiuddin
//* Email: phpdevsami@gmail.com
//* Skype: n0h4cks

//* - This is not an open source project, functions/classes
//*   or any other code form this script cannot be
//*   used for other scripts or applications.

//*   You are not allowed to resell this script.

//* - You are free to make modification/changes,
//*   however it must be for your own use.
//*********************************************************************//

	class Recover_password{
		private $conn;
		private $password_one;
		private $password_two;
		
		function initialize_connection($conn){
			if($conn instanceof PDO){
				$this->conn = $conn;
			}else{
				echo "Connection problem accured";
			}
		}
		
		function is_posted(){
			if(isset($_POST["change_password"])){
				$this->is_empty();
			}
		}
		
		private function is_empty(){
			$is_empty = null;
			$count = 0;
			
			foreach($_POST as $post_value){
				if(empty($post_value)){
					echo "Field/s are empty";
					break;
				}
				$count = $count + 1;
				
				if(!empty($post_value)){
					if($count == 2){
						$this->set_values();
					}
				}
			}
			
			/*
			if(isset($is_empty)){
				$this->set_values();
			}
			*/
		}
		
		private function set_values(){
			$this->password_one = strip_tags(stripslashes(trim($_POST["password_two"], '"')));
			$this->password_two = strip_tags(stripslashes(trim($_POST["password_one"], '"')));
			
			if($this->password_one == $this->password_two){
				if( strlen($this->password_one) > 10 ){
					$this->password_one = md5($this->password_one);
					$this->change_password_to_new();
				}else{
					header("location: ../error_message.php?message=The password you provided is too weak");
				}
			}else{
				header("location: ../error_message.php?message=Please make sure the passwords match");
			}
		}
		
		private function change_password_to_new(){
			$insert_pass_query = $this->conn->prepare("update registered_users set user_password = :user_password where username = :username");
			if( $insert_pass_query->execute(array('user_password'=>$this->password_one, 'username'=>$_SESSION["logged_in"])) ){
				header("location: ../error_message.php?message=Password has been sucessfully changed!");
			}else{
				header("location: ../error_message.php?message=Unknown error occured, please contact admin.");
			}
		}
	}

?>
