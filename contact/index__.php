<?php

Hook::set('shield.enter', function() use($site) {
    if ($site->is('page')) {
        $s = __DIR__ . DS . 'lot' . DS . 'asset' . DS;
        Asset::set($s . 'css' . DS . 'contact.min.css', 10);
        Asset::set($s . 'js' . DS . 'contact.min.js', 10, [
            'src' => function($src) {
                return $src . '#' . Plugin::state('contact', 'anchor')[1];
            }
        ]);
    }
}, 0);

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

Hook::set('shield.yield', 'fn_block_contact', 1);

require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';