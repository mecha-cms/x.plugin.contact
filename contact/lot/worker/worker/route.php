<?php

// Send a message!
$state = Plugin::state('contact');
Route::set('%*%/' . $state['path'], function($path) use($language, $url, $site, $state) {
    if (empty($state['email'])) {
        Guardian::abort('Missing email recipient.');
    }
    $page = PAGE . DS . $path;
    $page = File::exist([
        $page . '.page',
        $page . '.archive'
    ]);
    if (!Request::is('post') || !$page) {
        Guardian::kick($path);
    }
    $page = new Page($page);
    $token = Request::post('token', false);
    $title = Request::post('title', false);
    $author = Request::post('author', false);
    $email = Request::post('email', false);
    $link = Request::post('link', false);
    $type = Request::post('type', $state['page']['type']);
    $content = Request::post('content', false);
    if (!$token || !Guardian::check($token)) {
        Message::error('contact_token');
    }
    if (!$author) {
        Message::error('contact_void_field', $language->contact_author);
    } else {
        $author = To::text($author);
        if (Is::this($author)->gt($state['max']['author'])) {
            Message::error('contact_max', $language->contact_author);
        } else if (Is::this($author)->lt($state['min']['author'])) {
            Message::error('contact_min', $language->contact_author);
        }
    }
    if (!$email) {
        Message::error('contact_void_field', $language->contact_email);
    } else if (!Is::email($email)) {
        Message::error('contact_pattern_field', $language->contact_email);
    } else if (Is::this($email)->gt($state['max']['email'])) {
        Message::error('contact_max', $language->contact_email);
    } else if (Is::this($email)->lt($state['min']['email'])) {
        Message::error('contact_min', $language->contact_email);
    }
    if ($link) {
        if (!Is::url($link)) {
            Message::error('contact_pattern_field', $language->contact_link);
        } else if (Is::this($link)->gt($state['max']['link'])) {
            Message::error('contact_max', $language->contact_link);
        } else if (Is::this($link)->lt($state['min']['link'])) {
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
            $fn = 'From::' . __c2f__($type);
            if (is_callable($fn)) {
                $content = call_user_func($fn, $content);
            }
        }
        if (Is::this($content)->gt($state['max']['content'])) {
            Message::error('contact_max', $language->contact_content);
        } else if (Is::this($content)->lt($state['min']['content'])) {
            Message::error('contact_min', $language->contact_content);
        }
    }
    // Check for duplicate message
    if (Session::get('contact.content') === $content) {
        Message::error('contact_duplicate');
    } else {
        // Block user by IP address
        if (!empty($state['user_ip_x'])) {
            $ip = Get::ip();
            foreach ($state['user_ip_x'] as $v) {
                if ($ip === $v) {
                    Message::error('contact_user_ip_x', $ip);
                    break;
                }
            }
        }
        // Block user by UA keyword(s)
        if (!empty($state['user_agent_x'])) {
            $ua = Get::ua();
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
        'date' => (new Date($id))->F4,
        'title' => $title,
        'author' => $author,
        'email' => $email,
        'link' => $link,
        'content' => $content
    ];
    $style = $state['style'];
    ob_start();
    require __DIR__ . DS . '..' . DS . 'content.php';
    Message::send($email, $state['email'], To::text(__replace__($state['topic'], [
        'page' => $page,
        'site' => $site
    ])), ob_get_clean());
    Hook::fire('on.contact.set', [null, null, [$data]]);
    if (!Message::$x) {
        Message::success('contact_create');
        Session::set('contact', $data);
        Guardian::kick(Request::post('kick', Path::D($url->current) . '#' . $anchor[1]));
    } else {
        Request::save('post');
    }
    Guardian::kick(Path::D($url->current) . '#' . $anchor[1]);
});