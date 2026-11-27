#!/usr/bin/env python3
"""Build a clean theme zip for upload to WordPress, and the CCS SEO plugin's zip.

    python3 bin/build-release.py            # writes ../ccs-theme-<version>.zip
    python3 bin/build-release.py --list     # only print what would be left out

The working folder holds far more than the site uses: about 480MB of images,
of which the pages use about 11MB, plus notes, research and tooling. This
writes a zip with the theme's code and only the photos the code refers to.

What is left out:
- PDFs that no PHP, CSS, JS or JSON file names;
- photos in assets/images/site-photos, site-photos-extra and team that no
  PHP, CSS, JS or JSON file names (a size variant such as name-500w.webp is
  kept with its original);
- videos, .zip files and macOS files anywhere;
- everything that is not part of the running theme: .git, .github, .claude,
  .cursor, bin, docs, reference, design-system, _to_delete, the Markdown
  notes and phpstan.neon.

The folder inside the zip is named CCS, the same as on the site: WordPress
stores the Customizer wording against the theme's folder name, so a
different name would lose it.
"""
import os
import re
import sys
import zipfile

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
FOLDER = 'CCS'
SKIP_DIRS = {'.git', '.github', '.claude', '.cursor', 'bin', 'docs', 'reference', 'design-system', '_to_delete', 'node_modules', '__pycache__', 'Claude outputs'}
SKIP_FILES = {'.DS_Store', 'phpstan.neon', '.gitignore', 'Thumbs.db'}
SKIP_EXT = {'.md', '.zip', '.mp4', '.mov', '.avi', '.webm', '.map', '.log', '.pyc'}
PRUNED = ('assets/images/site-photos/', 'assets/images/site-photos-extra/', 'assets/images/team/')
CODE_EXT = ('.php', '.css', '.js', '.json')


def walk():
    for folder, dirs, files in os.walk(ROOT):
        dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
        for name in files:
            full = os.path.join(folder, name)
            yield os.path.relpath(full, ROOT).replace(os.sep, '/')


def main():
    listing = '--list' in sys.argv
    files = [f for f in walk() if os.path.basename(f) not in SKIP_FILES and os.path.splitext(f)[1].lower() not in SKIP_EXT and not os.path.basename(f).startswith('._')]
    code = ''
    for f in files:
        if f.endswith(CODE_EXT):
            with open(os.path.join(ROOT, f), encoding='utf-8', errors='ignore') as handle:
                code += handle.read()

    keep, left_out = [], []
    for f in files:
        if f.lower().endswith('.pdf'):
            # A PDF ships only if the theme links to it: an unused one (the 2025
            # fee schedule, Nov 2026) would sit at a public address with old prices.
            (keep if os.path.basename(f) in code else left_out).append(f)
            continue
        if f.startswith(PRUNED):
            name = os.path.basename(f)
            original = re.sub(r'-\d+w(?=\.webp$)', '', name)
            stem = os.path.splitext(original)[0]
            used = name in code or original in code or any((stem + ext) in code for ext in ('.jpg', '.jpeg', '.png', '.webp'))
            (keep if used else left_out).append(f)
        else:
            keep.append(f)

    size = lambda paths: sum(os.path.getsize(os.path.join(ROOT, p)) for p in paths) / 1e6
    print('kept: %d files, %.1fMB' % (len(keep), size(keep)))
    print('left out: %d unused photos and PDFs, %.1fMB' % (len(left_out), size(left_out)))
    if listing:
        for f in sorted(left_out):
            print('  ' + f)
        return

    version = '0.0.0'
    match = re.search(r"define\(\s*'THEME_VERSION',\s*'([^']+)'", open(os.path.join(ROOT, 'functions.php'), encoding='utf-8').read())
    if match:
        version = match.group(1)
    out = os.path.join(os.path.dirname(ROOT), 'ccs-theme-%s.zip' % version)
    with zipfile.ZipFile(out, 'w', zipfile.ZIP_DEFLATED) as archive:
        for f in sorted(keep):
            archive.write(os.path.join(ROOT, f), FOLDER + '/' + f)
    print('wrote %s (%.1fMB)' % (out, os.path.getsize(out) / 1e6))
    build_seo_plugin()


def build_seo_plugin():
    """The site's SEO lives in the CCS SEO plugin beside the theme (../ccs-seo); zip it too."""
    plugin = os.path.join(os.path.dirname(ROOT), 'ccs-seo')
    main_file = os.path.join(plugin, 'ccs-seo.php')
    if not os.path.isfile(main_file):
        print('CCS SEO plugin not found at %s: upload it separately' % plugin)
        return
    match = re.search(r"define\(\s*'CCS_SEO_VERSION',\s*'([^']+)'", open(main_file, encoding='utf-8').read())
    version = match.group(1) if match else '0.0.0'
    out = os.path.join(os.path.dirname(ROOT), 'ccs-seo-%s.zip' % version)
    with zipfile.ZipFile(out, 'w', zipfile.ZIP_DEFLATED) as archive:
        for folder, dirs, files in os.walk(plugin):
            dirs[:] = sorted(d for d in dirs if d not in SKIP_DIRS)
            for name in sorted(files):
                if name in SKIP_FILES or name.startswith('._') or os.path.splitext(name)[1].lower() in {'.zip', '.log'}:
                    continue
                path = os.path.join(folder, name)
                archive.write(path, 'ccs-seo/' + os.path.relpath(path, plugin))
    print('wrote %s (%.1fMB)' % (out, os.path.getsize(out) / 1e6))


if __name__ == '__main__':
    main()
