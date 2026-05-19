<?php

include '../database.php';

$id = $_GET['id'];

mysqli_query($con,"
    DELETE FROM cameras
    WHERE id='$id'
");

echo "
<script>
    alert('CCTV berhasil dihapus');
    window.location='?page=cctv';
</script>
";

?>