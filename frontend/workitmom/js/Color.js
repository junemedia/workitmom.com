Array.implement({

	hsvToRgb: function() {
	    var h = this[0];
	    var s = this[1];
	    var v = this[2];
	    var r;
	    var g;
	    var b;
	    if (s == 0) { // achromatic (grey)
	        return [v,v,v];
	    }
	    var htemp;
	    if (h == 360) {
	    	htemp = 0;
	    } else {
	    	htemp = h;
	    }
	    htemp = htemp/60;
	    var i = Math.floor(htemp); // integer <= h
	    var f = htemp - i; // fractional part of h
	    var p = v * (1-s);
	    var q = v * (1-(s*f));
	    var t = v * (1-(s*(1-f)));
	    if (i==0) {r=v;g=t;b=p;}
	    if (i==1) {r=q;g=v;b=p;}
	    if (i==2) {r=p;g=v;b=t;}
	    if (i==3) {r=p;g=q;b=v;}
	    if (i==4) {r=t;g=p;b=v;}
	    if (i==5) {r=v;g=p;b=q;}
	    r = Math.round(r);
	    g = Math.round(g);
	    b = Math.round(b);
	    
	    return [r,g,b];
	},

	rgbToHsv: function() {
	    var r = this[0];
	    var g = this[1];
	    var b = this[2];
	    var h;
	    var s;
	    var v = Math.max(Math.max(r, g), b);
	    var min = Math.min(Math.min(r, g), b);
	    var delta = v - min;
	    if (v == 0) {
	    	s = 0;
		} else {
	        s = delta / v;
	    }
	    if (s == 0) {
	        h = 0; //achromatic.  no hue
	    } else {
	        if (r == v) { // between yellow and magenta [degrees]
	        	h=60*(g-b)/delta;
	        } else if (g == v) { // between cyan and yellow
	            h = 120+60*(b-r)/delta;
	        } else if (b == v) { // between magenta and cyan
	        	h = 240+60*(r-g)/delta;
	        }
	    }
	    if (h < 0) {
	        h+=360;
	    }
	    
	    return [h,s,v];
	}
	
});