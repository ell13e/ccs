#!/bin/zsh
# Every internal link on the given CCS pages, checked for a 200.
#
#   link-check.sh / /contact-us/ /areas/maidstone/
#
# Prints only failures (status and URL), then a count. Redirects count as
# failures on purpose: a link that 301s to the homepage is a broken link that
# happens to load something.

BASE=${CCS_BASE:-http://127.0.0.1:9401}
(( $# )) || { echo "usage: link-check.sh /path/ ..." >&2; exit 1; }
LINKS=$(for p in "$@"; do
	curl -s "$BASE$p?lk=$RANDOM" | grep -o "href=\"${BASE}[^\"#]*" | sed 's/href="//'
done | sort -u | grep -v -e '/wp-content/' -e '/wp-json' -e '/feed/' -e 'xmlrpc' -e '/wp-includes/' -e '\.css' -e '\.js')
BAD=0
while read -r u; do
	[ -z "$u" ] && continue
	c=$(curl -s -o /dev/null -w '%{http_code}' "$u")
	if [ "$c" != "200" ]; then echo "$c $u"; BAD=$((BAD + 1)); fi
done <<< "$LINKS"
echo "checked $(print -r -- "$LINKS" | grep -c .) links, $BAD not 200"
