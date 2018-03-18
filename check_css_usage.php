#!/usr/bin/php
<?php
/**
 * Check that the CSS ID and class names in a file are used in a directory.
 */
if ($argc != 3) {
    die("Usage: $argv[0] CSS_FILE HTML_DIR\n");
}
list(, $cssFile, $htmlDir) = $argv;
$cssLines = file($cssFile);

$bootstrapClasses = [
    'active',
    'affix',
    'alert',
    'alert-danger',
    'alert-dismissible',
    'alert-info',
    'alert-link',
    'alert-success',
    'alert-warning',
    'badge',
    'bg-danger',
    'bg-info',
    'bg-primary',
    'bg-success',
    'bg-warning',
    'breadcrumb',
    'btn',
    'btn-block',
    'btn-danger',
    'btn-default',
    'btn-group',
    'btn-group-justified',
    'btn-group-lg',
    'btn-group-sm',
    'btn-group-vertical',
    'btn-group-xs',
    'btn-info',
    'btn-lg',
    'btn-link',
    'btn-primary',
    'btn-sm',
    'btn-success',
    'btn-warning',
    'btn-xs',
    'caption',
    'caret',
    'carousel',
    'carousel-caption',
    'carousel-control',
    'carousel-indicators',
    'carousel-inner',
    'center-block',
    'checkbox',
    'checkbox-inline',
    'clearfix',
    'close',
    'col-\w+-\w+',
    'collapse',
    'col-\w+-offset-\w+',
    'col-\w+-pull-\w+',
    'col-\w+-push-\w+',
    'container',
    'container-fluid',
    'control-label',
    'danger',
    'disabled',
    'divider',
    'dl-horizontal',
    'dropdown',
    'dropdown-header',
    'dropdown-menu',
    'dropdown-menu-right',
    'dropdown-toggle',
    'dropup',
    'embed-responsive',
    'embed-responsive-16by9',
    'embed-responsive-4by3',
    'embed-responsive-item',
    'fade',
    'form-control',
    'form-control-feedback',
    'form-control-static',
    'form-group',
    'form-horizontal',
    'form-inline',
    'glyphicon(-\\w+)*',
    'h1',
    'has-danger',
    'has-feedback',
    'has-success',
    'has-warning',
    'help-block',
    'hidden',
    'hidden-\w+',
    'hidden-print',
    'hide',
    'icon-bar',
    'icon-next',
    'icon-prev',
    'img-circle',
    'img-responsive',
    'img-rounded',
    'img-thumbnail',
    'in',
    'info',
    'initialism',
    'input-group',
    'input-group-addon',
    'input-group-btn',
    'input-group-lg',
    'input-group-sm',
    'input-lg',
    'input-sm',
    'invisible',
    'item',
    'jumbotron',
    'label',
    'label-danger',
    'label-info',
    'label-success',
    'label-warning',
    'lead',
    'left',
    'list-group',
    'list-group-item',
    'list-group-item-danger',
    'list-group-item-heading',
    'list-group-item-info',
    'list-group-item-success',
    'list-group-item-text',
    'list-group-item-warning',
    'list-inline',
    'list-unstyled',
    'mark',
    'media',
    'media-body',
    'media-heading',
    'media-list',
    'media-object',
    'modal',
    'modal-body',
    'modal-content',
    'modal-dialog',
    'modal-footer',
    'modal-header',
    'modal-lg',
    'modal-open',
    'modal-sm',
    'modal-title',
    'nav',
    'navbar',
    'navbar-brand',
    'navbar-btn',
    'navbar-collapse',
    'navbar-default',
    'navbar-fixed-bottom',
    'navbar-fixed-top',
    'navbar-form',
    'navbar-header',
    'navbar-inverse',
    'navbar-left',
    'navbar-link',
    'navbar-nav',
    'navbar-right',
    'navbar-static-top',
    'navbar-text',
    'navbar-toggle',
    'nav-justified',
    'nav-stacked',
    'nav-tabs',
    'next',
    'page-header',
    'pager',
    'pagination',
    'pagination-lg',
    'pagination-sm',
    'panel',
    'panel-body',
    'panel-collapse',
    'panel-danger',
    'panel-footer',
    'panel-group',
    'panel-heading',
    'panel-info',
    'panel-success',
    'panel-title',
    'panel-warning',
    'popover',
    'pre-scrollable',
    'prev',
    'previous',
    'progress',
    'progress-bar',
    'progress-bar-danger',
    'progress-bar-info',
    'progress-bar-striped',
    'progress-bar-success',
    'progress-bar-warning',
    'pull-left',
    'pull-right',
    'right',
    'row',
    'show',
    'small',
    'sr-only',
    'sr-only-focusable',
    'success',
    'tab-content',
    'table',
    'table-bordered',
    'table-condensed',
    'table-hover',
    'table-responsive',
    'tab-pane',
    'text-capitalize',
    'text-center',
    'text-danger',
    'text-hide',
    'text-info',
    'text-justify',
    'text-left',
    'text-lowercase',
    'text-muted',
    'text-nowrap',
    'text-primary',
    'text-right',
    'text-success',
    'text-uppercase',
    'text-warning',
    'thumbnail',
    'tooltip',
    'visible-\w+',
    'visible-print-block',
    'visible-print-inline',
    'visible-print-inline-block',
    'warning',
    'well',
    'well-lg',
    'well-sm'
];

// Load classes and IDs from file.
$defs = [];
foreach ($cssLines as $line) {
    if (preg_match('/^.*{/', $line) && preg_match_all('/([#.])([\w-]+)/', $line, $matches)) {
        foreach ($matches[1] as $i => $type) {
            $name = $matches[2][$i];
            if ($type == '#') {
                $defs['id'][$name] = 0;
            } else {
                $defs['class'][$name] = 0;
            }
        }
    }
}

// Load class and ID usage from directory.
$files = [];
$cmd = sprintf('find %s -type f -print', escapeshellarg($htmlDir));
exec($cmd, $files);
$uses = [];
foreach ($files as $file) {
    if (!preg_match('/\.(php|\w*htm\w*)$/', $file)) {
        continue;
    }
    $codeLines = file($file);
    foreach ($codeLines as $line) {
        if (preg_match_all('/\b(class|id)\s*=\s*([\'"])([\w\s-]*?)\2/i', $line, $matches)) {
            foreach ($matches[1] as $i => $type) {
                $type = strtolower($type);
                preg_match_all('/[\w-]+/', $matches[3][$i], $nameMatches);
                foreach ($nameMatches[0] as $name) {
                    $uses[$type][$name] = isset($uses[$type][$name]) ? $uses[$type][$name] + 1 : 1;
                }
            }
        }
    }
}

// Compare lists.
$unused = [];
$undefined = [];
foreach ($defs as $type => $names) {
    foreach (array_keys($names) as $name) {
        if (!isset($uses[$type][$name])) {
            $unused[$type][$name] = true;
        }
    }
}
foreach ($uses as $type => $counts) {
    foreach ($counts as $name => $count) {
        if (!isset($defs[$type][$name])) {
            $undefined[$type][$name] = $count;
        }
    }
}
if ($unused) {
    echo "Defined but not used:\n";
    foreach ($unused as $type => $names) {
        foreach (array_keys($names) as $name) {
            echo $type == 'id' ? '#' : '.';
            echo $name . "\n";
        }
    }
    echo "\n";
}
if ($undefined) {
    $regexp = '/^(' . implode('|', $bootstrapClasses) . ')$/';
    echo "Used but not defined:\n";
    foreach ($undefined as $type => $counts) {
        foreach ($counts as $name => $count) {
            if (preg_match($regexp, $name)) {
                continue;
            }
            echo $type == 'id' ? '#' : '.';
            echo $name . ' (' . $count . ")\n";
        }
    }
}
