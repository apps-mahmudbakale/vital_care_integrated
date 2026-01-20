<div>
    <div class="d-flex justify-content-end mb-4">
        <button type="button" id="record-vitals-btn" 
            data-url="{{ route('app.vitals.create', $patient->id) }}"
            class="btn btn-primary">
            Record New Vitals
        </button>
    </div>
    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- BP Chart -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Blood Pressure</h5>
                    <small class="text-muted">Systolic / Diastolic</small>
                </div>
                <div class="card-body">
                    <div id="bpChart"></div>
                </div>
            </div>
        </div>

        <!-- Pulse Chart -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Pulse Rate</h5>
                    <small class="text-muted">Beats per minute</small>
                </div>
                <div class="card-body">
                    <div id="pulseChart"></div>
                </div>
            </div>
        </div>

        <!-- Temperature Chart -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Temperature</h5>
                    <small class="text-muted">Celsius (°C)</small>
                </div>
                <div class="card-body">
                    <div id="tempChart"></div>
                </div>
            </div>
        </div>
        
         <!-- Resp Chart -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Respiratory Rate</h5>
                    <small class="text-muted">Breaths per minute</small>
                </div>
                <div class="card-body">
                    <div id="respChart"></div>
                </div>
            </div>
        </div>
        
         <!-- SpO2 Chart -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">SpO2</h5>
                    <small class="text-muted">Oxygen Saturation (%)</small>
                </div>
                <div class="card-body">
                    <div id="spo2Chart"></div>
                </div>
            </div>
        </div>
        
         <!-- Weight Chart -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Weight</h5>
                    <small class="text-muted">Kilograms (kg)</small>
                </div>
                <div class="card-body">
                    <div id="weightChart"></div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        let charts = {};

        const renderCharts = (vitals) => {
            // Helper to get series data or empty array
            const getData = (key) => vitals[key] || [];

            const getOptions = (name, data, color) => ({
                chart: {
                    type: 'area',
                    height: 200,
                    toolbar: { show: false },
                    sparkline: { enabled: false }
                },
                series: [{ name: name, data: data }],
                xaxis: { 
                    type: 'datetime',
                    labels: { show: false, datetimeFormatter: { year: 'yyyy', month: 'MMM \'yy', day: 'dd MMM', hour: 'HH:mm' } } 
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: { opacity: 0.3 },
                colors: [color],
                grid: { show: true, strokeDashArray: 4, padding: { left: 10, right: 10 } },
                tooltip: { x: { format: 'dd MMM HH:mm' }, theme: 'light' }
            });

            const bpOptions = {
                ...getOptions('BP', [], '#ff9f43'),
                series: [
                    { name: 'Systolic', data: getData('Systolic BP') },
                    { name: 'Diastolic', data: getData('Diastolic BP') }
                ],
                colors: ['#ea5455', '#7367f0']
            };

            // Destroy existing charts if any to prevent memory leaks/duplicates
            if (charts.bp) charts.bp.destroy();
            if (charts.pulse) charts.pulse.destroy();
            if (charts.temp) charts.temp.destroy();
            if (charts.resp) charts.resp.destroy();
            if (charts.spo2) charts.spo2.destroy();
            if (charts.weight) charts.weight.destroy();

            charts.bp = new ApexCharts(document.querySelector("#bpChart"), bpOptions); charts.bp.render();
            charts.pulse = new ApexCharts(document.querySelector("#pulseChart"), getOptions('Pulse', getData('Pulse'), '#ea5455')); charts.pulse.render();
            charts.temp = new ApexCharts(document.querySelector("#tempChart"), getOptions('Temperature', getData('Temperature'), '#ff9f43')); charts.temp.render();
            charts.resp = new ApexCharts(document.querySelector("#respChart"), getOptions('Respiration', getData('Respiration'), '#00bad1')); charts.resp.render();
            charts.spo2 = new ApexCharts(document.querySelector("#spo2Chart"), getOptions('SpO2', getData('SpO2'), '#28c76f')); charts.spo2.render();
            charts.weight = new ApexCharts(document.querySelector("#weightChart"), getOptions('Weight', getData('Weight'), '#7367f0')); charts.weight.render(); // Note: Check if 'Weight' is in seeds? It wasn't in migration seeds, only vitals. Wait, need to check migration content again.
        };

        // Initial Load
        renderCharts(@json($this->vitalsHistory));

        // Listen for updates
        Livewire.on('refreshVitals', (payload) => {
            const data = payload[0]?.data || payload.data; 
            if (data) renderCharts(data);
        });

        // Modal Loading Logic
        $(document).on('click', '#record-vitals-btn', function() {
            let url = $(this).data('url');
            let modal = $('#global-modal'); // Make sure this ID Matches global modal
            
            modal.modal('show');
            modal.find('.modal-content').html('<div class="p-5 text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            
            $.get(url, function(data) {
                modal.find('.modal-content').html(data);
            });
        });
        
        Livewire.on('close-modal', () => {
             $('#global-modal').modal('hide');
        });

    </script>
    @endscript
</div>
