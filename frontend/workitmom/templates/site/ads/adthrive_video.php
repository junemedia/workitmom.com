<!-- needed by video player -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<!-- don't stomp mooTools -->
<script>$.noConflict();</script>


<!-- CM Collapsible and Mobile Video Players -->
<div id="adthrive-desktop-video" style="margin-bottom:1em;">
  <script src="https://content.jwplatform.com/libraries/gka7Nb51.js"></script>
  <div class="player-container" style="margin: 0px auto; width: 90%;">
    <div class="player-position">
      <span class="copy"><img src="" style="display:inline-block; vertical-align:middle"><h3>OUR OTHER VIDEOS</h3></span>
      <div id="player" data-plid="vByieQJL" data-sticky data-shuffle></div>
    </div>
  </div>

  <script src="https://ads.adthrive.com/video/5bae49bc1b5c414750fdf288.js" async="true"></script>

  <style>
    .player-position { padding: 8px !important; }
  </style>

</div>

<div id="adthrive-mobile-video" style="display:flex; justify-content:center; margin-bottom:1em;">
  <center>
    <h3>OUR OTHER VIDEOS</h3>
    <script defer type="text/javascript" language="javascript" src="https://live.sekindo.com/live/liveView.php?s=87493&cbuster=%%CACHEBUSTER%%&pubUrl=%%REFERRER_URL_ESC_ESC%%&x=340&y=260&vp_contentFeedId=vByieQJL&subId=5bae49bc1b5c414750fdf288"></script>
  </center>
</div>

<script>
  if (!/Mobi|Android/i.test(navigator.userAgent)) {
    let player = document.getElementById("adthrive-mobile-video");
    player.remove();
  } else if (/Mobi|Android/i.test(navigator.userAgent)) {
    let player = document.getElementById("adthrive-desktop-video");
    player.remove();
  }
</script>
<!-- End CM Collapsible and Mobile Video Players -->
