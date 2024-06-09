<div class="row">
    <div class="col-md-7">
        <div class="xlist">
            <p class="none">No pet has been added</p>
            <ul class="list-group" id="respets"></ul>
        </div>
    </div>
    <div class="col-md-5 reqpop" id="respart5">
        <input type="hidden" id="respetid">
        <div class="form-data mb-3">
            <label for="respettype" class="form-label">Type</label>
            <select class="form-select py-2 px-3 rounded-3 req" id="respettype">
                <option value="">Select Pet Type</option>
            </select>
        </div>
        <div class="form-data mb-3">
            <label for="respbreed" class="form-label">Breed</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="respbreed" placeholder="Enter Breed">
        </div>
        <div class="form-data mb-3">
            <label for="respetname" class="form-label">Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3 req" id="respetname" placeholder="Enter Pet Name">
        </div>
        <div class="form-data mb-3">
            <label for="respbirthdate" class="form-label">Birthdate</label>
            <input type="text" class="form-control datepicker py-2 px-3 rounded-3 req" id="respbirthdate" placeholder="__/__/____">
        </div>
        <div class="row">
            <div class="col-md-6 d-grid">
                <button type="button" class="btn btn-add py-2 rounded-3 submit" data-func="savepet">Save Pet</button>
            </div>
            <div class="col-md-6 d-grid">
                <button type="button" class="btn btn-light border py-2 rounded-3" onclick="resetFields('respart5')">Reset</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="respetlist" id="respetlist">
</div>
<script>
    let pets = [];

    function initrespart5(){
        displaypets();
        resetFields('respart5');
    }

    function savepet(){
        let pet = {
            pettype: $("#respettype").val(),
            breed: $("#respbreed").val(),
            petname: $("#respetname").val(),
            birthdate: $("#respbirthdate").val()
        };
        if($("#respetid").val() != ""){
            pets[parseInt($("#respetid").val())] = pet;
        }
        else{
            pets.push(pet);
        }
        $("#respetlist").val(JSON.stringify(pets));
        resetFields('respart5');
        displaypets();
    }

    function displaypets(){
        $("#respets").html("");
        if(pets.length > 0){
            $("#respets").prev(".none").hide();
            $.each(pets, function(i, item){
                $("#respets").append("<li class='list-group-item'>" +
                    "<p class='mb-0'>Name: <b>" + item.petname + "</b></p>" +
                    "<p class='mb-0'>Breed: <b>" + item.breed + "</b></p>" +
                    "<p class='mb-0'>Type: <b>" + item.pettype + "</b></p>" +
                    "<button type='button' class='btn btn-white p-1 px-2 mx-1 border-success edit' onclick='editpet(" + i + ")'><i class='fa-solid fa-pen-to-square text-success'></i></button>" +
                    "<button type='button' class='btn btn-white p-1 px-2 mx-1 border-danger delete' onclick='deletepet(" + i + ")'><i class='fa-solid fa-trash-alt text-danger'></i></button>" +
                "</li>");
            });
        }else{
            $("#respets").prev(".none").show();
        }
    }

    function editpet(i){
        const pet = pets[i];
        $("#respetid").val(i);
        $("#respettype").val(pet.pettype);
        $("#respbreed").val(pet.breed);
        $("#respetname").val(pet.petname);
        $("#respbirthdate").val(pet.birthdate);
    }

    function deletepet(i){
        pets.splice(i, 1);
        displaypets();
    }
</script>