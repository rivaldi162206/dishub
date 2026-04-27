
<section class="blog-posts-area section-gap mt-70 mb-30">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 post-list blog-post-list">
        <div class="row">
            <div class="col-lg-6">
				<h4 style="text-align:center;">Bangkalan Kota</h4>
				<iframe width="100%" height="250"
					src="http://localhost:5000/video/1">
				</iframe>
				<h3 id="count1" style="text-align:center;color:#2e3192;">0</h3>
                <h4 id="status1" style="text-align:center;">-</h4>
				<canvas id="chart1"></canvas>
			</div>
            <!-- kamera vidio2-->
            <div class="col-lg-6">
                <h4 style="text-align:center;">Arosbaya</h4>
                <iframe width="100%" height="250" 
                     src="http://localhost:5000/video/2">
                </iframe>
                <h3 id="count2" style="text-align:center;color:#2e3192;">0</h3>
                <h4 id="status2" style="text-align:center;">-</h4>
                <canvas id="chart2"></canvas>
            </div>
            <!-- kamera video 3 -->
            <div class="col-lg-6">
                <h4 style="text-align:center;">tangkel</h4>
                <iframe width="100%" height="250" 
                     src="http://localhost:5000/video/3">
                </iframe>
                <h3 id="count3" style="text-align:center;color:#2e3192;">0</h3>
                <h4 id="status3" style="text-align:center;">-</h4>
                <canvas id="chart3"></canvas>
            </div>
            <!-- kamera vidio 4-->
              <div class="col-lg-6">
                <h4 style="text-align:center;">Alun-alun</h4>
                <iframe width="100%" height="250" 
                     src="http://localhost:5000/video/4">
                </iframe>
                <h3 id="count4" style="text-align:center;color:#2e3192;">0</h3>
                <h4 id="status4" style="text-align:center;">-</h4>
                <canvas id="chart4"></canvas>
            </div>
		</div>		
        </div>    
				</div>				
			</div>
		</div>
	</div>	
</section>

<!--js chart-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function updateCount() {
    fetch("get_count.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("vehicleCount").innerText = data.total;
        })
        .catch(error => console.log("Error:", error));
}

setInterval(updateCount, 1000);
</script>
<script>
// CHART PER KAMERA
function createChart(canvasId){
    return new Chart(document.getElementById(canvasId), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Kendaraan',
                data: [],
                borderWidth: 2
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

let chart1 = createChart('chart1');
let chart2 = createChart('chart2');
let chart3 = createChart('chart3');
let chart4 = createChart('chart4');


// =========================
// UPDATE PER KAMERA
// =========================
function updateCamera(cameraId, countId, chart) {
    fetch("get_count.php?camera_id=" + cameraId) 
    .then(res => res.json())
    .then(data => {

        let count = parseInt(data.total);
        let now = new Date().toLocaleTimeString();

        // update angka
        document.getElementById(countId).innerText = count;

        // update chart
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(count);

        if(chart.data.labels.length > 10){
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }

        chart.update();

        // ======================
        // STATUS GLOBAL (AMBIL DARI KAMERA 1)
        // ======================
        let status = "LANCAR";
        color ="green";

        if(count > 40){
            status = "PADAT";
            color = "orange";
        }
        if(count > 100){
            status = "MACET";
            color = "red";
        }

        let el = document.getElementById("status" + cameraId);
        el.innerText = status;
        el.style.color = color;
    });
}


// =========================
// LOOP UPDATE
// =========================
setInterval(() => {
    updateCamera(1, "count1", chart1);
    updateCamera(2, "count2", chart2);
    updateCamera(3, "count3", chart3);
    updateCamera(4, "count4", chart4);
}, 1000);

</script>