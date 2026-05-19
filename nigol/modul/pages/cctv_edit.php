<?php
include '../../database.php';
?>

$id = $_GET['id'];

$query = mysqli_query($con,"
    SELECT * FROM cameras
    WHERE id='$id'
");

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $location   = $_POST['location'];
    $camera_url = $_POST['camera_url'];
    $is_active  = $_POST['is_active'];

    mysqli_query($con,"
        UPDATE cameras
        SET
            location='$location',
            camera_url='$camera_url',
            is_active='$is_active'
        WHERE id='$id'
    ");

    echo "
    <script>
        alert('CCTV berhasil diupdate');
        window.location='?page=cctv';
    </script>";
}
?>

<div class="panel">

    <div class="panel-heading">
        <h3 class="panel-title">Edit CCTV</h3>
    </div>

    <div class="panel-body">

        <form method="POST">

            <div class="form-group">
                <label>Lokasi</label>

                <input type="text"
                       name="location"
                       class="form-control"
                       value="<?php echo $data['location']; ?>"
                       required>
            </div>

            <div class="form-group">
                <label>URL CCTV</label>

                <input type="text"
                       name="camera_url"
                       class="form-control"
                       value="<?php echo $data['camera_url']; ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Status</label>

                <select name="is_active" class="form-control">

                    <option value="1"
                    <?php if($data['is_active']==1) echo 'selected'; ?>>
                        Aktif
                    </option>

                    <option value="0"
                    <?php if($data['is_active']==0) echo 'selected'; ?>>
                        Nonaktif
                    </option>

                </select>
            </div>

            <button type="submit"
                    name="update"
                    class="btn btn-primary">
                Update
            </button>

        </form>

    </div>
</div>