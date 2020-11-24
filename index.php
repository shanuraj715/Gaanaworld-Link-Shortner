<?php
include './config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Shorten Links</title>
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<link rel="stylesheet" type="text/css" href="./css/style.css?id=<?php echo rand(0, 99);?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
	<script type="text/javascript" src="./js/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div class="loading">
		<img src="<?php echo SHORTER_SITE_URL;?>images/loading.svg" class="loading_image">
	</div>

	<div class="message_warning message message_animation">
		<p><i class="fas fa-exclamation-circle"></i><span class="msg_war_text"></span></p>
	</div>

	<div class="message_success message" style="color: black;">
		<p><i class="fas fa-check-square"></i><span class="msg_succ_text"></span></p>
	</div>

	<div class="shortner_container">
		<div class="shortner_block">
			<p class="heading">Short Your Links</p>
			<div class="form">
				<input class="input_text" id="url_input" type="text" name="link" placeholder="<?php echo SITE_URL;?>song/123456/song_name">
				<button class="input_submit" title="Create Shorten Link"><i class="fas fa-arrow-circle-right"></i></button>
			</div>
			<div class="ajax_request_loading">
				<img src="<?php echo SHORTER_SITE_URL;?>images/ajax_loading.svg" class="ajax_loading">
			</div>

			<div class="data_output">
				<span class="shorted_url" id="shorted_url">https://gaanaworld.in/s/abcd</span>
				<button class="copy_btn" title="Copy URL"><i class="far fa-copy"></i></button>
			</div>
		</div>

		<script type="text/javascript">

			$(document).ready( () => {
				let loader = $('.loading');
				setTimeout( () => {
					loader.css('display', 'none');
				}, 1000);
				
			})

			$('.input_submit').click( () => {
				let input_text = $('#url_input').val();
				$.ajax({
					dataType: 'json',
					url: '<?php echo SHORTER_SITE_URL;?>ajax/short_url.php',
					type: 'POST',
					data: 'url=' + input_text,
					success: ( data ) => {
						if(data.status == 'ok'){
							$('.shorted_url').html(data.url);
							$('.data_output').css('display', 'flex');
						}
						if(data.status == 'failed'){

						}
					},
					error: ( data ) => {
						
					}
				})
			});

			$(document).ajaxStart( () => {
				$('.ajax_request_loading').css('display', 'initial');
				$('.data_output').css('display', 'none');
			});

			$(document).ajaxStop( () => {
				$('.ajax_request_loading').css('display', 'none');
			});



			$('.copy_btn').click( () => {
				let text_to_copy = $('.shorted_url').html();
				navigator.clipboard.writeText(text_to_copy).then( () => {
					let msg_box = $('.message_success');
					msg_box.css('display', 'initial');
					$('.msg_succ_text').html("Link Copied");
					setTimeout( () => {
						msg_box.css('display', 'none');
					$('.msg_succ_text').html("");
					}, 3000)
				}, ( err ) => {
					console.log( err );
				});
			})
			










			
		</script>

	</div>
</body>
</html>