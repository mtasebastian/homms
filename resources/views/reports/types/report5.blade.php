<table class="table">
    <thead>
        <tr>
            <th scope="col" class="align-top tbl-d-none">ID</th>
            <th scope="col" class="align-top tbl-d-none">Visitor Type</th>
            <th scope="col" class="align-top">Visitor Name</th>
            <th scope="col" class="align-top">Purpose</th>
            <th scope="col" class="align-top">Time In</th>
            <th scope="col" class="align-top">Time Out</th>
            <th scope="col" class="align-top tbl-d-none">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $visitor)
        <tr id="res_{{ $visitor->id }}"
            @php
                if($visitor->time_out == ''){
                    echo "onclick='optvisit(" . $visitor->id . ")'";
                }
            @endphp
        >
            <td class="tbl-d-none">{{ $visitor->id }}</td>
            <td class="tbl-d-none">{{ $visitor->visitor_type }}</td>
            <td>{{ $visitor->name }}</td>
            <td class="w-25">{{ $visitor->purpose }}</td>
            <td>{{ date("m/d/Y h:i A", strtotime($visitor->time_in)) }}</td>
            <td>{{ $visitor->time_out != '' ? date("m/d/Y h:i A", strtotime($visitor->time_out)) : '' }}</td>
            <td class="tbl-d-none">{{ date("m/d/y", strtotime($visitor->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>