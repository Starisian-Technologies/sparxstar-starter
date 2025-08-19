# sparxstar-starter
A repository for Starisian Technologies SPARXSTAR WordPress plugin development: AI-powered tools and WordPress plugin development for SparxStar, AiWA, and language preservation projects. Includes custom code for linguistics, community contribution, and multilingual marketing automation.

Starisian Starter Template (master blueprint)
==========================================

1) Top-level docs (trust, clarity, compliance)
----------------------------------------------

*   **README.md**: Purpose, scope, supported stacks (WordPress/PHP 8.2+, Node LTS), how to use this template, and when to fork vs. extend.
    
*   **ARCHITECTURE.md**: System boundaries, module layout, data flow, threat model overview, and performance expectations.
    
*   **CONTRIBUTING.md**: Branch strategy, commit style (Conventional Commits), review criteria, required checks.
    
*   **CODE\_OF\_CONDUCT.md**: Behavior standards.
    
*   **SECURITY.md**: How to report vulnerabilities, triage SLA, disclosure policy.
    
*   **LICENSE.md**: Starisian proprietary license (San Diego, CA jurisdiction) with notes for downstream dual-licensing when applicable.
    
*   **ETHICS.md**: Org-wide ethical principles (AI responsibility, community impact).
    
*   **CULTURAL\_GUIDELINES.md**: Inclusive language, localization expectations, content sensitivity guardrails.
    
*   **ROADMAP.md**: Milestones and decision checkpoints.
    
*   **DECISIONS.md**: Architecture Decision Records (ADR log).
    
*   **RELEASE\_NOTES.md / CHANGELOG.md**: Versioned change log and human-readable highlights.
    
*   **SUPPORT.md / MAINTAINERS.md**: Where to get help; owners and escalation.
    

2) Repo hygiene & configuration
-------------------------------

*   **.editorconfig**, **.gitattributes**, **.gitignore** tuned for PHP/JS/WordPress, audio/media, logs, build artifacts.
    
*   **CODEOWNERS**: Paths mapped to teams (security, infra, web, data).
    
*   **example.env** (+ notes on secret management), **/docs/** for expanded guides, **/templates/** for reusable issue/PR snippets and boilerplate copy.
    

3) Linting, formatting, and quality gates
-----------------------------------------

*   **JavaScript/TypeScript**: .eslintrc.\*, .eslintignore with a modern ruleset and plugin notes; **Prettier** via .prettierrc, .prettierignore.
    
*   **Styles**: .stylelintrc.\*, .stylelintignore (CSS/SCSS).
    
*   **PHP**: phpcs.xml (PSR-12 + WordPress rules alignment), phpstan.neon(.dist) with baseline policy.
    
*   **Markdown**: .markdownlint.json for docs quality.
    
*   **Git hooks**: Husky + lint-staged (pre-commit: ESLint/Stylelint/Prettier/PHPCS; pre-push: tests/analyzers).
    
*   **Commit policy**: commitlint.config.\*, czrc for Conventional Commits.
    

4) Testing & analysis
---------------------

*   **PHPUnit**: phpunit.xml.dist, /tests/phpunit/ (bootstrap, fixtures, integration notes).
    
*   **JS tests** (optional): Jest/Vitest config and /tests/js/.
    
*   **Coverage**: Documented thresholds; LCOV/Clover output paths; how CI enforces minimums.
    
*   **Static analysis**: PHPStan levels, ESLint strictness tiers, Stylelint rules; guidance on suppressions and ADRs.
    

5) CI/CD (GitHub-first, GitLab-ready)
-------------------------------------

*   **GitHub Actions** (.github/workflows/):
    
    *   ci.yml: matrix for PHP versions and Node LTS; jobs for PHPCS, PHPStan, PHPUnit, ESLint, Stylelint, Markdownlint, coverage upload.
        
    *   lint-only.yml: fast PR feedback.
        
    *   security.yml: Composer audit, npm audit, secret scanning, Dependabot triage.
        
    *   release.yml: tagged releases, changelog build, artifact packaging.
        
*   **Dependabot**: .github/dependabot.yml for Composer, npm, Actions.
    
*   **GitLab mirrors** (if needed): .gitlab-ci.yml stages (lint, test, build, security, release) and **gl-codequality.yml** integration for MR widgets.
    

6) Supply chain & legal guardrails
----------------------------------

*   **Lockfiles required**: composer.lock, package-lock.json or pnpm-lock.yaml.
    
*   **License scanning**: Policy note (e.g., allowed/denied licenses) and CI step placeholder.
    
*   **Provenance/SLSA note**: Where to add provenance when relevant.
    
*   **Third-party notices**: THIRD\_PARTY\_LICENSES.md guidance.
    

7) Product scaffolding (descriptive only)
-----------------------------------------

*   **/src/**: Core application/plugin/library structure (PSR-4 namespaces, layered architecture).
    
*   **/assets/**: Logos, UI references with usage and licensing notes.
    
*   **/schemas/**: JSON/YAML for shared data contracts (e.g., content metadata).
    
*   **/data/**: Small synthetic sample sets for tests and docs.
    
*   **/examples/**: Narrative examples of intended usage; no production code.
    
*   **/templates/**: Starter blueprints (e.g., WP plugin skeleton, REST endpoint outline, content model outline).
    

8) Operations & environments
----------------------------

*   **LOCAL\_DEVELOPMENT.md**: Required tool versions, make/npx scripts, Docker guidance (if used).
    
*   **RELEASE\_PROCESS.md**: Versioning policy, tag naming, backport rules.
    
*   **INCIDENTS.md**: Light-weight postmortem template and severity guide.
    

SPARXSTAR Profile (uses the Starisian core as-is)
=================================================

Add a brief profile file to the core template so SPARXSTAR projects can simply inherit without edits:

*   **/profiles/sparxstar/PROFILE.md**: What to keep “as-is,” environment expectations (WordPress multisite, PHP-FPM, Cloudflare), performance SLO notes.
    
*   **/profiles/sparxstar/QUALITY-GATES.md**: Min coverage thresholds, PHPCS standards, JS rules strictness.
    
*   **/profiles/sparxstar/CI-NOTES.md**: Which workflows are required, required status checks before merge.
    
*   **/profiles/sparxstar/SECURITY-ADDENDA.md**: Any SPARXSTAR-specific hardening or disclosure variations.
