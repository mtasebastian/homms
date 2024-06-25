<table class="table">
    <thead>
        <tr>
            <th scope="col" class="align-top tbl-d-none">ID</th>
            <th scope="col" class="align-top">Complaint Type</th>
            <th scope="col" class="align-top">Complainant</th>
            <th scope="col" class="align-top tbl-d-none">Defendant</th>
            <th scope="col" class="align-top tbl-d-none">Details</th>
            <th scope="col" class="align-top tbl-d-none">Reported To</th>
            <th scope="col" class="align-top text-center">Status</th>
            <th scope="col" class="align-top">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $complaint)
        <tr id="comp_{{ $complaint->id }}" onclick="optcomp({{ $complaint->id }})">
            <td class="tbl-d-none">{{ $complaint->id }}</td>
            <td>{{ $complaint->complaint_type }}</td>
            <td>{{ $complaint->resident->fullname }}</td>
            <td class="tbl-d-none">{{ $complaint->defendant->fullname }}</td>
            <td class="tbl-d-none">{{ $complaint->details }}</td>
            <td class="tbl-d-none">{{ $complaint->reported_to->name }}</td>
            <td class="text-center">{{ $complaint->status }}</td>
            <td>{{ date("m/d/y", strtotime($complaint->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>