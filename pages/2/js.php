var _BASE_B = 'pages/2';
CPageB = {
	_PAGE_I: 2,
	_I_DEF: { 
		eyeback: {
			ok: false,
			src: _BASE_B + '/eyeback.png',
			img: new Image(),
			width: 400,
			height: 397
		}, eyeiris: {
			ok: false,
			src: _BASE_B + '/eyeiris.png',
			img: new Image(),
			width: 400,
			height: 397
		}, eyepupil: {
			ok: false,
			src: _BASE_B + '/eyepupil.png',
			img: new Image(),
			width: 400,
			height: 397
		}, lslide: {
			ok: false,
			src: _BASE_B + '/lslide.png',
			img: new Image(),
			width: 37,
			height: 38
		}, rslide: {
			ok: false,
			src: _BASE_B + '/rslide.png',
			img: new Image(),
			width: 37,
			height: 38
		}
	},
	
	 _ANI_I: false,
	 
	_posBak: { l: 0, t: 0, w: 0, h: 0 },
	_posIris: { l: 0, t: 0, w: 0, h: 0 },
	_posPupil: { l: 0, t: 0, w: 0, h: 0 },
	 _posTxtMain: { l: 0, t: 0, w: 0, h: 0 },
	
	_baseOffTop: 70,
	
	init: function(pcon)
	{
		pcon.appendChild(CPageB._I_DEF.eyeback.img);
		pcon.appendChild(CPageB._I_DEF.eyeiris.img);
		pcon.appendChild(CPageB._I_DEF.eyepupil.img);
		
		CPageB._txtMain = CUi.createElm( 'DIV', 'txtmain' );
		CPageB._txtMain.innerHTML = '<?php echo( str_replace( "'", '"', str_replace( array( "\r", "\n"), "", file_get_contents("${_M_WBASE}/pages/2/content.html.php") ))); ?>';
		pcon.appendChild(CPageB._txtMain);
		
		CPageB._txtExtra = CUi.createElm( 'DIV', 'txtextra' );  // text div
		CPageB._txtExtra.setAttribute( 'id', 'area-subsection-c' );
		CPageB._txtExtra.style.display = 'none';
		CPageB._txtExtra.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( "\n", "", file_get_contents('pages/2/content_extra.html') ))); ?>';
		CUi.doc.body.appendChild(CPageB._txtExtra);
		
		CPageB._I_DEF.lslide.img.setAttribute( 'id', 'arrow-right-c' );
		CPageB._I_DEF.lslide.img.onclick = function(e) {CHelp.slideSubSection(true, CPageB._txtExtra);}
		CPageB._I_DEF.lslide.img.style.display='block';
		CPageB._I_DEF.lslide.img.style.position = 'absolute';
		pcon.appendChild(CPageB._I_DEF.lslide.img);
		
		CPageB._I_DEF.rslide.img.setAttribute( 'id', 'arrow-left-c' );
		CPageB._I_DEF.rslide.img.setAttribute( 'name', 'arrowback' );
		CPageB._I_DEF.rslide.img.onclick = function(e) {CHelp.slideSubSection(false, CPageB._txtExtra); }
		CPageB._I_DEF.rslide.img.style.display='block';
		CPageB._txtExtra.appendChild(CPageB._I_DEF.rslide.img);
		
		CPageB.draw(pcon, true);
		CPageB.ani(pcon);
	},
	
	ani: function(pcon)
	{
		if( !CUi._isTab )
		{
			if(CPageB._posBak)
			{
				var pos = CUtil.cloneOB( CPageB._posBak );
				pos.t = CPageB._I_DEF.eyeback.offsetTop - pcon.offsetTop;
				CPageB.setPosition(CPageB._I_DEF.eyeback, pos);
				
				pos = CUtil.cloneOB( CPageB._posIris );
				pos.t = CPageB._I_DEF.eyeiris.offsetTop - pcon.offsetTop;
				CPageB.setPosition(CPageB._I_DEF.eyeiris, pos);
				
				pos = CUtil.cloneOB( CPageB._posPupil );
				pos.t = CPageB._I_DEF.eyepupil.offsetTop - pcon.offsetTop;
				CPageB.setPosition(CPageB._I_DEF.eyepupil, pos);			
				CPageB.aniPupil(pcon, pos.t);
			}
		}
	},
	
	aniPupil: function(pcon, ot)
	{
		var midH = ((window.innerHeight/2) - CPageB._baseOffTop ), incY = ( 1 / (midH) );
		ot = midH - Math.abs(ot);
		
		var sX = 0.45, sy = CUtil.getScrollY();

		if( ot > 0 )
		{
			sX = 1-(pcon.offsetTop / window.innerHeight);
			if(sX > 1) {sX = 1; }
			else if(sX < 0.45) { sX = 0.45; }	
		}
		
		CUi.transformMe( CPageB._I_DEF.eyepupil.img, 'scaleX(' + sX + ')' );
	},

	draw: function(pcon, bStore)
	{
		CPageB.drawEye(bStore);
		CPageB.positionText();
	},
	
	drawEye: function(bStore)
	{
		// EYE IMAGE ////////////// ////////////// ////////////// ////////////// 
		var cw = window.innerWidth, ch = window.innerHeight;
											
		CPageB._posBak = CHelp.getPosition(true, CPageB._baseOffTop, CPageB._I_DEF.eyeback.width,CPageB._I_DEF.eyeback.height);
		CPageB.setPosition(CPageB._I_DEF.eyeback, CPageB._posBak, bStore);
		
		CPageB._posIris = CHelp.getPosition(true, CPageB._baseOffTop, CPageB._I_DEF.eyeiris.width,CPageB._I_DEF.eyeiris.height);
		CPageB.setPosition(CPageB._I_DEF.eyeiris, CPageB._posIris, bStore);
		
		CPageB._posPupil = CHelp.getPosition(true, CPageB._baseOffTop, CPageB._I_DEF.eyepupil.width,CPageB._I_DEF.eyepupil.height);
		CPageB.setPosition(CPageB._I_DEF.eyepupil, CPageB._posPupil, bStore);
	},
	
	setPosition: function(ob, pos, bStore)
	{
		ob.img.style.position = 'absolute';
		//ob.img.style.zIndex = (200 - ziM);
		ob.img.style.left = pos.l + 'px';
		ob.img.style.top = pos.t + 'px';
		ob.img.style.width = pos.w + 'px';
		ob.img.style.height = pos.h  + 'px';
		
		// !CUtil.varok(ob.offsetTop) (consider only first setting this if nessasery otherwise remove for now
		if (bStore) { setTimeout( function() { 
			ob.offsetTop = ob.img.offsetTop;
			//CUi.logFB('nit CPageB._posBak.t = ' + CPageB._posBak.t + ' - ' + ob.offsetTop);
		}, 10 ); }
	},
	
	positionText: function()
	{
		var cw = window.innerWidth, ch = window.innerHeight, cwh = parseInt((cw / 2));
		CPageB._txtMain.style.position = 'absolute';
		
		CPageB._posTxtMain = CHelp.getPosition(false, 50, false, false, (CPageB._I_DEF.lslide.width + 10) );
		
		CPageB._txtMain.style.position = 'absolute';
		//CPageB._txtMain.style.overflow = 'hidden';
		CPageB._txtMain.style.left = CPageB._posTxtMain.l + 'px';
		CPageB._txtMain.style.top = CPageB._posTxtMain.t + 'px';
		CPageB._txtMain.style.width = CPageB._posTxtMain.w + 'px';
		CPageB._txtMain.style.height = CPageB._posTxtMain.h + 'px';
		
		setTimeout( function() {
			var chead = CUtil.getChildByName(CPageB._txtMain, 'ctitle','DIV', true),
				cinfo =  CUtil.getChildByName(CPageB._txtMain, 'cinfo','DIV', true);
			
			cinfo.style.height = (CPageB._txtMain.offsetHeight - chead.offsetHeight - 25) + 'px';
			
			CPageB._I_DEF.lslide.img.style.left = (CPageB._txtMain.offsetLeft + CPageB._txtMain.offsetWidth + 10) + 'px';
			CPageB._I_DEF.lslide.img.style.top = parseInt(CPageB._txtMain.offsetTop + (CPageB._txtMain.offsetHeight / 2) - (CPageB._I_DEF.lslide.height / 2)) + 'px';
		}, 10 );
	},
};

window[ CHelp._M_PAGE_HOOK_ID + CPageB._PAGE_I ] = {
	loadMe: function(cb) {
		CUi.loadImages(CPageB._I_DEF, function(li) {
			cb(CPageB._PAGE_I);
		} );
	},
	
	startMe: function(pcon) {
		CPageB.init(pcon);
	},
	
	onResize: function(pcon) {
		CPageB.draw(pcon, true);
	},
	
	onScroll: function(pcon) {
		CPageB.ani(pcon)
	},
	
	onStart: function() {
		
	},
	
	onStop: function() {
	}
};
