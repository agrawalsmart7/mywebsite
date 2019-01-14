// let's see if there is any bypass for this code.

</html>
<h1><center> Search </center></h1>


<form action="" method="GET">
	<center>Search Here:- <br>
	<input type="text" name="param1" value="<?php test1();?>"><br>
	<input type="text" name="param2" value="<?php test2(); ?>"><br>
	<input type="text" name="param3" value="<?php test3()?>"><br>
	<input type="text" name="param4" value="<?php test4()?>"><br>

	<input type="submit" name="submit" value="Go"></center>

</form>
</html>

<?php


function is_html($string)
{
  if( preg_match("/<[^<]+>/",$string) != 0){
	  
	  echo "XSS detect";
  }
  else{
	  
	  echo strtoupper("$string");
  }
}

$param  = array('param1', 'param2', 'param3', 'param4');
			
function test1() {
	
	$v1 = @$_GET['param1'];
	is_html($v1);
}

function test2() {
	$v2 = @$_GET['param2'];
	is_html($v2);
}


function test3() {
	$v3 = @$_GET['param3'];
	is_html($v3);
}

function test4() {
	$v4 = @$_GET['param4'];
	is_html($v4);
}

 
 ?>
