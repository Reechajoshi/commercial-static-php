<script type="text/javascript"> <!--
<?php
	
	echo("
		window.menuid = 'TAB_CONTENT';
		menuInit = function() { try { 
		var areaTopMargin = 15;
		var areaBaseMargin = 25;
		
		var areaTopMarginCB = function(me,stab)
		{								
			h = \"<div style='overflow:hidden;width:100%;height:\" + areaTopMargin + \";background-color: \" + stab.extraBackdrop + \"'>\";
			h += '</div>';				
			
			return('');
		};
		
		var areaBaseMarginCB = function(me,stab)
		{
			var h = '<table width=100% height=\"'+areaBaseMargin+'px\"><tr><td align=right valign=center id=orgtxt><div style=\"color:#000;\"><a class=\"txthead\" target=\"_blank\" style=\"text-decoration:none;\" href=\"http://mgtech.in\">Powered by Macgregor Techknowlogy Pvt Ltd.</a>&#160;&#160;&#160;&#160;&#160;&#160;</div></td></tr></table>';
			return( h );
		};
		
		var doc=document;
		var tabs = new CTabs.tabs(menuid,{
			tabBackground: 'transparent',
			useFullArea: true,
			userTabControl: false, 
			userTabControl_WithAccel: false,
			tabSideIsOn: true,
			tabSideCB: function(me)
			{
				return('<div><a class=awtxt href=# title=Refresh onClick=\"' + me.getJSRun('refSelectedIFrame()') + '\">Refresh</a></div>');
			},
			areaBackGroundColour: '#ebf2f8',
			tabHeadBackGroundColour: '#ebf2f8',
			roundCorners: false,
			areaBaseMarginCB: areaBaseMarginCB,
			areaBaseMargin:areaBaseMargin
		} );
		
	
	tabs.addIFrameTab(menuid + 'ab', { name: 'About Us', iframeURL: '$me?b=ab', txtColor: '#28A9D3', txtSelectColor: '#28A9D3', tabLImage: 'images/tabs/tabl.png', tabTImage: 'images/tabs/tabt.png', tabRImage: 'images/tabs/tabr.png', extraBackdrop: '#28A9D3', borderSelect: '#fff 1px solid' } );	
	
	tabs.addIFrameTab(menuid + 'wrk', { name: 'Work', iframeURL: '$me?b=wrk', txtColor: '#28A9D3', txtSelectColor: '#28A9D3', tabLImage: 'images/tabs/tabl.png', tabTImage: 'images/tabs/tabt.png', tabRImage: 'images/tabs/tabr.png', extraBackdrop: '#28A9D3', borderSelect: '#fff 1px solid' } );
	
	tabs.addIFrameTab(menuid + 'prodsup', { name: 'Production Support', iframeURL: '$me?b=prodsup', txtColor: '#28A9D3', txtSelectColor: '#28A9D3', tabLImage: 'images/tabs/tabl.png', tabTImage: 'images/tabs/tabt.png', tabRImage: 'images/tabs/tabr.png', extraBackdrop: '#28A9D3', borderSelect: '#fff 1px solid' } );
	
	tabs.addIFrameTab(menuid + 'contus', { name: 'Contact Us', iframeURL: '$me?b=contus', txtColor: '#28A9D3', txtSelectColor: '#28A9D3', tabLImage: 'images/tabs/tabl.png', tabTImage: 'images/tabs/tabt.png', tabRImage: 'images/tabs/tabr.png', extraBackdrop: '#28A9D3', borderSelect: '#fff 1px solid' } );
	
	tabs.setHTML(0);
	} catch(e) {} } ");
	
?>
// --></script>

