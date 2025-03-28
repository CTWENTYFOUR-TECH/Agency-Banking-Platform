<?php
include('../Config/_permission.php');
session_destroy();
session_unset();

echo "<script>
        window.location='../authentication/signin'
    </script>";
?>