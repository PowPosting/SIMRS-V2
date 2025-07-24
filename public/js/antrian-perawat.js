let table;
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // Inisialisasi DataTable
    const tabelAntrian = document.getElementById('tabelAntrian');
    if (!tabelAntrian) {
        console.error('Tabel antrian tidak ditemukan!');
        return;
    }
    
    console.log('Initializing DataTable...');
    table = $(tabelAntrian).DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/index.php/perawat/getAntrianPendaftaran',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('Error:', error);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);
                alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
            },
            dataSrc: function(json) {
                console.log('Response received:', json);
                if (json.error) {
                    console.error('Server error:', json.message);
                    return [];
                }
                return json.data || [];
            }
        },
        columns: [
            {data: 'no_antrian'},
            {data: 'no_rm'},
            {data: 'nama_pasien'},
            {data: 'poli_tujuan'},
            {data: 'status'},
            {
                data: null,
                render: function(data, type, row) {
                    return `<button class="btn btn-primary btn-sm" onclick="mulaiPemeriksaan(${row.id})">
                        Mulai Pemeriksaan
                    </button>`;
                }
            }
        ],
        language: {
            emptyTable: "Tidak ada antrian pasien saat ini",
            processing: "Sedang memuat data...",
            search: "Pencarian:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data yang ditampilkan",
            infoFiltered: "(difilter dari total _MAX_ data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        initComplete: function() {
            console.log('DataTable initialization complete');
        }
    });

    // Refresh data setiap 30 detik
    setInterval(function() {
        table.ajax.reload(null, false);
    }, 30000);
});

function mulaiPemeriksaan(idAntrian) {
    document.getElementById('id_antrian').value = idAntrian;
    $('#modalPemeriksaan').modal('show');
}

document.getElementById('btnSimpanPemeriksaan').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('formPemeriksaan'));
    
    fetch('/perawat/simpanPemeriksaan', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            $('#modalPemeriksaan').modal('hide');
            document.getElementById('formPemeriksaan').reset();
            table.ajax.reload();
            alert('Data pemeriksaan berhasil disimpan');
        } else {
            alert('Terjadi kesalahan: ' + JSON.stringify(data));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});

