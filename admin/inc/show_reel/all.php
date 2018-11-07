<?php
	
	$parent_tab = 'TAB_ARTWORKS';
	$pgnum = 1;
	$frm_submit = "$me?b=sreel";
	$comboHTML = false;
	$srctxt = false;
	$frmname = "allgrp";
	
	if( isset( $_GET[ 'ac' ] ) )
	{
		if( $_GET[ 'ac' ] == 'ncode' )
		{
			$newcode = trim( $_POST[ 'newcode' ] );
			if( strlen( $newcode ) > 0 )
			{
				if( strlen( $newcode )<=40 )
				{
					$is_preview_downloded = $hlp->saveVideoCode($newcode, $vdesc);					
					if($is_preview_downloded)
					{
						$q = "insert into youtube_videos (code , vdesc , added_on) values ('$newcode' , '".addslashes($vdesc)."', now()) ; ";
						$res = $hlp->_db->db_query( $q );
						
						if($res)
							$hlp->echo_ok( "Video \" $newcode \" has been added." );
						else
							$hlp->echo_err( "Unable to add video \"$newcode\"." );
					}
				}
				else
				{
					$hlp->echo_err( "Unable to add the video" );
					$hlp->echo_err( "Video code should be maximum 40 characters long");
				}	
			}
			else
			{
				$hlp->echo_err( "Please specify the video code.");
			}
		}
		else if( $_GET[ 'ac' ] == 'd' )
		{
			$code = base64_decode( $_GET[ 'code' ] );
			$q = "delete from youtube_videos where code='$code';";
		
			if( $hlp->_db->db_query( $q ) )
			{
				$cmd = "rm -rf /var/www/FAR-dev/admin/youtube_videos/${code}" ;
				echo($cmd);
				exec($cmd , $op , $ret);
				
				if( $ret == 0 )
					$hlp->echo_ok( "Video has been removed." );
				else
					$hlp->echo_err( "Sorry, unable to remove video." );
			}
			else
				$hlp->echo_err( "Sorry, unable to remove video." );
		}
	}
	
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	
	echo( $hlp->getLinkAncHtml( 'anewc',100,'asb rviewdash','#','document.getElementById("frmnewcode").style.display="block";',20,'images/ic/newc.png','Add' ) );
	
	echo( '</div>' );
		
	echo( " <div style='display:none;' name=frmnewcode id=frmnewcode >
					<form method=post action='$me?b=sreel&ac=ncode' >
						<div style='padding-left:20px;padding-top:10px;' >
						Please enter the youtube video code
						</div>
						<div style='padding-left:20px;padding-top:10px;' >
							<div style='width:320px;float:left;' ><input type=text name=newcode style='width:300px;'></div>
							<div><button class=roundbutton type=submit >Add</button></div>
						</div>
					</form>	
				<div class='txtheadwithbg' >&#160;</div>
	</div>");

	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
		$srctxt = trim( $_POST[ 'cbosrctxt' ] );
	}	
	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );

	$allcntx = $hlp->_db->db_return( "select count(*) cnt from youtube_videos;", array( 'cnt' ) );
	$allcnt = intval( $allcntx[0] );
	
	if( $allcnt > 0 )
	{
		$q = "select count(*) cnt from youtube_videos where code like '%$srctxt%';";
		$cntx = $hlp->_db->db_return( $q, array( "cnt" ) );
		$cnt = intval( $cntx[0] );
		
		if( $cnt > 0 )
		{
			$q = "select * from youtube_videos where vdesc like '%$srctxt%' order by priority desc;";
			//HACK : no need of paging this should be removed
			$startIndex = ( ($pgnum-1) * $GROUP_DISPLAY_PER_PAGE );	
			
			if( $cnt > $GROUP_DISPLAY_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab, $cnt, $frm_submit."&cbo", $frmname, $pgnum, $GROUP_DISPLAY_PER_PAGE);
				
				$q .= " LIMIT $startIndex,$GROUP_DISPLAY_PER_PAGE ;";
			}
			
			$res = $hlp->_db->db_query( $q );	
			if( $res )
			{
				$showNumRow = intval( $hlp->_db->db_num_rows( $res ) );
						
				if( ( $startIndex + 1 ) === ( $showNumRow + $startIndex ) && $pgnum == 1 )
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 1 of 1.</div></div>' );
				else
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing '.( $startIndex + 1 )." to ".( $showNumRow + $startIndex ).' of '.$cnt.'.</div></div>' );
				
				$hlp->searchBox( $parent_tab,$frm_submit,$srctxt,$comboHTML,$frmname,false,false );
				
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$code = $row[ 'code' ];
					$vdesc = $row[ 'vdesc' ];
					$preview_path = "youtube_videos/${code}/preview.jpg";
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$val_style = "color:#3f95b1;white-space:normal;width:610px;padding-left:5px;";
					echo( '<div name=entrydiv class="gencon bviewdash" style="white-space:nowrap;width:100%;padding-top:10px;" ><table name=tbl class=txt style="background-color:#f8f8f8;" border=0 width=100%>' );
					echo( '<tr valign=top><td align=left valign=top style="width:400px;">
						<div ><table class=txt valign=top style="width:100%"><tr valign=top><td valign=top style="width:200px">' );
					if( file_exists( $preview_path ) )	
						echo( '<div style="float:left" ><img width=150 src="'.$preview_path.'" />&#160;&#160;&#160;</div></td><td>' );
						echo( '<div id="txt" style = color:#10647e;><b>'.$vdesc.'</b></div>' );
						echo( '<div style="padding-top:7px;" ><br>');//outer div
							
						echo( $hlp->getLinkAncHtml( 'Video',60,'asb ',$me.'?b=sreel&ac=d&code='.base64_encode( $code ),'confirm( "Are you sure, you want to delete \"'.addslashes( $code ).'\"?" )',20,'images/ic/idelete.gif','Delete',$parent_tab,true ) );
										
						echo('<div></td></tr></table></td><td valign=top style="width:350px"><div>' );//div ends
	
					echo( '</div></td></tr>' );
						
					echo( '</table></div>' );
				}	
			}
			else
				echo( "<div style='padding:20px;' >No videos to show for search text \"$srctxt\".</div>" );	
		}
		else
		{
			echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 0 of 0.</div></div>' );
			$hlp->searchBox( $parent_tab,$frm_submit,$srctxt,$comboHTML,$frmname,false,false );
			echo( "There are no videos to show for search text \"$srctxt\"." );	
		}	
	}
	else
		echo( "<div style='padding:20px;' >No videos to show.</div>" );	

?>