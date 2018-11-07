<div class="gc-wrap txt-main">
<?php
	require( 'content-title.html' );
	
	require( '../../admin/conf/vars.php' );
	require( '../../admin/helper/class.helper.php' );
	
	$hlp = new chlp();
	
	$q = "select * from content where cid = '3';";
				
	$res = $hlp->_db->db_query( $q );	
	if( ( $row = $hlp->_db->db_get( $res ) ) !== false )
	{
		echo($row['cdesc']);
	}
?>
</div>