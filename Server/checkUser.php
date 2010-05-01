<?php



// **** CHANGE
exec("/PATH/TO/PYTHON/python /PATH/TO/rumpusAddUser.py --check " . $_GET["username"], &$output, &$return);

if($return == 0)
	echo 'false';
else
	echo 'true';

?>