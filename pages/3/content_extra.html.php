<div class="gc-wrap txt-main" style="margin:0px auto;width:900px">
	<div style="margin-top:50px">
<?php
	require( 'content_extra-title.html' );
?>
<!-- <iframe width="400" height="225" src="http://www.youtube.com/embed/QFcA6ZnKi70" frameborder="0" allowfullscreen></iframe> -->			
		<div name=cright class=gsc style="width:350px">
<?php
	require( '../../admin/conf/vars.php' );
	require( '../../admin/helper/class.helper.php' );

	$hlp = new chlp();

	$q = "select * from content where cid = '2';";
		
	$res = $hlp->_db->db_query( $q );	
	if( ( $row = $hlp->_db->db_get( $res ) ) !== false )
	{
		echo($row['cdesc']);
	}
?>
		</div>
	</div>
</div>