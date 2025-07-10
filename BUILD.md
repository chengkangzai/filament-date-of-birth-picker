# Build Process

This package uses a custom build pipeline with esbuild and Tailwind CSS to compile JavaScript and CSS assets.

## Prerequisites

- Node.js (v16 or higher)
- npm

## Installation

```bash
npm install
```

## Development

For development with file watching:

```bash
npm run dev
```

This will start both CSS and JavaScript compilation in watch mode.

## Production Build

To build assets for production:

```bash
npm run build
```

This will:
1. Compile and minify CSS with Tailwind CSS
2. Purge unused CSS classes
3. Compile and minify JavaScript with esbuild

## Build Output

The build process generates:
- `resources/dist/filament-date-of-birth-picker.css` - Compiled and purged CSS
- `resources/dist/filament-date-of-birth-picker.js` - Compiled JavaScript

## Build Artifacts

### Option 1: Commit Build Artifacts (Current)
Build artifacts are currently committed to the repository for immediate use. This allows users to install the package without requiring a build step.

### Option 2: Ignore Build Artifacts
To ignore build artifacts and require users to build:

1. Uncomment the `/resources/dist/` line in `.gitignore`
2. Remove existing build artifacts from git:
   ```bash
   git rm -r resources/dist/
   git commit -m "Remove build artifacts from git"
   ```
3. Update installation instructions to include build step

## Package Structure

```
resources/
├── css/
│   └── index.css              # Source CSS with Tailwind directives
├── js/
│   └── index.js              # Source JavaScript Alpine.js component
└── dist/                     # Build output (CSS + JS)
    ├── filament-date-of-birth-picker.css
    └── filament-date-of-birth-picker.js
```

## Build Tools

- **esbuild**: Fast JavaScript bundler and minifier
- **Tailwind CSS**: Utility-first CSS framework
- **PostCSS**: CSS processor
- **@awcodes/filament-plugin-purge**: Filament-specific CSS purging
- **npm-run-all**: Run multiple npm scripts in parallel