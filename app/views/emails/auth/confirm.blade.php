<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Bagpipe Email Confirmation</h2>

		<div>
			To confirm your email. Please select the link below 
			
			<a href="{{ URL::to('register/confirm/' . $confirmation_code ) }}">{{ URL::to('register/confirm/' . $confirmation_code ) }}</a><br/>
			<br><br>
			
			<p>This link will expire in {{ Config::get('auth.reminder.expire', 10) }} minutes.</p>
		</div>
	</body>
</html>
