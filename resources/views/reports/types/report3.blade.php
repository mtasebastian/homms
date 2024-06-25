<table class="table">
    <thead>
        <tr>
            <th scope="col" class="align-top tbl-d-none">ID</th>
            <th scope="col" class="align-top">Full Name</th>
            <th scope="col" class="align-top tbl-d-none">Address</th>
            <th scope="col" class="align-top tbl-d-none">Email Address</th>
            <th scope="col" class="align-top tbl-d-none">Mobile Number</th>
            <th scope="col" class="align-top text-center">Status</th>
            <th scope="col" class="align-top">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $resident)
        <tr id="res_{{ $resident->id }}" onclick="optres({{ $resident->id }})">
            <td class="tbl-d-none">{{ $resident->id }}</td>
            <td>{{ $resident->fullname }}</td>
            <td class="w-25 tbl-d-none">{{ 'Brgy. ' . ucwords(strtolower($resident->barangay->name)) . " " . $resident->hoaaddress . ", " . ucwords(strtolower($resident->city->name)) . (str_contains(strtolower($resident->city->name), 'city') ? ', ' : ' City, ') . ucwords(strtolower($resident->province->name)) }}</td>
            <td class="tbl-d-none">{{ $resident->email_address }}</td>
            <td class="tbl-d-none">{{ $resident->mobile_number }}</td>
            <td class="text-center">{{ $resident->status() }}</td>
            <td>{{ date("m/d/y", strtotime($resident->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>