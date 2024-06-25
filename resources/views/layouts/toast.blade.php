<div class="toast-container position-absolute top-0 end-0 m-2 tmain">
    <div class="toast show rounded-4 align-items-center {{ $type != '' ? 'bg-' . $type : '' }} text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-animation="true" id="toast_bg">
        <div class="d-flex tcont">
            <div class="toast-body mx-2 row">
                <div class="col-2"><i class="fa-solid fa-check-circle tcheck"></i></div>
                <div class="col-10">
                    <h6 class="ttitle mx-2" id="toast_type">{{ $type == "success" ? 'Success' : 'Error' }}</h6>
                    <p class="tmessage mx-2" id="toast_msg">{{ $message }}</p>
                </div>
            </div>
            <i class="fa-solid fa-xmark mx-3" data-bs-dismiss="toast" aria-label="Close"></i>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        @if($type != "" && $message != "")
        $(".toast").toast("show");
        @else
        $(".toast").toast("hide");
        @endif
    });

    function showtoast(type, msg){
        $("#toast_bg").removeClass("bg-success");
        $("#toast_bg").removeClass("bg-error");
        $("#toast_bg").addClass("bg-" + type.toLowerCase());
        $("#toast_type").text(type);
        $("#toast_msg").text(msg);
        $(".toast").toast("show");
    }
</script>