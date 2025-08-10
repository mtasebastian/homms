<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Request Type</th>
            <th scope="col">Requested By</th>
            <th scope="col">Type</th>
            <th scope="col">Validity</th>
            <th scope="col">Approved By</th>
            <th scope="col">Checked By</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $req)
        <tr>
            <td>{{ $req->id }}</td>
            <td>{{ $req->request_type }}</td>
            <td>{{ $req->reqBy->fullname }}</td>
            <td>{{ $req->type }}</td>
            <td>{{ $req->valid_from != '' ? date("m/d/Y", strtotime($req->valid_from)) . ' to ' . date("m/d/Y", strtotime($req->valid_to)) : '' }}</td>
            <td>{{ $req->appBy ? $req->appBy->name : '' }}</td>
            <td>{{ $req->chkBy ? $req->chkBy->name : '' }}</td>
            <td class="text-center">{{ $req->request_status }}</td>
            <td>{{ date("m/d/y", strtotime($req->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>