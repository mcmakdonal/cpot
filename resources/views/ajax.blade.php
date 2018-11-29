<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("button").click(function(){
        $.ajax({
        	url: "http://mvec.demotoday.net/api/user/create",
        	method: 'post',
        	data: {
	"u_identity" : "1234567890123",
	"u_owner" : "AAAA",
	"u_email" : "AAA@AAA.com",
	"u_password" : "12345",
	"u_phone" : "0801956432",
	"u_store" : "Korea king",
	"u_addr" : "Korea",
	"u_province" : "1",
	"u_district" : "1",
	"u_subdistrcit" : "1",
	"u_zipcode" : "10260",
	"u_community" : "Ghost in Korea",
	"u_lat" : "1",
	"u_long" : "1",
	"u_desc" : "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
},
        	success: function(result){
            console.log(result);
        }});
    });
});
</script>
</head>
<body>

<div id="div1"><h2>Let jQuery AJAX Change This Text</h2></div>

<button>Get External Content</button>

</body>
</html>
