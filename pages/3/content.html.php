<div class="gc-wrap txt-main">
	<div name=ctitle class=gsc>
		<div class="page_head" style="">WORK</div>
		<div style="width:100%;overflow:hidden !important;">
			<div class=gc-ruler><span>&#160;</span></div>
			<div style="float:left;width:78%;display:table-cell;vertical-align:top;">
				<span class="span_dark" style="white-space:normal">PRODUCTION HOUSE SHOW REEL</span><br/>
				<a class="con-a span_light" href=# onclick="CHelp.slideSubSection(true, CPageC._txtExtra);return(false)" style="white-space:normal">DIRECTORS</a><br>
			</div>
		</div>
	</div>
	<div name=cinfo class=gsc style="width:450px">
		<div class=gc-rel style="margin-top:50px">
			<div class=gc-utube-over>&#160;</div>
			<div class=gc-utube>
<?php
	require( '../../admin/conf/vars.php' );
	require( '../../admin/helper/class.helper.php' );

	$hlp = new chlp();
	
	$q = "select code from youtube_videos order by priority desc;";
				
	$res = $hlp->_db->db_query( $q );	
	
	while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
	{
		$vcode = $row['code'];
		echo('<a class=gsc href=# onClick="CHelp.showUTubeVid(this);return(false);" name="'.$vcode.'"><img class=iload src="admin/youtube_videos/'.$vcode.'/preview.jpg" border=0 width=250px align=center /></a>');
	}
?>
			</div>
		</div>
	</div>
</div>
		