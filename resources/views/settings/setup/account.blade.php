<div class="row">
    <div class="col-md-3 p-4">
        <div class="form-data mb-3">
            <label for="accountname" class="form-label">Name</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" id="accountname" name="accountname">
        </div>
        <div class="form-data mb-3">
            <label for="accountcontact" class="form-label">Mobile Number</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" id="accountcontact" name="accountcontact">
        </div>
        <div class="form-data mb-3">
            <label for="accountemail" class="form-label">Email</label>
            <input type="text" class="form-control py-2 px-3 rounded-3" id="accountemail" name="accountemail">
        </div>
        <div class="form-data mb-3">
            <label for="accountpass" class="form-label">New Password</label>
            <input type="password" class="form-control py-2 px-3 rounded-3" id="accountpass" name="accountpass">
        </div>
        <div class="form-data mb-3">
            <label for="accountcpass" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control py-2 px-3 rounded-3" id="accountcpass" name="accountcpass">
        </div>
        <div class="form-data text-center">
            <button onclick="updateAccount()" class="btn btn-add w-100 px-3 py-2 rounded-3 me-3">Update Account</button>
        </div>
    </div>
</div>
<script>
    $(function(){
        loadAccount();
    });

    function loadAccount(){
        $.get("{{ route('settings.get_account') }}", function(data, status){
            if(status.includes("success")){
                $("#accountid").val(data.id);
                $("#accountname").val(data.name);
                $("#accountemail").val(data.email);
                $("#accountcontact").val(data.mobileno);
            }
        });
    }

    function updateAccount(){
        if($("#accountpass").val() === $("#accountcpass").val()){
            let params = {
                _token: "{{ csrf_token() }}",
                name: $("#accountname").val(),
                email: $("#accountemail").val(),
                mobileno: $("#accountcontact").val(),
                password: $("#accountpass").val()
            };
            $.post("{{ route('settings.update_account') }}", params).done(function(res){
                if(res.includes("success")){
                    showtoast("Success", "Account has been updated.");
                }
            });
        }
        else{
            alert("Passwords do not match.");
        }
    }
</script>