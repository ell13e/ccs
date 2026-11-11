#!/bin/zsh
# True-width full-page screenshot of a CCS page (default 375px wide).
#
#   mobile-shot.sh /contact-us/ out.png [height=4000] [width=375]
#
# Headless Chrome on macOS won't lay out narrower than ~500px, so a "375px"
# window screenshot is really ~500px and cropped. This frames the page in a
# same-origin iframe of the exact width instead, then crops to it. The
# temporary harness file is removed on exit.

set -e
SCRIPT_DIR=${0:A:h}
THEME_DIR=${SCRIPT_DIR:h:h:h:h}
BASE=${CCS_BASE:-http://127.0.0.1:9401}
CHROME=${CHROME:-"/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"}
PAGE=${1:?usage: mobile-shot.sh /path/ out.png [height] [width]}
OUT=${2:?usage: mobile-shot.sh /path/ out.png [height] [width]}
H=${3:-4000}
W=${4:-375}

HARNESS="$THEME_DIR/_ccs-shot.html"
PROFILE=$(mktemp -d)
RAW=$(mktemp -t ccsshot).png
trap 'rm -f "$HARNESS" "$RAW"; rm -rf "$PROFILE"' EXIT

cat > "$HARNESS" <<'EOF'
<!doctype html><meta charset="utf-8"><style>html,body{margin:0;background:#888}iframe{display:block;border:0;background:#fff}</style>
<iframe id="f"></iframe>
<script>
var p=new URLSearchParams(location.search),f=document.getElementById('f');
f.style.width=(p.get('w')||'375')+'px';f.style.height=(p.get('h')||'4000')+'px';
f.onload=function(){setTimeout(function(){try{f.contentDocument.getAnimations().forEach(function(a){a.finish();});}catch(e){}},1500);};
f.src=p.get('u');
</script>
EOF

U=$(python3 -c 'import sys,urllib.parse;print(urllib.parse.quote(sys.argv[1],safe=""))' "$PAGE")
"$CHROME" --headless=new --disable-gpu --hide-scrollbars --user-data-dir="$PROFILE" \
	--window-size=$(( W > 500 ? W : 600 )),$H --virtual-time-budget=15000 \
	--screenshot="$RAW" "$BASE/wp-content/themes/CCS/_ccs-shot.html?w=$W&h=$H&u=$U" 2>/dev/null
magick "$RAW" -crop ${W}x${H}+0+0 +repage "$OUT"
echo "$OUT"
