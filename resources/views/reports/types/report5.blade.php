<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Visitor Type</th>
            <th scope="col">Visitor Name</th>
            <th scope="col">Purpose</th>
            <th scope="col">Time In</th>
            <th scope="col">Time Out</th>
            <th scope="col">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $visitor)
        <tr>
            <td>{{ $visitor->id }}</td>
            <td>{{ $visitor->visitor_type }}</td>
            <td>{{ $visitor->name }}</td>
            <td>{{ $visitor->purpose }}</td>
            <td>{{ date("m/d/Y h:i A", strtotime($visitor->time_in)) }}</td>
            <td>{{ $visitor->time_out != '' ? date("m/d/Y h:i A", strtotime($visitor->time_out)) : '' }}</td>
            <td>{{ date("m/d/y", strtotime($visitor->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>