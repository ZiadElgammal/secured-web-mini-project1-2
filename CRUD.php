<?php
$method = $_SERVER['REQUEST_METHOD'];
function db_connect()
{
  $servername = "localhost";
  $username = "root";
  $password = "machine1";
  $dbname = "contacts";
  /// Create connection
  $conn = new mysqli($servername, $username, $password,$dbname);
  if ($conn->connect_error) {
  	die("Connection failed: " . $conn->connect_error);
} return $conn;
}//db_connect close
function RetriveContact($id)
{ $conn = db_connect();
 //echo "get contact func";
//$sql = "SELECT id, first_name, last_name FROM MyGuests";
  $sql = "select * from `contact`".($id?" WHERE id=$id":'');
  $con_results = mysqli_query($conn, $sql);
  $data = array();//this array to save data from db
  $result = mysqli_fetch_assoc($con_results);
 $data[] = $result;

 if(mysqli_num_rows($con_results) > 0){

   $sql = "SELECT phone_number FROM phone_numbers WHERE contact_id ='$id' ";
   $con_results = mysqli_query($conn, $sql);
   $result = mysqli_fetch_assoc($con_results);
   $phone[] = $result;

 }
 print_r(json_encode($data));
 print_r(json_encode($phone));
 die();
}

function InsertData()
{
  if (file_get_contents('php://input',true)){
  $json_requst =file_get_contents('php://input',true);
  $data = json_decode($json_requst, TRUE);
 //print_r($data);
  $name = $data['first_name'];
  $surname = $data['last_name'];
  $phone = $data['phone_number'];
   }
  $conn = db_connect();
  $name = mysqli_real_escape_string($conn,$name);
  $surname = mysqli_real_escape_string($conn,$surname);
  $phone = mysqli_real_escape_string($conn,$phone);
      $sql = "INSERT INTO contact"." (first_name,last_name)"." VALUES ('".  $name."' , '".$surname."');";
      $results = mysqli_query($conn, $sql);
      if($results){
        $contactID = mysqli_insert_id($conn);

        $sql = "INSERT INTO phone_numbers" ." (phone_title,phone_number, default_num ,contact_id)"
        ." VALUES ("."'HOME'".",".$phone.","."1".",".$contactID.");";
        $results = mysqli_query($conn, $sql);
        echo "data inserted in tables";
        if(!$results ){
          die("SQL error " . mysqli_error($conn));
        }
      } else{
        die("SQL error " . mysqli_error($conn));
      }
}


function UpdateData()
{
   if (file_get_contents('php://input',true)){
   $json_requst =file_get_contents('php://input',true);
   $data = json_decode($json_requst, TRUE);
  //print_r($data);
   $id = $data["id"];//print_r($id);
   $name = $data['first_name'];
   $surname = $data['last_name'];
   $phone = $data['phone_number'];
 }
$conn = db_connect();
 $sql = " UPDATE contact SET first_name ='$name' ,  last_name ='$surname'  WHERE id= '$id' " ;
 $result = mysqli_query($conn,$sql);

 if ($result) {
   echo " data is updated ";
   echo "<br/>";
 }else {
   die("data failed".mysqli_error($conn));
   echo "<br/>";
 }

 $sql = " UPDATE phone_numbers SET phone_number ='$phone' WHERE contact_id= '$id' " ;
 $result = mysqli_query($conn,$sql);

 if ($result) {
   echo "phone updated <br>";
 }else {
   die("data failed".mysqli_error($conn));
   echo "<br/>";
 }
}// update func close

 function DeleteData()
{
  if (file_get_contents('php://input',true)){
  $json_requst =file_get_contents('php://input',true);
  $data = json_decode($json_requst, TRUE);
 //var_dump($data);
  $id = $data["id"];//var_dump($id);
}
  $conn = db_connect();
  $id = mysqli_real_escape_string($conn,$id);//var_dump($id);
  $sql = "DELETE FROM contact WHERE id='$id'";
  $conn_results = mysqli_query($conn, $sql);
  $sql = "DELETE FROM  phone_numbers  WHERE  contact_id = '$id'";
  $conn_results = mysqli_query($conn, $sql);
  if(!$conn_results){
    die("Sql Error " . mysqli_error($conn));
  }
  else {
    die("Record Deleted" . mysqli_error($conn));
  }
}//delete function close

switch ($method) {
case 'GET': //retrive
$id = $_GET['id'];
//print_r($id);
//function to retrive contact
RetriveContact($id);
break;
case 'PUT'://update
UpdateData();
break;
case 'POST'://insert into table
InsertData();
break;
case 'DELETE'://delete data from table
DeleteData();

}
  ?>
