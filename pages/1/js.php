var _BASE_A = 'pages/1';
	
CPageA = {
	_I_DEF: { 
		far: {
			ok: false,
			src: _BASE_A + '/far.jpg',
			img: new Image(),
			width: 450,
			height: 434
		}, shadow: {
			ok: false,
			src: _BASE_A + '/shadow.jpg',
			img: new Image(),
			width: 450,
			height: 85
		} 
	},
	
	_PAGE_I: 1,
	 
	 _ANI_I: false,
	 
	_posFar: { l: 0, t: 0, w: 0, h: 0 },
	_posShadow: { l: 0, t: 0, w: 0, h: 0 },
	_posTxtMain: { l: 0, t: 0, w: 0, h: 0 },
	
	init: function(pcon)
	{
		pcon.appendChild(CPageA._I_DEF.far.img);
		pcon.appendChild(CPageA._I_DEF.shadow.img);
		
		CPageA._txtMain = CUi.createElm( 'DIV', 'txtmain' );
		CPageA._txtMain.innerHTML = '<div class="gc-wrap txt-main"><div class=page_head>FILM&#160;PRODUCTION</div><div class=txt-sblue>With that finishing touch...<br/>Adding value to breed quality films!</div></div>';
		
		pcon.appendChild(CPageA._txtMain);
		
		CPageA.draw(pcon);
		CPageA.ani(true);
	},
	
	draw: function()
	{
		CPageA.drawLogo();
		CPageA.drawShadow();
		CPageA.positionText();
	},
	
	positionText: function()
	{
		var cw = window.innerWidth, cwh = parseInt((cw / 2)),ch = window.innerHeight;
		
		CPageA._txtMain.style.position = 'absolute';
		
		CPageA._posTxtMain.w = (cwh * 0.9);
		if(CPageA._posTxtMain.w > 510) { CPageA._posTxtMain.w = 510; }
		CPageA._posTxtMain.l = cwh; //+ ((cwh - CPageA._posTxtMain.w) / 2);
		if(CPageA._posTxtMain.l < parseInt((cw / 2))) { CPageA._posTxtMain.l = parseInt((cw / 2)); }
		
		CPageA._posTxtMain.t = parseInt(CPageA._posFar.t + (CPageA._posFar.h/4));
		
		CPageA._txtMain.style.position = 'absolute';
		CPageA._txtMain.style.left = CPageA._posTxtMain.l + 'px';
		CPageA._txtMain.style.top = CPageA._posTxtMain.t + 'px';
		CPageA._txtMain.style.width = CPageA._posTxtMain.w + 'px';
	},
		
	drawShadow: function()
	{
		var cw = window.innerWidth, ch = window.innerHeight;

		CPageA._posShadow.l = CPageA._posFar.l;
		CPageA._posShadow.w = CPageA._posFar.w;

		CPageA._posShadow.t = (CPageA._posFar.t + CPageA._posFar.h) + ( (CPageA._I_DEF.shadow.height/2) + 10);
		CPageA._posShadow.h = parseInt((CPageA._I_DEF.shadow.height / CPageA._I_DEF.shadow.width) * CPageA._posShadow.w);
		CPageA.setPositionAll(CPageA._I_DEF.shadow.img, CPageA._posShadow);
	},

	drawLogo: function()
	{
		// FAR IMAGE ////////////// ////////////// ////////////// ////////////// 
		var cw = window.innerWidth, ch = window.innerHeight;
			
		CPageA._posFar.w = parseInt((cw / 2) * 0.65);
		
		if(CPageA._posFar.w > 380) CPageA._posFar.w = 380;
		else if(CPageA._posFar.w < 200) CPageA._posFar.w = 200;
		
		CPageA._posFar.l = parseInt((cw / 4) - (CPageA._posFar.w/2));
		if(CPageA._posFar.l < 50) { CPageA._posFar.l = 50; }

		CPageA._posFar.h = parseInt((CPageA._I_DEF.far.height / CPageA._I_DEF.far.width) * CPageA._posFar.w);
		
		//CPageA._posFar.t = parseInt( (ch - CPageA._posFar.h ) / 2 ) - 100;
		CPageA._posFar.t = parseInt(ch/5);
		if(CPageA._posFar.t < 60) { CPageA._posFar.t = 60; }
		
		CPageA.setPositionAll(CPageA._I_DEF.far.img, CPageA._posFar);
	},
	
	setPositionAll: function(obelm, pos)
	{
		obelm.style.position = 'absolute';
		obelm.style.left = pos.l + 'px';
		obelm.style.top = pos.t + 'px';
		obelm.style.width = pos.w + 'px';
		obelm.style.height = pos.h  + 'px';
	},
	
	ani: function(bStart)
	{
		if( CUi._isAndroid && !CUi._isAndroid_Opera )
			return ; // DO NOT PROCESS THIS ANI FOR ANDROID IF NOT OPERA
		
		if(CPageA._ANI_I) { clearInterval(CPageA._ANI_I); CPageA._ANI_I = false; }
			
		if(bStart)
		{
			//alert(CPageA._posFar.t);
			if( CPageA._posFar.t >= 25 )
			{
				var midH = CPageA._posFar.t, moveY = 3, mAccl = -1, mDelta = (moveY/13), mMovingAbove = false,
					opaVal = 1, opaAccl = -1, opaDelta = 0.03;
				
				CPageA._ANI_I = setInterval( function() {
					
					moveY += ( mAccl * mDelta);
					
					if(CPageA._posFar.t < midH)
					{
						if( moveY < 0 ) { mAccl = 1; opaAccl = -1; } else { opaAccl = 1; }
						if(!mMovingAbove) { mMovingAbove = true; moveY = -3; }
						opaVal += (opaAccl * opaDelta);
						if(opaVal<0) { opaVal=0; } else if(opaVal>1) { opaVal=1; }
					}
					else if(CPageA._posFar.t > midH)
					{
						mAccl = -1;
						if(mMovingAbove) { mMovingAbove = false; moveY = 3; } // bad hack 					
						opaVal = 1;						
					}
					
					CPageA._posFar.t += ( moveY );
					CPageA.setPositionAll( CPageA._I_DEF.far.img, CPageA._posFar );
					
					CPageA._I_DEF.shadow.img.style.opacity = opaVal;
				}, 35 );
			}
		}
	}
};

window[ CHelp._M_PAGE_HOOK_ID + CPageA._PAGE_I ] = {
	loadMe: function(cb) {
		CUi.loadImages(CPageA._I_DEF, function(li) {
			cb(CPageA._PAGE_I);
		} );
	},
	
	startMe: function(pcon, cb) {
		var lI = setInterval( function() {
			if(CPageA._I_DEF.far.ok)
			{
				CPageA.init(pcon);
				clearInterval(lI);
				cb();
			}
		}, 100 );
	},
	
	onResize: function(pcon) {
		CPageA.ani(false);
		CPageA.draw(pcon);
		setTimeout( function() { CPageA.ani(true); }, 10 );
	},
	
	onScroll: function() {},
	
	onStart: function() {
		
	},
	
	onStop: function() {
	}
};
