<?php if (empty($_REQUEST['url']) || empty($_REQUEST['style']) || $_REQUEST['edit']) { ?>
<!DOCTYPE html lang="en">
<html>
<head>
    <title>Stylize</title>
</head>
<body>
    <form action="<?= $_SERVER['PHP_SELF'] ?>">
        <div>
            <label>URL</label>
            <input name="url" type="text" value="<?= $_REQUEST['url'] ?>"/>
        </div>
        <div>
            <label>Style block</label>
            <pre>&lt;style&gt;</pre>
            <textarea name="style" cols="80" rows="<?= max(5, substr_count($_REQUEST['style'], "\n") + 3) ?>"><?= $_REQUEST['style'] ?></textarea>
            <pre>&lt;/style&gt;</pre>
        </div>
        <div>
            <label>Script block</label>
            <pre>&lt;script&gt;</pre>
            <textarea name="script" cols="80" rows="<?= max(5, substr_count($_REQUEST['script'], "\n") + 3) ?>" ><?= $_REQUEST['script'] ?></textarea>
            <pre>&lt;/script&gt;</pre>
        </div>
        <div>
            <button type="submit">Stylize</button>
        </div>
    </form>
</body>
</html>
<?php
    exit;
}

$html = file_get_contents($_REQUEST['url']);
echo str_replace('</body>', "<script>\n{$_REQUEST['script']}\n</script>\n</body>", str_replace('</head>', "<style>\n{$_REQUEST['style']}\n</style>\n</head>", $html));
