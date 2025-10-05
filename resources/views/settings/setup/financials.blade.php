<div class="row">
    <div class="col-md-4 p-4">
        <div class="card rounded-4 p-4">
            <h4 class="msubtitle">Add Bill</h4>
            <input type="hidden" id="finbillid">
            <div class="form-data mb-3">
                <label for="finbillname" class="form-label">Bill Name</label>
                <input type="text" class="form-control py-2 px-3 rounded-3" id="finbillname">
            </div>
            <div class="form-data mb-3">
                <label for="finbillamt" class="form-label">Amount</label>
                <input type="number" class="form-control py-2 px-3 rounded-3" id="finbillamt">
            </div>
            <div class="form-data text-center">
                <button
                    type="button"
                    class="btn btn-add px-3 py-2 rounded-3 me-3
                    @if(!$checker->routePermission('settings.save_financial'))
                    disabled
                    @endif
                    "
                    onclick="saveBill()"
                    id="btnaddbill"
                >
                    Submit Bill
                </button>
                <button
                    type="button"
                    class="btn btn-danger px-3 py-2 rounded-3 me-3
                    @if(!$checker->routePermission('settings.delete_financial'))
                    disabled
                    @endif
                    "
                    onclick="deleteBill()"
                    id="btnremovebill"
                >
                    Delete Bill
                </button>
                <button type="button" class="btn btn-default px-3 py-2 rounded-3 border" onclick="resetBill()" id="btnresetbill">Reset</button>
            </div>
        </div>
    </div>
    <div class="col-md-8 p-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Bill Name</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody id="tblfinancials">
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        loadfinancials();
        resetBill();
    });

    function loadfinancials(){
        $("#tblfinancials").html("");
        $.get("{{ route('settings.financials') }}", function(data, status){
            if(status.includes("success")){
                if(data.length == 0){
                    $("#tblfinancials").html("<tr><td colspan='3' class='text-center'>No bills has been added</td></tr>");
                }
                $.each(data, function(i, item){
                    $("#tblfinancials").append("<tr id='bill" + item.id + "' onclick='editBill(" + item.id + ")'>" +
                        "<td class='align-middle'>" + item.bill_name + "</td>" +
                        "<td class='align-middle'>" + parseFloat(item.bill_amt).toFixed(2) + "</td>" +
                    "</tr>");
                });
            }
        });
    }

    function resetBill(){
        $("#finbillid").val("");
        $("#finbillname").val("");
        $("#finbillamt").val("");
        $("#btnremovebill").hide();
    }

    function editBill(id){
        const obj = $("#bill" + id);
        $("#tblfinancials tr").removeClass("active");
        $("#tblfinancials #bill" + id).addClass("active");
        $("#finbillid").val(id);
        $("#finbillname").val(obj.find("td").eq(0).text());
        $("#finbillamt").val(obj.find("td").eq(1).text());
        $("#btnremovebill").show();
    }

    function saveBill(){
        let id = $("#finbillid").val();
        let name = $("#finbillname").val();
        let amt = $("#finbillamt").val();
        let params = {
            _token: "{{ csrf_token() }}",
            id: id,
            name: name,
            amt: amt
        };
        $.post("{{ route('settings.save_financial') }}", params).done(function(res){
            if(res.includes("success")){
                if(id != ""){
                    showtoast("Success", "Bill has been Updated.");
                }
                else{
                    showtoast("Success", "Bill has been Added.");
                }
                loadfinancials();
                resetBill();
            }
        });
    }

    function deleteBill(){
        let params = {
            _token: "{{ csrf_token() }}",
            id: $("#finbillid").val()
        };
        $.post("{{ route('settings.delete_financial') }}", params).done(function(res){
            if(res.includes("success")){
                showtoast("Success", "Bill has been Deleted.");
                loadfinancials();
                resetBill();
            }
        });
    }
</script>