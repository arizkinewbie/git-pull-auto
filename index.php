<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Git Pull Automation Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container">
        <h1>GIT Pull Automation Management</h1>
        <button class="btn btn-primary mb-3" id="tambahLink">Tambah Link Web</button>
        <button class="btn btn-secondary mb-3" id="syncAllActive">Sync Semua (Active)</button>
        <div id="dataContainer"></div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Form Link Web</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formLink">
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label for="web_link" class="form-label">URL</label>
                            <input type="text" class="form-control" id="web_link" name="web_link" placeholder="URL berisi respon dari git pull (shell_exec)" required>
                            <small class="text-muted">contoh: https://pull.arizkinewbie.com/pull.php</small>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Fungsi untuk menampilkan data dari JSON
        const loadData = () => {
            $.getJSON("data.json", function(data) {
                var html = "<table id='usersTable' class='table table-striped' style='width:100%;'>";
                html += "<thead><tr><th>ID</th><th>Link Website</th><th>Status</th><th>Action</th></tr></thead><tbody>";
                $.each(data, function(key, value) {
                    html += "<tr>";
                    html += "<td>" + value.id + "</td>";
                    html += "<td>" + value.web_link + "</td>";
                    html += "<td>" + value.status + "</td>";
                    html += "<td style='white-space: nowrap;'><a href='#' class='btn btn-success btn-sync' data-link='" + value.web_link + "'>Sync</a> <a href='#' class='btn btn-primary btn-edit' data-id='" + value.id + "' data-link='" + value.web_link + "' data-status='" + value.status + "'>Edit</a> <a href='#' class='btn btn-danger btn-delete' data-id='" + value.id + "'>Hapus</a></td>";
                    html += "</tr>";
                });
                html += "</tbody></table>";
                $('#dataContainer').html(html);

                // Inisialisasi DataTables
                $('#usersTable').DataTable({
                    "scrollX": true,
                    "scrollCollapse": true,
                    "order": [
                        [2, 'asc']
                    ],
                });
            });
        };

        // Panggil fungsi loadData saat halaman dimuat
        $(document).ready(function() {
            loadData();
        });

        // Tambah atau Edit
        $('#tambahLink').click(function() {
            $('#modalFormLabel').text('Tambah Link Web');
            $('#id').val('');
            $('#web_link').val('');
            $('#status').val('active');
            $('#modalForm').modal('show');
        });

        // Edit
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var web_link = $(this).data('link');
            var status = $(this).data('status');
            $('#modalFormLabel').text('Edit Link Web');
            $('#id').val(id);
            $('#web_link').val(web_link);
            $('#status').val(status);
            $('#modalForm').modal('show');
        });

        // Submit Form
        $('#formLink').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'aksi_tambah_edit.php',
                data: formData,
                success: function(response) {
                    $('#modalForm').modal('hide');
                    loadData();
                }
            });
        });

        // Hapus
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            if (confirm('Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    type: 'POST',
                    url: 'aksi_hapus.php',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        loadData();
                    }
                });
            }
        });

        // Sync
        $(document).on('click', '.btn-sync', function() {
            var url = $(this).data('link');
            $.ajax({
                type: 'POST',
                url: 'aksi_sync.php',
                data: {
                    url: url
                },
                success: function(response) {
                    alert(response);
                    loadData();
                }
            });
        });
    </script>
</body>

</html>