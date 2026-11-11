#!/bin/zsh
# Audit CCS pages with design-audit.js in headless Chrome, at a given width.
#
#   run-audit.sh [--width 375|1440] [--json] /path/ [/another/ ...]
#   run-audit.sh --width 375 / /contact-us/ /areas/maidstone/
#
# How: writes a temporary same-origin harness page into the theme root (served
# by the local Playground at /wp-content/themes/CCS/), which loads each path in
# an iframe of the requested width, runs the audit, and prints JSON into the
# page. Chrome's --dump-dom hands that back; the harness is deleted on exit.
# Works whether or not the browser pane is visible.
#
# Env: CCS_BASE (default http://127.0.0.1:9401), CHROME (default macOS Chrome).

set -e
SCRIPT_DIR=${0:A:h}
THEME_DIR=${SCRIPT_DIR:h:h:h:h}
BASE=${CCS_BASE:-http://127.0.0.1:9401}
CHROME=${CHROME:-"/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"}
WIDTH=1440
JSON=0
PATHS=()
while (( $# )); do
	case "$1" in
		--width) WIDTH="$2"; shift 2 ;;
		--json) JSON=1; shift ;;
		*) PATHS+=("$1"); shift ;;
	esac
done
(( ${#PATHS} )) || { echo "usage: run-audit.sh [--width N] [--json] /path/ ..." >&2; exit 1; }

HARNESS="$THEME_DIR/_ccs-audit.html"
PROFILE=$(mktemp -d)
trap 'rm -f "$HARNESS"; rm -rf "$PROFILE"' EXIT

{
	print -r -- '<!doctype html><meta charset="utf-8"><body><pre id="out">pending</pre><script>'
	cat "$SCRIPT_DIR/design-audit.js"
	print -r -- '</script><script>'
	print -r -- 'var W=+(new URLSearchParams(location.search).get("w")||1440);var P=JSON.parse(decodeURIComponent(location.hash.slice(1)));var out={};'
	# One run per page, whichever comes first: the load event, or 8s. A page with
	# a slow third-party iframe (the CV Minder job portal) may never fire load,
	# which used to stall the whole run.
	print -r -- 'function next(i){if(i>=P.length){document.getElementById("out").textContent=JSON.stringify(out);return;}var f=document.createElement("iframe"),done=false;f.style.cssText="position:absolute;left:0;top:0;width:"+W+"px;height:900px;border:0";document.body.appendChild(f);function run(){if(done)return;done=true;setTimeout(function(){try{out[P[i]]=ccsDesignAudit(f.contentDocument,f.contentWindow);}catch(e){out[P[i]]={error:String(e)};}f.remove();next(i+1);},1800);}f.onload=run;setTimeout(run,8000);var u=P[i];f.src=u+(u.indexOf("?")<0?"?":"&")+"audit="+Date.now();}'
	print -r -- 'next(0);</script>'
} > "$HARNESS"

HASH=$(python3 -c 'import json,sys,urllib.parse;print(urllib.parse.quote(json.dumps(sys.argv[1:])))' "${PATHS[@]}")
BUDGET=$(( ${#PATHS} * 11000 + 5000 ))
DOM=$("$CHROME" --headless=new --disable-gpu --hide-scrollbars --user-data-dir="$PROFILE" \
	--window-size=$(( WIDTH + 60 )),900 --virtual-time-budget=$BUDGET --dump-dom \
	"$BASE/wp-content/themes/CCS/_ccs-audit.html?w=$WIDTH#$HASH" 2>/dev/null)

print -r -- "$DOM" | python3 "$SCRIPT_DIR/format-audit.py" "$JSON" "$WIDTH"
