<div class="row">
    <div class="col-md-7 mb-3 mb-md-0">
        <div class="xlist">
            <p class="none">No occupant has been added</p>
            <ul class="list-group" id="resoccupants"></ul>
        </div>
    </div>
    <div class="col-md-5 reqpop" id="respart3">
        <input type="hidden" id="resoccupantid">
        <div class="form-data mb-3">
            <label for="resolastname" class="form-label">Last Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resolastname" placeholder="Enter Last Name">
        </div>
        <div class="form-data mb-3">
            <label for="resofirstname" class="form-label">First Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resofirstname" placeholder="Enter First Name">
        </div>
        <div class="form-data mb-3">
            <label for="resomiddlename" class="form-label">Middle Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" id="resomiddlename" placeholder="Enter Middle Name">
        </div>
        <div class="form-data mb-3">
            <label for="resoemailaddress" class="form-label">Email Address</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resoemailaddress" placeholder="Enter Email Address">
        </div>
        <div class="form-data mb-3">
            <label for="resomobilenumber" class="form-label">Mobile Number</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resomobilenumber" placeholder="Enter Mobile Number">
        </div>
        <div class="row mb-3">
            <div class="col-md-5 form-data">
                <label for="resoage" class="form-label">Age</label>
                <input type="text" class="form-control py-2 px-3 rounded-3 req" id="resoage" placeholder="Enter Age">
            </div>
            <div class="col-md-7 form-data">
                <label for="resogender" class="form-label">Gender</label>
                <select class="form-select py-2 px-3 rounded-3 req" id="resogender" placeholder="Enter Gender">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
        <div class="form-data mb-3">
            <label for="resrelation" class="form-label">Relationship to Owner</label>
            <select class="form-select py-2 px-3 rounded-3 req" id="resrelation">
                <option value="">Select Relation</option>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 d-grid">
                <button type="button" class="btn btn-add py-2 rounded-3 submit" data-func="saveoccupant">Save Occupant</button>
            </div>
            <div class="col-md-6 d-grid">
                <button type="button" class="btn btn-light border py-2 rounded-3" onclick="resetFields('respart3')">Reset</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="resoccupantlist" id="resoccupantlist">
</div>
<script>
    let occupants = [];

    function initrespart3(){
        displayoccupants();
        resetFields('respart3');
    }

    function saveoccupant(){
        let occupant = {
            lastname: $("#resolastname").val(),
            firstname: $("#resofirstname").val(),
            middlename: $("#resomiddlename").val(),
            emailaddress: $("#resoemailaddress").val(),
            mobilenumber: $("#resomobilenumber").val(),
            age: $("#resoage").val(),
            gender: $("#resogender").val(),
            relation: $("#resrelation").val()
        };
        if($("#resoccupantid").val() != ""){
            occupants[parseInt($("#resoccupantid").val())] = occupant;
        }
        else{
            occupants.push(occupant);
        }
        $("#resoccupantlist").val(JSON.stringify(occupants));
        resetFields('respart3');
        displayoccupants();
    }

    function displayoccupants(){
        $("#resoccupants").html("");
        if(occupants.length > 0){
            $("#resoccupants").prev(".none").hide();
            $.each(occupants, function(i, item){
            $("#resoccupants").append("<li class='list-group-item'>" +
                "<p class='mb-0'>Full Name: <b>" + item.lastname + ", " + item.firstname + " " + item.middlename.charAt(0) + ".</b></p>" +
                "<p class='mb-0'>Email Address: <b>" + item.emailaddress + "</b></p>" +
                "<p class='mb-0'>Mobile Number: <b>" + item.mobilenumber + "</b></p>" +
                "<p class='mb-0'>Relationship to Owner: <b>" + item.relation + "</b></p>" +
                "<button type='button' class='btn btn-white p-1 px-2 mx-1 border-success edit' onclick='editoccupant(" + i + ")'><i class='fa-solid fa-pen-to-square text-success'></i></button>" +
                "<button type='button' class='btn btn-white p-1 px-2 mx-1 border-danger delete' onclick='deleteoccupant(" + i + ")'><i class='fa-solid fa-trash-alt text-danger'></i></button>" +
            "</li>");
        });
        }else{
            $("#resoccupants").prev(".none").show();
        }
    }

    function editoccupant(i){
        const occupant = occupants[i];
        $("#resoccupantid").val(i);
        $("#resolastname").val(occupant.lastname);
        $("#resofirstname").val(occupant.firstname);
        $("#resomiddlename").val(occupant.middlename);
        $("#resoemailaddress").val(occupant.emailaddress);
        $("#resomobilenumber").val(occupant.mobilenumber);
        $("#resoage").val(occupant.age);
        $("#resogender").val(occupant.gender);
        $("#resrelation").val(occupant.relation);
    }

    function deleteoccupant(i){
        occupants.splice(i, 1);
        displayoccupants();
    }
</script>