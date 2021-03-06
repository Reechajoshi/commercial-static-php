<?php
	include_once( "class.db.php" );
	
	class chlp{
		var $_isIE = false;
		
		var $_db = false;
		var $_db_datastore = false;
		
		function chlp($db_connect = true)
		{
			GLOBAL $DB_NAME, $DB_USER, $DB_PASS;
			
			if( $db_connect )
				$this->_db = new cdb( $DB_NAME, $DB_USER, $DB_PASS );
			
			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			$this->_isIE = (strpos($ua,"msie")!==false);
			$this->_isGecko = (strpos($ua,"gecko")!==false);	
		}
		
		 // for image saving
		
		
		function getLinkAncHtml($aid,$w,$asb,$anc,$clCB,$imgh,$imgl,$txt,$parentTab = 'TAB_CONTENT',$direct = false )
		{
			$clEvnt = 'onclick';
			
			if($anc=='#')
				$clCB .= ';return(false);';
			else if(strpos($clCB,'direct')===0)
				$clCB = 'window.location.replace("'.$anc.'");return(false);';
			else if(strpos($clCB,'confirm')===0)
			{
				if( $direct )
					$clCB = 'if('.$clCB.') { window.location.replace("'.$anc.'"); } return(false);';
				else
					$clCB = 'if('.$clCB.') { CTabs.getTabObject("'.$parentTab.'").submitFormData("'.$anc.'"); } ;return(false);';			
			}	
			else if($clCB=='')
				$clCB = 'CTabs.getTabObject(window.menuid).submitFormData("'.$anc.'");return(false);';
				
			return("<div class='$asb'>
				<a id=$aid href=# $clEvnt='$clCB' target=_self class=acur>
					<table width=$w border=0><tr><td align=center valign=top><img height=$imgh border=0 src='$imgl' /></td></tr>
					<tr><td align=center valign=top><span id=txt>$txt</span></td></tr></table>
				</a>
			</div>");
		}
		
		function echo_err($m)
		{
			$c='txtheadwithbg';
			echo("<div class='$c gencon'> $m </div>");
		}
		
		function echo_ok($m)
		{
			$c='txtheadwithbg';
			echo("<div class='$c gencon'> $m </div>");
		}
		
		function getDisplayPageComboHTML($parent_tab,$cnt,$frm_submit,$frmname,$page_num,$page_display_sz)
		{
			$page_sz = ceil( $cnt/$page_display_sz );
			$page_combo = "<div id=pagingcombo style='text-align:right;padding-right:10px;'>
							<select name=pageCombo onChange='CUtil.getParentByName(this,\"$frmname\").action=\"$frm_submit\";CUtil.getParentByName(this,\"$frmname\").submit();'>";
			
			$cs = 1;$ce = $page_sz;
			$cbo_resize = false;
			
			if( $page_sz>12 )
			{
				$cbo_resize = true;
				if( $page_num>7 )
					$cs = $page_num-5;
				if( $page_sz>($page_num+5) )	
					$ce = $page_num+5;
			}
			
			if( $cbo_resize )
			{
				$page_combo .= "<option value=1".( ( $page_num==1 )?(' SELECTED '):('') ).">Page 1</option>";
				for( $c = $cs;$c<=$ce;$c++ )
				{
					if( !($c==1 || $c==$page_sz) )
						$page_combo .= "<option value=$c ".( ( $page_num==$c )?(' SELECTED '):('') )." >Page $c</option>";
				}	
				$page_combo .= "<option value=$page_sz ".( ( $page_num==$page_sz )?(' SELECTED '):('') )." >Page $page_sz</option>";	
			}
			else
			{
				for( $c = 1;$c<=$page_sz;$c++ )
					$page_combo .= "<option value=$c ".( ( $page_num==$c )?(' SELECTED '):('') )." >Page $c</option>";
			}
			$page_combo .= "</select>
							</div>";
			return($page_combo);
		}
		
		function searchBox($parent_tab,$frmsubmit,$srctxt,$comboHTML,$frmname='srccontent',$name_ext = false,$beforeCombo = false)
		{
			echo( "<div>
					<form method=post action='$frmsubmit' name=$frmname id=$frmname >
					<div >
						<input type=hidden value='$srctxt' name='cbosrctxt' />
						<div>
							<table class=txt style='width:96%;' >
								<tr>
									<td style='width:60px;' >
										Search :
									</td>
									<td id=srcinputcl >
										<div style='padding-bottom:2px;' >
											<input type=text name='srctxt".( ( $name_ext !== false )?( $name_ext ):( '' ) )."' id=searchclient style='width:500px;' value='$srctxt' onKeyPress='if( CUtil.isKeyEnterPressed(event)) { CUtil.getParentByName( this,\"$frmname\" ).submit(); }' >
										</div>
									</td>
									".( ( $beforeCombo )?( "<td style='text-align:right;' id=cmbtd >$beforeCombo</td>" ):( "" ) )."
									".( ( $comboHTML )?( "<td style='text-align:right;' id=cmbtd >$comboHTML</td>" ):( "" ) )."
								</tr>
							</table>
						</div>
					</form>
				</div>" );
		}
		
		function echoFileHeader($contenttype,$filename,$size,$asattachment = true)
		{
			header( "Content-Type: $contenttype" );
			header( "Content-Disposition: ".( ( $asattachment )?( "attachment" ):( "inline" ) )."; filename=\"".$filename."\"");
			header( "Accept-Ranges: bytes" );
			header( "Content-Length: $size" );
			header( "Connection: keep-alive" );
		}
		
		function format_space($sp)
		{
			if($sp<1024)
				return($sp.' B');
			else if($sp < 1048576)
				return(round($sp/1024,2).' KB');
			else if( $sp < 1073741824 )
				return(round($sp/1048576,2).' MB');
			else 
				return(round($sp/1073741824,2).' GB');
		}
		
		function convertToBytes($size,$form = 'GB')
		{
			if( $form == 'GB' )
				$size = $size*1073741824;
			else if( $form == 'MB' )
				$size = $size*1048576;
			else if( $form == 'KB' )
				$size = $size*1024;
			return( $size )	;
		}
		
		function trimText($str,$size=100)
		{
			if( strlen( $str ) > ($size - 3) )
				return( substr( $str,0,$size-3 )."..." );
			else
				return( $str );
		}
		
		function getunqid($s)
		{
			return(md5(uniqid(time(),true).$s));
		}

		function saveVideoCode($videoCode, &$vdesc)
		{
			$dirpath = "youtube_videos/$videoCode" ;
			if( file_exists($dirpath) )
			{
				$this->echo_ok("This video is already present.");
				return(false);	
			}
			
			mkdir($dirpath,0755);
			$preview_path = $dirpath."/preview.jpg";
			$play_img_path = "images/play.png" ;
			$previw_data = file_get_contents("http://img.youtube.com/vi/".$videoCode."/0.jpg");
				
			$put = file_put_contents( $preview_path,$previw_data );
			
			if(file_exists( $preview_path ))
			{
				$cmd_overlay = "composite -gravity center -quality 92 \( ${play_img_path} \) ${preview_path} ${preview_path}" ;
				
				exec($cmd_overlay , $opt , $ret);
				
				if( $ret == 0 )
				{
					$cmd = "convert ${preview_path} -resize 250x188! ${preview_path}";	
				
					exec($cmd , $op , $ret);
					if($ret == 0)
					{
						$vdesc = $this->getYouTubeTitle($videoCode);
						return true ;
					}
					else
					{
						$this->echo_ok("Unable to generate the video preview, please contact support");
						@rmdir($dirpath);
						return(false);
					}
				}
				else
				{
					$this->echo_ok("Unable to generate the video preview, please contact support");
					@rmdir($dirpath);
					return(false);
				}	
			}
			
			$this->echo_ok("Something went wrong, please contact support.");
			@rmdir($dirpath);
			return(false);
		}
		
		function getMainContentHTML($fl)
		{		
			global $me, $DWN_FILE;
			
			$html = '';
			if($fl == '')
				$html = ( $this->getMainContentHTML( 't'.base64_encode('home') ) );
			else
			{
				$param = base64_decode( substr( $fl, 1 ) );
				
				if($fl[0] == 'c')
				{
					$q = "select categories.cid as cid, categories.cimg as cimg, categories.chtml as chtml, categories.cname as cname, products.pname as pname, products.pid as pid from products, categories where products.pgroup=categories.cid and categories.cid='${param}';";
					
					$res = $this->_db->db_query( $q );
					if( ( $res ) && ( ( $row = $this->_db->db_get( $res ) ) !== false ) )
					{			
						$ctitle = $row[ 'cname' ];
						$chtml = $row[ 'chtml' ];
						$cimg = $row[ 'cimg' ];
						
						if( strlen( $cimg ) > 0 )
							$chtml = "<table><tr><td valign=top ><img src='${DWN_FILE}?iid=".base64_encode( $cimg )."' /></td><td valign=top >$chtml</td></tr></table>";
						
						$html = "<div class='gc box-title-right gtxt-box-title'>${ctitle}</div>${chtml}<div class=gc-wrap>";
						$pid_ex = 'p'.base64_encode( $row[ 'pid' ] );
						$pname = htmlspecialchars( $row[ 'pname' ] );
						
						$html .= '<div class=gsc><div class=prod-check>&#160;</div><div class="prod-side-bar"><a class="a-high gtxt" href=\''.$me.'fl='.$pid_ex.'\' onclick="CHelp.clickMe(\''.$pid_ex.'\');return(false);" name="'.$pname.'">'.$pname.'</a></div></div>';
						while( ( $row = $this->_db->db_get( $res ) ) !== false ) {
							$pid_ex = 'p'.base64_encode( $row[ 'pid' ] );
							$pname = htmlspecialchars( $row[ 'pname' ] );
						
							$html .= '<div class=gsc><div class=prod-check>&#160;</div><div class="prod-side-bar"><a class="a-high gtxt" href=\''.$me.'fl='.$pid_ex.'\' onclick="CHelp.clickMe(\''.$pid_ex.'\');return(false);" name="'.$pname.'">'.$pname.'</a></div></div>';
							
						}
						
						$html .= '</div>';
					}
				}
				else if($fl[0] == 'p')
				{
					$retx = ( $this->_db->db_return( "select pname, phtml, pimg from products where pid='${param}';", array( 'pname', 'phtml', 'pimg' ) ) );
					$html = false;
					if( strlen( $retx[ 2 ] ) > 0 )
							$html = "<table><tr><td valign=top ><img src='${DWN_FILE}?iid=".base64_encode( $retx[ 2 ] )."' /></td><td valign=top >".$retx[ 1 ]."</td></tr></table>";
					else
						$html = $retx[ 1 ];
					$html = '<div class="gc box-title-right gtxt-box-title">'.$retx[ 0 ].'</div>'.$html;
				}
				else if($fl[0] == 't')
				{				
					if( file_exists( "frontend/inc/content_${param}.php" ) )
						$html = ( file_get_contents( "frontend/inc/content_${param}.php" ) );
				}
			}
			
			return( $html );
		}
				
		function is_backend_login_ok()
		{
			$u = $_SERVER["PHP_AUTH_USER"];
			$p = $_SERVER["PHP_AUTH_PW"];
			return( ( $u == 'jamie' && ( $p == 'pin' ) ) );
		}
		
		function getYouTubeTitle($vid)
		{
			$feedURL = 'http://gdata.youtube.com/feeds/api/videos/'.$vid;
			$entry = simplexml_load_file($feedURL);
			$video = $this->parseVideoEntry($entry);
			return($video->title);
		}
		
		// function to parse a video <entry>
		function parseVideoEntry($entry) {      
			$obj= new stdClass;

			// get author name and feed URL
			$obj->author = $entry->author->name;
			$obj->authorURL = $entry->author->uri;

			// get nodes in media: namespace for media information
			$media = $entry->children('http://search.yahoo.com/mrss/');
			$obj->title = $media->group->title;
			$obj->description = $media->group->description;

			// get video player URL
			$attrs = $media->group->player->attributes();
			$obj->watchURL = $attrs['url']; 

			// get video thumbnail
			$attrs = $media->group->thumbnail[0]->attributes();
			$obj->thumbnailURL = $attrs['url']; 

			// get <yt:duration> node for video length
			$yt = $media->children('http://gdata.youtube.com/schemas/2007');
			$attrs = $yt->duration->attributes();
			$obj->length = $attrs['seconds']; 

			// get <yt:stats> node for viewer statistics
			$yt = $entry->children('http://gdata.youtube.com/schemas/2007');
			$attrs = $yt->statistics->attributes();
			$obj->viewCount = $attrs['viewCount']; 

			// get <gd:rating> node for video ratings
			$gd = $entry->children('http://schemas.google.com/g/2005'); 
			if ($gd->rating) { 
				$attrs = $gd->rating->attributes();
				$obj->rating = $attrs['average']; 
			} else {
				$obj->rating = 0;         
			}

			// get <gd:comments> node for video comments
			$gd = $entry->children('http://schemas.google.com/g/2005');
			if ($gd->comments->feedLink) { 
				$attrs = $gd->comments->feedLink->attributes();
				$obj->commentsURL = $attrs['href']; 
				$obj->commentsCount = $attrs['countHint']; 
			}

			// get feed URL for video responses
			$entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
			$nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/
			schemas/2007#video.responses']"); 
			if (count($nodeset) > 0) {
				$obj->responsesURL = $nodeset[0]['href'];      
			}

			// get feed URL for related videos
			$entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
			$nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/
			schemas/2007#video.related']"); 
			if (count($nodeset) > 0) {
				$obj->relatedURL = $nodeset[0]['href'];      
			}

			// return object to caller  
			return $obj;      
		}
	}
?>