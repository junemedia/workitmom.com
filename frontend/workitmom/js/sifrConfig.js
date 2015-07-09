var vag_rounded = {
      src: SITEINSECUREURL+'/'+SITEASSETURL+'/swf/vag_rounded.swf'
};
 
sIFR.activate(vag_rounded);

sIFR.replace(vag_rounded, {
      selector: 'h1',
	  css: [
      '.sIFR-root { font-weight:normal; color:#333; text-transform: lowercase; }',
	  'em {font-style:italic; }',
	  'strong {text-decoration: none; }',
	  'a {text-decoration: none; color:#333;}',
	  'a:hover {text-decoration: underline; color:#333;}'
      ],
      wmode: 'transparent'
});


sIFR.replace(vag_rounded, {
      selector: 'h2',
	  css: [
      '.sIFR-root { font-weight:normal; color:#2798bd; text-transform: lowercase; }',
	  'em {font-style:italic; }',
	  'strong {text-decoration: none; }',
	  'a {text-decoration: none; color:#2798bd;}',
	  'a:hover {text-decoration: underline; color:#2798bd;}'
      ],
      wmode: 'transparent'
});
