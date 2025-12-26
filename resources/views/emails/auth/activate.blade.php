<h1>Welcome {{ $first_name=Input::get('first_name') }}!</h1>, <br/><br/>
	<p> Hello, {{ $first_name }} </p>
<p>
This message is to confirm that you've signed up at LUSTRATIO Ltd. 
with the email {{ $email= Input::get('email') }}.
 Please activate your account using the following link.

 </p>

...<br/><br/>
{{ $link }}<br/>
...