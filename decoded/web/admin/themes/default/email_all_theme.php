<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function email_all_theme() {
}
var message_body = $("#message_body").val();
var d = {"from_name": from_name,"from_email": from_email,"subject": subject,"message_body":message_body, "sendall": sendall, "savechanges":1};
submitit(d, {
after_handle:function(data, p){
if(data.tasks){
$("#taskID").val(data["tasks"]["taskID"]);
$("#no_res").val(data["tasks"]["no_res"]);
if(data["tasks"]["taskID"] != ""){
getTaskID(data["tasks"]["num_res"]);
interval = setInterval(function(){
getTaskID(data["tasks"]["num_res"])
},2000);
}
}else{
$("#send_email").prop("disabled", false);
}
}
});
})
});
function getTaskID() {
});
$("#progress_bar").css("display","block");
$("#loadlogs").show();
$("#progress").attr("aria-valuenow", (count));
$("#progress").css("width", (process)+"%");
$("#progress span").text(data +"/"+ count);
if(status = "Compelet" && process == 100  ){
clearInterval(interval); // stop the interval
setTimeout(function(){
$("#progress_bar").css("display","none");
$("#done_msg").html("<b>'.__('Email(s) Sent Successfully').'</b>");
$("#send_email").prop("disabled", false);
},2000)
}
}
});
}
}
</script>';
softfooter();
}