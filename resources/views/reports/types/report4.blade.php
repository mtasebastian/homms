<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Complaint Type</th>
            <th scope="col">Complainant</th>
            <th scope="col">Defendant</th>
            <th scope="col">Details</th>
            <th scope="col">Reported To</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $complaint)
        <tr>
            <td>{{ $complaint->id }}</td>
            <td>{{ $complaint->complaint_type }}</td>
            <td>{{ $complaint->resident->fullname }}</td>
            <td>{{ $complaint->complaint_to ? $complaint->defendant->fullname : '' }}</td>
            <td>{{ $complaint->details }}</td>
            <td>{{ $complaint->reported_to->name }}</td>
            <td class="text-center">{{ $complaint->status }}</td>
            <td>{{ date("m/d/y", strtotime($complaint->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>