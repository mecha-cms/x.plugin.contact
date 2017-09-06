<?php

Hook::set('asset.top', function($content) use($site) {
    if ($site->is === 'page') {
        $o = array_replace([
            'id' => Plugin::state(__DIR__, 'anchor')[1]
        ], (array) a(Config::get('page.o.js.CONTACT', [])));
        return $content . '<script>window.CONTACT=' . json_encode($o) . ';</script>';
    }
    return $content;
}, 9.9);

ob_start();
extract(Plugin::state(__DIR__));
require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'form.php';
Lot::set('contact', ob_get_clean(), __DIR__);

function fn_block_contact($content, $lot = []) {
    global $language;
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'contact.min.css');
    return Block::replace('contact', function($a, $b, $c) use($language) {
        $kick = "";
        $topic = '<p class="form-contact-input form-contact-input:title"><label for="form-contact-input:title">' . $language->contact_title . '</label><span>' . Form::text('*title', null, $language->contact_f_title, ['classes' => ['input', 'block'], 'id' => 'form-contact-input:title']) . '</span></p>';
        if (isset($b['kick'])) {
            $kick = Form::hidden('kick', $b['kick']);
        }
        if (isset($b['topic'])) {
            if (is_array($b['topic'])) {
                $topic = '<p class="form-contact-select form-contact-select:title"><label for="form-contact-select:title">' . $language->contact_title . '</label><span>' . Form::select('*title', array_merge(["" => $language->contact_f_title], $b['topic']), null, ['classes' => ['select', 'block'], 'id' => 'form-contact-select:title']) . '</span></p>';
            } else {
                $topic = Form::hidden('title', $b['topic']);
            }
        }
        return __replace__(Lot::get('contact', "", __DIR__), [
            'kick' => $kick,
            'topic' => $topic
        ]);
    }, $content);
}

Block::set('contact', 'fn_block_contact', 10);

require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';