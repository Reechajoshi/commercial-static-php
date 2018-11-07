var _BASE_D = 'pages/4';
	
CPageD = {
	_PAGE_I: 4,
	
	_I_DEF: { 
		reel1: {
			ok: false,
			src: _BASE_D + '/reel1.png',
			img: new Image(),
			width: 400,
			height: 333
		}, reel2: {
			ok: false,
			src: _BASE_D + '/reel2.png',
			img: new Image(),
			width: 400,
			height: 399
		}, reel3: {
			ok: false,
			src: _BASE_D + '/reel3.png',
			img: new Image(),
			width: 400,
			height: 399
		}, lslide: {
			ok: false,
			src: _BASE_D + '/lslide.png',
			img: new Image(),
			width: 37,
			height: 38
		}, rslide: {
			ok: false,
			src: _BASE_D + '/rslide.png',
			img: new Image(),
			width: 37,
			height: 38
		}
	},
	
	 _ANI_I: false,
	 
	 _posTxtMain: { l: 0, t: 0, w: 0, h: 0 }, //text position
	 
	_baseOffTop: 70,
	 
	init: function(pcon)
	{
		CPageD._imgReel = new Array();
		CPageD._posImg = new Array();
		
		for(var i=0; i <= 2; i++)
		{
			CPageD._imgReel[i] = CPageD._I_DEF[ 'reel' + (i+1) ];
			pcon.appendChild(CPageD._imgReel[i].img);
		}
		
		CPageD._txtMain = CUi.createElm( 'DIV', 'txtmain' );  // text div
		CPageD._txtMain.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( array( "\r", "\n"), "", file_get_contents("${_M_WBASE}/pages/4/content.html.php") ))); ?>';
		
		pcon.appendChild(CPageD._txtMain);
		
		CPageD._txtExtra = CUi.createElm( 'DIV', 'txtextra' );  // text div
		CPageD._txtExtra.setAttribute( 'id', 'area-subsection-c' );
		CPageD._txtExtra.style.display = 'none';
		CPageD._txtExtra.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( "\n", "", file_get_contents('pages/4/content_extra.html') ))); ?>';
		CUi.doc.body.appendChild(CPageD._txtExtra);
		
		CPageD._txtExtraClient = CUi.createElm( 'DIV', 'txtextra_client' );  // text div
		CPageD._txtExtraClient.setAttribute( 'id', 'area-subsection-c' );
		CPageD._txtExtraClient.style.display = 'none';
		CPageD._txtExtraClient.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( "\n", "", file_get_contents('pages/4/content_extra_clientele.html') ))); ?>';
		
		CUi.doc.body.appendChild(CPageD._txtExtraClient);
		
		CPageD._imgArrowR = CPageD._I_DEF[ 'lslide' ];
		CPageD._imgArrowR.img.setAttribute( 'id', 'arrow-right-c' );
		CPageD._imgArrowR.img.onclick = function(e) {CHelp.slideSubSection(true, CPageD._txtExtra); }
		CPageD._imgArrowR.img.style.display='block';
		CPageD._imgArrowR.img.style.position = 'absolute';
		pcon.appendChild(CPageD._imgArrowR.img);
		
		CPageD._imgArrowL = CPageD._I_DEF[ 'rslide' ];
		CPageD._imgArrowL.img.setAttribute( 'id', 'arrow-left-c' );
		CPageD._imgArrowL.img.setAttribute( 'name', 'arrowback' );
		CPageD._imgArrowL.img.onclick = function(e) {CHelp.slideSubSection(false, CPageD._txtExtra); }
		CPageD._imgArrowL.img.style.display='block';
		
		CPageD._txtExtra.appendChild(CPageD._imgArrowL.img);
		
		CPageD._imgArrowClientL = new Image(); //CPageD._I_DEF[ 'rslide' ];
		CPageD._imgArrowClientL.src = CPageD._imgArrowL.img.src; // COPY SRC WILL copy from cache
		CPageD._imgArrowClientL.setAttribute( 'id', 'arrow-left-c' );
		CPageD._imgArrowClientL.setAttribute( 'name', 'arrowback' );
		CPageD._imgArrowClientL.onclick = function(e) {CHelp.slideSubSection(false, CPageD._txtExtraClient); }
		CPageD._imgArrowClientL.style.display='block';
		
		CPageD._txtExtraClient.appendChild(CPageD._imgArrowClientL);
		
		CPageD.draw(pcon);
		CPageD.ani(pcon);
	},
	
	draw: function(pcon)
	{
		CPageD.drawReel();
		CPageD.positionText();
	},
	
	positionText: function()
	{
		var cw = window.innerWidth, ch = window.innerHeight, cwh = parseInt((cw / 2));
		
		CPageD._txtMain.style.position = 'absolute';
		
		CPageD._posTxtMain = CHelp.getPosition(false, 50, false, false, (CPageD._imgArrowR.width + 10) );
		
		CPageD._txtMain.style.position = 'absolute';
		CPageD._txtMain.style.left = CPageD._posTxtMain.l + 'px';
		CPageD._txtMain.style.top = CPageD._posTxtMain.t + 'px';
		CPageD._txtMain.style.width = CPageD._posTxtMain.w + 'px';
		CPageD._txtMain.style.height = CPageD._posTxtMain.h + 'px';
		
		setTimeout( function() {
			var chead = CUtil.getChildByName(CPageD._txtMain, 'ctitle','DIV', true),
				cinfo =  CUtil.getChildByName(CPageD._txtMain, 'cinfo','DIV', true);
			
			cinfo.style.height = (CPageD._txtMain.offsetHeight - chead.offsetHeight - 10) + 'px';
			
			CPageD._imgArrowR.img.style.left = (CPageD._txtMain.offsetLeft + CPageD._txtMain.offsetWidth + 10) + 'px';
			CPageD._imgArrowR.img.style.top = parseInt(CPageD._txtMain.offsetTop + (CPageD._txtMain.offsetHeight / 2) - (CPageD._imgArrowR.height / 2)) + 'px';
		}, 10 );
	},
	
	ani: function(pcon)
	{
		try
		{
			for(var i=0; i <= 2; i++)
			{
				var pos = CUtil.cloneOB( CPageD._posImg[i] );
				pos.t = CPageD._imgReel[i].offsetTop - pcon.offsetTop;
				CPageD.setPosition(CPageD._imgReel[i], pos);
			}
			
			CPageD.aniReel(pcon, pos.t);
		} catch(e) {}
	},
	
	aniReel: function(pcon, ot)
	{
		var sR = (CPageD._imgReel[0].img.offsetTop);
		if(sR > 0) { sR = 0; } else { sR = Math.abs(sR); }
		if((sR > 360) || (sR < 0) ) {sR = 0; }
		
		CUi.transformMe( CPageD._imgReel[2].img, 'rotate(' + sR + 'deg)' );
		//CPageD._lastSX = sX;
	},

	drawReel: function()
	{
		// REEL IMAGE ////////////// ////////////// ////////////// ////////////// 
		var cw = window.innerWidth, ch = window.innerHeight;
		
		for(var i=0; i <= 2; i++)
		{
			CPageD._posImg[i] = CHelp.getPosition(true, CPageD._baseOffTop, CPageD._imgReel[i].width, CPageD._imgReel[i].height);
			CPageD.setPosition(CPageD._imgReel[i], CPageD._posImg[i], true);
		}
	},
	
	setPosition: function(ob, pos, bStore)
	{
		if(ob.img)
		{
			ob.img.style.position = 'absolute';
			ob.img.style.left = pos.l + 'px';
			ob.img.style.top = pos.t + 'px';
			ob.img.style.width = pos.w + 'px';
			ob.img.style.height = pos.h  + 'px';
		
			if (bStore) { setTimeout( function() { 
				ob.offsetTop = ob.img.offsetTop;
			}, 10 ); }
		}
	}
};

window[ CHelp._M_PAGE_HOOK_ID + CPageD._PAGE_I ] = {
	loadMe: function(cb) {
		CUi.loadImages(CPageD._I_DEF, function(li) {
			cb(CPageD._PAGE_I);
		} );
	},
	
	startMe: function(pcon) {
		CPageD.init(pcon);
	},
	
	onResize: function(pcon) {
		CPageD.draw(pcon);
	},
	
	onScroll: function(pcon) {
		CPageD.ani(pcon)
	},
	
	onStart: function() {
		
	},
	
	onStop: function() {
	}
};
