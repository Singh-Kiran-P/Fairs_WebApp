<!DOCTYPE html>
<html>
<head>
	<title>Insert data in MySQL database using Ajax</title>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
 <!-- Register form -->
 <div id="authForm">
        <center>
          <h1> Kermis Register Form </h1>
          <form action="" method="post">
            <input class="text" type="text" placeholder="Enter Username" id="username" required>
            <input class="text" type="email" placeholder="Enter Email" id="email" required>
            <input class="text" type="password" placeholder="Enter Password" id="password" required>
            <input class="text" type="password" placeholder="ReEnter Password" id="password2" required>
            <button type="button" id="btn" class="normalbutton"> Register </button>
          </form>
          <p id="error"></p>
        </center>
      </div>

<script>
$(document).ready(function() {
	$('#btn').on('click', function() {
		// $("#butsave").attr("disabled", "disabled");
		var username = $('#username').val();
		var email = $('#email').val();
		var password = $('#password').val();
		var password2 = $('#password2').val();
		if(username!="" && email!=""  && password!="" ){
			$.ajax({
				url: "./server/auth/register.php",
				type: "POST",
				data: {
					username: username,
					email: email,
					password: password,
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#error').html('Registration successful !');
					}
					else if(dataResult.statusCode==201){
						$("#error").show();
						$('#error').html('Email ID already exists !');
					}

				}
			});
		}
		else{
			alert('Please fill all the field !');
		}
	});
	$('#butlogin').on('click', function() {
		var email = $('#email_log').val();
		var password = $('#password_log').val();
		if(email!="" && password!="" ){
			$.ajax({
				url: "save.php",
				type: "POST",
				data: {
					type:2,
					email: email,
					password: password
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						location.href = "welcome.php";
					}
					else if(dataResult.statusCode==201){
						$("#error").show();
						$('#error').html('Invalid EmailId or Password !');
					}

				}
			});
		}
		else{
			alert('Please fill all the field !');
		}
	});
});
</script>
</body>
</html>
