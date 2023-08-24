<?php



function usersControl($conn)
{
    $usersControl = $conn->query("SELECT * FROM `users`");
    $usersControlCount = $usersControl->rowCount();

    if($usersControlCount == 0){
        return true;
    }else{
        return false;
    }

}
