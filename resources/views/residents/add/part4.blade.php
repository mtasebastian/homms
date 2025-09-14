<div class="row">
    <div class="col-md-7 mb-3 mb-md-0">
        <div class="xlist">
            <p class="none">No vehicle has been added</p>
            <ul class="list-group" id="resvehicles"></ul>
        </div>
    </div>
    <div class="col-md-5 reqpop" id="respart4">
        <input type="hidden" id="resvehicleid">
        <div class="form-data mb-3">
            <label for="resvehicletype" class="form-label">Type</label>
            <select class="form-select py-2 px-3 rounded-3 req" id="resvehicletype">
                <option value="">Select Vehicle Type</option>
            </select>
        </div>
        <div class="form-data mb-3">
            <label for="resbrand" class="form-label">Brand</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resbrand" placeholder="Enter Label">
        </div>
        <div class="form-data mb-3">
            <label for="resmodel" class="form-label">Model</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resmodel" placeholder="Enter Model">
        </div>
        <div class="form-data mb-3">
            <label for="resplatenumber" class="form-label">Plate Number</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resplatenumber" placeholder="Enter Plate Number">
        </div>
        <div class="row">
            <div class="col-md-6 d-grid">
                <button type="button" class="btn btn-add py-2 rounded-3 submit" data-func="savevehicle">Save Vehicle</button>
            </div>
            <div class="col-md-6 d-grid">
                <button type="button" class="btn btn-light border py-2 rounded-3" onclick="resetFields('respart4')">Reset</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="resvehiclelist" id="resvehiclelist">
</div>
<script>
    function initrespart4(){
        displayvehicles();
        resetFields('respart4');
    }

    function savevehicle(){
        let vehicle = {
            vehicletype: $("#resvehicletype").val(),
            brand: $("#resbrand").val(),
            model: $("#resmodel").val(),
            platenumber: $("#resplatenumber").val()
        };
        if($("#resvehicleid").val() != ""){
            vehicles[parseInt($("#resvehicleid").val())] = vehicle;
        }
        else{
            vehicles.push(vehicle);
        }
        $("#resvehiclelist").val(JSON.stringify(vehicles));
        resetFields('respart4');
        displayvehicles();
    }

    function displayvehicles(){
        $("#resvehicles").html("");
        if(vehicles.length > 0){
            $("#resvehicles").prev(".none").hide();
            $.each(vehicles, function(i, item){
                $("#resvehicles").append("<li class='list-group-item'>" +
                    "<p class='mb-0'>Brand/Model: <b>" + item.brand + " " + item.model + "</b></p>" +
                    "<p class='mb-0'>Type: <b>" + item.vehicletype + "</b></p>" +
                    "<p class='mb-0'>Plate Number: <b>" + item.platenumber + "</b></p>" +
                    "<button type='button' class='btn btn-white p-1 px-2 mx-1 border-success edit' onclick='editvehicle(" + i + ")'><i class='fa-solid fa-pen-to-square text-success'></i></button>" +
                    "<button type='button' class='btn btn-white p-1 px-2 mx-1 border-danger delete' onclick='deletevehicle(" + i + ")'><i class='fa-solid fa-trash-alt text-danger'></i></button>" +
                "</li>");
            });
        }else{
            $("#resvehicles").prev(".none").show();
        }
    }

    function editvehicle(i){
        const vehicle = vehicles[i];
        $("#resvehicleid").val(i);
        $("#resvehicletype").val(vehicle.vehicletype);
        $("#resbrand").val(vehicle.brand);
        $("#resmodel").val(vehicle.model);
        $("#resplatenumber").val(vehicle.platenumber);
    }

    function deletevehicle(i){
        vehicles.splice(i, 1);
        $("#resvehiclelist").val(JSON.stringify(vehicles));
        displayvehicles();
    }
</script>