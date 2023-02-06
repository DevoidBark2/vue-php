<?php
$connect = new mysqli("localhost","root","","vue-php");
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT,DELETE");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: Accept,Content-Type,Content-Length,Accept-Encoding,
        X-CSRF-Token,Authorization");
    exit(0);
}

$res = array('error' => false);
$action="";
if(isset($_GET['action'])){
    $action = $_GET['action'];
}

if($action == 'addcomment'){
    $name = $_POST['user_name'];
    $comment = $_POST['comment'];
    $sql = "INSERT INTO `comments` (`user_name`,`comment`) VALUES ('$name','$comment')";
    $result=$connect->query($sql);
    if($result === true){
        $res['message'] = $name . ", ваш комментарий добавлен!";
    }
    else{
        $res['message'] = 'Error!';
    }
}

if($action == 'getcomments'){
    $sql = "SELECT * FROM `comments`";
    $result = $connect->query($sql);
    $num = mysqli_num_rows($result);
    $commentData = array();
    if($num > 0){
        while($row = $result->fetch_assoc()){
            array_push($commentData,$row);
        }
        $reverse_data=array_reverse($commentData);
        $res['error'] = false;
        $res['comment_data'] = $reverse_data;
    }else{
        $res['error'] = false;
        $res['message'] = 'Not found data!';
    }
}

if($action == 'deletecomment'){
    $del = $_GET['id'];
    $sql="DELETE FROM comments WHERE id=$del";
    $result=$connect->query($sql);
    if($result === true){
        $res['message']="Комментарий удален!";
    }
    else{
        $res['message']= "Ошибка удаления";
    }
}
$connect->close();
die();
?>