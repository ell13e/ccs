#!/usr/bin/env python3
"""Write assets/images/media-metadata.csv: one row for each photo the theme uses.

    python3 bin/build-media-csv.py

Columns, the same as the Restwell sheet: filename, alt_text, title, caption,
description, plus used_in (where the theme prints it).

The alt text is taken from the template that prints the photo, so the sheet
starts as a faithful record of what the site says today. A photo printed as
decoration (alt="") has an empty alt_text. Rows already in the sheet keep
their alt_text, caption and description: run this again after adding or
renaming a photo and nothing you have edited is lost.

The theme reads alt_text back for photos printed with ccs_section_photo()
(inc/theme-images.php): a non-empty alt_text here replaces the template's.
"""
import csv
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMAGES = os.path.join(ROOT, 'assets', 'images')
OUT = os.path.join(IMAGES, 'media-metadata.csv')
DIRS = ('site-photos', 'site-photos-extra', 'careers', 'journey', 'stock', 'team', 'share')
SKIP = {'.git', '.github', '.claude', '.cursor', 'bin', 'docs', 'reference', 'design-system', '_to_delete', 'node_modules'}

sources = {}
for folder, dirs, files in os.walk(ROOT):
    dirs[:] = [d for d in dirs if d not in SKIP]
    for name in files:
        if name.endswith(('.php', '.css')):
            path = os.path.join(folder, name)
            with open(path, encoding='utf-8', errors='ignore') as handle:
                sources[os.path.relpath(path, ROOT)] = handle.read().split('\n')

existing = {}
if os.path.exists(OUT):
    with open(OUT, newline='', encoding='utf-8') as handle:
        for row in csv.DictReader(handle):
            existing[row['filename']] = row

ALT = re.compile(r"""(?:'alt'\s*=>\s*|esc_attr__\(\s*|esc_attr_e\(\s*)(?:__\(\s*)?'((?:[^'\\]|\\.)*)'""")
rows = []
for sub in DIRS:
    folder = os.path.join(IMAGES, sub)
    if not os.path.isdir(folder):
        continue
    for name in sorted(os.listdir(folder)):
        if not re.search(r'\.(jpe?g|png|webp)$', name, re.I) or re.search(r'-\d+w\.webp$', name):
            continue
        used_in, alt = [], ''
        for path, lines in sources.items():
            for i, line in enumerate(lines):
                if name not in line:
                    continue
                used_in.append(path)
                # The alt text is on the same line or within the next few.
                for look in lines[i:i + 9]:
                    found = ALT.search(look)
                    if found and len(found.group(1)) > 12:
                        alt = alt or found.group(1).replace("\\'", "'")
                        break
        if not used_in:
            continue
        rel = sub + '/' + name
        stem = os.path.splitext(name)[0]
        title = re.sub(r'\s+', ' ', re.sub(r'[-_]+', ' ', stem)).strip().capitalize()
        old = existing.get(rel, {})
        rows.append({
            'filename': rel,
            'alt_text': old.get('alt_text') or alt,
            'title': old.get('title') or title,
            'caption': old.get('caption', ''),
            'description': old.get('description', ''),
            'used_in': '; '.join(sorted(set(used_in))),
        })

with open(OUT, 'w', newline='', encoding='utf-8') as handle:
    writer = csv.DictWriter(handle, fieldnames=['filename', 'alt_text', 'title', 'caption', 'description', 'used_in'])
    writer.writeheader()
    writer.writerows(rows)
print('%d photos, %d with alt text, %d without (decorative, or alt set elsewhere)' % (len(rows), sum(1 for r in rows if r['alt_text']), sum(1 for r in rows if not r['alt_text'])))
