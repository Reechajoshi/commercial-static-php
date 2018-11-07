var _BASE_C = 'pages/3';
	
CPageC = {
	_PAGE_I: 3,

	_I_DEF: { 
		camera_1: {
			ok: false,
			src: _BASE_C + '/camera_1.png',
			img: new Image(),
			width: 400,
			height: 401
		}, camera_2: {
			ok: false,
			src: _BASE_C + '/camera_2.png',
			img: new Image(),
			width: 400,
			height: 401
		}, camera_3: {
			ok: false,
			src: _BASE_C + '/camera_3.png',
			img: new Image(),
			width: 400,
			height: 401
		}, camera_4: {
			ok: false,
			src: _BASE_C + '/camera_4.png',
			img: new Image(),
			width: 400,
			height: 401
		}, lslide: {
			ok: false,
			src: _BASE_C + '/lslide.png',
			img: new Image(),
			width: 37,
			height: 38
		}, rslide: {
			ok: false,
			src: _BASE_C + '/rslide.png',
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
		CPageC._imgCam = new Array();
		
		CPageC._imgCamHolder = { img: document.createElement( 'DIV' ) }; //hack img REFERS TO any object image!!
		CPageC._imgCamHolder.img.style.overflow = 'hidden';
		CPageC._imgCamHolder.img.style.textAlign = 'center';
		
		pcon.appendChild( CPageC._imgCamHolder.img );
		
		CPageC._txtMain = CUi.createElm( 'DIV', 'txtmain' );  // text div
		CPageC._txtMain.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( array( "\r", "\n"), "", file_get_contents("${_M_WBASE}/pages/3/content.html.php") ))); ?>';
		
		pcon.appendChild(CPageC._txtMain);
		
		CPageC._txtExtra = CUi.createElm( 'DIV', 'txtextra' );  // text div
		CPageC._txtExtra.setAttribute( 'id', 'area-subsection-c' );
		CPageC._txtExtra.style.display = 'none';
		CPageC._txtExtra.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( array( "\r", "\n"), "", file_get_contents("${_M_WBASE}/pages/3/content_extra.html.php") ))); ?>';
		
		CUi.doc.body.appendChild(CPageC._txtExtra);
		
		CPageC._I_DEF.lslide.img.setAttribute( 'id', 'arrow-right-c' );
		CPageC._I_DEF.lslide.img.onclick = function(e) {CHelp.slideSubSection(true, CPageC._txtExtra); }
		CPageC._I_DEF.lslide.img.style.display='block';
		CPageC._I_DEF.lslide.img.style.position = 'absolute';
		pcon.appendChild(CPageC._I_DEF.lslide.img);
		
		CPageC._I_DEF.rslide.img.setAttribute( 'id', 'arrow-left-c' );
		CPageC._I_DEF.rslide.img.setAttribute( 'name', 'arrowback' );
		CPageC._I_DEF.rslide.img.onclick = function(e) {CHelp.slideSubSection(false, CPageC._txtExtra); }
		CPageC._I_DEF.rslide.img.style.display='block';
		CPageC._txtExtra.appendChild(CPageC._I_DEF.rslide.img);
		
		CPageC._imgCam[0] = CPageC._I_DEF.camera_1;
		pcon.appendChild(CPageC._imgCam[0].img);
		
		for(var i=1; i <= 3; i++)
		{
			//CPageC._imgCam[i] = CPageC._I_DEF[ 'camera_' + (i+1) ];
			CPageC._imgCam[i] = ( eval( 'CPageC._I_DEF.camera_' + (i+1) ));
		
			CPageC._imgCam[i].img.style.position = 'absolute';
			CPageC._imgCam[i].img.style.left = '0px';
			CPageC._imgCam[i].img.style.top = '0px';
			CPageC._imgCam[i].img.style.width = '100%';
			CPageC._imgCam[i].img.style.height = 'auto'; //NOTE: IE Bug, auto no set to default.
			CPageC._imgCamHolder.img.appendChild(CPageC._imgCam[i].img);
		}
		
		CPageC.draw(pcon);
		CPageC.ani(pcon);
	},
	
	draw: function(pcon)
	{
		CPageC.drawCam();
		CPageC.positionText();
	},
	
	positionText: function()
	{
		var cw = window.innerWidth, ch = window.innerHeight, cwh = parseInt((cw / 2));
		CPageC._txtMain.style.position = 'absolute';
		
		CPageC._posTxtMain = CHelp.getPosition(false, 50, false, false, (CPageC._I_DEF.lslide.width + 10) );
		
		CPageC._txtMain.style.position = 'absolute';
		CPageC._txtMain.style.left = CPageC._posTxtMain.l + 'px';
		CPageC._txtMain.style.top = CPageC._posTxtMain.t + 'px';
		CPageC._txtMain.style.width = CPageC._posTxtMain.w + 'px';
		CPageC._txtMain.style.height = CPageC._posTxtMain.h + 'px';
		
		setTimeout( function() {
			var chead = CUtil.getChildByName(CPageC._txtMain, 'ctitle','DIV', true),
				cinfo =  CUtil.getChildByName(CPageC._txtMain, 'cinfo','DIV', true);
			
			cinfo.style.height = (CPageC._txtMain.offsetHeight - chead.offsetHeight - 10) + 'px';
			
			CPageC._I_DEF.lslide.img.style.left = (CPageC._txtMain.offsetLeft + CPageC._txtMain.offsetWidth + 10) + 'px';
			CPageC._I_DEF.lslide.img.style.top = parseInt(CPageC._txtMain.offsetTop + (CPageC._txtMain.offsetHeight / 2) - (CPageC._I_DEF.lslide.height / 2)) + 'px';
		}, 10 );
	},
	
	ani: function(pcon)
	{
		try
		{
			var pos = CUtil.cloneOB( CPageC._posImg );
			pos.t = CPageC._imgCam[0].offsetTop - pcon.offsetTop;
			CPageC.setPosition(CPageC._imgCam[0], pos);
			
			var posHold = CUtil.cloneOB( CPageC._posHol );
			posHold.t = CPageC._imgCamHolder.offsetTop - pcon.offsetTop;
			CPageC.setPosition(CPageC._imgCamHolder, posHold);
			
			CPageC.aniCam(pcon, pos.t);
		}
		catch(e) {}
	},
	
	aniCam: function(pcon, ot)
	{
		var midH = ((window.innerHeight/2) - CPageC._baseOffTop ), incY = ( 1 / (midH) );
		ot = midH - Math.abs(ot);
		var sXBase = 0.59;
		
		var sX = sXBase, sy = CUtil.getScrollY();
		
		if( ot > 0 )
		{
			sX = 1-(pcon.offsetTop / window.innerHeight);
			
			if(sX > 1) {sX = 1; }
			else if(sX < sXBase) { sX = sXBase; }	
		}
		
		//if(!CUtil.varok(CPageC._lastSX))
		//	CPageC._lastSX = sX;
		
			CUi.transformMe( CPageC._imgCam[3].img, 'scale(' + sX + ')' );
			CUi.transformMe( CPageC._imgCam[2].img, 'scale(' + sX + ')' );
		//CPageC._lastSX = sX;
	},

	drawCam: function()
	{
		// CAM IMAGE ////////////// ////////////// ////////////// ////////////// 
		var cw = window.innerWidth, ch = window.innerHeight;
		
		CPageC._posImg = CHelp.getPosition(true, CPageC._baseOffTop, CPageC._imgCam[0].width, CPageC._imgCam[0].height);
		CPageC.setPosition(CPageC._imgCam[0], CPageC._posImg, true);
		CPageC._posHol = CHelp.getPosition(true, CPageC._baseOffTop, CPageC._imgCam[0].width, CPageC._imgCam[0].height);
		CPageC.setPosition(CPageC._imgCamHolder, CPageC._posHol, true);
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
		
			if (bStore ) { setTimeout( function() { 
				ob.offsetTop = ob.img.offsetTop;
			}, 10 ); }
		}
	}
};

window[ CHelp._M_PAGE_HOOK_ID + CPageC._PAGE_I ] = {
	loadMe: function(cb) {
		CUi.loadImages(CPageC._I_DEF, function(li) {
			cb(CPageC._PAGE_I);
		} );
	},
	
	startMe: function(pcon) {
		CPageC.init(pcon);
	},
	
	onResize: function(pcon) {
		CPageC.draw(pcon);
	},
	
	onScroll: function(pcon) {
		CPageC.ani(pcon)
	},
	
	onStart: function() {
		
	},
	
	onStop: function() {
	}
};
