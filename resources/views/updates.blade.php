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
