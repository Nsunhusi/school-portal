<?php
if (isset($_GET['instructor'])) {
    header('Location: instructor');
}else {
    header('Location: student');
}

?>