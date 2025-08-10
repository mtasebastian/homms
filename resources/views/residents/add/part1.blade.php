<div id="respart1">
    <div class="row mb-3">
        <div class="col-md-6 form-data">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="1" name="resstatus" id="resstatus" checked>
                &nbsp;
                Active
            </label>
        </div>
        <div class="col-md-6 form-data">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" value="1" name="resrenovated" id="resrenovated" checked>
                &nbsp;
                Renovated?
            </label>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 form-data">
            <label for="resphase" class="form-label req">Phase</label>
            <select class="form-select py-2 px-3 rounded-3" name="resphase" id="resphase" required>
                <option value="">Select Phase</option>
            </select>
        </div>
        <div class="col-md-3 form-data">
            <label for="reshouseno" class="form-label req">House Number</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="reshouseno" id="reshouseno" placeholder="Enter House Number" required>
        </div>
        <div class="col-md-3 form-data">
            <label for="resblock" class="form-label req">Block</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resblock" id="resblock" placeholder="Enter Block" required>
        </div>
        <div class="col-md-3 form-data">
            <label for="reslot" class="form-label req">Lot</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="reslot" id="reslot" placeholder="Enter Lot" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 form-data">
            <label for="resprovince" class="form-label req">Province</label>
            <select class="form-select py-2 px-3 rounded-3" name="resprovince" id="resprovince" onchange="loadcities(this.value)" required>
                <option value="">Select Province</option>
                @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ ucwords(strtolower($province->name)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-data">
            <label for="rescity" class="form-label req">City</label>
            <select class="form-select py-2 px-3 rounded-3" name="rescity" id="rescity" onchange="loadbarangays(this.value)" required>
                <option value="">Select City</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 form-data">
            <label for="resbarangay" class="form-label req">Barangay</label>
            <select class="form-select py-2 px-3 rounded-3" name="resbarangay" id="resbarangay" required>
                <option value="">Select Barangay</option>
            </select>
        </div>
        <div class="col-md-6 form-data">
            <label for="resstreet" class="form-label req">Street</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resstreet" id="resstreet" placeholder="Enter Street" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 form-data">
            <label for="resunitarea" class="form-label">Unit Area</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resunitarea" id="resunitarea" placeholder="Enter Unit Area Size">
        </div>
        <div class="col-md-3 form-data">
            <label for="resmoveindate" class="form-label">Move-in Date</label>
            <input type="text" class="form-control datepicker py-2 px-3 rounded-3" name="resmoveindate" id="resmoveindate" placeholder="__/__/____">
        </div>
        <div class="col-md-3 form-data">
            <label for="reshousecolor" class="form-label">House Color</label>
            <select class="form-select py-2 px-3 rounded-3" name="reshousecolor" id="reshousecolor" required>
                <option value="">Select House Color</option>
            </select>
        </div>
    </div>
</div>
<script>
    function initrespart1(){
        respart1action();
        $("#respart1").find("input[type='text'], select").val("");
        $("#resstatus").prop("checked", true);
        $("#resrenovated").prop("checked", false);
    }

    function respart1action(){
        $("#respart1 input[type='text']").each(function(){
            if($(this).prop("required")){
                $(this).on("blur", function(){
                    chkrespart1();
                });
            }
        });
        $("#respart1 select").each(function(){
            if($(this).prop("required")){
                $(this).on("change", function(){
                    chkrespart1();
                })
            }
        });
    }

    function chkrespart1(){
        let respart1dis = $("#respart1").find("input[type='text'][required], select[required]").length;
        $("#respart1").find("input[type='text'][required], select[required]").each(function(){
            if($(this).val() != ""){
                respart1dis -= 1;
            }
        });
        if(respart1dis > 0){
            $("#btnrnext").attr("disabled", true);
        }
        else{
            $("#btnrnext").attr("disabled", false);
        }
    }

    function loadcities(id){
        $("#rescity").html("<option value=''>Select City</option>");
        $.get("{{ route('load_cities') }}?province=" + id, function(data, status){
            if(status.includes("success")){
                $.each(data, function(i, item){
                    $("#rescity").append("<option value='" + item.id + "'>" + formatString(item.name) + (item.name.toLowerCase().includes('city') ? "" : " City") + "</option>");
                });
                if(Object.keys(params).length > 0){
                    $("#rescity").val(params.city_id);
                }
            }
        });
    }

    function loadbarangays(id){
        $("#resbarangay").html("<option value=''>Select Barangay</option>");
        $.get("{{ route('load_brgys') }}?city=" + id, function(data, status){       
            if(status.includes("success")){
                $.each(data, function(i, item){
                    $("#resbarangay").append("<option value='" + item.id + "'>Brgy. " + formatString(item.name) + "</option>");
                });
                if(Object.keys(params).length > 0){
                    $("#resbarangay").val(params.barangay_id);
                }
            }
        });
    }
</script>