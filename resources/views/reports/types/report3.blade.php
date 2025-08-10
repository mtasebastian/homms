<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Full Name</th>
            <th scope="col">Address</th>
            <th scope="col">Email Address</th>
            <th scope="col">Mobile Number</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $resident)
        <tr>
            <td>{{ $resident->id }}</td>
            <td>{{ $resident->fullname }}</td>
            <td>{{ 'Brgy. ' . ucwords(strtolower($resident->barangay->name)) . " " . $resident->hoaaddress . ", " . ucwords(strtolower($resident->city->name)) . (str_contains(strtolower($resident->city->name), 'city') ? ', ' : ' City, ') . ucwords(strtolower($resident->province->name)) }}</td>
            <td>{{ $resident->email_address }}</td>
            <td>{{ $resident->mobile_number }}</td>
            <td class="text-center">{{ $resident->status() }}</td>
            <td>{{ date("m/d/y", strtotime($resident->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>