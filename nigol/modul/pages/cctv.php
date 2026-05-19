<?php
include '../database.php';
?>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Management CCTV</h3>
    </div>

    <div class="panel-body">

        <a href="?page=cctv_add" class="btn btn-primary mb-3">
            Tambah CCTV
        </a>

        <br><br>

        <table class="table table-bordered" id="bootstrap-data-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lokasi</th>
                    <th>URL CCTV</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php

            $query = mysqli_query($con,"
                SELECT * FROM cameras
                ORDER BY id DESC
            ");

            while($row = mysqli_fetch_assoc($query)){

            ?>

            <tr>

                <td><?php echo $row['id']; ?></td>

                <td><?php echo $row['location']; ?></td>

                <td><?php echo $row['camera_url']; ?></td>

                <td>
                    <?php
                    if($row['is_active'] == 1){
                        echo "Aktif";
                    }else{
                        echo "Nonaktif";
                    }
                    ?>
                </td>

                <td>

                    <a href="?page=cctv_edit&id=<?php echo $row['id']; ?>"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="?page=cctv_hapus&id=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus CCTV ini?')">
                        Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>
</div>