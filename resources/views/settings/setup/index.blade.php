<form method="post" enctype="multipart/form-data" id="systemForm">
    <div class="row p-4">
        <div class="col-2">
            <div class="form-data mb-3">
                <label for="systemlogo" class="form-label">Logo</label>
                <img src="" style="max-width: 100%; height: auto; margin-bottom: 10px;" id="previewlogo">
                <input type="file" class="form-control py-2 px-3 rounded-3" id="systemlogo" name="systemlogo">
                <label class="text-danger px-1">(Maximum of 150 KB size for Logo)</label>
            </div>
        </div>
        <div class="col-3">
            <div class="form-data mb-3">
                <label for="systemname" class="form-label">Name</label>
                <input type="text" class="form-control py-2 px-3 rounded-3" id="systemname" name="systemname">
            </div>
            <div class="form-data mb-3">
                <label for="systemaddress" class="form-label">Address</label>
                <textarea class="form-control py-2 px-3 rounded-3" id="systemaddress" name="systemaddress"></textarea>
            </div>
            <div class="form-data mb-3">
                <label for="systemcontact" class="form-label">Contact Number</label>
                <input type="text" class="form-control py-2 px-3 rounded-3" id="systemcontact" name="systemcontact">
            </div>
            <div class="form-data mb-3">
                <label for="systemtin" class="form-label">TIN Number</label>
                <input type="text" class="form-control py-2 px-3 rounded-3" id="systemtin" name="systemtin">
            </div>
            <div class="form-data text-center">
                <button type="submit" class="btn btn-add w-100 px-3 py-2 rounded-3 me-3">Update Settings</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function(){
        loadSettings();
        $('#previewlogo').hide();
        $('#systemlogo').on('change', function(){
            const file = this.files[0];
            const maxSize = 1024 * 150;

            if(file && file.size > maxSize){
                alert('File size exceeds 150KB!');
                $(this).val('');
            }
            else{
                if(file && file.type.startsWith('image/')){
                    const reader = new FileReader();
                    reader.onload = function(e){
                        $('#previewlogo').attr('src', e.target.result).show();
                        $('#previewlogo').show();
                    }
                    reader.readAsDataURL(file);
                }
                else{
                    $('#previewlogo').hide();
                }
            }
        });
    });

    function disableFields(val){
        $("#systemlogo").prop("disabled", val);
        $("#systemname").prop("disabled", val);
        $("#systemaddress").prop("disabled", val);
        $("#systemcontact").prop("disabled", val);
        $("#systemtin").prop("disabled", val);
    }

    function loadSettings(){
        $.get("{{ route('settings.get_settings') }}", function(data, status){
            if(status.includes("success")){
                Object.entries(data).forEach(([key, value]) => {
                    if(key === "systemlogo" && value.content !== null){
                        $("#previewlogo").attr("src", "data:" + value.mime + ";base64," + value.content);
                        $("#previewlogo").show();
                    }
                    else{
                        $("#" + key).val(value.text);
                    }
                })
            }
        });
    }

    $('#systemForm').on('submit', function(e){
        e.preventDefault();
        disableFields(true);

        const formData = new FormData();
        const file = $('#systemlogo')[0].files[0];

        formData.append('systemlogo', file);
        formData.append('systemname', $("#systemname").val());
        formData.append('systemaddress', $("#systemaddress").val());
        formData.append('systemcontact', $("#systemcontact").val());
        formData.append('systemtin', $("#systemtin").val());
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('settings.save_settings') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                if(res.includes("success")){
                    showtoast("Success", "System settings has been saved.");
                }
                disableFields(false)
            },
            error: function(xhr, status, error){
                console.error('Upload error:', error);
            }
        });
    });
</script>