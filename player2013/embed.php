<style>
              .html5-control-right.yt-uix-button.yt-uix-button-player.html5-watch-on-youtube-button {
                display: none;
              }

              .el-embedded .html5-info-bar {
                display: none !important;
              }

              .ytp-button-fullscreen-enter {
                display: block !important;
              }
            </style>
            <script>
              var ytcsi = {
                gt: function(n) {
                  n = (n || '') + 'data_';
                  return ytcsi[n] || (ytcsi[n] = {
                    tick: {},
                    span: {},
                    info: {}
                  });
                },
                tick: function(l, t, n) {
                  ytcsi.gt(n).tick[l] = t || +new Date();
                },
                span: function(l, s, n) {
                  ytcsi.gt(n).span[l] = (typeof s == 'number') ? s : +new Date() - ytcsi.data_.tick[l];
                },
                info: function(k, v, n) {
                  ytcsi.gt(n).info[k] = v;
                }
              };
              (function() {
                var perf = window['performance'] || window['mozPerformance'] || window['msPerformance'] || window['webkitPerformance'];
                ytcsi.tick('_start', perf ? perf['timing']['responseStart'] : null);
              })();
              if (document.webkitVisibilityState == 'prerender') {
                ytcsi.info('prerender', 1);
                document.addEventListener('webkitvisibilitychange', function() {
                  ytcsi.tick('_start');
                }, false);
              }
              try {
                ytcsi.pt_ = (window.chrome && chrome.csi().pageT || window.gtbExternal && gtbExternal.pageT() || window.external && external.pageT);
                if (ytcsi.pt_) {
                  ytcsi.info('pt', Math.floor(ytcsi.pt_));
                }
              } catch (e) {}
            </script>
            <div id="player" class="full-frame"></div>
            <div id="player-unavailable" style="display: none;" class="ytp-error hid">
              <div id="unavailable-submessage" class="ytp-error-content"></div>
            </div>
            <script id="js-2900126986" class="www-embed-player" src="//s.ytimg.com/yts/jsbin/www-embed-player-vflUmkt61.js" data-loaded="true"></script>
            <script>
              yt.setConfig({
                'EVENT_ID': "RUXDUpmwIoiRiQLA54GABQ",
                'VIDEO_ID': "<?php echo htmlspecialchars($_video['rid']); ?>",
                'POST_MESSAGE_ORIGIN': "*",
                'EURL': "http:\/\/www.itv.com\/news\/topic\/gangnam-style\/"
              });
              yt.setConfig({
                'PLAYER_CONFIG': {
                  "sts": 16057,
                  "params": {
                    "allowfullscreen": "true",
                    "allowscriptaccess": "always",
                    "bgcolor": "#000000"
                  },
                  "url_v8": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/cps.swf",
                  "min_version": "8.0.0",
                  "html5": false,
                  "url": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/watch_as3.swf",
                  "args": {
                    "enablejsapi": "0",
                    "iurlmaxres": "\/dynamic\/thumbs\/<?php echo htmlspecialchars($_video['thumbnail']); ?>",
                    "threed_module": "1",
                    "ldpj": "-3",
                    "fexp": "931319,927606,901479,930102,916624,909717,924616,932295,936912,936910,923305,936913,907231,907240,921090",
                    "hl": "en_US",
                    "eurl": "http:\/\/www.itv.com\/news\/topic\/gangnam-style\/",
                    "el": "embedded",
                    "allow_embed": 1,
                    "avg_rating": 4.57515427974,
                    "idpj": "-7",
                    "video_id": "<?php echo htmlspecialchars($_video['rid']); ?>",
                    "view_count": 5462747,
                    "allow_ratings": 1,
                    "cc3_module": "1",
                    "sw": "1.0",
                    "iurlsd": "\/dynamic\/thumbs\/<?php echo htmlspecialchars($_video['thumbnail']); ?>",
                    "cc_module": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/subtitle_module.swf",
                    "iurl": "\/dynamic\/thumbs\/<?php echo htmlspecialchars($_video['thumbnail']); ?>",
                    "cc_font": "Arial Unicode MS, arial, verdana, _sans",
                    "length_seconds": <?php echo $_video['duration']; ?>,
                    "title": "<?php echo htmlspecialchars($_video['title']); ?>",
                    "cr": "US",
                    "advideo": "1",
                    "streaminglib_module": "1",
                    "rel": "1",
                    "is_html5_mobile_device": false
                  },
                  "attrs": {
                    "height": "100%",
                    "id": "video-player",
                    "width": "100%"
                  },
                  "url_v9as2": "http:\/\/s.ytimg.com\/yts\/swfbin\/player-vflqv4MLv\/cps.swf",
                  "assets": {
                    "js": "\/\/s.ytimg.com\/yts\/jsbin\/html5player-vflG49soT.js",
                    "html": "\/2013_html5_player_template",
                    "css": "\/yts\/cssbin\/www-player-vflBjKccE.css"
                  }
                },
                'EMBED_HTML_TEMPLATE': "\u003ciframe width=\"__width__\" height=\"__height__\" src=\"__url__\" frameborder=\"0\" allowfullscreen\u003e\u003c\/iframe\u003e",
                'EMBED_HTML_URL': "\/\/vt.billyisreal.com\/embed\/__videoid__"
              });
              yt.setMsg('FLASH_UPGRADE', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"http:\/\/s.ytimg.com\/yts\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            You need to upgrade your Adobe Flash Player to watch this video. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eDownload it from Adobe.\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");
              yt.setMsg('PLAYER_FALLBACK', "The Adobe Flash Player or an HTML5 supported browser is required for video playback. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eGet the latest Flash Player\u003c\/a\u003e \u003cbr\u003e \u003ca href=\"\/html5\"\u003eLearn more about upgrading to an HTML5 browser\u003c\/a\u003e");
              yt.setMsg('QUICKTIME_FALLBACK', "The Adobe Flash Player or QuickTime is required for video playback. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eGet the latest Flash Player\u003c\/a\u003e \u003cbr\u003e \u003ca href=\"http:\/\/www.apple.com\/quicktime\/download\/\"\u003eGet the latest version of QuickTime\u003c\/a\u003e");
              yt.setMsg('AUTOPLAY_MESSAGE', {
                "other": "Next video in #",
                "case1": "Next video in 1"
              });

              writeEmbed();
            </script>
            <script>
              ytcsi.span('st', 8);
              yt.setConfig({
                'TIMING_ACTION': "",
                'TIMING_INFO': {
                  "e": "931319,927606,901479,930102,916624",
                  "yt_lt": "cold",
                  "ei": "RUXDUpmwIoiRiQLA54GABQ",
                  "yt_li": 0,
                  "yt_spf": 0
                }
              });
            </script>