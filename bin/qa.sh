#!/bin/zsh
# One command for the checks in docs/BUILD-STANDARD.md §4 that a machine can run.
#
#   bin/qa.sh            # against the local Playground on 127.0.0.1:9401
#   CCS_BASE=http://127.0.0.1:9401 CHROME=/path/to/chrome bin/qa.sh
#
# Runs, in order: PHP syntax on every theme file, the homepage copy registry
# test, the design audit at 1440, 900, 768 and 375 on every page type, the internal
# link check, and the old-site redirects. Exits non-zero on the first kind of failure it finds, after
# printing all of them. Used by .github/workflows/qa.yml.
#
# The vacancies page is left out on purpose: it embeds the CV Minder job portal,
# which should only be requested from the registered origin.

SCRIPT_DIR=${0:A:h}
THEME_DIR=${SCRIPT_DIR:h}
AUDIT=$THEME_DIR/.claude/skills/ccs-premium-design/scripts
cd "$THEME_DIR" || exit 1
FAIL=0

PAGES=(/ /about-home-care-maidstone/ /areas/ /areas/maidstone/ /careers/ /contact-us/
	/switch-to-us/ /costs-and-funding/ /cqc-and-our-care/ /getting-started/ /home-care-services-kent/ /news-and-updates/
	/resources/ /resources/care-guides/ /resources/faqs/ /resources/referral-information/
	/services/domiciliary-care/ /services/complex-care/ /who-youll-meet/ /who-youll-meet/victoria-walker/ /privacy-policy/
	"/?s=care" /not-a-page/)

echo "== PHP syntax"
BAD=$(find . -name '*.php' -not -path './reference/*' -not -path './node_modules/*' -not -path './vendor/*' -print0 \
	| xargs -0 -n1 php -l 2>&1 | grep -v '^No syntax errors')
if [ -n "$BAD" ]; then print -r -- "$BAD"; FAIL=1; else echo "ok"; fi

echo "== Homepage copy registry"
php bin/test-home-copy.php || FAIL=1

for W in 1440 900 768 375; do
	echo "== Design audit at ${W}px"
	OUT=$("$AUDIT/run-audit.sh" --width $W $PAGES 2>&1)
	CLEAN=$(print -r -- "$OUT" | grep -c '^  no flags$')
	if [ "$CLEAN" -ne ${#PAGES} ]; then
		# Print each page that is not clean: its heading line and its last line.
		print -r -- "$OUT" | awk '/^\// {page=$0} /^  / {last=$0} /^$/ {if (page != "" && last != "  no flags") print page "\n" last; page=""; last=""} END {if (page != "" && last != "  no flags") print page "\n" last}'
		echo "$CLEAN of ${#PAGES} pages clean"
		FAIL=1
	else
		echo "ok: ${#PAGES} pages, no flags"
	fi
done

echo "== Internal links"
LINKS=$("$AUDIT/link-check.sh" ${PAGES:#/not-a-page/} 2>&1)
print -r -- "$LINKS" | tail -5
print -r -- "$LINKS" | tail -1 | grep -q ', 0 not 200$' || FAIL=1

echo "== Old-site redirects"
# Every old address in inc/old-site-redirects.php must answer 301 to its target, and the target must load.
BASE=${CCS_BASE:-http://127.0.0.1:9401}
RBAD=0; RN=0
php -r 'define("ABSPATH","/"); function apply_filters($t,$v){return $v;} function add_action(){} require "inc/old-site-redirects.php"; foreach (ccs_old_site_redirects() as $from => $to) { echo $from, " ", $to, "\n"; }' | while read -r FROM TO; do
	GOT=$(curl -s -m 30 -o /dev/null -w '%{http_code} %{redirect_url}' "$BASE$FROM")
	END=$(curl -s -m 30 -o /dev/null -w '%{http_code}' "$BASE$TO")
	if [ "$GOT" != "301 $BASE$TO" ] || [ "$END" != "200" ]; then echo "  $FROM: got $GOT, target answers $END (wanted 301 $TO, 200)"; fi
done > /tmp/ccs-redirects.$$ 2>&1
RN=$(php -r 'define("ABSPATH","/"); function apply_filters($t,$v){return $v;} function add_action(){} require "inc/old-site-redirects.php"; echo count(ccs_old_site_redirects());')
if [ -s /tmp/ccs-redirects.$$ ]; then cat /tmp/ccs-redirects.$$; FAIL=1; else echo "ok: $RN redirects"; fi
rm -f /tmp/ccs-redirects.$$

[ $FAIL -eq 0 ] && echo "QA passed" || echo "QA FAILED"
exit $FAIL
