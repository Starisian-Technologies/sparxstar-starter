# Tooling Configuration Guide

This guide explains all the development tools configured for this project and how to customize them.

## Table of Contents

- [PHP Tools](#php-tools)
- [JavaScript Tools](#javascript-tools)
- [CSS Tools](#css-tools)
- [Testing Tools](#testing-tools)
- [Build Tools](#build-tools)
- [Git Hooks](#git-hooks)

## PHP Tools

### PHPCS (PHP_CodeSniffer)

**Purpose**: Enforces WordPress and PSR coding standards.

**Configuration**: `phpcs.xml.dist`

**Key Settings**:

- Standards: WordPress-Extra, WordPress-Docs
- Minimum WordPress version: 6.4
- Scanned paths: Root PHP files and `src/` directory only
- Excluded: `vendor/`, `node_modules/`, `tests/`, `assets/`, minified files

**Usage**:

```bash
# Check code
composer run lint:php

# Auto-fix issues
composer run fix:php

# Check specific file
./vendor/bin/phpcs src/core/SparxstarGluonCore.php
```

**Customization**:

To change text domain:

```xml
<property name="text_domain" type="array">
    <element value="your-text-domain"/>
</property>
```

To change prefix:

```xml
<property name="prefixes" type="array">
    <element value="your_prefix"/>
</property>
```

### PHPStan (Static Analysis)

**Purpose**: Finds bugs and type errors without running code.

**Configuration**: `phpstan.neon.dist`

**Key Settings**:

- Level: 5 (moderate strictness)
- PHP Version: 8.2
- Scanned paths: `src/` only
- Baseline: `phpstan-baseline.neon` (existing errors)

**Usage**:

```bash
# Run analysis
composer run analyze:php

# Generate new baseline (to accept current errors)
./vendor/bin/phpstan analyse --generate-baseline

# Check specific file
./vendor/bin/phpstan analyse src/core/SparxstarGluonCore.php
```

**Customization**:

Increase strictness:

```yaml
parameters:
    level: 8 # Max level
```

Add custom ignores:

```yaml
ignoreErrors:
    - '#Your custom error pattern#'
```

### Rector (Refactoring)

**Purpose**: Automatically modernizes PHP code and applies best practices.

**Configuration**: `rector.php`

**Key Settings**:

- PHP version: 8.2
- Scanned: `src/` only
- Sets enabled: dead code removal, code quality, type declarations

**Usage**:

```bash
# Preview changes
composer run refactor:php

# Apply changes
composer run refactor:php:fix

# Process specific file
./vendor/bin/rector process src/core/SparxstarGluonCore.php
```

**Customization**:

Skip specific rules:

```php
->withSkip([
    YourRuleClass::class,
])
```

Add custom paths:

```php
->withPaths([
    __DIR__ . '/src',
    __DIR__ . '/includes',
])
```

### PHPUnit (Unit Testing)

**Purpose**: Unit testing framework for PHP.

**Configuration**: `phpunit.xml.dist`

**Usage**:

```bash
# Run all tests
composer run test:php

# Run specific test
./vendor/bin/phpunit tests/phpunit/ExampleTest.php

# With coverage (requires Xdebug)
./vendor/bin/phpunit --coverage-html coverage/
```

**Customization**:

Add test suites:

```xml
<testsuite name="Integration Tests">
    <directory>tests/integration</directory>
</testsuite>
```

## JavaScript Tools

### ESLint

**Purpose**: Lints JavaScript code for errors and style issues.

**Configuration**: `eslint.config.js`

**Key Settings**:

- ECMAScript version: 2021 (ES12)
- Environment: Browser + Node
- Extends: @eslint/js recommended, Prettier
- Ignores: node_modules, vendor, assets, tests

**Usage**:

```bash
# Lint JS files
npm run lint:js

# Auto-fix
npx eslint src/js --fix

# Check specific file
npx eslint src/js/admin.js
```

**Customization**:

Add custom rules:

```javascript
rules: {
    'no-console': 'warn',
    'prefer-const': 'error',
}
```

Add TypeScript support:

```javascript
// Install: npm install --save-dev @typescript-eslint/parser @typescript-eslint/eslint-plugin
parser: '@typescript-eslint/parser',
plugins: ['@typescript-eslint'],
```

### Jest (Testing)

**Purpose**: JavaScript unit testing framework.

**Configuration**: `jest.config.js`

**Usage**:

```bash
# Run tests
npm test

# Watch mode
npm test -- --watch

# Coverage
npm test -- --coverage

# Specific file
npm test -- src/js/__tests__/example.test.js
```

**Customization**:

Configure in `jest.config.js`:

```javascript
module.exports = {
    testEnvironment: 'jsdom', // For DOM testing
    coverageThreshold: {
        global: {
            statements: 80,
            branches: 80,
            functions: 80,
            lines: 80,
        },
    },
};
```

## CSS Tools

### Stylelint

**Purpose**: Lints CSS for errors and enforces style conventions.

**Configuration**: `.stylelintrc.json`

**Key Settings**:

- Extends: stylelint-config-standard
- Ignored: `.stylelintignore` (vendor, node_modules, assets, minified files)

**Usage**:

```bash
# Lint CSS
npm run lint:css

# Auto-fix
npx stylelint "src/css/**/*.css" --fix

# Specific file
npx stylelint src/css/admin.css
```

**Customization**:

Add rules in `.stylelintrc.json`:

```json
{
    "extends": "stylelint-config-standard",
    "rules": {
        "color-hex-length": "short",
        "max-nesting-depth": 3
    }
}
```

## Testing Tools

### Playwright (E2E Testing)

**Purpose**: End-to-end browser testing.

**Configuration**: `playwright.config.js`

**Key Settings**:

- Test directory: `tests/e2e`
- Base URL: Environment variable or localhost:8080
- Browsers: Chromium, Firefox, WebKit

**Usage**:

```bash
# Run all tests
npm run test:e2e

# Specific browser
npx playwright test --project=chromium

# Headed mode (see browser)
npx playwright test --headed

# Debug
npx playwright test --debug

# Generate tests interactively
npx playwright codegen http://localhost:8080
```

**Customization**:

```javascript
export default defineConfig({
    retries: 3, // Retry failed tests
    workers: 4, // Parallel workers
    timeout: 30000, // Test timeout
});
```

### Puppeteer

**Purpose**: Headless Chrome automation (alternative to Playwright).

**Usage**:

```javascript
const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto('http://localhost:8080');
    // Your automation code
    await browser.close();
})();
```

## Build Tools

### Terser (JS Minification)

**Purpose**: Minifies JavaScript files.

**Configuration**: `scripts/build-js.js`

**Settings**:

- Source: `src/js/**/*.js`
- Output: `assets/js/**/*.min.js`
- Source maps: Generated
- Compression: 2 passes, keeps console.error

**Customization**:

Edit `scripts/build-js.js`:

```javascript
const result = await minify(code, {
    compress: {
        drop_console: true, // Remove all console
        drop_debugger: true,
        passes: 3,
    },
    mangle: {
        toplevel: true,
    },
});
```

### clean-css (CSS Minification)

**Purpose**: Minifies CSS files.

**Configuration**: `scripts/build-css.js`

**Settings**:

- Source: `src/css/**/*.css`
- Output: `assets/css/**/*.min.css`

**Customization**:

Edit `scripts/build-css.js` to add options:

```javascript
const cmd = `npx cleancss -o "${destFile}" --compatibility ie9 --level 2 "${srcFile}"`;
```

### WP-CLI (i18n)

**Purpose**: Generates translation POT files.

**Usage**:

```bash
# Generate POT file
npm run makepot

# Or directly
wp i18n make-pot . languages/plugin-textdomain.pot --domain=plugin-textdomain
```

## Git Hooks

### Husky

**Purpose**: Runs scripts before Git commits.

**Configuration**: `.husky/` directory

**Setup**:

```bash
npm run prepare
```

### lint-staged

**Purpose**: Runs linters only on staged files.

**Configuration**: `package.json`

**Settings**:

- JS/TS files: ESLint + Prettier
- CSS files: Stylelint + Prettier
- PHP files: PHPCS + PHPStan
- JSON/MD/YML: Prettier

**Customization**:

Edit `package.json`:

```json
"lint-staged": {
    "*.{js,ts}": ["eslint --fix", "prettier --write"],
    "*.php": ["phpcs", "phpstan analyse"],
}
```

## CI/CD Configuration

### GitHub Actions Workflows

Located in `.github/workflows/`:

1. **ci.yml**: Continuous integration (linting, testing, building)
2. **release.yml**: Automated releases from tags
3. **security.yml**: Security scanning
4. **accessibility.yml**: Accessibility testing
5. **code-quality.yml**: HTML/CSS/JS validation

Each workflow is documented in the file with comments.

## Environment Variables

Create `.env` file (based on `example.env`):

```bash
# WordPress Test Environment
WP_BASE_URL=http://localhost:8080
WP_ADMIN_USER=admin
WP_ADMIN_PASSWORD=password

# Testing
PLAYWRIGHT_HEADLESS=true
```

## Troubleshooting

### Tools not working after update

```bash
# Reinstall dependencies
rm -rf node_modules vendor
composer install
npm install
```

### PHPStan baseline outdated

```bash
composer run analyze:php -- --generate-baseline
```

### Playwright browsers missing

```bash
npx playwright install --with-deps
```

## Best Practices

1. **Run linters before committing**: Use git hooks (automatically set up)
2. **Keep baselines minimal**: Fix issues rather than ignoring them
3. **Update dependencies regularly**: Check for security updates
4. **Write tests**: Maintain high code coverage
5. **Document custom rules**: Explain why rules are disabled

## Additional Resources

- [PHPCS Documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)
- [Rector Documentation](https://getrector.com/documentation)
- [ESLint Documentation](https://eslint.org/docs/latest/)
- [Stylelint Documentation](https://stylelint.io/)
- [Playwright Documentation](https://playwright.dev/)
- [Jest Documentation](https://jestjs.io/)
