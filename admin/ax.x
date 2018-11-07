<?php
	header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

	require( 'conf/vars.php' );
	require( 'helper/class.helper.php' );
	
	$me = $_SERVER[ "PHP_SELF" ];
	$hlp = new chlp();
	
	echo('<html><head>');	
	if( $_GET['a']=='t' )
	{
		echo( '</head>' );
		require( 'inc/frmtop.php' );
	}
	else if( $_GET['a']=='r' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/menu_main.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['a']=='cont' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/contents/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['a']=='shreel' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/show_reel/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( ($_GET['b'] == 'ab') || ($_GET['b'] == 'wrk') || ($_GET['b'] == 'prodsup') || ($_GET['b'] == 'contus') ) 
	{
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		// CSS //////////////////////////////////////////////////////////////
		echo('<style type="text/css">');	
			require('../css/page.css');
		echo('</style>');
		echo( "</head>" );
		require( 'inc/contents/show_content.php' );
	}
	else if ( $_GET['b'] == 'sreel' )
	{
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/show_reel/all.php' );
	}
	else if( $_GET['b'] == 'imageupload' )
		require( "Fileuploader/uploader.html" );	
	else
	{
		echo( "<head></head><frameset rows='40,*'>
			<frame src='$me?a=t' frameborder=0 marginheight=0 marginwidth=0 name=ft noresize=noresize scrolling=no />
			<frame src='$me?a=r' frameborder=0 marginheight=0 marginwidth=0 name=fb noresize=noresize scrolling=auto />
		</frameset>" );
	}
	echo('</html>');
?>