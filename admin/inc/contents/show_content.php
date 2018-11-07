<?php
	if(isset($_GET['cid']))
	{
		$cid=base64_decode($_GET['cid']);
		$type = $_GET['b'];
		if ( isset( $_POST[ 'htmlsrc' ] ))
		{
			$cdesc = str_replace('\"', '"', $_POST['htmlsrc']);
			if(strlen($cdesc) != 0)
			{
				$q = "update content set cdesc='$cdesc' where cid ='$cid';";

				if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
					$hlp->echo_ok( "Content has been updated." );
				else
					$hlp->echo_err( "Content coluld not be saved." );
			}
			else
			$hlp->echo_err("Text cannot be blank");
		} 
	}
	else
	{
		if(isset ($_GET['b']))
		{
			$type = $_GET['b'] ;
			switch($type)
			{
				case 'ab' : $cid = 1 ;break;
				case 'wrk' : $cid = 2; break;
				case 'prodsup' : $cid = 3; break;
				case 'contus' : $cid = 4; break;
			}
			
			$q = "select * from content where cid ='$cid';";
			
			$res = $hlp->_db->db_query( $q );	
			while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
			{
				$cid = $row[ 'cid' ];
				$cdesc = $row ['cdesc'];
			}
		}
	}
	
	$uri = $me.'?b='.$type.'&cid='.base64_encode($cid);
	
	if( $type == 'ab' )
		require( '../pages/2/content-title.html');
	else if( $type == 'wrk' )
		require( '../pages/3/content_extra-title.html');
	else if( $type == 'prodsup' )
		require( '../pages/4/content-title.html');
	else if( $type == 'contus' )
		require( '../pages/5/content-title.html');
	
	echo('<form name=frmWrt method="post" action="'.$uri.'" enctype="multipart/form-data">
		<div style="padding-top:10px;" ><textarea id="htmlsrc" name="htmlsrc" rows="20" cols="80" style="width: 95%">'.$cdesc.'</textarea></div> 
		<div style="width:320px;text-align:center;padding-top:10px;" ><button type=submit class=roundbutton style="width:100px;" >Save</button></div>	
		</form>');
	echo( '<body>' );
	
	echo( $szo );
	
	echo( '<div class=gencon style="padding-top:3px;">' );
	
	$toolbar_type = "BasicToolbar";
	//require("ckeditor/ckeditor.php");
?>

	<script type="text/javascript">
		window.CKEDITOR_BASEPATH='./ckeditor/';
	</script>
	
	<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	
	<script type="text/javascript">
		if(true)
		{
			CKEDITOR.replace('htmlsrc');
			CKEDITOR.config.contentsCss = '../css/page.css';
		}
		else
		{
			tinyMCE.init( {
				// General options
				mode : "textareas",
				remove_script_host : false,
				convert_urls : false,
				content_css : '../css/page.css',
				theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
				font_size_style_values : "10px,12px,13px,14px,16px,18px,20px",
				plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
				theme_advanced_buttons1 : "newdocument,save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,media,advhr,|,print,|,ltr,rtl",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,|,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak,|,fshare,lnshare,twiitshare,mailtofriend,|,myaddname,myaddfirstname,myaddlastname,|,myaddunsub,|,myaddA,myaddB,myaddC,|,template",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true
			} );
		}
	</script>
	
<?php
	//$CKEditor = new CKEditor();
	//$CKEditor->returnOutput = true;
	//echo($CKEditor->replace("htmlsrc"));
?>

</div>
</body>
