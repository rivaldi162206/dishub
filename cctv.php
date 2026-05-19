<?php
include 'database.php';

$query = mysqli_query($con, "SELECT * FROM cameras WHERE is_active = 1");
?>

<section class="blog-posts-area section-gap mt-70 mb-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 post-list blog-post-list">

                <div class="row">

                <?php while($cam = mysqli_fetch_assoc($query)) { ?>

                    <div class="col-lg-6 mb-5">

                        <!-- NAMA LOKASI -->
                        <h4 style="text-align:center;color:white;">
                            <?php echo $cam['location']; ?>
                        </h4>

                        <!-- STREAM VIDEO -->
                        <iframe
                            width="100%"
                            height="250"
                            src="http://localhost:5000/video/<?php echo $cam['id']; ?>"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>

                        <!-- JUMLAH -->
                        <h5 style="text-align:center;color:white;">
                            Jumlah kendaraan saat ini
                        </h5>

                        <h3
                            id="count<?php echo $cam['id']; ?>"
                            style="text-align:center;color:#00e5ff;">
                            0
                        </h3>

                        <!-- STATUS -->
                        <h4
                            id="status<?php echo $cam['id']; ?>"
                            style="text-align:center;color:white;">
                            -
                        </h4>

                        <!-- CHART -->
                        <canvas id="chart<?php echo $cam['id']; ?>"></canvas>

                    </div>

                <?php } ?>

                </div>

            </div>
        </div>
    </div>
</section>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

let charts = {};

// ===============================
// CREATE CHART
// ===============================
function createChart(canvasId){

    return new Chart(document.getElementById(canvasId), {

        type: 'line',

        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Kendaraan',
                data: [],
                borderWidth: 2,
                tension: 0.3
            }]
        },

        options: {
            responsive: true,

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// ===============================
// INIT CHART SEMUA KAMERA
// ===============================
<?php

$queryChart = mysqli_query($con, "SELECT * FROM cameras WHERE is_active = 1");

while($camChart = mysqli_fetch_assoc($queryChart)) {

?>

charts[<?php echo $camChart['id']; ?>] =
    createChart('chart<?php echo $camChart['id']; ?>');

<?php } ?>

// ===============================
// UPDATE CAMERA
// ===============================
function updateCamera(cameraId){

    let chart = charts[cameraId];

    // ==========================
    // AMBIL DATA DARI DATABASE PHP
    // ==========================
    fetch("get_count.php?camera_id=" + cameraId)

    .then(response => response.json())

    .then(data => {

        let count = parseInt(data.total);

        if(isNaN(count)){
            count = 0;
        }

        let now = new Date().toLocaleTimeString();

        // UPDATE TEXT
        document.getElementById(
            "count" + cameraId
        ).innerText = count;

        // UPDATE CHART
        chart.data.labels.push(now);

        chart.data.datasets[0].data.push(count);

        if(chart.data.labels.length > 10){

            chart.data.labels.shift();

            chart.data.datasets[0].data.shift();
        }

        chart.update();
    })

    .catch(error => {
        console.log("COUNT ERROR:", error);
    });


    // ==========================
    // STATUS AI FASTAPI
    // ==========================
    fetch("http://localhost:5000/api/count/" + cameraId)

    .then(response => response.json())

    .then(ai => {

        let status = ai.status || "-";

        let color = "green";

        if(status === "PADAT"){
            color = "orange";
        }

        if(status === "MACET"){
            color = "red";
        }

        let statusElement = document.getElementById(
            "status" + cameraId
        );

        statusElement.innerText = status;

        statusElement.style.color = color;
    })

    .catch(error => {
        console.log("STATUS ERROR:", error);
    });
}

// ===============================
// LOOP UPDATE SEMUA CAMERA
// ===============================
setInterval(() => {

<?php

$queryLoop = mysqli_query($con, "SELECT * FROM cameras WHERE is_active = 1");

while($camLoop = mysqli_fetch_assoc($queryLoop)) {

?>

    updateCamera(<?php echo $camLoop['id']; ?>);

<?php } ?>

}, 1000);

</script>