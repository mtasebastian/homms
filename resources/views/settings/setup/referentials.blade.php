<div class="row">
    <div class="col-md-2 p-0">
        <nav class="nav flex-column sysref">
            <a class="nav-link active" id="sysref_1">Financials</a>
            <a class="nav-link" id="sysref_2">Requests</a>
            <a class="nav-link" id="sysref_3">Residents</a>
            <a class="nav-link" id="sysref_4">Complaints</a>
            <a class="nav-link" id="sysref_5">Visitors</a>
        </nav>
        <div class="px-4 sysref2">
            <select class="form-select py-2 px-3 rounded-3" id="showsys">
                <option value="1">Financials</option>
                <option value="2">Requests</option>
                <option value="3">Residents</option>
                <option value="4">Complaints</option>
                <option value="5">Visitors</option>
            </select>
        </div>
    </div>
    <div class="col-md-10 sysrefcont p-0" id="sysrefcont1">
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refdisctype" class="form-label">Discount Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refdisctype" id="refdisctype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refreqtype" class="form-label">Request Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refreqtype" id="refreqtype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="reqtranstype" class="form-label">Transaction Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refreqtranstype" id="refreqtranstype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refphase" class="form-label">Phase</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refphase" id="refphase" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refcitizenship" class="form-label">Citizenship</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refcitizenship" id="refcitizenship" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refvehicletype" class="form-label">Vehicle Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refvehicletype" id="refvehicletype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refcomptype" class="form-label">Complaint Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refcomptype" id="refcomptype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
                <div class="col-md-6 form-data mb-3 mb-md-0">
                    <label for="refvisitortype" class="form-label">Visitor Type</label>
                    <select class="form-select py-2 px-3 rounded-3" name="refvisitortype" id="refvisitortype" required>
                        <option value="">Select Referential</option>
                        @foreach($referentials as $referential)
                        <option value="{{ $referential->id }}">{{ $referential->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-data mb-3 mb-md-0">
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
        <button class="btn btn-add px-3 py-2 rounded-3" id="btnsaverefs" onclick="saverefsetup();">Save Changes</button>
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
        $("#showsys").change(function(){
            $(".sysref a").removeClass("active");
            $("#sysref_" + $(this).val()).addClass('active');
            $(".sysrefcont").hide();
            $("#sysrefcont" + $(this).val()).show();
        });
    });

    function loadrefsetup(){
        @foreach($refsetup as $ref)
        $("#ref{{ $ref->for }}").val("{{ $ref->ref_id }}");
        @endforeach
    }

    function saverefsetup(){
        $("#btnsaverefs").prop("disabled", true);
        $("#btnsaverefs").text("Saving...");
        $("#btnsaverefs").css("opacity", "80%");
        let params = {};
        params._token = "{{ csrf_token() }}";
        const arr = ['disctype', 'mod', 'reqtype', 'reqstatus', 'reqtranstype', 'phase', 'housecolor', 'citizenship', 'relation', 'vehicletype', 'pettype', 'comptype', 'compstatus', 'visitortype', 'presentedid'];
        $.each(arr, function(i, val){
            params[val] = $("#ref" + val).val();
        });
        $.post("{{ route('settings.save_refential_setup') }}", params).done(function(res){
            if(res.includes("success")){
                showtoast("Success", "Referential Setup has been Updated.");
                $("#btnsaverefs").prop("disabled", false);
                $("#btnsaverefs").text("Save Changes");
                $("#btnsaverefs").css("opacity", "100%");
            }
        });
    }
</script>