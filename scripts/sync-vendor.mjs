#!/usr/bin/env node
// Sync vendored frontend assets to the versions pinned in assets/package.json.
//
// Rationale: Dependabot only bumps assets/package.json. The committed vendor
// copies under assets/ are a hand-picked subset and are NOT managed by npm.
// This script pulls the pinned package via `npm pack` and overwrites ONLY the
// files that already exist in the vendored target directory (matched by base
// name). Files that exist locally but not upstream (project-custom files such
// as smartmenus/custom.css) are left untouched, and no new files are added.

import { execFileSync } from 'node:child_process';
import { mkdtempSync, rmSync, readdirSync, statSync, readFileSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join, basename, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const scriptDir = dirname(fileURLToPath(import.meta.url));
const assetsDir = join(scriptDir, '..', 'assets');

// npm package name -> vendored directory under assets/
const MAP = {
    '@fortawesome/fontawesome-free': 'FontAwesome',
    'bootstrap': 'bootstrap5',
    'bootstrap4': 'bootstrap4',
    'leaflet': 'leaflet',
    'smartmenus': 'smartmenus',
};

// npm package name -> library label as shown in the "Vendor Lizenzen" list of
// pages/help.changelog.php. Only packages that appear there are listed; their
// displayed version is kept in sync with assets/package.json.
const LICENSE_LABELS = {
    '@fortawesome/fontawesome-free': 'FontAwesome Free',
    'bootstrap': 'Bootstrap 5',
    'bootstrap4': 'Bootstrap 4',
    'leaflet': 'Leaflet',
    'smartmenus': 'Smartmenus',
};

/** Recursively list all files under a directory. */
function listFiles(dir) {
    const out = [];
    for (const entry of readdirSync(dir)) {
        const full = join(dir, entry);
        if (statSync(full).isDirectory()) {
            out.push(...listFiles(full));
        } else {
            out.push(full);
        }
    }
    return out;
}

/** Resolve the real npm spec for a dependency value (handles npm: aliases). */
function resolveSpec(name, value) {
    if (value.startsWith('npm:')) {
        return value.slice(4); // e.g. "npm:bootstrap@4.6.2" -> "bootstrap@4.6.2"
    }
    return `${name}@${value}`;
}

const pkg = JSON.parse(readFileSync(join(assetsDir, 'package.json'), 'utf8'));
const deps = pkg.dependencies || {};

// Optional CLI filter: only sync the named packages (as used by the CI workflow
// to touch only the dependency that Dependabot actually bumped). Without args,
// every mapped package is synced.
const filter = process.argv.slice(2);

// Load the "Vendor Lizenzen" changelog once so we can keep the displayed
// version numbers in sync with the manifest.
const changelogPath = join(assetsDir, '..', 'pages', 'help.changelog.php');
let changelog = readFileSync(changelogPath, 'utf8');
let changelogChanged = false;

let changed = 0;

for (const [name, targetSub] of Object.entries(MAP)) {
    if (filter.length > 0 && !filter.includes(name)) {
        continue;
    }
    const value = deps[name];
    if (!value) {
        continue;
    }
    const spec = resolveSpec(name, value);
    const targetDir = join(assetsDir, targetSub);

    const tmp = mkdtempSync(join(tmpdir(), 'vendor-'));
    try {
        console.log(`Fetching ${spec} ...`);
        const tgz = execFileSync('npm', ['pack', spec, '--silent'], { cwd: tmp })
            .toString().trim().split('\n').pop();
        execFileSync('tar', ['xzf', tgz], { cwd: tmp });
        const upstreamRoot = join(tmp, 'package');
        const upstreamFiles = listFiles(upstreamRoot);

        // Index upstream files by base name; prefer paths containing "dist".
        const byBase = new Map();
        for (const f of upstreamFiles) {
            const b = basename(f);
            const existing = byBase.get(b);
            if (!existing || (f.includes('/dist/') && !existing.includes('/dist/'))) {
                byBase.set(b, f);
            }
        }

        for (const localFile of listFiles(targetDir)) {
            const b = basename(localFile);
            const src = byBase.get(b);
            if (!src) {
                continue; // project-custom file, not shipped by the package
            }
            const before = readFileSync(localFile);
            const after = readFileSync(src);
            if (!before.equals(after)) {
                writeFileSync(localFile, after);
                console.log(`  updated ${targetSub}/${localFile.slice(targetDir.length + 1)}`);
                changed++;
            }
        }
    } finally {
        rmSync(tmp, { recursive: true, force: true });
    }

    // Keep the version shown in the "Vendor Lizenzen" list in sync. The version
    // in the license entry always starts with a digit, so the Ekko-Lightbox
    // entry ("Bootstrap (Ekko) ...") is not matched.
    const label = LICENSE_LABELS[name];
    if (label) {
        const version = spec.split('@').pop();
        const re = new RegExp('(' + label.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ' \\()\\d[^)]*(\\))');
        const updated = changelog.replace(re, `$1${version}$2`);
        if (updated !== changelog) {
            changelog = updated;
            changelogChanged = true;
            console.log(`  license list: ${label} -> ${version}`);
        }
    }
}

if (changelogChanged) {
    writeFileSync(changelogPath, changelog);
}

console.log(changed > 0 ? `Done: ${changed} file(s) updated.` : 'Done: vendor files already in sync.');
