<!doctype html>

<?php
	//$_M_WBASE = 'http://localhost/internal/web/digit9.0/FAR/source';
	$_M_WBASE = 'http://farcommercials.com';
	
	$PAGE_COUNT = 5;
	
	echo( '<html><head>');
	
	require('inc/head.php');
	
	// CSS //////////////////////////////////////////////////////////////
	echo('<style type="text/css">');	
		require('css/global.css');
		require('css/main.css');
		require('css/page.css');
		require('css/win.css');
		
		for($i=1; $i <= $PAGE_COUNT; $i++)
		{
			if(file_exists("pages/${i}/css.php"))
				require( "pages/${i}/css.php" );
		}
		
	echo('</style>');
	//////////////////////////////////////////////////////////////
	
	// JS //////////////////////////////////////////////////////////////
	echo('<script type="text/javascript">');
	
		require('all.js.php');
		
		echo("\n\n");
		if(isset( $_GET['page'] ) )
		{
			$sp = ($_GET['page']);
			//if($sp >=1 && $sp <= $PAGE_COUNT)
				echo('window._startOverridePage = '.$_GET['page']);
		}
		echo("\n\n");
	echo('</script>');
	//////////////////////////////////////////////////////////////
	//$TEST_PAGE='pages/1/content.html';
	
	// HEAD END BODY BEGIN
	echo("</head>");
	
	if( isset($TEST_PAGE))
	{
		echo("<body onload='CUi.init(document);'><div id=pagetest class=page style='width:100%;height:100%'>");		
		require($TEST_PAGE);
		echo("</div>");
	}
	else
	{
		echo("<body onload='CHelp.init(${PAGE_COUNT}, document);'>");
		
		echo("<div id=loadset>Loading ...</div>");
		echo("<div id=scrollset>&#160;</div>");

		for($i=1; $i <= $PAGE_COUNT; $i++)
		{
			require( "pages/${i}/main.php" );
		}
	}	
	
	echo('</body></html>');
?>

