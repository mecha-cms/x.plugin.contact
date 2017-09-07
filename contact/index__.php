<?php

Hook::set('asset:head', function($content) use($site) {
    if ($site->is === 'page') {
        $o = array_replace([
            'id' => Plugin::state(__DIR__, 'anchor')[1]
        ], (array) a(Config::get('page.o.js.CONTACT', [])));
        return $content . '<script>window.CONTACT=' . json_encode($o) . ';</script>';
    }
    return $content;
}, 9.9);

function fn_block_contact($content, $lot = []) {
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'contact.min.css');
    return Block::replace('contact', function($a, $b, $c) {
        ob_start();
        extract(Lot::get(null, []));
        extract(Plugin::state(__DIR__));
        $lot = $b;
        require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'form.php';
        return ob_get_clean();
    }, $content);
}

Block::set('contact', 'fn_block_contact', 10);

require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';