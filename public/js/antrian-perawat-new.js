const loadAntrianData = () => {
    const container = $('#antrianContainer');
    
    // Show loading state
    container.html(`
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data antrian...</p>
        </div>
    `);

    // Fetch data
    $.ajax({
        url: '/perawat/getAntrianPendaftaran',
        method: 'GET',
        success: function(response) {
            if (!response.data || response.data.length === 0) {
                container.html(`
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <h4 class="text-muted">Tidak ada antrian saat ini</h4>
                        <p class="text-muted">Silahkan tunggu hingga ada pasien yang mendaftar</p>
                    </div>
                `);
                return;
            }

            // Render cards
            container.empty();
            const template = document.getElementById('antrianCardTemplate').innerHTML;
            
            response.data.forEach(antrian => {
                // Debug: log created_at untuk memastikan format benar
                console.log('Antrian:', antrian.no_antrian, 'Created at:', antrian.created_at);
                
                const cardHtml = template
                    .replace(/{{id}}/g, antrian.id)
                    .replace(/{{no_antrian}}/g, antrian.no_antrian)
                    .replace(/{{no_rm}}/g, antrian.no_rm)
                    .replace(/{{nama_pasien}}/g, antrian.nama_pasien)
                    .replace(/{{poli_tujuan}}/g, antrian.poli_tujuan)
                    .replace(/{{status}}/g, antrian.status)
                    .replace(/{{created_at}}/g, antrian.created_at || new Date().toISOString());
                
                container.append(cardHtml);
            });

            // Initialize timeago with Indonesian locale
            jQuery.timeago.settings.allowFuture = true;
            jQuery.timeago.settings.lang = "id";
            $("time.timeago").timeago();
        },
        error: function(xhr, status, error) {
            container.html(`
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3 d-block"></i>
                    <h4 class="text-danger">Gagal memuat data</h4>
                    <p class="text-muted">Terjadi kesalahan saat memuat data antrian. Silahkan coba lagi.</p>
                    <button class="btn btn-outline-primary mt-3" onclick="loadAntrianData()">
                        <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                    </button>
                </div>
            `);
            console.error('Error:', error);
        }
    });
};

// Search functionality
const filterAntrian = (searchText) => {
    const cards = $('#antrianContainer .col-md-6');
    if (searchText.length === 0) {
        cards.show();
        return;
    }

    searchText = searchText.toLowerCase();
    cards.each(function() {
        const card = $(this);
        const content = card.text().toLowerCase();
        if (content.includes(searchText)) {
            card.show();
        } else {
            card.hide();
        }
    });
};

// Document ready
$(document).ready(function() {
    // Initial load
    loadAntrianData();

    // Search handler
    let searchTimer;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => filterAntrian(this.value), 300);
    });

    // Modal form submission
    $('#btnSimpanPemeriksaan').click(function() {
        const formData = $('#formPemeriksaan').serialize();
        const btn = $(this);
        
        // Disable button and show loading
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span>Menyimpan...');
        
        $.ajax({
            url: '/perawat/simpanPemeriksaan',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#modalPemeriksaan').modal('hide');
                $('#formPemeriksaan')[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data pemeriksaan berhasil disimpan',
                    timer: 2000,
                    showConfirmButton: false
                });
                loadAntrianData();
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data'
                });
            },
            complete: function() {
                // Re-enable button and restore text
                btn.prop('disabled', false).html('Simpan & Lanjutkan');
            }
        });
    });

    // Handle modal button clicks
    $(document).on('click', '.btn-mulai-pemeriksaan', function() {
        const id = $(this).data('id');
        window.location.href = '/perawat/tandavitalpasien/' + id;
    });

    // Auto refresh every 30 seconds
    setInterval(loadAntrianData, 30000);
});
