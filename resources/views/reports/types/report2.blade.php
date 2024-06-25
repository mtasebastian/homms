<table class="table">
    <thead>
        <tr>
            <th scope="col" class="align-top tbl-d-none">ID</th>
            <th scope="col" class="align-top">Request Type</th>
            <th scope="col" class="align-top">Requested By</th>
            <th scope="col" class="align-top tbl-d-none">Type</th>
            <th scope="col" class="align-top tbl-d-none">Validity</th>
            <th scope="col" class="align-top tbl-d-none">Approved By</th>
            <th scope="col" class="align-top tbl-d-none">Checked By</th>
            <th scope="col" class="align-top text-center">Status</th>
            <th scope="col" class="align-top">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $req)
        <tr id="req_{{ $req->id }}" onclick="optreq({{ $req->id }}, '{{ $req->qr_code }}');">
            <td class="tbl-d-none">{{ $req->id }}</td>
            <td>{{ $req->request_type }}</td>
            <td>{{ $req->reqBy->fullname }}</td>
            <td class="tbl-d-none">{{ $req->type }}</td>
            <td class="tbl-d-none">{{ $req->valid_from != '' ? date("m/d/Y", strtotime($req->valid_from)) . ' to ' . date("m/d/Y", strtotime($req->valid_to)) : '' }}</td>
            <td class="tbl-d-none">{{ $req->appBy ? $req->appBy->name : '' }}</td>
            <td class="tbl-d-none">{{ $req->chkBy ? $req->chkBy->name : '' }}</td>
            <td class="text-center">{{ $req->request_status }}</td>
            <td>{{ date("m/d/y", strtotime($req->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>