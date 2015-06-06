<?php
include "scripts/php/simple_html_dom.php";
function findTeachers($mobile) {
	$html = file_get_html('http://cv.sduhsd.net/staff/');
	$rows = $html->find("#sites-canvas-main-content table tbody tr td div center table tbody tr");
	$count = count($rows);
	$i = 1;
	$array = array();
	foreach($rows as $element) {
		$returnArray = array();
		if($i < $count && $i !== 1) {
			foreach($element->find("td") as $td) {
				if(strstr($td->plaintext,"Email")) {
					$email = $td->find("a");
					if(count($email) > 0) {
						$email = $email[0]->href;
						$returnArray[] = $email;
					} else {
						$returnArray[] = "";
					}
				} elseif(strstr($td->plaintext,"Website")) {
					$url = $td->find("a");
					if(count($url) > 0) {
						$url = $url[0]->href;
						$returnArray[] = $url;
					} else {
						$returnArray[] = "";
					}
				} else {
					$trimmed = $td->plaintext;
					$returnArray[] = $trimmed;
				}
			}
		}
		$i++;
		$array[] = $returnArray;
	};
	unset($array[0]);
	unset($array[count($array)]);
	$newArray = array();
	foreach($array as $row) {
		$newArrayRow = array();
		$newArrayRow["Name"] = $row[0];
		$newArrayRow["Subject"] = $row[1];
		$newArrayRow["Email"] = $row[2];
		$newArrayRow["SoftEmail"] = str_replace("mailto:","",$row[2]);
		$newArrayRow["Website"] = $row[3];
		if(!$mobile) {
			$newArrayRow["SoftUrl"] = str_replace(array("https://","http://"),"",$row[3]);
		} else {
			$newArrayRow["SoftUrl"] = "Website";
		}
		$newArray[$row[0]] = $newArrayRow;
	}
	$array = $newArray;
	return str_replace("\\u00a0","",json_encode($array));
}
if(isset($_GET['ajax'])) {
	echo findTeachers(isset($_GET['mobile']));
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Find A CVMS Teacher
		</title>
		<link href='https://fonts.googleapis.com/css?family=Ubuntu:500|Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<style type="text/css">
			body {
				background-color: #CCCCCC;
				margin: 0px;
			}
			div {
				background-color: white;
			}
			#results {
				display: none;
			}
			#searchBar {
				margin: 0px;
			}
			#searchBar {
				-webkit-box-shadow: -1px 1px 5px 0px rgba(50, 50, 50, 0.2);
				-moz-box-shadow: -1px 1px 5px 0px rgba(50, 50, 50, 0.2);
				box-shadow: -1px 1px 5px 0px rgba(50, 50, 50, 0.2);
				transition: box-shadow 0.2s;
				position: fixed;
				width: 100%;
				top: 0px;
			}
			#searchBar:hover {
				-webkit-box-shadow: -1px 1px 3px 0px rgba(50, 50, 50, 0.1);
				-moz-box-shadow: -1px 1px 3px 0px rgba(50, 50, 50, 0.1);
				box-shadow: -1px 1px 3px 0px rgba(50, 50, 50, 0.1);
			}
			input {
				outline: none;
				border: none;
				height: 100%;
				width: 100%;
				box-sizing: border-box;
			}
			#results {
				margin: 86px 18px -71px 18px;
				-webkit-box-shadow: -1px 1px 5px 0px rgba(50, 50, 50, 0.2);
				-moz-box-shadow: -1px 1px 5px 0px rgba(50, 50, 50, 0.2);
				box-shadow: -1px 1px 5px 0px rgba(50, 50, 50, 0.2);
				transition: box-shadow 0.2s;
				border-radius: 2px;
			}
			#results:hover {
				-webkit-box-shadow: -1px 1px 3px 0px rgba(50, 50, 50, 0.1);
				-moz-box-shadow: -1px 1px 3px 0px rgba(50, 50, 50, 0.1);
				box-shadow: -1px 1px 3px 0px rgba(50, 50, 50, 0.1);
			}
			#table {
				width: 100%;
				padding: 8px 12px;
			}
			#search {
				padding: 20px 10px;
				margin: 0px;
				font-family: Ubuntu;
				font-weight: 500;
				font-size: 20px;
				background-image: none;
				background-repeat: no-repeat;
				background-position: right 10px center;
			}
			th {
				font-family: Ubuntu;
				font-size: 20px;
				text-align: left;
				color: #141414;
			}
			td {
				font-family: Open Sans;
				font-size: 19px;
				color: #212121;
			}
			a {
				color: #000000;
			}
		</style>
	</head>
	<body>
		<div id="searchBar">
			<input type="text" id="search" placeholder="Search for a teacher by name or subject..." autofocus autocomplete="off">
		</div>
		<div id="results">
			<table id="table">
				<tr id="header"></tr>
			</table>
			<span id="departmentChair" style="display: block; padding: 0px 0px 18px 18px; font-family: Open Sans; font-size: 19px;"></span>
		</div>
		<span style="display: block; margin-top: 87px; text-align: center; font-family: Open Sans; font-size: 14px; margin-bottom: 15px; color: #636363">
			Find A CVMS Teacher &#183;
			<a href="https://hussain.cf" style="color: #636363">Copyright &copy; 2015 Hussain Khalil</a> &#183;
			Powered By <a href="https://www.pythne.tk" style="color: #636363">Pythne</a> &#183;
			Made Using <a href="http://simplehtmldom.sourceforge.net/" style="color: #636363">Simple HTML DOM</a> &#183;
			Results Parsed From <a href="http://cv.sduhsd.net/staff/" style="color: #636363">CVMS Website</a> &#183;
			<a href="https://github.com/SanPilot/findacvmsteacher" target="_blank" style="color: #636363">Fork Me On GitHub</a>
			<noscript> &#183; <b><a href="http://www.enable-javascript.com/" target="_blank" style="color: #636363">JavaScript Is Required For This Site</a></b></noscript>
		</span>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			function stripSlashes(str) {
				return str.replace(/^\W+$/, "").replace(/\\/g, "");
			}
			function findFirst() {
				if($("tr").length > 1) {
					window.location = $(".site > a").eq(0).attr("href");
				}
			}
			function mobile() {
				var check = false;
				(function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
				return check;
			}
			function draw(obj) {
				$("#table").html($("#table").html()+"<tr><td>"+obj.Name+"</td><td>"+obj.Subject+"</td><td><a href='"+obj.Email+"'>"+obj.SoftEmail+"</a></td><td class='site'><a href='"+obj.Website+"'>"+obj.SoftUrl+"</a></td></tr>");
			}
			function clear() {
				$("#table").html("<tr><th>Teacher</th><th>Subject</th><th>Email</th><th>Website</th></tr>");
			}
			$("#search").on('keyup',function(event) {
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					findFirst();
				}
			});
			var teachers;
			$(document).ready(function() {
				var ajaxURL = "/?ajax";
				if(mobile()) {
					ajaxURL += "&mobile";
				}
				$.ajax(ajaxURL, {
					beforeSend:function() {
						$("#search").css("backgroundImage","url(/static/ui/loader.gif)");
					},
					error:function() {
						$("#search").val("An error occurred! Please try again.");
						$("#search").select();
						$("#search").css("backgroundImage","none");
					},
					success:function(result) {
						teachers = JSON.parse(result);
						$.each(teachers,function(key,val) {
							draw(val);
						});
						$("#departmentChair").html("<b>** Department Chair Memeber</b>");
						$("#header").html("<th>Teacher</th><th>Subject</th><th>Email</th><th>Website</th>");
						$("#results").css("display","block");
						$("#search").css("background-image","none");
					},
					timeout:5000
				});
				var last = "";
				var falsesearch = false;
				$("#search").keyup(function() {
					falsesearch = false;
					if($("#search").val() !== "") {
						if($("#search").val() != last) {
							clear();
							last = stripSlashes($("#search").val());
							var index = 0;
							$.each(teachers, function(key, val) {
								if(!last) {
									clear();
									$.each(teachers, function(key,val) {
										draw(val);
									});
									falsesearch = true;
									return false;
								}
								if(val.Name.toLowerCase().search(last.toLowerCase()) != -1 || val.Subject.toLowerCase().search(last.toLowerCase()) != -1) {
									draw(val);
									index++;
								}
							});
							if(index === 0 && !falsesearch) {
								$("#table").html("<td>No results!</td>");
								$("#departmentChair").css("display","none");
							} else {
								$("#departmentChair").css("display","block");
							}
						}
					} else {
						clear();
						$.each(teachers, function(key,val) {
							draw(val);
						});
					}
				});
			});
		</script>
	</body>
</html>
