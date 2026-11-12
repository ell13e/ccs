#!/bin/zsh
# Chrome that can never reach the CV Minder job portal (the Current Vacancies
# page's iframe). The client asked to keep requests to it to a minimum, so
# screenshots and audits of that page run with its hosts mapped to a dead
# address: the frame renders empty and nothing is sent to CV Minder.
#
#   CHROME=.claude/skills/ccs-premium-design/scripts/chrome-noportal.sh \
#     .claude/skills/ccs-premium-design/scripts/mobile-shot.sh /careers/current-vacancies/ out.png
exec "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" \
	--host-resolver-rules="MAP cvminder.com 127.0.0.1:9, MAP *.cvminder.com 127.0.0.1:9" "$@"
