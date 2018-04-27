<?php

Hook::set('asset:head', function($content) use($site) {
    if ($site->is('page')) {
        $o = array_replace([
            'id' => Plugin::state('contact', 'anchor')[1]
        ], (array) a(Config::get('page.o.js.CONTACT', [])));
        return $content . '<script>window.CONTACT=' . json_encode($o) . ';</script>';
    }
    return $content;
}, 9.9);

Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'contact.min.css');

function fn_block_contact($content) {
    return Block::replace('contact', function($a, $b, $c) {
        ob_start();
        extract(Lot::get(null, []));
        extract(Plugin::state('contact'));
        $lot = $b;
        require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'form.php';
        return ob_get_clean();
    }, $content);
}

Hook::set('shield.yield', 'fn_block_contact');

require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';