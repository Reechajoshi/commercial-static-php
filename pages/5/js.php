var _BASE_E = 'pages/5';
	
CPageE = {
	_PAGE_I: 5,
	
	_I_DEF: { 
		dial1: {
			ok: false,
			src: _BASE_E + '/dial1.png',
			img: new Image(),
			width: 400,
			height: 403
		}, dial2: {
			ok: false,
			src: _BASE_E + '/dial2.png',
			img: new Image(),
			width: 400,
			height: 403
		}, dial3: {
			ok: false,
			src: _BASE_E + '/dial3.png',
			img: new Image(),
			width: 400,
			height: 403
		}, dial4: {
			ok: false,
			src: _BASE_E + '/dial4.png',
			img: new Image(),
			width: 400,
			height: 403
		}
	},
	
	 _ANI_I: false,
	 
	 _posTxtMain: { l: 0, t: 0, w: 0, h: 0 }, //text position
	_baseOffTop: 70,
	 
	init: function(pcon)
	{
		CPageE._imgDial = new Array();
		CPageE._posImg = new Array();
		
		for(var i=0; i <= 3; i++)
		{
			if( CUtil.varok( CPageE._I_DEF[ 'dial' + (i+1) ] ) )
				CPageE._imgDial[i] = CPageE._I_DEF[ 'dial' + (i+1) ];
			else if( CUtil.varok( CPageE._I_DEF[ 'dial' + (i+1) ] ) )
				CPageE._imgDial[i] = CPageE._I_DEF[ 'dial' + (i+1) ];
			else { return false; }
			pcon.appendChild(CPageE._imgDial[i].img);
		}
		
		CPageE._txtMain = CUi.createElm( 'DIV', 'txtmain' );
		CPageE._txtMain.innerHTML = '<?php echo(str_replace( "'", '"', str_replace( array( "\r", "\n"), "", file_get_contents("${_M_WBASE}/pages/5/content.html.php") ))); ?>';
		
		pcon.appendChild(CPageE._txtMain);
		
		CPageE.draw(pcon);
		CPageE.ani(pcon);
	},
	
	draw: function(pcon)
	{
		CPageE.drawDial();
		CPageE.positionText();
	},
	
	positionText: function()
	{
		var cw = window.innerWidth, ch = window.innerHeight, cwh = parseInt((cw / 2));
		
		CPageE._txtMain.style.position = 'absolute';
		
		CPageE._posTxtMain = CHelp.getPosition(false, 50 );
		
		CPageE._posTxtMain.w = (cwh * 0.9);
		if(CPageE._posTxtMain.w > 510) { CPageE._posTxtMain.w = 510; }
		CPageE._posTxtMain.l = cwh; //+ ((cwh - CPageE._posTxtMain.w) / 2);
		if(CPageE._posTxtMain.l < parseInt((cw / 2))) { CPageE._posTxtMain.l = parseInt((cw / 2)); }	
		
		
		
		CPageE._txtMain.style.left = CPageE._posTxtMain.l + 'px';
		CPageE._txtMain.style.top = CPageE._posTxtMain.t + 'px';
		CPageE._txtMain.style.width = CPageE._posTxtMain.w + 'px';
		CPageE._txtMain.style.height = CPageE._posTxtMain.h + 'px';
		
		setTimeout( function() {
			var chead = CUtil.getChildByName(CPageE._txtMain, 'ctitle','DIV', true),
				cinfo =  CUtil.getChildByName(CPageE._txtMain, 'cinfo','DIV', true);
			
			cinfo.style.height = (CPageE._txtMain.offsetHeight - chead.offsetHeight - 10) + 'px';
		}, 10 );
	},
	
	ani: function(pcon)
	{
		try
		{
			for(var i=0; i <= 3; i++)
			{
				var pos = CUtil.cloneOB( CPageE._posImg[i] );
				pos.t = CPageE._imgDial[i].offsetTop - pcon.offsetTop;
				CPageE.setPosition(CPageE._imgDial[i], pos);
			}
			
			CPageE.aniReel(pcon, pos.t);
		} catch(e) {}
	},
	
	aniReel: function(pcon, ot)
	{
		var sR = (CPageE._imgDial[0].img.offsetTop);
		if(sR > 0) { sR = 0; } else { sR = Math.abs(sR); }
		if((sR > 360) || (sR < 0) ) {sR = 0; }
		
		CUi.transformMe( CPageE._imgDial[1].img, 'rotate(' + sR + 'deg)' );
		CUi.transformMe( CPageE._imgDial[2].img, 'rotate(' + sR + 'deg)' );
		//CPageE._lastSX = sX;
	},

	drawDial: function()
	{
		var cw = window.innerWidth, ch = window.innerHeight;
		
		for(var i=0; i <= 3; i++)
		{
			CPageE._posImg[i] = CHelp.getPosition(true, CPageE._baseOffTop, CPageE._imgDial[i].width, CPageE._imgDial[i].height);
			CPageE.setPosition(CPageE._imgDial[i], CPageE._posImg[i], true);
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

window[ CHelp._M_PAGE_HOOK_ID + CPageE._PAGE_I ] = {
	loadMe: function(cb) {
		CUi.loadImages(CPageE._I_DEF, function(li) {
			cb(CPageE._PAGE_I);
		} );
	},
	
	startMe: function(pcon) {
		CPageE.init(pcon);
	},
	
	onResize: function(pcon) {
		CPageE.draw(pcon);
	},
	
	onScroll: function(pcon) {
		CPageE.ani(pcon)
	},
	
	onStart: function() {
		
	},
	
	onStop: function() {
	}
};
