import { readFileSync, mkdirSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { build } from 'esbuild';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const outputDirectory = path.join(root, 'public/dist/assets/bundles');

const cssFiles = [
    'public/dist/assets/extensions/sweetalert2/sweetalert2.min.css',
    'public/dist/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/boxicons/css/boxicons.min.css',
    'public/dist/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css',
    'public/dist/assets/compiled/css/table-datatable-jquery.css',
    'public/dist/assets/compiled/css/app.css',
    'public/dist/assets/compiled/css/app-dark.css',
    'node_modules/select2/dist/css/select2.min.css',
    'public/dist/assets/extensions/summernote/summernote-lite.min.css',
    'public/css/admin-modern.css',
];

const jsFiles = [
    'public/dist/assets/extensions/jquery/jquery.min.js',
    'public/dist/assets/static/js/components/dark.js',
    'public/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js',
    'public/dist/assets/compiled/js/app.js',
    'node_modules/select2/dist/js/select2.min.js',
    'public/dist/assets/extensions/datatables.net/js/jquery.dataTables.min.js',
    'public/dist/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js',
    'public/dist/assets/static/js/pages/datatables.js',
    'public/dist/assets/extensions/sweetalert2/sweetalert2.min.js',
    'public/dist/assets/extensions/summernote/summernote-lite.min.js',
    'public/js/sagita-confirm.js',
];

function resolveFiles(files) {
    return files.map((file) => {
        const absolutePath = path.join(root, file);
        readFileSync(absolutePath);
        return absolutePath;
    });
}

async function buildAdminAssets() {
    mkdirSync(outputDirectory, { recursive: true });

    const resolvedCssFiles = resolveFiles(cssFiles);
    const cssImports = resolvedCssFiles
        .map((file) => `@import "${file.replaceAll('\\', '/')}";`)
        .join('\n');

    await build({
        stdin: {
            contents: cssImports,
            resolveDir: root,
            sourcefile: 'admin-assets.css',
            loader: 'css',
        },
        bundle: true,
        minify: true,
        outfile: path.join(outputDirectory, 'admin.css'),
        assetNames: 'fonts/[name]-[hash]',
        loader: {
            '.eot': 'file',
            '.svg': 'file',
            '.ttf': 'file',
            '.woff': 'file',
            '.woff2': 'file',
        },
        plugins: [{
            name: 'remove-remote-font-import',
            setup(buildContext) {
                buildContext.onLoad({
                    filter: /dist[\\/]assets[\\/]compiled[\\/]css[\\/]app\.css$/,
                }, async ({ path: file }) => ({
                    contents: readFileSync(file, 'utf8').replace(
                        /^\s*@import\s+url\(["']?https:\/\/fonts\.googleapis\.com\/[^"')]*["']?\)\s*;?\s*$/gm,
                        '',
                    ),
                    loader: 'css',
                    resolveDir: path.dirname(file),
                }));
            },
        }],
    });

    const js = resolveFiles(jsFiles)
        .map((file) => readFileSync(file, 'utf8'))
        .join('\n;\n');

    await build({
        stdin: {
            contents: js,
            resolveDir: root,
            sourcefile: 'admin-assets.js',
            loader: 'js',
        },
        minifySyntax: true,
        minifyWhitespace: true,
        minifyIdentifiers: false,
        target: 'es2018',
        outfile: path.join(outputDirectory, 'admin.js'),
    });
}

buildAdminAssets().catch((error) => {
    console.error(error);
    process.exitCode = 1;
});
