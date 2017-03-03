// function EnterLogin(a){
// 	$(".show-err").hide();
// 	if(a.keyCode==13){login()}
// }
// function login(){
// 	name=$("#username").val();
// 	pass=$("#password").val();
// 	if(name==""&&pass==""){
// 		$(".erorr_username").show();
// 		$(".erorr_password").show();
// 		$("#divError").html("");return false}
// 	else{
// 		if(pass==""&&name!=""){
// 			$(".erorr_username").hide();
// 			$(".erorr_password").show();
// 			$("#divError").html("");return false}
// 		else{
// 			if(pass!=""&&name==""){
// 				$(".erorr_password").hide();
// 				$(".erorr_username").show();
// 				$("#divError").html("");return false}
// 			else{
// 				if(name!=""&&pass!=""){
// 					var a=root+"admincp/login/";
// 					$.post(a,{user:name,pass:pass},function(b){
// 						console.log(b);
// 						if(b==1){location.href=root+"admincp/connect"}
// 						else{$(".show-err").show()}
// 					})
// 				}
// 			}
// 		}
// 	}
// }
