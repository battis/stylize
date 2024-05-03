<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/../vendor/autoload.php';

$_GET['xwrCalname'] = $_GET['X-WR-CALNAME'];

if (
    empty($_GET['url']) ||
    (empty($_GET['style']) &&
        empty($_GET['script']) &&
        empty($_GET['xWrCalname']) &&
        empty($_GET['head'])) ||
    !empty($_GET['edit'])
) {
    $twig = new Environment(new FilesystemLoader(__DIR__ . '/../templates'));
    echo $twig->render(
        'form.html',
        array_merge(
            [
                'action' => $_SERVER['REQUEST_URI'],
            ],
            $_GET
        )
    );
    exit();
}

$html = file_get_contents($_GET['url']);
if (!empty($_GET['style'])) {
    $html = str_replace(
        '</head>',
        "<style>\n{$_GET['style']}\n</style>\n</head>",
        $html
    );
}
if (!empty($_GET['head'])) {
    $html = str_replace('<head>', "<head>\n{$_GET['head']}", $html);
}
if (!empty($_GET['script'])) {
    $html = str_replace(
        '</body>',
        "<script>\n{$_GET['script']}\n</script>\n</body>",
        $html
    );
}
if (!empty($_GET['xWrCalname'])) {
    $html = preg_replace(
        "/(X-WR-CALNAME:).+\r\n/",
        "$1:{$_GET['xWrCalname']}",
        $html,
        1,
        $replaced
    );
    if (!$replaced) {
        $html = str_replace(
            'BEGIN:VCALENDAR',
            "BEGIN:VCALENDAR\r\nX-WR-CALNAME:{$_GET['xWrCalname']}",
            $html
        );
    }
    header('Content-Type: text/calendar');
    header(
        "Content-Disposition: attachment; filename={$_GET['xWrCalname']}.ics"
    );
}
if (!empty($_GET['transp'])) {
    $html = str_replace(
        'END:VEVENT',
        "TRANSP:{$_GET['transp']}\r\nEND:VEVENT",
        $html
    );
}
if (!empty($_GET['search']) && !empty($_GET['replace'])) {
    $html = preg_replace($_GET['search'], $_GET['replace'], $html);
}
echo $html;
