<!DOCTYPE html>
<html>
	<head>
		<title>Test webapp</title>
		<meta charset="utf-8" />
		<style>
			body{
				font-family: sans-serif;
				font-size: 1.3rem;
				margin: auto;
				width: 50%;
				background: #e6e6e6;
				overflow: hidden;
			}

			.user_container{
				background: white;
				padding: 15px;
				/* margin: 20px 0; */
				height: 97vh;
				text-align: center;
			}

			.text_cont{
				text-align: left;
				width: 550px;
				margin: auto;
			}

			.left{
				width: 50%;
				height: 135px;
				float: left;
			}

			.right{
				width: 50%;
				float: right;
				text-align: right;
			}

			#login-button{
				text-align: center;
			}

			#logout{
				padding: 10px;
				margin-top: 20px;
				-webkit-appearance: button;
				-moz-appearance: button;
				appearance: button;
				color: black;
				text-decoration: none;
			}

			#login{
				position: relative;
				-webkit-appearance: button;
				-moz-appearance: button;
				appearance: button;
				color: black;
				text-decoration: none;
				padding: 10px;
				top: 7rem;
			}
		</style>
	</head>
	<body>
		<?php
			if(isset($_COOKIE['_ui'])) {
				$array = base64_decode($_COOKIE['_ui']);
				$array = json_decode($array, true);
				?>
				<div class="user_container">
				<h1>Dataporten demo app</h1>
				<div class="text_cont">
				<div class="left">
				<p id="name">Name: <?php echo $array['user']['name']; ?></p>
				Mail: <a id="mailto" href="mailto:<?php echo $array['user']['email']; ?>"><?php echo $array['user']['email']; ?></a>
				<br />
				</div>
				<div class="right">
				<img id="prof_pic" src="https://api.dataporten.no/userinfo/v1/user/media/<?php echo $array["user"]["profilephoto"]; ?>" alt="profile picture">
				<br />
				</div>
				</div>
				<textarea id="dataporten_info" disabled style="margin: 0px; height: 365px; width: 550px;"><?php
						echo json_encode($array, JSON_PRETTY_PRINT);
					?>
				</textarea>
				<br>
				<br>
				<a id="logout" href="cb.php?logout">Logout</a>
				<div>
				<?php
			} else {
			
		?>
		<div class="user_container">
					<?php if(getenv('DATAPORTEN_CLIENTID') == FALSE) {
							echo "<h1> Demo App running on ".gethostname()."</h1>";
					} else {
							echo "<h2>Dataporten Demo App  on ".gethostname()."</h2>";
					?>
					<div id="login-button">
							<a id="login" href="cb.php">Login</a>
					</div>
					</div>
			<?php   }
			}
		?>
	</body>
</html>