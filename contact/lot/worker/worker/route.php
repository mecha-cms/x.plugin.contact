<?php

// Send a message!
$state = Plugin::state('contact');
Route::set('%*%/' . $state['path'], function($path) use($date, $language, $site, $state, $url) {
    if (empty($state['email'])) {
        Guardian::abort('Missing email recipient.');
    }
    if (!HTTP::is('post') || !$page = File::exist([
        PAGE . DS . $path . '.page',
        PAGE . DS . $path . '.archive'
    ])) {
        Guardian::kick($path);
    }
    $page = new Page($page);
    $token = HTTP::post('token', false);
    $title = HTTP::post('title', false);
    $author = HTTP::post('author', false);
    $email = HTTP::post('email', false);
    $link = HTTP::post('link', false);
    $type = HTTP::post('type', $state['contact']['type']);
    $content = HTTP::post('content', false);
    if (!$token || !Guardian::check($token)) {
        Message::error('contact_token');
    }
    if (!$author) {
        Message::error('contact_void_field', $language->contact_author);
    } else {
        $author = To::text($author);
        if (Is::this($author)->GT($state['max']['author'])) {
            Message::error('contact_max', $language->contact_author);
        } else if (Is::this($author)->LT($state['min']['author'])) {
            Message::error('contact_min', $language->contact_author);
        }
    }
    if (!$email) {
        Message::error('contact_void_field', $language->contact_email);
    } else if (!Is::EMail($email)) {
        Message::error('contact_pattern_field', $language->contact_email);
    } else if (Is::this($email)->GT($state['max']['email'])) {
        Message::error('contact_max', $language->contact_email);
    } else if (Is::this($email)->LT($state['min']['email'])) {
        Message::error('contact_min', $language->contact_email);
    }
    if ($link) {
        if (!Is::URL($link)) {
            Message::error('contact_pattern_field', $language->contact_link);
        } else if (Is::this($link)->GT($state['max']['link'])) {
            Message::error('contact_max', $language->contact_link);
        } else if (Is::this($link)->LT($state['min']['link'])) {
            Message::error('contact_min', $language->contact_link);
        }
    }
    if (!$content) {
        Message::error('contact_void_field', $language->contact_content);
    } else {
        $content = To::text($content, HTML_WISE . ',img', true);
        if ($state['page']['type'] === 'HTML' && strpos($content, '</p>') === false) {
            // Replace new line with `<br>` tag
            $content = '<p>' . str_replace(["\n\n", "\n"], ['</p><p>', '<br>'], $content) . '</p>';
        } else {
            $fn = 'From::' . $type;
            if (is_callable($fn)) {
                $content = call_user_func($fn, $content);
            }
        }
        if (Is::this($content)->GT($state['max']['content'])) {
            Message::error('contact_max', $language->contact_content);
        } else if (Is::this($content)->LT($state['min']['content'])) {
            Message::error('contact_min', $language->contact_content);
        }
    }
    // Check for duplicate message
    if (Session::get('contact.content') === $content) {
        Message::error('contact_duplicate');
    } else {
        // Block user by IP address
        if (!empty($state['user_ip_x'])) {
            $ip = Get::IP();
            foreach ($state['user_ip_x'] as $v) {
                if ($ip === $v) {
                    Message::error('contact_user_ip_x', $ip);
                    break;
                }
            }
        }
        // Block user by UA keyword(s)
        if (!empty($state['user_agent_x'])) {
            $ua = Get::UA();
            foreach ($state['user_agent_x'] as $v) {
                if (stripos($ua, $v) !== false) {
                    Message::error('contact_user_agent_x', $ua);
                    break;
                }
            }
        }
        // Check for spam keyword(s) in comment
        if (!empty($state['query_x'])) {
            $s = $author . $email . $link . $content;
            foreach ($state['query_x'] as $v) {
                if (stripos($s, $v) !== false) {
                    Message::error('contact_query_x', $v);
                    break;
                }
            }
        }
    }
    $id = time();
    $anchor = $state['anchor'];
    $data = [
        'title' => $title,
        'author' => $author,
        'email' => $email,
        'link' => $link,
        'content' => $content
    ];
    $style = $state['style'];
    Hook::fire('on.contact.set', [null, null, [$data]]);
    if (!Message::$x) {
        ob_start();
        require __DIR__ . DS . '..' . DS . 'content.php';
        Message::send($email, $state['email'], To::text(__replace__($state['topic'], [
            'config' => $site,
            'date' => $date,
            'language' => $language,
            'page' => $page,
            'site' => $site,
            'url' => $url,
            'u_r_l' => $url
        ])), ob_get_clean());
        Message::success('contact_create');
        Session::set('contact', $data);
        Guardian::kick(HTTP::post('kick', Path::D($url->current) . '#' . $anchor[1]));
    } else {
        HTTP::save('post');
    }
    Guardian::kick(Path::D($url->current) . '#' . $anchor[1]);
});