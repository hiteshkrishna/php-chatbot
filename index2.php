<?php
include('database.inc.php');

if (isset($_POST['userID'])) {
	$val = $_POST['userID'];
	$cookie_name = "userID";
	setcookie($cookie_name, $val, time() + (86400 * 30), "/"); // 86400 = 1 day
	header("Location:index2.php");
}

if (!isset($_COOKIE['userID'])) {
	header("Location:index.php");
} else {
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex, nofollow">
	<title>PHP Chatbot</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="style.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>

<body onload="y()">

	<div style="width: 100%; "> <br>
		<center><?php echo "iD: - $_COOKIE[userID]" ?></center>
	</div>
	<div class="container">
		<div class="row justify-content-md-center mb-4">
			<div class="col-md-6">
				<!--start code-->
				<div class="card">
					<div class="card-body messages-box">
						<ul class="list-unstyled messages-list">
							<?php
							$res = mysqli_query($con, "select * from message where userID='$_COOKIE[userID]'");
							if (mysqli_num_rows($res) > 0) {
								$html = '';
								while ($row = mysqli_fetch_assoc($res)) {
									$message = $row['message'];
									$src = $row['src'];

									$added_on = $row['added_on'];
									$strtotime = strtotime($added_on);
									$time = date('h:i A', $strtotime);
									$type = $row['type'];
									if ($type == 'user') {
										$class = "messages-me";
										$imgAvatar = "user_avatar.png";
										$name = "Me";
										$download = "";
									} else {
										$class = "messages-you";
										$imgAvatar = "bot_avatar.png";
										$name = "Chatbot";
										$download = "	<a href='$src'	>Download</a>";
									}
									if ($src == "NO file") {
										$download = "";
									}

									$html .= '<li class="' . $class . ' clearfix"><span class="message-img"><img src="image/' . $imgAvatar . '" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">' . $name . '</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' . $time . '</span></small> </div><p class="messages-p">' . $message . '</p></div>	' . $download . '								</li>';
								}
								echo $html;
							} else {
							?>
								<li class="messages-me clearfix start_chat">
									Please start
								</li>
							<?php
							}
							?>

						</ul>
					</div>
					<div class="card-header">
						<div class="input-group">
							<input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type your message here..." />
							<span class="input-group-append">
								<input type="button" class="btn btn-primary" value="Send" onclick="send_msg()">
							</span>
						</div>
					</div>
				</div>
				<!--end code-->
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function getCurrentTime() {
			var now = new Date();
			var hh = now.getHours();
			var min = now.getMinutes();
			var ampm = (hh >= 12) ? 'PM' : 'AM';
			hh = hh % 12;
			hh = hh ? hh : 12;
			hh = hh < 10 ? '0' + hh : hh;
			min = min < 10 ? '0' + min : min;
			var time = hh + ":" + min + " " + ampm;
			return time;
		}

		function send_msg() {
			jQuery('.start_chat').hide();
			var txt = jQuery('#input-me').val();
			var html = '<li class="messages-me clearfix"><span class="message-img"><img src="image/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p">' + txt + '</p></div></li>';
			jQuery('.messages-list').append(html);
			jQuery('#input-me').val('');
			if (txt) {
				jQuery.ajax({
					url: 'get_bot_message.php',
					type: 'post',
					data: 'txt=' + txt,
					success: function(result, src) {
						var html = '<li class="messages-you clearfix"><span class="message-img"><img src="image/bot_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Chatbot</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p"><a href="' + src + '"	>Download</a>' + result + '</p></div></li>';
						jQuery('.messages-list').append(html);
						jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight);

						location.reload();

					}
				});

			}

		}
		//  location.reload();


		jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight)
	</script>
</body>

</html>