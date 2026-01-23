# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Comprehensive development tooling setup
- Automated build pipeline for JS/CSS minification
- Complete CI/CD workflows (testing, security, accessibility)
- Documentation for build process and tooling

## [1.0.0] - 2025-01-22

### Added

- PHP 8.2+ support with PSR-4 autoloading
- WordPress VIP coding standards compliance
- PHPCS configuration for WordPress standards
- PHPStan static analysis (level 5)
- Rector for automated PHP refactoring
- PHPUnit for unit testing
- ESLint for JavaScript linting
- Stylelint for CSS linting
- Jest for JavaScript unit testing
- Playwright for E2E testing
- Puppeteer for browser automation
- Automated asset building (Terser for JS, clean-css for CSS)
- GitHub Actions workflows:
    - Continuous Integration (linting, testing, building)
    - Release automation with version bumping
    - Security scanning (CodeQL, dependency audits)
    - Accessibility testing
    - Code quality validation
- WP-CLI integration for i18n (makepot)
- .distignore for clean release distributions
- Comprehensive documentation
- Example source files and tests

### Changed

- Configured linting to only scan root and src/ directories
- Excluded third-party and generated files from analysis

### Developer Experience

- Pre-commit hooks with Husky and lint-staged
- Automated code formatting with Prettier
- Source maps for minified assets
- Parallel test execution
- Comprehensive error reporting

## [0.1.0] - 2025-01-01

### Added

- Initial scaffold
- Basic plugin structure
- WordPress plugin boilerplate

---

## Release Notes Format

Each release should include:

###

New features and capabilities

### Changed

Changes to existing functionality

### Deprecated

Features that will be removed in future releases

### Removed

Features that have been removed

### Fixed

Bug fixes

### Security

Security improvements and vulnerability fixes
