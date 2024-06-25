<table class="table">
    <thead>
        <tr>
            <th scope="col" class="align-top tbl-d-none">ID</th>
            <th scope="col" class="align-top">Resident</th>
            <th scope="col" class="align-top">Bill Period</th>
            <th scope="col" class="align-top">Bill Amount</th>
            <th scope="col" class="align-top">Balance</th>
            <th scope="col" class="align-top tbl-d-none">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $financial)
        <tr id="fin_{{ $financial->id }}" onclick="optfin({{ $financial->id }})">
            <td class="tbl-d-none">{{ $financial->id }}</td>
            <td>{{ $financial->resident->fullname }}</td>
            <td>{{ date("m/Y", strtotime($financial->bill_period)) }}</td>
            <td>{{ number_format($financial->bill_amount, 2, '.', ',') }}</td>
            <td>{{ number_format($financial->balance, 2, '.', ',') }}</td>
            <td class="tbl-d-none">{{ date("m/d/y", strtotime($financial->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>