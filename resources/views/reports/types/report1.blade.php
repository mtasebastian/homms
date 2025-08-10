<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Resident</th>
            <th scope="col">Year</th>
            <th scope="col">Month</th>
            <th scope="col" class="text-end">Amount</th>
            <th scope="col" class="text-end">Payment</th>
            <th scope="col" class="text-end">Balance</th>
            <th scope="col">Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($results as $financial)
        <tr>
            <td>{{ $financial->id }}</td>
            <td>{{ $financial->resident->fullname }}</td>
            <td>{{ $financial->bill_year }}</td>
            <td>{{ $financial->monthname }}</td>
            <td class="text-end">{{ number_format($financial->bill_amount, 2, '.', ',') }}</td>
            <td class="text-end">{{ number_format($financial->payments()->sum('payment'), 2, '.', ',') }}</td>
            <td class="text-end">{{ number_format($financial->balances()->sum('balance'), 2, '.', ',') }}</td>
            <td>{{ date("m/d/y", strtotime($financial->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="d-flex"><div class="mx-auto">{{ $results->links() }}</div></div>