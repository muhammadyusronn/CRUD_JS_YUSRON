<?php
$hostname     = "localhost";
$username     = "root";
$password     = "";
$databasename = "koding-next";
// Create connection
$conn = mysqli_connect($hostname, $username, $password,$databasename);
// Check connection
if (!$conn) {
    die("Unable to Connect database: " . mysqli_connect_error());
}
$db=$conn;
// fetch query
function fetch_data(){
    global $db;
    $query="SELECT * from tasks ORDER BY id ASC";
    $exec=mysqli_query($db, $query);
    if(mysqli_num_rows($exec)>0){
        $row= mysqli_fetch_all($exec, MYSQLI_ASSOC);
        return $row;  
            
    }else{
        return $row=[];
    }
    }
    function show_data($fetchData){
    echo '<table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tasks</th>
                    <th scope="col">Description</th>
                    <th scope="col"></th>
                </tr>
            </thead>';
    if(count($fetchData)>0){
        $sn=1;
        foreach($fetchData as $data){ 
    echo "<tr>
            <td>".$data['id']."</td>
            <td>".$data['task']."</td>
            <td>".$data['description']."</td>
            <td>
                <a class='btn btn-warning' onclick='editData(".$data['id'].")'>EDIT</a>
                <a class='btn btn-danger' onclick='deleteData(".$data['id'].")'>DELETE</a>
            </td>
        </tr>";
        
    $sn++; 
        }
    }else{
        
    echo "<tr>
            <td colspan='7'>No Data Found</td>
        </tr>"; 
    }
    echo "</table>";
    }
if($_GET['act']=='getAll'){    
    $fetchData= fetch_data();
    show_data($fetchData);
}
if($_GET['act']=="insert"){
    $query = "INSERT INTO `tasks`(`task`, `description`) VALUES ('".$_POST['task']."','".$_POST['description']."')";
    $exc = mysqli_query($conn, $query);
    $status = ($exc) ? 'success' : 'failed';
    $message = ($exc) ? 'Successfully insert data!' : 'Insert data failed!';
    header("Content-Type: application/json");
    echo json_encode(array('status' => $status, 'message'=> $message));
}
if($_GET['act']=="update"){
    $query = "UPDATE `tasks` SET `task`='".$_POST['task']."', `description`='".$_POST['description']."' WHERE `id`=".$_POST["id"];
    $exc = mysqli_query($conn, $query);
    $status = ($exc) ? 'success' : 'failed';
    $message = ($exc) ? 'Successfully update data!' : 'Update data failed!';
    header("Content-Type: application/json");
    echo json_encode(array('status' => $status, 'message'=> $message));
}
if($_GET['act']=="edit"){
    $query="SELECT * from tasks WHERE id=".$_GET['editId'];
    $exc= mysqli_query($conn,$query);
    $data = mysqli_fetch_assoc($exc);
    echo json_encode($data);
}
if($_GET['act']=="delete"){
    $query="DELETE from tasks WHERE id=".$_GET['deleteId'];
    $exec= mysqli_query($conn,$query);
    if($exec){
      echo "Data was deleted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }
}
?>