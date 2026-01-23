#!/usr/bin/env node

/**
 * Build script for CSS files
 * Minifies CSS files from src/css/ to assets/css/
 * Uses clean-css for minification
 */

const fs = require('fs').promises;
const path = require('path');
const { exec } = require('child_process');
const { promisify } = require('util');

const execAsync = promisify(exec);

const SRC_DIR = path.join(__dirname, '..', 'src', 'css');
const DEST_DIR = path.join(__dirname, '..', 'assets', 'css');

/**
 * Recursively get all CSS files from a directory
 */
async function getCssFiles(dir) {
    const files = [];
    try {
        const entries = await fs.readdir(dir, { withFileTypes: true });
        for (const entry of entries) {
            const fullPath = path.join(dir, entry.name);
            if (entry.isDirectory()) {
                files.push(...(await getCssFiles(fullPath)));
            } else if (
                entry.isFile() &&
                entry.name.endsWith('.css') &&
                !entry.name.endsWith('.min.css')
            ) {
                files.push(fullPath);
            }
        }
    } catch (error) {
        if (error.code !== 'ENOENT') {
            throw error;
        }
    }
    return files;
}

/**
 * Minify a single CSS file
 */
async function minifyFile(srcFile) {
    const relativePath = path.relative(SRC_DIR, srcFile);
    const destFile = path.join(
        DEST_DIR,
        relativePath.replace(/\.css$/, '.min.css')
    );

    console.log(`Minifying: ${relativePath}`);

    // Ensure destination directory exists
    await fs.mkdir(path.dirname(destFile), { recursive: true });

    // Use cleancss CLI to minify
    const cmd = `npx cleancss -o "${destFile}" "${srcFile}"`;

    try {
        await execAsync(cmd);
        console.log(`  → ${path.relative(process.cwd(), destFile)}`);
    } catch (error) {
        throw new Error(`Failed to minify ${srcFile}: ${error.message}`);
    }
}

/**
 * Main build function
 */
async function build() {
    console.log('Building CSS files...\n');

    const files = await getCssFiles(SRC_DIR);

    if (files.length === 0) {
        console.log('No CSS files found in src/css/');
        return;
    }

    for (const file of files) {
        try {
            await minifyFile(file);
        } catch (error) {
            console.error(`Error minifying ${file}:`, error.message);
            process.exit(1);
        }
    }

    console.log(`\n✓ Successfully minified ${files.length} CSS file(s)`);
}

// Run the build
build().catch((error) => {
    console.error('Build failed:', error);
    process.exit(1);
});
