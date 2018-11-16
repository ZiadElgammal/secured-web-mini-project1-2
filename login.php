 <?php
/// require_once("CRUD_functions.php");
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
echo "post method<br>";
$data = json_decode(file_get_contents('php://input'), true);
//print_r($data);echo "<br>";
$_POST['username'] = $data['username'];
$_POST['password'] = $data['password'];
//print_r( $_POST['password']);

/// databasr cretantials
$servername = "localhost";
$username = "root";
$password = "machine1";
$dbname = "contacts";
/// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
          echo "Connected successfully to database <br/> ";
///sql injection
$userid = mysqli_real_escape_string($conn,$_POST['username']);
$pass = mysqli_real_escape_string($conn,$_POST['password']);

$sql = "SELECT * FROM login WHERE username  = '$userid'and password = '$pass'  ";
//print_r($sql);

$con_results = mysqli_query($conn, $sql);

$usersdb = array();//this array to save data from db
$i =0;
while($row = mysqli_fetch_assoc($con_results)) {
  $usersdb[]=$row;$i =$i+1;
        print_r($usersdb[0]['username']);

        echo " <br>“status code”:200 <br/>";
        echo "user found ";
        echo "<br>you have logged in<br>";
}

    if($i== 0){
	echo " “error_code”:101 <br/>";
	echo "user not found ";
   }
 //while
}//if method post
 ?>
