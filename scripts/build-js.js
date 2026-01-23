#!/usr/bin/env node

/**
 * Build script for JavaScript files
 * Minifies JS files from src/js/ to assets/js/
 * Uses Terser for minification
 */

const fs = require('fs').promises;
const path = require('path');
const { minify } = require('terser');

const SRC_DIR = path.join(__dirname, '..', 'src', 'js');
const DEST_DIR = path.join(__dirname, '..', 'assets', 'js');

/**
 * Recursively get all JS files from a directory
 */
async function getJsFiles(dir) {
    const files = [];
    try {
        const entries = await fs.readdir(dir, { withFileTypes: true });
        for (const entry of entries) {
            const fullPath = path.join(dir, entry.name);
            if (entry.isDirectory()) {
                files.push(...(await getJsFiles(fullPath)));
            } else if (
                entry.isFile() &&
                entry.name.endsWith('.js') &&
                !entry.name.endsWith('.min.js')
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
 * Minify a single JS file
 */
async function minifyFile(srcFile) {
    const code = await fs.readFile(srcFile, 'utf-8');
    const relativePath = path.relative(SRC_DIR, srcFile);
    const destFile = path.join(
        DEST_DIR,
        relativePath.replace(/\.js$/, '.min.js')
    );

    console.log(`Minifying: ${relativePath}`);

    const result = await minify(code, {
        sourceMap: {
            filename: path.basename(destFile),
            url: path.basename(destFile) + '.map',
        },
        compress: {
            drop_console: false,
            passes: 2,
        },
        mangle: true,
    });

    // Ensure destination directory exists
    await fs.mkdir(path.dirname(destFile), { recursive: true });

    // Write minified file
    await fs.writeFile(destFile, result.code);

    // Write source map
    if (result.map) {
        await fs.writeFile(destFile + '.map', result.map);
    }

    console.log(`  → ${path.relative(process.cwd(), destFile)}`);
}

/**
 * Main build function
 */
async function build() {
    console.log('Building JavaScript files...\n');

    const files = await getJsFiles(SRC_DIR);

    if (files.length === 0) {
        console.log('No JavaScript files found in src/js/');
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

    console.log(`\n✓ Successfully minified ${files.length} JavaScript file(s)`);
}

// Run the build
build().catch((error) => {
    console.error('Build failed:', error);
    process.exit(1);
});
