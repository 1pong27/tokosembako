@extends('layout.app')

@section('title', 'Data Kategori')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Data Kategori
        </h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
            <a href="#modal-form" class="btn btn-primary modal-tambah" data-toggle="modal" data-target="#modal-form">Tambah Data</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-kategori" enctype="multipart/form-data"> <!-- Tambahkan enctype di sini -->
                                    <div class="form-group">
                                        <label for="nama_kategori">Nama Kategori</label>
                                        <input type="text" class="form-control" name="nama_kategori" placeholder="Nama Kategori" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea class="form-control" id="deskripsi" cols="30" rows="10" name="deskripsi" placeholder="Masukkan deskripsi" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="gambar">Gambar</label>
                                        <input type="file" class="form-control-file" name="gambar" required>
                                    </div>
                                    <div class="from-group">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(function() {
            $.ajax({
                url: '/api/categories',
                success: function(response) {
                    let rows = '';

                    response.data.forEach(function(category, index) {
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${category.nama_kategori}</td>
                                <td>${category.deskripsi}</td>
                                <td><img src="/uploads/${category.gambar}" width="150"></td>
                                <td>
                                    <a data-toggle="modal" href="#modal-form" data-id="${category.id}" class="btn btn-warning modal-ubah">Edit</a>
                                    <a href="#" data-id="${category.id}" class="btn btn-danger btn hapus">Hapus</a>
                                </td>
                            </tr>
                        `;
                    });

                    $('tbody').append(rows);
                }
            });

            $(document).on('click', '.hapus', function() {
                const categoryId = $(this).data('id');
                const token = getCokie('token'); // Typo yang diperbaiki di sini

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/categories/${categoryId}`,
                            type: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`
                            },
                            success: function(response) {
                                Swal.fire('Sukses', 'Data berhasil dihapus!', 'success');
                                location.reload(); 
                            },
                            error: function(error) {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                            }
                        });
                    }
                });
            });

           // Tambahkan event click pada tombol tambah untuk menampilkan modal
$('.modal-tambah').click(function() {
    $('#modal-form').modal('show');
});

// Tambahkan event submit pada form untuk menangani penambahan data
// Tambahkan event click pada tombol tambah untuk menampilkan modal
$('.modal-tambah').click(function() {
    $('#modal-form').modal('show');
});

// Tambahkan event submit pada form untuk menangani penambahan data
$('.form-kategori').submit(function(e) {
    e.preventDefault();
    const token = localStorage.getItem('token');
    const formData = new FormData(this);

    $.ajax({
        url: 'api/categories',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        success: function(data) {
            if (data.success) {
                alert('Data berhasil ditambahkan');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401) {
                // Handle unauthorized access (redirect to login page, show error message, etc.)
                console.log("Unauthorized access. Redirect to login page or show error message.");
            } else {
                console.log(error);
            }
        }
    });
});



            // Tambahkan event click pada tombol edit untuk menampilkan modal
            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show');
            });
        });
    </script>
@endpush
