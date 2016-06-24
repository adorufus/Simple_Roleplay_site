<?
require_once("lib.php");

if($user->is_logged_in()){
   header ('Location: gamestart.php');
}

if(isset($_POST['submit'])){
   if(strlen($_POST['username']) < 3){
      $error[] = "username is too short, mate!";
   }
   else{
      $stmt = $db->prepare('SELECT username FROM user WHERE username = :username');
      $stmt -> execute(array(':username' => $_POST['username']));
      $row = $stmt -> fetch(PDO::FETCH_ASSOC);
      
      if(!empty($row['username'])){
         $error[] = 'This creature already exists';
      }
   }
   
  $classes = $_POST['class']; if($_POST['class'] == ""){
      $error[] = 'Choose your Class!!';
   }

   if(strlen($_POST['password']) < 3){
      $error[] = 'Password is too short';
   }
   
   
   if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
      $error[] = 'Fill with your true email!!';
   }
   else{
      $stmt = $db -> prepare('SELECT email FROM user WHERE email = :email');
      $stmt -> execute(array(':email' => $_POST['email']));
      $row = $stmt -> fetch(PDO::FETCH_ASSOC);
      
      if(!empty($row['email'])){
         $error[] = 'This Email already registered!';
      }
   }
   
   if(!isset($error)){
      $hashedpassword = $user -> password_hash($_POST['password'], PASSWORD_BCRYPT);
      
      $activasion = md5(uniqid(rand(),true));
      
      try{
         $stmt = $db -> prepare('INSERT INTO user(name, username, email, class, password) VALUES(:name, :username, :email, $classes, :password)');
         $stmt -> execute(array(
         ':username' => $_POST['username'],
         ':password' => $hashedpassword,
         ':email' => $_POST['email'],
         ':active' => $activasion
         ));
         $id = $db -> lastInsertId('id');
         
         $to = $_POST['email'];
         $subject = "Registration Confirmation";
         $body = "Thankyou for join the middle earth
         <p>To activate your account, please click on this link: <a href='" .DIR. "activate.php?x=$id &y= $activasion '>" .DIR."activate.php?x=$id &y= $activasion </a></p>
<br/>
<br/>
Regards";

$mail = new Mail();
$mail -> setFrom(SITEEMAIL);
$mail -> addAddress($to);
$mail -> subject($subject);
$mail -> body($body);
$mail -> send();

header('Location:index.php?action=joined');
exit;
}
catch(PDOException $e){
   $error[] = $e -> getMessage();
}
}
}
?>
<html>
<head>
<title>Welcome to online rpg</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<center><h1>Selamat datang di Middle Earth!</h1></center>
<br/>
The Online board rpg game
<br/>
<br/>
<form name="login" action="" method="post" autocomplete="off">
<b>Sign-up here the choosen one!</b>
<br/>
Already on the pub? <a href="login.php">Login here!</a>
<br/>
<hr/>

<?php
if(isset($error)){
   foreach($error as $error){
      echo '<p class="bg-danger">'.$error.'</p>';
   }
}

if(isset($_GET['action'])&& $_GET['action']=='joined'){
   echo "<h2 class='bg-success'>You are now registered,we sent an activation to the registered email. Activate it to get access to the PUB</h2>";
}
?>

   
<b>Your name:</b>
<input type="text" placeholder="Enter your name" name="full_name" tabindex="1">
<br/>
<b>Username:</b>
<input type="text" id="username" placeholder="Enter your sacred Username" name="username" value="<? if(isset($error)){echo $_POST['username'];}?>" tabindex="2">
<br/>

<b>Email:</b>
<input type="email" id="email" placeholder="Enter your Email" name="email" value="<? if(isset($error)){echo $_POST['email'];}?>" tabindex="3">
<br/>
<b>Select your class</b>
<select name="class" id="selectClass" tabindex="4">
<option value="">Select..</option>
<option value="warrior">Warrior</option>
<option value="wizard">Wizard</option>
<option value="monk">Monk</option>
<option value="elf">Elf</option>
<option value="orc">Orc</option>
</select>
<br/>
<b>Password:</b>
<input type="password" name="password" id="password" tabindex="5">
<br/>
<b>Confirm password</b>
<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder ="Confirm Password" tabindex = "6" >
<br/>
<input id="button" type="submit" name="submit" value="Register" tabindex="7">
</form>
</body>
</html>
