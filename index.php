<?php
    include 'database.php';

    $obj = new database();
    //$obj->insert('student',['fname'=>'shweta','lname'=>'goswami','email'=>'shweta@gmail.com','phno'=>'789456130']);
    //$obj->update('student',['fname'=>'shweta','lname'=>'goswami','email'=>'lal@gmail.com','phno'=>'789456130'],3);
    $obj->delete('student',6);
?>