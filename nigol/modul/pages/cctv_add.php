<?php
include __DIR__ . '/../../database.php';

// PROSES SIMPAN
if(isset($_POST['simpan'])){

    $location   = mysqli_real_escape_string($con, $_POST['location']);
    $camera_url = mysqli_real_escape_string($con, $_POST['camera_url']);
    $is_active  = mysqli_real_escape_string($con, $_POST['is_active']);

    $query = mysqli_query($con,"
        INSERT INTO cameras(
            location,
            camera_url,
            is_active
        )
        VALUES(
            '$location',
            '$camera_url',
            '$is_active'
        )
    ");

    if($query){

        echo "
        <script>
            alert('CCTV berhasil ditambahkan');
            window.location='?page=cctv';
        </script>
        ";

    }else{

        echo "
        <script>
            alert('Gagal menambahkan CCTV');
        </script>
        ";
    }
}
?>

<div class="panel">

    <div class="panel-heading">
        <h3 class="panel-title">
            Tambah CCTV
        </h3>
    </div>

    <div class="panel-body">

        <form method="POST">

            <!-- LOKASI -->
            <div class="form-group">
                <label>Nama Lokasi CCTV</label>

                <input
                    type="text"
                    name="location"
                    class="form-control"
                    placeholder="Contoh: Bangkalan Kota"
                    required
                >
            </div>

            <!-- URL -->
            <div class="form-group">
                <label>URL CCTV / RTSP / Video</label>

                <input
                    type="text"
                    name="camera_url"
                    class="form-control"
                    placeholder="Contoh: rtsp:// atau link video"
                    required
                >
            </div>

            <!-- STATUS -->
            <div class="form-group">

                <label>Status Kamera</label>

                <select
                    name="is_active"
                    class="form-control"
                >

                    <option value="1">
                        Aktif
                    </option>

                    <option value="0">
                        Nonaktif
                    </option>

                </select>

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                name="simpan"
                class="btn btn-primary"
            >
                Simpan CCTV
            </button>

            <a
                href="?page=cctv"
                class="btn btn-default"
            >
                Kembali
            </a>

        </form>

    </div>

</div>