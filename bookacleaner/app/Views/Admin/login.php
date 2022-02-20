<div class="container">
    <div class="row">
        
        <div class="col-md-4 col-md-offset-4">
            <h4 style="text-align: left; margin-top: 25%;">Admin Dashboard</h4>
            <hr class="colorgraph" style="margin-top:0;margin-bottom:0;padding:0;"/>
            <div class="login-box">
                <form>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control input-lg" tabindex="1" autocomplete="off" required="">
                                <label class="floatingText">Username</label>
                            </div>
                        </div> 
                    </div>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control input-lg" tabindex="2" autocomplete="off" required="">
                                <label class="floatingText">Password</label>
                            </div>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                Login
                            </button>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <hr class="colorgraph" style="margin:0;padding:0;"/>

            <p style="text-align: center; padding: 15px; font-size: 12px; font-weight: 300;">
                <i class="fa fa-copyright" aria-hidden="true"></i>
                A-Team / Page rendered in {elapsed_time} seconds
            </p>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.floatingText').click(function() {
            if($(this).prev()[0].tagName == "INPUT" || $(this).prev()[0].tagName == "TEXTAREA") {
                $(this).siblings().select();
            } else {
                // can't open <select> :(
            }
            
        });
    });
</script>

<div id="yt-wrap" style="visibility:hidden;">
    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div id="ytplayer"></div>
</div>

<script>
  // (c) https://jsfiddle.net/x45ur3kd/1/
  // 2. This code loads the IFrame Player API code asynchronously.
  var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/player_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // 3. This function creates an <iframe> (and YouTube player)
  //    after the API code downloads.
  var player;
  function onYouTubePlayerAPIReady(){
    player = new YT.Player('ytplayer', {
      width: '100%',
      height: '100%',
      videoId: 'fQwOtZUd9FY',
      events: {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange
      }
    });
  }

  // 4. The API will call this function when the video player is ready.
  function onPlayerReady(event) {
    event.target.playVideo();
    //player.mute(); // comment out if you don't want the auto played video muted
    
    player.unMute();
  }

  // 5. The API calls this function when the player's state changes.
  //    The function indicates that when playing a video (state=1),
  //    the player should play for six seconds and then stop.
  function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.ENDED) {
      player.seekTo(0);
      player.playVideo();
      player.setVolume(30);
    }
  }
  function stopVideo() {
    player.stopVideo();
  }
  
</script>

