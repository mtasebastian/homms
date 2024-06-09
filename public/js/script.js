$.fn.hasAttr = function(name){
    return this.attr(name) !== undefined;
};

$.fn.ddtrigger = function(){
    const obj = $(this);
    obj.find(".btndd").click(function(){
        obj.find(".txtdd").focus();
    });
    obj.find(".txtdd").keyup(function(){
        const filter = $(this).val().toUpperCase();
        const cont = obj.find(".optlist");
        cont.find("button").each(function(){
            if($(this).text().toUpperCase().includes(filter)){
                $(this).show();
            }
            else{
                $(this).hide();
            }
        });
    });
    obj.find(".optlist").find("button").each(function(){
        $(this).click(function(){
            obj.find(".ddval").val($(this).attr("id"));
            obj.find(".btndd").text($(this).text());
        });
    });
    return this;
};

$.fn.reqpop = function(){
    const obj = $(this);
    obj.find(".req").each(function(){
        const elem = $(this);
        if(elem.is("select")){
            elem.change(function(){
                if($(this).val() != ""){
                    elem.next(".requiredp").remove();
                }
            });
        }
        if(elem.is("input") || elem.is("textarea")){
            elem.blur(function(){
                if($(this).val() != ""){
                    elem.next(".requiredp").remove();
                }
            });
        }
    });
    obj.find(".submit").click(function(){
        const btn = $(this);
        let ecnt = 0;
        obj.find(".req").each(function(){
            const elem = $(this);
            if(elem.val() == ""){
                if(elem.next(".requiredp").length == 0){
                    elem.after("<div class='requiredp'><div class='img'><svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Layer_1' x='0px' y='0px' viewBox='0 0 24 24' style='enable-background:new 0 0 24 24;' xml:space='preserve'><script xmlns=''/><style type='text/css'>.st0{fill:#F26322;} .st1{fill:#FFFFFF;}</style><path class='st0' d='M21.6,24H2.4C1.1,24,0,22.9,0,21.6V2.4C0,1.1,1.1,0,2.4,0h19.2C22.9,0,24,1.1,24,2.4v19.2  C24,22.9,22.9,24,21.6,24z'/><path class='st1' d='M12.8,20h-1.6c-0.4,0-0.8-0.4-0.8-0.8v-1.6c0-0.4,0.4-0.8,0.8-0.8h1.6c0.4,0,0.8,0.4,0.8,0.8v1.6  C13.6,19.6,13.3,20,12.8,20z'/><path class='st1' d='M13.6,4.8c0-0.4-0.4-0.8-0.8-0.8h-1.6c-0.4,0-0.8,0.4-0.8,0.8v3.8l0.4,5.8c0,0.4,0.3,0.8,0.8,0.8l0,0l0,0h0.8  l0,0l0,0c0.4,0,0.8-0.4,0.8-0.8l0.4-5.8L13.6,4.8L13.6,4.8z'/><script xmlns=''/></svg></div>Please fill out this field</div>");
                    ecnt += 1;
                    return false;
                }
            }
            else{
                elem.next(".requiredp").remove();
            }
        });
        if(btn.hasAttr("data-func") && ecnt == 0){
            eval(btn.attr("data-func"))();
        }
    });
};

$(document).ready(function(){
    $(".datepicker").datepicker();
    $(".timepicker").timepicker();
    $(".ddtrigger").ddtrigger();
    $(".reqpop").each(function(){
        $(this).reqpop();
    });
    $(document).on('mouseup', function(e){
        var container = $("#chat");
        if (!container.is(e.target) && container.has(e.target).length === 0){
            if(container.find(".dropdown-menu").hasClass("show")){
                $("#chatbtn").click();
            }
        }
    });
});

function formatString(str){
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
}

function resetFields(cont){
    $("#" + cont).find("input, textarea, select").val("");
    $(".requiredp").remove();
}

function formatDate(str = ""){
    let arr = getDateNow().split("-");
    if(str != ""){
        arr = str.split("-");
    }
    return arr[1] + "/" + arr[2] + "/" + arr[0];
}

function getDateNow(){
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if(month.length < 2){
        month = '0' + month;
    }
    if(day.length < 2){ 
        day = '0' + day;
    }
    return [year, month, day].join('-');
}