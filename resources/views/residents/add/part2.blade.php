<div id="respart2">
    <div class="row mb-3">
        <div class="col-md-4 form-data">
            <label for="reslastname" class="form-label req">Last Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="reslastname" id="reslastname" placeholder="Enter Last Name" required>
        </div>
        <div class="col-md-4 form-data">
            <label for="resfirstname" class="form-label req">First Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resfirstname" id="resfirstname" placeholder="Enter First Name" required>
        </div>
        <div class="col-md-4 form-data">
            <label for="resmiddlename" class="form-label">Middle Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resmiddlename" id="resmiddlename" placeholder="Enter Middle Name">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 form-data">
            <label for="reshomeaddress" class="form-label">Home Address</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="reshomeaddress" id="reshomeaddress" placeholder="Enter Home Address" readonly>
        </div>
        <div class="col-md-6 form-data">
            <label for="resotheraddress" class="form-label">Other Address</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resotheraddress" id="resotheraddress" placeholder="Enter Other Address">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4 form-data">
            <label for="resmobilenumber" class="form-label req">Mobile Number</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resmobilenumber" id="resmobilenumber" placeholder="Enter Mobile Number" required>
        </div>
        <div class="col-md-4 form-data">
            <label for="resemailaddress" class="form-label req">Email Address</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resemailaddress" id="resemailaddress" placeholder="Enter Email Address" required>
        </div>
        <div class="col-md-4 form-data">
            <label for="rescitizenship" class="form-label req">Citizenship</label>
            <select class="form-select py-2 px-3 rounded-3" name="rescitizenship" id="rescitizenship" required>
                <option value="">Select Citizenship</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 form-data">
            <label for="resdateofbirth" class="form-label req">Date of Birth</label>
            <input type="text" class="form-control py-2 px-3 datepickerBig rounded-3" name="resdateofbirth" id="resdateofbirth" placeholder="__/__/____" onchange="computeAge()" required>
        </div>
        <div class="col-md-3 form-data">
            <label for="resage" class="form-label req">Age</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resage" id="resage" placeholder="Enter Age" readonly required>
        </div>
        <div class="col-md-6 form-data">
            <label for="resplaceofbirth" class="form-label">Place of Birth</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resplaceofbirth" id="resplaceofbirth" placeholder="Enter Place of Birth">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 form-data">
            <label for="rescivilstatus" class="form-label req">Civil Status</label>
            <select class="form-select py-2 px-3 rounded-3" name="rescivilstatus" id="rescivilstatus" required>
                <option value="">Select Civil Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
                <option value="Annulled">Annulled</option>
            </select>
        </div>
        <div class="col-md-3 form-data">
            <label for="resgender" class="form-label req">Gender</label>
            <select class="form-select py-2 px-3 rounded-3" name="resgender" id="resgender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="col-md-6 form-data">
            <label for="resoccupation" class="form-label">Occupation</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="resoccupation" id="resoccupation" placeholder="Enter Occupation">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 form-data">
            <label for="rescompanyname" class="form-label">Company Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="rescompanyname" id="rescompanyname" placeholder="Enter Company Name">
        </div>
        <div class="col-md-6 form-data">
            <label for="rescompanyaddress" class="form-label">Company Address</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="rescompanyaddress" id="rescompanyaddress" placeholder="Enter Company Address">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 form-data">
            <label for="rescontactperson" class="form-label">Emergency Contact Person</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="rescontactperson" id="rescontactperson" placeholder="Enter Emergency Contact Person">
        </div>
        <div class="col-md-6 form-data">
            <label for="rescontactpersonno" class="form-label">Emergency Contact Person Number</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" name="rescontactpersonno" id="rescontactpersonno" placeholder="Enter Emergency Contact Person Number">
        </div>
    </div>
</div>
<script>
    function initrespart2(){
        respart2action();
        $("#respart2").find("input[type='text'], select").val("");
    }

    function respart2action(){
        $("#respart2 input[type='text']").each(function(){
            if($(this).prop("required")){
                $(this).on("blur", function(){
                    chkrespart2();
                });
            }
        });
        $("#respart2 select").each(function(){
            if($(this).prop("required")){
                $(this).on("change", function(){
                    chkrespart2();
                });
            }
        });
    }

    function chkrespart2(){
        let respart2dis = $("#respart2").find("input[type='text'][required], select[required]").length;
        $("#respart2").find("input[type='text'][required], select[required]").each(function(){
            if($(this).val() != ""){
                respart2dis -= 1;
            }
        });
        if(respart2dis > 0){
            $("#btnrnext").attr("disabled", true);
        }
        else{
            $("#btnrnext").attr("disabled", false);
        }
    }

    function computeAge(){
        const today = new Date();
        const birth = new Date($("#resdateofbirth").val());
        let age = today.getFullYear() - birth.getFullYear();
        const monthDifference = today.getMonth() - birth.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        $("#resage").val(age);
    }
</script>