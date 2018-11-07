CHelp = {
	_M_ELM_SCROLL: false,
	_M_PAGES: new Array(),
	
	_M_PC: 0, _cp: 0, _np: 0,
	_M_MENU_BAR_CY: 28, // consider Top of scroll
	
	_M_PAGE_HOOK_ID: 'PHOOK',
	
	_inPageScroll: false,
	obInSlide: false,
	
	init: function(pc, doc)
	{
		CUi.init(doc);
		CHelp._M_PC = (pc);
		
		CUi.hookScroll( 'scrollpage', CHelp.onScrollers );
		CUi.hookResize( 'resizepage', CHelp.onResize );
		
		if( CUi._isTab )
			CUi.doc.body.style.overflow = 'hidden';
				
		CHelp.initPages( function() {
			CHelp.initSocialV();
			CHelp.initMenu();
			CHelp.monitorHashChange(); // TO ENABLE RELOAD AND BACK NAVIGATION OF MENU CHOICES
			
			CHelp.initScroll();
			
			CHelp.startPages( function() { 
				CHelp.removeLoading();
				
				if(window._startOverridePage)
					setTimeout( function() { CHelp.goToPage( window._startOverridePage ); }, 1 );
			} );
		} );
	},
	
	removeLoading: function()
	{
		var lselm = document.getElementById('loadset');
		if(lselm)
			lselm.parentNode.removeChild( lselm );
	},
	
	startPages: function(cb)
	{
		// FIRST PAGE HAS AUTH AS TO WHEN TO START THE APP
		CHelp._M_PAGES[0].ref.startMe(CHelp._M_PAGES[0].elm, function() {
			for( var ix = 1; ix < CHelp._M_PC; ix++ )
			{
				if( CUtil.varok( CHelp._M_PAGES[ix].ref ) && CUtil.varok( CHelp._M_PAGES[ix].ref.startMe ))
				{
					CHelp._M_PAGES[ix].ref.startMe(CHelp._M_PAGES[ix].elm);
				}
			}
			
			cb();
		} );
	},
		
	informOnScroll: function()
	{
		for( var ix = 0; ix < CHelp._M_PC; ix++ )
		{
			if( CUtil.varok( CHelp._M_PAGES[ix].ref ) && CUtil.varok( CHelp._M_PAGES[ix].ref.onScroll ))
			{
				CHelp._M_PAGES[ix].ref.onScroll(CHelp._M_PAGES[ix].elm);
			}
		}
	},
	
	resizePages: function()
	{
		for( var ix = 0; ix < CHelp._M_PC; ix++ )
		{
			if( CUtil.varok( CHelp._M_PAGES[ix].ref ) && CUtil.varok( CHelp._M_PAGES[ix].ref.onResize ))
			{
				CHelp._M_PAGES[ix].ref.onResize(CHelp._M_PAGES[ix].elm);
			}
		}
	},
	
	initPages: function( funcInit )
	{
		for( var ix = 0; ix < CHelp._M_PC; ix++ )
		{
			CHelp._M_PAGES[ ix ] = { elm: CUtil.getChildByName( CUi.doc.body, 'page' + (ix+1), 'DIV', false) };
			if(!CHelp._M_PAGES[ ix ])
				return(false);
			
			CHelp._M_PAGES[ ix ].elm.style.top = window.innerHeight + 'px';
			CHelp._M_PAGES[ ix ].elm.style.width = window.innerWidth + 'px';
			CHelp._M_PAGES[ ix ].elm.style.height = '0px';
			CHelp._M_PAGES[ ix ].elm.style.zIndex = ix;
			
			if( CUtil.varok( window[ CHelp._M_PAGE_HOOK_ID + (ix+1) ] ) )
			{
				CHelp._M_PAGES[ix].ref = window[ CHelp._M_PAGE_HOOK_ID + (ix+1) ];
				CHelp._M_PAGES[ix].bloaded = false;
				CHelp._M_PAGES[ix].ref.loadMe(function(_ix) {
					CHelp._M_PAGES[(_ix-1)].bloaded = true;
				} );
			}
			else
				CHelp._M_PAGES[ix].bloaded = true;
		}
		
		//funcInit();
		
		var lI = setInterval( function() {
			var bIsLoaded = true;
			
			if(false)
			{ // NOTE: TODO: FOR NOW SKIP WAITING FOR EACH PAGE TO LOAD
				for( var ix = 0; ix < CHelp._M_PC; ix++ )
				{
					if( CHelp._M_PAGES[ix].bloaded == false )
						{ bIsLoaded = false; }
				}
			}
			
			if(bIsLoaded)
			{
				clearInterval(lI);
				funcInit();
			}
		}, 500 );
	},
	
	getScrollCY: function()
	{
		return(window.innerHeight); 
	},
	
	initSocialV: function()
	{
		if(true)
		{
			CHelp._socialV = document.createElement('DIV');
			CHelp._socialV.setAttribute('id', 'social-top');
			
			var html = '<div style="width:80px">';
			
			html += '<span style="width:22px"><a name=nor target=_blank href="http://www.facebook.com/far.commercials"><img src=img/fb.png width=22px /></a></span>';
			html += '<span style="width:22px"><a name=nor target=_blank href="http://vimeo.com/search?q=farcommercials"><img src=img/vimeo.png width=22px /></a></span>';
			html += '<span style="width:22px"><a name=nor target=_blank href="http://www.youtube.com/farcommercials"><img src=img/yt.png width=22px /></a></span>';
			
			html += '</div>';
			
			CHelp._socialV.innerHTML = html;
			
			document.body.appendChild( CHelp._socialV );
			
			setTimeout( function() {
				CHelp.positionSocialV();
				CUtil.applyToChildNodes( CHelp._socialV, 'A', true, function(oba) {
					oba.style.width = (26) + 'px';
				} );
			}, 10 );
		}
	},
	
	positionSocialV: function()
	{
		/*var cl = ( (window.innerWidth * ( (window.innerWidth>1000) ? (0.70) : (0.80) )) - (CHelp._menu.childNodes[0].offsetWidth) ); // original */
		var scl = (window.innerWidth - (CHelp._socialV.childNodes[0].offsetWidth + 15) );
		CHelp._socialV.style.left = scl + 'px';
	},
	
	initMenu: function(mtodo)
	{
		var mtodo =  new Array();
		
		if( CUi._isTab )
		{
			mtodo.push( { text: 'Home', page: 1 } );
		}
		
		mtodo.push( { text: 'About Us', page: 2 } );
		mtodo.push( { text: 'Work', page: 3 } );
		mtodo.push( { text: 'Production Support', page: 4 } );
		mtodo.push( { text: 'Contact Us', page: 5 } );
		
		CHelp._menu = document.createElement('DIV');
		CHelp._menu.setAttribute('id', 'menu-top');
		
		var html = '<div>';
		for(var im in mtodo)
		{
			html += '<a href=# onClick="CHelp.goToPage(' +  mtodo[im].page + ');return(false);">' + mtodo[im].text + '</a>';	
			if( im < mtodo.length-1)
				html += '<span>&#160;|&#160;</span>';
		}
		
		html += '</div>';
		
		CHelp._menu.innerHTML = html;
		
		document.body.appendChild( CHelp._menu );
		
		setTimeout( function() {
			CHelp.positionMenu();
			CUtil.applyToChildNodes( CHelp._menu, 'A', true, function(oba) {
				if(oba.getAttribute('name') && oba.getAttribute('name') == 'nor')
					oba.style.width = (26) + 'px';
				else
					oba.style.width = (oba.offsetWidth + 20) + 'px';
			} );
		}, 10 );
	},
	
	positionMenu: function()
	{
		/*var cl = ( (window.innerWidth * ( (window.innerWidth>1000) ? (0.70) : (0.80) )) - (CHelp._menu.childNodes[0].offsetWidth) ); // original */
		var cl = ( (window.innerWidth * ( (window.innerWidth>500) ? (0.60) : (0.4) )) - (CHelp._menu.childNodes[0].offsetWidth) );
		if(cl < 30) { cl=30;}
		CHelp._menu.childNodes[0].style.left = cl + 'px';
	},
	
	initScroll: function()
	{
		CHelp._M_PAGES[ 0 ].elm.style.left = '0px';
		CHelp._M_PAGES[ 0 ].elm.style.top = '0px';
		CHelp._M_PAGES[ 0 ].elm.style.height = (CHelp.getScrollCY()) + 'px';		
	
		if( !CUi._isTab )
		{
			CHelp.setScroll();
			
			// INITIALISE STATE ers
			CHelp._npTop = 0;
			CHelp._npHeight = 0;
			CHelp._cp = 0; CHelp._np = 1;
			
			CHelp._M_SCROLL_START = CHelp._npTop;
			
			window.scrollTo(0, 0); // NOTE: do not remove this is needed for strange refresh behaviour
			window.scrollTo(0, window.innerHeight); // strange behaviour
			//CHelp.goToPage(0);
			setTimeout( function() { window.scrollTo(0, 0); }, 10 ); // STart Page
		}
	},
	
	incPageI: function()
	{ 
		CHelp._np++;
		CHelp._cp = (CHelp._np-1); // SHOW CLEARLY THE RELATIONSHIP
	},
	
	decPageI: function()
	{
		CHelp._np--;
		CHelp._cp = (CHelp._np-1);
	},
	
	setScroll: function()
	{
		var elmScroll = CUi.doc.getElementById( 'scrollset' );
		
		if(elmScroll)
		{
			// SET SCROLL RANGE VIA FORCE OBJECT
			elmScroll.style.left = '0px';
			elmScroll.style.top = (((CHelp._M_PC) * CHelp.getScrollCY())) + 'px';
		}
	},
	
	setPageYH: function(pi, pt, ph)
	{
		CHelp._M_PAGES[ pi ].elm.style.top = pt + 'px';
		CHelp._M_PAGES[ pi ].elm.style.height = ph + 'px';
	},
	
	execPage: function(bIsStart)
	{
		//CUi.logFB( 'SS = ' + bIsStart + ' - ' + CHelp._np);
	},
	
	scrollTo: function(ydest)
	{
		window.scrollTo(0, ydest);
		setTimeout( function() { window.scrollTo(0, ydest); }, 10 );
	},
	
	monitorHashChange: function()
	{		
		CHelp._lastHash = ''; //location.hash.substr(1);		
		setInterval( function() {
			cur_lastHash = location.hash.substr(1);
			if( CHelp._lastHash != cur_lastHash )
			{
				if(cur_lastHash.indexOf('page') == 0)
				{
					var goto_page = parseInt(cur_lastHash.substr(4));
					if(goto_page>=0 && goto_page<=CHelp._M_PC)
						CHelp.goToPage( goto_page );
				}
				else
					CHelp.goToPage( 0 );
			}
			
			CHelp._lastHash = cur_lastHash;
			
		}, 100 );
	},
	
	setNavHash: function(pn)
	{
		CHelp._curPageOnRef = pn;
		var lh = 'page' +  CHelp._curPageOnRef;
		CHelp._lastHash = lh;
		location.hash = "#" + lh;
	},
	
	_inSlide: false,
	
	slideSubSection: function(bSlideRight, scon)
	{
		if( CUi._isTab )
			CHelp.slideSubSection_tab( bSlideRight, scon );
		else
			CHelp.slideSubSection_desk( bSlideRight, scon );
	},
	
	slideSubSection_tab: function(bSlideRight, scon)
	{
		if( CHelp._inSlide == false )
		{
			var xDest = 0, xorg = 0, xmid = (cw/2), moveX = 0, mDirection = 0;
			var xDelta = (1 / 13), mAccelIncUp = true, mAccel = 1;		
			var menuLeft = CHelp._menu.offsetLeft, menuLeftEnd = (xDest - xorg);
			
			//CHelp._inSlide = true;
			var cw = CUi.get_view_cxy(true), ch = CUi.get_view_cxy(false);
			var sLeft = cw, sWidth = 0;
			
			if(bSlideRight && CHelp.obInSlide == false)
			{				
				CHelp.obInSlide = scon;
				
				sLeft = cw; sWidth = 0;
				xDest = 0; xorg = cw;
				moveX = 0; mDirection = -20;
				xDelta = (1 / 13); mAccelIncUp = true; mAccel = 1;			
				menuLeft = 0, menuLeftEnd = (xDest - xorg);
				
				setTimeout( function() {
					var larr = CUtil.getChildByName( scon, 'arrowback', 'IMG', false );
					if(larr)
					{
						larr.style.position = 'absolute';
						larr.style.left = (cw - larr.offsetWidth - 20) + 'px';
						larr.style.top = ((ch / 2) - (larr.offsetHeight / 2) - 20) + 'px';					
					}
				}, 10 );
			}
			else if(!bSlideRight && (CHelp.obInSlide != false) )
			{
				CHelp.obInSlide = false;
				
				sLeft = 0; sWidth = cw;
				xDest = cw; xorg = 0;
				moveX = 0; mDirection = +20;
				xDelta = (1 / 13); mAccelIncUp = true; mAccel = 1;
				menuLeft = (xorg - xDest), menuLeftEnd = 0;
			}
			else
				return( false );
				
			CUi.setTransitionOn( CHelp._menu, {
				transitionProperty: 'left,width', transitionTimingFunction: 'ease-in-out', transitionDuration: '2s'
			}, {
				left:  menuLeftEnd + 'px'
			}, function() { alert('abc') } );
			
			CUi.setTransitionOn( scon, { position: 'fixed', zIndex: 15, display: 'block',
				left: sLeft + 'px', width: sWidth + 'px', top: '0px', height: ch + 'px',
				transitionProperty: 'left,width', transitionTimingFunction: 'ease-in-out', transitionDuration: '2s' }, 
				{ left: xDest + 'px', width: xorg + 'px' }
			);
		}
	},
	
	slideSubSection_desk: function(bSlideRight, scon)
	{
		if( CHelp._inSlide == false )
		{
			CHelp._inSlide = true;
			var cw = CUi.get_view_cxy(true), ch = CUi.get_view_cxy(false);
			var sLeft = cw, sWidth = 0;
		
			var xDest = 0, xorg = 0, xmid = (cw/2), moveX = 0, mDirection = 0;
			var xDelta = (1 / 13), mAccelIncUp = true, mAccel = 1;		
			var menuLeft = CHelp._menu.offsetLeft, menuLeftEnd = (xDest - xorg);
			
			var bEnableBodyScrollAfter = false;
			
			if(bSlideRight && CHelp.obInSlide == false)
			{
				CHelp.obInSlide = scon;
				CUi.doc.body.style.overflow = 'hidden'; // disable scroll
				
				sLeft = cw; sWidth = 0;
				xDest = 0; xorg = cw;
				moveX = 0; mDirection = -20;
				xDelta = (1 / 13); mAccelIncUp = true; mAccel = 1;			
				menuLeft = 0, menuLeftEnd = (xDest - xorg);
			
				scon.style.position = 'fixed';
				scon.style.zIndex = 15;
				scon.style.display = 'block';
				scon.style.left = sLeft + 'px'; scon.style.height = ch + 'px';
				scon.style.top = '0px'; scon.style.width = sWidth + 'px';	

				
				setTimeout( function() {
					var larr = CUtil.getChildByName( scon, 'arrowback', 'IMG', false );
					if(larr)
					{
						larr.style.position = 'absolute';
						larr.style.left = (cw - larr.offsetWidth - 20) + 'px';
						larr.style.top = ((ch / 2) - (larr.offsetHeight / 2) - 20) + 'px';					
					}
				}, 10 );
			}
			else if(!bSlideRight && (CHelp.obInSlide != false) )
			{
				CHelp.obInSlide = false;
				bEnableBodyScrollAfter = true;
				
				sLeft = 0; sWidth = cw;
				xDest = cw; xorg = 0;
				moveX = 0; mDirection = +20;
				xDelta = (1 / 13); mAccelIncUp = true; mAccel = 1;
				menuLeft = (xorg - xDest), menuLeftEnd = 0;
			}
			else
				return( false );
			
			if( CUi._isTab )
			{
				CHelp._menu.style.left = menuLeftEnd + 'px';
				
				scon.style.left = xDest + 'px';
				scon.style.width = xorg + 'px';
				
				if(bEnableBodyScrollAfter)
					CUi.doc.body.style.overflow = 'auto'; // enable
				
				CHelp._inSlide = false;
			}
			else
			{
				CHelp._goSlideInterval = setInterval( function() {
					var doSet = false;
					
					moveX += ((mDirection * xDelta) * mAccel);
					
					if( (mDirection<0) && (sLeft < xmid) )
					{
						if( (sLeft <= xDest) || ( (sLeft+moveX) <= xDest ) )
							doSet = true;
						else
							mAccel = -0.98;
					}
					else if( (mDirection>0) && (sLeft > xmid) )
					{
						if( (sLeft >= xDest) || ( (sLeft+moveX) >= xDest ) )
							doSet = true;
						else
							mAccel = -0.98;
					}
					
					if( (doSet) )
					{
						CHelp._menu.style.left = menuLeftEnd + 'px';
						
						scon.style.left = xDest + 'px'; scon.style.width = xorg + 'px';
						
						if(bEnableBodyScrollAfter)
							CUi.doc.body.style.overflow = 'auto'; // enable
						
						CHelp._inSlide = false;
						clearInterval( CHelp._goSlideInterval );
					}
					else
					{	
						sLeft += moveX; sWidth = cw - sLeft;
						menuLeft += moveX;
						CHelp._menu.style.left = menuLeft + 'px';
						scon.style.left = sLeft + 'px'; scon.style.width = sWidth + 'px';
					}
				}, 35);
			}
		}
	},
	
	goToPage: function(pn)
	{
		if( CUi._isTab )
		{
			CHelp._goToPage_direct(pn);
		}
		else
		{
			CHelp._goToPage_scroll(pn);
		}
	},
	
	_goToPage_direct: function(pn)
	{
		// reset position of all pages
		for( var ix = 0; ix < CHelp._M_PC; ix++ )
		{
			CHelp._M_PAGES[ ix ].elm.style.top = window.innerHeight + 'px';
			CHelp._M_PAGES[ ix ].elm.style.width = window.innerWidth + 'px';
			CHelp._M_PAGES[ ix ].elm.style.height = '0px';
		}
		
		CHelp.setPageYH((pn-1), 0, CHelp.getScrollCY() );
		
		// BUG: DUE TO BUG IN  IOS: CAUSE THE INTERNAL ENGINE TO RELOAD SRC FROM ISELF.
		var ielms = CHelp._M_PAGES[ (pn-1) ].elm.getElementsByTagName('IMG');
		for( var ixi in ielms )
		{
			ielms[ ixi ].src = ielms[ ixi ].src;
			ielms[ ixi ].style.display = 'block';
			ielms[ ixi ].style.visibility = 'visible';
		}
		
		CUtil.applyToChildNodes( CHelp._M_PAGES[ (pn-1) ].elm, 'IMG', true, function(oi) { 
			oi.src = oi.src;
			oi.style.display = 'block';
			oi.style.visibility = 'visible';
		} );
	},
	
	_goToPage_scroll: function(pn)
	{	
		if(pn >=1 || pn <= 5)
		{
			if(CHelp._inPageScroll == false)
			{			
				CHelp._inPageScroll = true;
				
				var yDest = ((pn-1) * CHelp.getScrollCY()), yorg = CUtil.getScrollY(),
					moveY = ( (yDest < yorg) ? (-5) : (5) ), mDirection = ( (yDest < yorg) ? (-20) : (20) ),
					ymDelta = (1 / 13), mAccelIncUp = true, mAccel = 1;
					
				CHelp._goToPageInterval = setInterval( function() {
					var doSet = false, y = CUtil.getScrollY();
					
					moveY += ((mDirection * ymDelta) * mAccel);
					
					if( mDirection < 0 )
					{
						if( (y <= yDest) || ( (y+moveY) <= yDest ) )
							doSet = true;
						else if( y < Math.abs(yorg + ((yDest-yorg)/2) ) )
							mAccel = -0.9;
					}
					else if( mDirection > 0 )
					{
						if( ( y >= yDest ) || ( (y+moveY) >= yDest ) )
							doSet = true;
						else if( y > Math.abs(yDest + ((yorg-yDest)/2) ) )
							mAccel = -0.9;				
					}
					
					if( (doSet) )
					{
						CHelp.scrollTo(yDest);					
						clearInterval( CHelp._goToPageInterval );
						CHelp.setNavHash(pn);
						CHelp._inPageScroll = false;
					}
					else
					{			
						window.scrollBy( 0, moveY );					
					}
						
					CHelp.handleScroll();
				}, 35);
			}
		}
	},
	
	// TODO: item w/h needs to be one variable; and named for left only, like bRRMargin
	getPosition: function(isLeft, tadjust, item_width, item_height, bRRMargin)
	{
		var cw = window.innerWidth, ch = CHelp.getScrollCY(),
			pos = { l: 0, t: 0, w: 0, h: 0 };

		if(isLeft)
		{
			pos.w = parseInt((cw / 2) * 0.51);
			
			if(pos.w > 380) pos.w = 380;
			else if(pos.w < 200) pos.w = 200;
			
			pos.l = parseInt((cw / 4) - (pos.w/2));
			if(pos.l < 50) { pos.l = 50; }

			pos.h = parseInt((item_height / item_width) * pos.w);
			
			//pos.t = parseInt( (ch - pos.h ) / 2 ) - tadjust;
			pos.t = parseInt( ch / 3 ) - tadjust;
			
			if(pos.t < 30) { pos.t = (30); }
		}
		else
		{
			pos.w = parseInt((cw / 2) * 0.91);
			if(pos.w > 550) pos.w = 550;
			else if(pos.w < 200) pos.w = 200;
			
			pos.l = parseInt(cw / 2);
			pos.t = parseInt( ch / 5 ) - tadjust;
			if(pos.t < 30) { pos.t = (30); }
			pos.h = ch - (pos.t) - 20;
			if(pos.h < 50) { pos.h = 50; }
			if(pos.t + pos.h >= ch) { pos.h = 350; }
			
			// If arrow present keep space.
			if(CUtil.varok(bRRMargin))
			{
				if( (pos.l + pos.w + bRRMargin + 20) > cw)
					pos.w -= bRRMargin;
			}
		}
		
		return(pos);
	},
	
	handleScroll: function()
	{
		var y = CUtil.getScrollY(),
			cy = CHelp.getScrollCY();
		
		CHelp._npOldTop = CHelp._npTop;
		CHelp._npHeight = y - CHelp._M_SCROLL_START;
		CHelp._npTop = (cy - CHelp._npHeight);
		
		if( CUtil.varok(CHelp._prevScrolY) && (y != CHelp._prevScrolY) )
		{
			if( (y > CHelp._prevScrolY) && CHelp._npTop < (cy / 2) )
			{
				CHelp.setNavHash(CHelp._np+1);		
			}
			else if( (y < CHelp._prevScrolY) && CHelp._npTop < (cy / 2) )
			{
				CHelp.setNavHash(CHelp._np);		
			}
		}
		
		if( y != CHelp._prevScrolY )
			CHelp._prevScrolY = y;
			
		if( CHelp._npTop < 0 )
		{
			CHelp.setPageYH( CHelp._np, 0, cy );
			CHelp.execPage(false);
			
			if( CHelp._np < (CHelp._M_PC - 1) )
			{
				CHelp._M_SCROLL_START = (cy * (CHelp._np)); // + (CHelp._npTop); // adjust as per overshoot
				CHelp.incPageI();
				CHelp.execPage(true);
			}
		}
		else if( CHelp._npTop > (cy) )
		{
			CHelp.setPageYH( CHelp._np, cy, 0 );
			CHelp.execPage(false);
			
			if( CHelp._np > 0 )
			{
				CHelp._M_SCROLL_START = (cy * (CHelp._np-2)); //(-1 * (window.innerHeight * (CHelp._np-1))); // + (CHelp._npTop); // adjust as per overshoot
				CHelp.decPageI();
				CHelp.execPage(true);
			}
		}
		else
		{
			CHelp.setPageYH( CHelp._np, CHelp._npTop, CHelp._npHeight);
		}
		
		CHelp.informOnScroll();		
	},
	
	onScrollers: function(e)
	{
		CHelp.handleScroll(); //, 1 );
		setTimeout( function() { CHelp.handleScroll(); }, 200 );
	},
	
	onResize: function(e)
	{
		if( !CUi._isTab )
		{
			CHelp.setScroll();
			
			// Width of all pages needs to be set and previous page of scroll's height needs to be set. ////////////// ////////////// 
			for( var ix = 0; ix < CHelp._M_PC; ix++ )
			{
				CHelp._M_PAGES[ ix ].elm.style.width = window.innerWidth + 'px';
			}
			
			for( var ix = 0; ix <= CHelp._cp; ix++ )
			{
				CHelp._M_PAGES[ ix ].elm.style.height =  window.innerHeight + 'px';
			}
			//////////////// ////////////// ////////////// ////////////// ////////////// ////////////// ////////////// ////////////// 
			
			// HACK: Note do not fan out any resize calls to pages; for now. Due to bug just force reload of page on resize (which will hopefully reload from cachers)
			// TODO renove window reload 
			//
			
			// RELOAD FROM CACHE; if apache sends supporting expiration headers that would be good.
			setTimeout( function() {
				document.body.innerHTML = '<div id=loadset>Loading ...</div>'; // BOOM FORCE 
				window.location.reload( false );
			}, 1 ); 
			
			// TODOL Fix bugs in resize of pages as due processes permits
			//
			//CHelp.resizePages();
			CHelp.handleScroll();
			CHelp.positionMenu();
		}
	},
	
	showUTubeVid: function(ob)
	{
		try {
			//var n = ob.getAttribute('name').split('|');
			//if(n.length==2)
			{
				var vid = ob.getAttribute('name'); //'QFcA6ZnKi70', vtitle = 'My Video';
				var html = '<div style="margin-top:10px;background-color:#000;"><iframe width="560" height="315" src="http://www.youtube.com/embed/' + vid + '" frameborder="0" allowfullscreen></iframe></div>';
				
				html += '<div style="margin: 10px auto 0px; text-align:center"><button onclick="var e = getElementById(\'winblockers\');e.parentNode.removeChild(e);document.body.style.overflow=\'auto\';return(false);" style="background-color:#bbb;padding: 0px 25px">Close</button></div>';
				
				var elm = document.createElement('DIV');
				elm.setAttribute('id','winblockers');
				
				var bhtml = '<table class=winblockers-table><tr><td align=center valign=middle>' + html + '</td></tr></table>';
				elm.innerHTML = bhtml;
				document.body.appendChild(elm);
				document.body.style.overflow='hidden';
			}
		} catch(e) {}
	}
};