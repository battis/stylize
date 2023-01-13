<?php

if (empty($_REQUEST['url']) || empty($_REQUEST['style']) || $_REQUEST['edit']) {
    echo <<< EOT
<!DOCTYPE html lang="en">
<html>
<head>
    <title>Stylize</title>
</head>
<body>
    <form action="{$_SERVER['PHP_SELF']}">
        <div>
            <label>URL</label>
            <input name="url" type="text" value="{$_REQUEST['url']}"/>
        </div>
        <div>
            <label>Style block</label>
            <pre>&lt;style&gt;<pre>
            <textarea name="style">{$_REQUEST['style']}</textarea>
            <pre>&lt;/style&gt;</pre>
        </div>
        <div>
            <button type="submit">Stylize</button>
    </form>
</body>
</html>
EOT;
    exit;
}

$html = file_get_contents($_REQUEST['url']);
echo str_replace('</head>', "<style>\n{$_REQUEST['style']}\n</style>\n</head>", $html);
