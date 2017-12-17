<a class="btn btn-lg btn-default" href="?ic=c">Customer</a>
<a class="btn btn-lg btn-default" href="?ic=v">Vendor</a>

</br></br>
<?php
	if (isset($_COOKIE['CH-userdata'])){
		echo 'Cookie value: '.$_COOKIE['CH-userdata'];
	} else {
		echo 'No Cookie';
	}
?>