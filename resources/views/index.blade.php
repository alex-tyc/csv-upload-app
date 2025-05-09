<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>File Upload Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- File Upload Section -->
        <div class="card">
            <div class="card-header">
                <h4>Upload a File</h4>
            </div>
            <div class="card-body">
                <form id="upload-form" action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" class="form-control" name="file">
                    </div>
                    <button id="upload-button" type="submit" class="btn btn-primary">Upload File</button>
                </form>
            </div>
        </div>

        <!-- Uploaded Files Table -->
        <div id="upload-table" class="mt-4">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>File Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lists as $list)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($list->created_at)->format('j-n-y g:ia') }} 
                            ({{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() }})
                        </td>
                        <td>{{ $list->file_name }}</td>
                        <td>{{ ucfirst($list->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setInterval(function () {
            fetch("{{ route('update.table') }}")
            .then(res => res.text())
            .then(html => {
                document.getElementById("upload-table").innerHTML = html;
            });
        }, 10000);
    </script>
    <script>
        document.getElementById('upload-form').addEventListener('submit', function () {
            const btn = document.getElementById('upload-button');
            btn.disabled = true;
            btn.innerText = 'Uploading...';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
