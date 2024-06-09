<div class="row">
    <div class="col-md-2 p-0">
        <nav class="nav flex-column sysref">
            <a class="nav-link active" id="sysref_1">Financials</a>
            <a class="nav-link" id="sysref_2">Requests</a>
            <a class="nav-link" id="sysref_3">Residents</a>
            <a class="nav-link" id="sysref_4">Complaints</a>
            <a class="nav-link" id="sysref_5">Visitors</a>
        </nav>
    </div>
    <div class="col-md-10 sysrefcont p-0" id="sysrefcont1">
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refdisctype" class="form-label">Discount Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refdisctype" id="refdisctype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refmod" class="form-label">Mode of Payment</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refmod" id="refmod" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10 sysrefcont p-0" id="sysrefcont2">
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refreqtype" class="form-label">Request Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refreqtype" id="refreqtype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refreqstatus" class="form-label">Request Status</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refreqstatus" id="refreqstatus" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10 sysrefcont p-0" id="sysrefcont3">
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refphase" class="form-label">Phase</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refphase" id="refphase" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refhousecolor" class="form-label">House Color</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refhousecolor" id="refhousecolor" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refcitizenship" class="form-label">Citizenship</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refcitizenship" id="refcitizenship" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refrelation" class="form-label">Relationship to Owner</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refrelation" id="refrelation" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refvehicletype" class="form-label">Vehicle Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refvehicletype" id="refvehicletype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refpettype" class="form-label">Pet Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refpettype" id="refpettype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10 sysrefcont p-0" id="sysrefcont4">
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refcomptype" class="form-label">Complaint Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refcomptype" id="refcomptype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refcompstatus" class="form-label">Complaint Status</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refcompstatus" id="refcompstatus" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10 sysrefcont p-0" id="sysrefcont5">
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-6 form-data">
                    <label for="refvisitortype" class="form-label">Visitor Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refvisitortype" id="refvisitortype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data">
                    <label for="refpresentedid" class="form-label">Presented ID</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refpresentedid" id="refpresentedid" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="text-end mt-4">
        <button class="btn btn-add" onclick="saverefsetup();">Save Changes</button>
    </div>
</div>
<script>
    $(function(){
        loadrefsetup();
        $(".syscont").hide();
        $("#syscont1").show();
        $(".sys_setup li").each(function(){
            $(this).click(function(){
                let pt = $(this).attr("id").split("_");
                $(".sys_setup li a").removeClass("active");
                $(this).find("a").addClass('active');
                $(".syscont").hide();
                $("#syscont" + pt[1]).show();
            });
        });

        $(".sysrefcont").hide();
        $("#sysrefcont1").show();
        $(".sysref a").each(function(){
            $(this).click(function(){
                let pt = $(this).attr("id").split("_");
                $(".sysref a").removeClass("active");
                $(this).addClass('active');
                $(".sysrefcont").hide();
                $("#sysrefcont" + pt[1]).show();
            });
        });
    });

    function loadrefsetup(){
        @foreach($refsetup as $ref)
        $("#ref{{ $ref->for }}").val("{{ $ref->ref_id }}");
        @endforeach
    }

    function saverefsetup(){
        let params = {};
        params._token = "{{ csrf_token() }}";
        const arr = ['disctype', 'mod', 'reqtype', 'reqstatus', 'phase', 'housecolor', 'citizenship', 'relation', 'vehicletype', 'pettype', 'comptype', 'compstatus', 'visitortype', 'presentedid'];
        $.each(arr, function(i, val){
            params[val] = $("#ref" + val).val();
        });
        $.post("{{ route('settings.save_refential_setup') }}", params).done(function(res){
            if(res == "success"){
                showtoast("Success", "Referential Setup has been Updated.");
            }
        });
    }
</script>