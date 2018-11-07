<?php
	require('js/util.js');
	require('js/ui.js');
	require('js/talk.js');
	require('js/win.js');
	require('js/helper.js');
	
	for($i=1; $i <= $PAGE_COUNT; $i++)
	{
		if(file_exists("pages/${i}/js.php"))
			require( "pages/${i}/js.php" );
	}
?>