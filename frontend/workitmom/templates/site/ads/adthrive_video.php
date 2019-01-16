<!-- needed by video player -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<!-- don't stomp mooTools -->
<script>$.noConflict();</script>
<!-- our tweaks to their styles -->
<style>
  #adthrive-desktop-video, #adthrive-mobile-video { margin-bottom: 1em; }
  #adthrive-desktop-video .player-position { padding: 8px !important; }
</style>


<!-- CM Collapsible and Mobile Video Players -->
<div id="adthrive-desktop-video">
  <script defer src="https://content.jwplatform.com/libraries/t7yZDLId.js"></script>
  <div class="player-container" style="margin: 0px auto;">
    <div class="player-position">
      <span class="copy"><h3>Our Other Videos</h3></span>
      <div id="player" data-plid="vByieQJL" data-sticky data-shuffle></div>
    </div>
  </div>
  <script defer src="https://ads.adthrive.com/video/5bae49bc1b5c414750fdf288.js"></script>
</div>
<div id="adthrive-mobile-video" style="display:flex; justify-content:center;">
  <center>
    <div>
      <h3>Our Other Videos</h3>
      <script defer type="text/javascript" language="javascript" src="https://live.sekindo.com/live/liveView.php?s=87493&cbuster=%%CACHEBUSTER%%&pubUrl=%%REFERRER_URL_ESC_ESC%%&x=340&y=260&vp_contentFeedId=FG6Lhe9F&subId=5bae49bc1b5c414750fdf288"></script>
    </div>
  </center>
</div>
<script>
  if (!/Mobi|Android/i.test(navigator.userAgent)) {
    let player = document.getElementById('adthrive-mobile-video');
    player.remove();
  } else if (/Mobi|Android/i.test(navigator.userAgent)) {
    let player = document.getElementById('adthrive-desktop-video');
    player.remove();
  }
</script>

<!-- End CM Collapsible and Mobile Video Players -->
