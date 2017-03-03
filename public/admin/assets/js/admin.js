Number.prototype.formatMoney = function(c, d, t){
var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
$(document).ready(function () {
	$("#caledar_from").datepicker({
		changeMonth : true,
		changeYear : true,
		dateFormat : "dd-mm-yy",
		yearRange : "1930:2050"
	});
	$("#caledar_to").datepicker({
		changeMonth : true,
		changeYear : true,
		dateFormat : "dd-mm-yy",
		yearRange : "1930:2050"
	});
	$(".fancyboxClick").fancybox();
	$("#saveContent").click(function () {
		$("#loader").fadeIn()
	});
	
	$("#applyContent").click(function () {
		$("#loader").fadeIn()
	});

	$(".gr_perm_error").width($(".right_content").width() - 2);
	$(".gr_perm_success").width($(".right_content").width() - 2);
	$("#frmManagement input").keypress(function (b) {
		if (b.which == 13) {
			save();
			return false
		}
	});
	var a = $.url(document.location.href);
	if (a.segment(-1) != "update" && a.segment(-2) != "update" && a.segment(-1) != "update2" && a.segment(-2) != "update2") {
		if (a.fsegment(1) == "back" || a.fsegment(1) == "save") {
			if (a.fsegment(1) == "save") {
				show_perm_success()
			}
			if (a.segment(-1) != "update_profile" && a.segment(-1) != "setting") {
				if ($("#start").val() == "") {
					$("#start").val(0)
				}
				searchContent($("#start").val(), 10)
				// searchContent2($("#start").val(), 10)
			}
		} else {
			if (a.segment(-1) != "update_profile" && a.segment(-1) != "setting") {
				if (module != "admincp") {
					searchContent(0, 10)
					//searchContent2(0, 10)
				}
			}
		}
	}
});
function show_perm_denied() {
	$(".gr_perm_error").fadeIn(500);
	$("#loader").fadeOut(300);
	$(".table").css("marginTop", 4);
	setTimeout("$('.gr_perm_error').fadeOut(300); $('.table').css('marginTop',0);", 5000)
}
function show_perm_success() {
	$(".gr_perm_success").fadeIn(500);
	$("#loader").fadeOut(300);
	$(".table").css("marginTop", 4);
	setTimeout("$('.gr_perm_success').fadeOut(300); $('.table').css('marginTop',0);", 5000)
}
function searchContent(d, b) {
	if (b == undefined) {
		if ($("#per_page").val()) {
			b = $("#per_page").val()
		} else {
			b = 10
		}
	}
	var a = $("#func_sort").val();
	var c = $("#type_sort").val();
	var fa = $("#first_access").val();
	$("#start").val(d);
	$.post(root + "admincp/" + module + "/ajaxLoadContent", {
		func_order_by : a,
		order_by : c,
		start : d,
		first_access : fa,
		per_page : b,
		dateFrom : $("#caledar_from").val(),
		dateTo : $("#caledar_to").val(),
		content : $("#search_content").val()
	}, function (e) {
		$("#loadContent").html(e);
		$(".fancyboxClick").fancybox();
		$(".sort").removeClass("icon_sort_desc");
		$(".sort").removeClass("icon_sort_asc");
		$(".sort").addClass("icon_no_sort");
		if (c == "DESC") {
			$("#" + a).addClass("icon_sort_desc")
		} else {
			$("#" + a).addClass("icon_sort_asc")
		}
	})
}

function searchContentDonate(d, b) {
	var server = $('#select_sv').val();
	if (b == undefined) {
		if ($("#per_page").val()) {
			b = $("#per_page").val()
		} else {
			b = 10
		}
	}
	var a = $("#func_sort").val();
	var c = $("#type_sort").val();
	var fa = $("#first_access").val();
	$("#start").val(d);
	$.post(root + "admincp/" + module + "/ajaxLoadContent", {
		id : server,
		func_order_by : a,
		order_by : c,
		start : d,
		first_access : fa,
		per_page : b,
		dateFrom : $("#caledar_from").val(),
		dateTo : $("#caledar_to").val(),
		content : $("#search_content").val()
	}, function (e) {
		$("#ajax_loadContent").html(e);
		$(".fancyboxClick").fancybox();
		$(".sort").removeClass("icon_sort_desc");
		$(".sort").removeClass("icon_sort_asc");
		$(".sort").addClass("icon_no_sort");
		if (c == "DESC") {
			$("#" + a).addClass("icon_sort_desc")
		} else {
			$("#" + a).addClass("icon_sort_asc")
		}
	})
}
// function enterSearch(a) {
// 	console.log(a);
// 	// if (a.keyCode == 13) {
// 	// 	searchContent(0);
// 	// }
// }
function enterSearchDonate(a) {
	if (a.keyCode == 13) {
		searchContentDonate(0);
	}
}
function sort(b) {
	var a = $("#func_sort").val();
	var c = $("#type_sort").val();
	if (b == a) {
		if (c == "DESC") {
			$("#type_sort").val("ASC")
		} else {
			$("#type_sort").val("DESC")
		}
	} else {
		$("#func_sort").val(b);
		$("#type_sort").val("DESC")
	}
	searchContent(0, $("#per_page").val())
}
function updateStatus(d, a, c) {
	var b = root + "admincp/" + c + "/ajaxUpdateStatus";
	$.post(b, {
		id : d,
		status : a
	}, function (e) {
		$("#loadStatusID_" + d).html(e);
		if (c == "admincp_modules") {
			$.get(root + "admincp/menu", function (f) {
				$("#loadMenu").html(f)
			})
		}
	})
}
function updateServer_Status(d, a, c) {
	var b = root + "admincp/" + c + "/ajaxUpdateServer_Status";
	$.post(b, {
		id : d,
		server_status : a
	}, function (e) {
		$("#loadServer_StatusID_" + d).html(e);
		if (c == "admincp_modules") {
			$.get(root + "admincp/menu", function (f) {
				$("#loadMenu").html(f)
			})
		}
	})
}
function updateSlide(d, a, c) {
    var b = root + "admincp/" + c + "/ajaxUpdateSlide";
    $.post(b, {
        id : d,
        slide : a
    }, function (e){
        $("#loadSlideID_" + d).html(e);
        if (c == "admincp_modules") {
            $.get(root + "admincp/menu", function (f) {
                $("#loadMenu").html(f)
            })
        }
    })
}
function updateShow(d, a, c) {
    var b = root + "admincp/" + c + "/ajaxUpdateShow";
    $.post(b, {
        id : d,
        show : a
    }, function (e){
        $("#loadShowID_" + d).html(e);
        if (c == "admincp_modules") {
            $.get(root + "admincp/menu", function (f) {
                $("#loadMenu").html(f)
            })
        }
    })
}


function updateDacsac(d, a, c) {
    var b = root + "admincp/" + c + "/ajaxUpdateDacsac";
    $.post(b, {
        id : d,
        dacsac : a
    }, function (e){
        $("#loadDacsacID_" + d).html(e);
        if (c == "admincp_modules") {
            $.get(root + "admincp/menu", function (f) {
                $("#loadMenu").html(f)
            })
        }
    })
}

function updateView(d, a, c){
    var b = root + "admincp/" + c + "/ajaxUpdateView";

    $.post(b, {
        id : d,
        view : a
    }, function (e) {
        $("#loadViewID_" + d).html(e);
        if (c == "admincp_modules") {
            $.get(root + "admincp/menu", function (f) {
                $("#loadMenu").html(f)
            })
        }
    })
}
function updateCoin(id) {
	$.post(
		root + "admincp/" + module + "/resendCoin",
		{
			id : id
		},
		function(data){
			if(data == 'success'){
				alert("Done successfully! ");
				searchContent(0, $("#per_page").val());
			}
		}
	);
}

function selectAllItems(a) {
	if (document.getElementById("selectAllItems").checked == false) {
		$(".jqTransformCheckboxWrapper a").addClass("jqTransformChecked");
		for (var b = 0; b < a; b++) {
			if (document.getElementById("item" + b) != null) {
				$(".item_row" + b).addClass("row_active");
				itemCheck = document.getElementById("item" + b);
				itemCheck.checked = true
			}
		}
	} else {
		$(".jqTransformCheckboxWrapper a").removeClass("jqTransformChecked");
		for (var b = 0; b < a; b++) {
			if (document.getElementById("item" + b) != null) {
				$(".item_row" + b).removeClass("row_active");
				itemCheck = document.getElementById("item" + b);
				itemCheck.checked = false
			}
		}
	}
}
function showStatusAll() {
	var a = $("#per_page").val();
	for (var b = 0; b < a; b++) {
		if (document.getElementById("item" + b) != null) {
			if (document.getElementById("item" + b).checked == true) {
				updateStatus($("#item" + b).val(), 0, module)
			}
		}
	}
}
function hideStatusAll() {
	var a = $("#per_page").val();
	for (var b = 0; b < a; b++) {
		if (document.getElementById("item" + b) != null) {
			if (document.getElementById("item" + b).checked == true) {
				updateStatus($("#item" + b).val(), 1, module)
			}
		}
	}
}
function deleteItem(c) {
	var a = confirm("Are you sure delete item?");
	if (a) {
		var b = root + "admincp/" + module + "/delete";
		$.post(b, {
			id : c
		}, function (d) {
			if (d == "permission-denied") {
				show_perm_denied()
			} else {
				searchContent($("#start").val(), $("#per_page").val())
			}
		})
	}
}
function deleteAll() {
	var b = confirm("Are you sure delete item selected?");
	if (b) {
		var a = $("#per_page").val();
		for (var d = 0; d < a; d++) {
			if (document.getElementById("item" + d) != null) {
				if (document.getElementById("item" + d).checked == true) {
					id = $("#item" + d).val();
					var c = root + "admincp/" + module + "/delete";
					$.post(c, {
						id : id
					}, function (e) {
						if (e == "permission-denied") {
							show_perm_denied()
						} else {
							searchContent($("#start").val(), $("#per_page").val())
						}
					})
				}
			}
		}
	}
}
function chk_perm(b, a) {
	if (a != "no_access") {
		if (a == "read") {
			if ($("#read" + b).attr("checked") == "checked") {
				$("#noaccess" + b).attr("checked", true);
				$("#write" + b).attr("checked", false);
				$("#delete" + b).attr("checked", false);
				$(".custom_noaccess" + b).addClass("jqTransformChecked");
				$(".custom_write" + b).removeClass("jqTransformChecked");
				$(".custom_delete" + b).removeClass("jqTransformChecked")
			} else {
				$("#noaccess" + b).attr("checked", false);
				$(".custom_noaccess" + b).removeClass("jqTransformChecked")
			}
		} else {
			$("#read" + b).attr("checked", true);
			$("#noaccess" + b).attr("checked", false);
			$(".custom_read" + b).addClass("jqTransformChecked");
			$(".custom_noaccess" + b).removeClass("jqTransformChecked")
		}
	} else {
		if ($("#noaccess" + b).attr("checked") == "checked") {
			$("#read" + b).attr("checked", true);
			$(".custom_read" + b).addClass("jqTransformChecked")
		} else {
			$(".perm_access" + b).attr("checked", false);
			$(".custom_read" + b).removeClass("jqTransformChecked");
			$(".custom_write" + b).removeClass("jqTransformChecked");
			$(".custom_delete" + b).removeClass("jqTransformChecked")
		}
	}
};
function resendCoin(){
	var a = $("#per_page").val();
	for (var b = 0; b < a; b++) {
		if (document.getElementById("item" + b) != null) {
			if (document.getElementById("item" + b).checked == true) {
				updateCoin($("#item" + b).val())
			}
		}
	}
}

function searchContentConfig(d,b){
	if (b == undefined) {
		if ($("#per_page").val()) {
			b = $("#per_page").val()
		} else {
			b = 100
		}
	}
	var a = $("#func_sort").val();
	var c = $("#type_sort").val();
	var fa = $("#first_access").val();
	$("#start").val(d);
	$.post(root + "admincp/" + module + "/ajaxLoadContent", {
		func_order_by : a,
		order_by : c,
		start : d,
		first_access : fa,
		per_page : b,
		content : $("#filter").val(),
		rate_km: $('#rate-km').val(),
		time_bg: $('#time-begin').val(),
		time_en: $('#time-end').val()
	}, function (e) {
		$("#ajax_loadContent").html(e);
		$(".fancyboxClick").fancybox();
		$(".sort").removeClass("icon_sort_desc");
		$(".sort").removeClass("icon_sort_asc");
		$(".sort").addClass("icon_no_sort");
		if (c == "DESC") {
			$("#" + a).addClass("icon_sort_desc")
		} else {
			$("#" + a).addClass("icon_sort_asc")
		}
	})
}


function getEditor(id){
	return CKEDITOR.instances[id].getData();
}

function updateReport(){	
	$('.refname').each(function(index, value){
		var total = 0;
		$(value).find('td.number').each(function(k, v){
			total += Number($(v).html());
		});
		$(value).find('.last').html(total);
	})
}
