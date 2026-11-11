"""Turn run-audit.sh's dumped DOM into a readable report (or raw JSON).

Usage (called by run-audit.sh): format-audit.py <json:0|1> <width>  < dumped-dom
"""
import html
import json
import re
import sys

dom = sys.stdin.read()
m = re.search(r'<pre id="out">(.*?)</pre>', dom, re.S)
if not m or m.group(1).strip() == "pending":
    sys.exit("audit did not finish: raise the budget, or check the site is up at CCS_BASE")
data = json.loads(html.unescape(m.group(1)))

if sys.argv[1] == "1":
    print(json.dumps(data, indent=1))
    sys.exit()

width = sys.argv[2]
LISTS = [
    ("skips", "heading skips"),
    ("smallTargets", "small targets"),
    ("contrast", "contrast"),
    ("nestedCards", "box in box"),
    ("centredParagraphs", "centred multi-line"),
    ("dots", "dots"),
    ("emoji", "emoji"),
    ("glows", "glows"),
    ("primaryPerSection", "2+ primaries in one section"),
    ("filledSecondary", "secondary action in a filled button"),
    ("repeatedPrimary", "same primary twice in the first screen"),
    ("staticBoxes", "boxed but nothing clickable"),
    ("distortedImages", "stretched images"),
    ("softImages", "soft images"),
    ("nestedRadius", "inner radius too big"),
    ("smallText", "text under 13px"),
    ("pureBlack", "pure black text"),
    ("tightLeading", "line height under 1.5"),
    ("longUppercase", "long uppercase"),
    ("vagueLabels", "vague labels"),
    ("shortSelects", "dropdown with 4 or fewer options"),
    ("autoplay", "autoplay"),
    ("newTabUnannounced", "new tab not announced"),
    ("missingAlt", "img without alt"),
    ("sameGroundBands", "same-ground bands touching"),
    ("noFocusStyle", "no visible focus"),
]

for path, r in data.items():
    print()
    if "error" in r:
        print("%s @%spx: ERROR %s" % (path, width, r["error"]))
        continue
    flags = []
    if r["overflow"] > 0:
        flags.append("overflow %spx" % r["overflow"])
    if r["h1"] != 1:
        flags.append("h1 x%s" % r["h1"])
    for key, name in LISTS:
        if r.get(key):
            flags.append("%s: %s" % (name, "; ".join(r[key][:5])))
    print("%s @%spx" % (path, width))
    print("  eyebrows %d %s | accents %d %s" % (len(r["eyebrows"]), r["eyebrows"], len(r["accents"]), r["accents"]))
    print("  cards %d (max depth %d) | side bars %s | fonts %s" % (
        r["cards"], r["maxCardDepth"], r["sideBarColours"] or "none", r["fontFamilies"]))
    pads = r.get("sectionPaddings") or {}
    if pads:
        total = sum(pads.values())
        top = max(pads.values())
        # Mechanical spacing: one value on (nearly) every section of a long page.
        uniform = total >= 5 and top / total >= 0.8
        print("  section padding %s%s" % (pads, "  <- uniform: vary it (rule 9)" if uniform else ""))
    if r.get("gradients"):
        print("  gradients: %s" % r["gradients"])
    if r.get("declaredExceptions"):
        print("  declared exceptions: %s" % "; ".join(r["declaredExceptions"]))
    if r.get("retinaSoftImages"):
        print("  sharp at 1x, short of 2x (needs a larger original): %s" % "; ".join(r["retinaSoftImages"][:4]))
    if r.get("under44"):
        print("  under 44px (review): %s" % "; ".join(r["under44"][:8]))
    print("  " + (" | ".join(flags) if flags else "no flags"))
