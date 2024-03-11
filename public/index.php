<?php if (
  empty($_REQUEST["url"]) ||
  (empty($_REQUEST["style"]) &&
    empty($_REQUEST["script"]) &&
    empty($_REQUEST["X-WR-CALNAME"])) ||
  !empty($_REQUEST["edit"])
) { ?>
<!DOCTYPE html lang="en">
<html>

<head>
    <title>Stylize</title>
</head>

<body>
    <form action="<?= $_SERVER["REQUEST_URI"] ?>">
        <div>
            <label>URL</label>
            <input name="url" type="text" value="<?= $_REQUEST["url"] ?? "" ?>"/>
        </div>
        <div>
            <label>Style block</label>
            <pre>&lt;style&gt;</pre>
            <textarea name=" style" cols="80" rows="<?= max(
    5,
    substr_count($_REQUEST["style"] ?? "", "\n") + 3
) ?>"><?= $_REQUEST["style"] ?? "" ?></textarea>
            <pre>&lt;/style&gt;</pre>
        </div>
        <div>
            <label>Script block</label>
            <pre>&lt;script&gt;</pre>
            <textarea name="script" cols="80" rows="<?= max(
                5,
                substr_count($_REQUEST["script"] ?? "", "\n") + 3
            ) ?>"><?= $_REQUEST["script"] ?? "" ?></textarea>
            <pre>&lt;/script&gt;</pre>
        </div>
        <div>
            <label>Calendar Feed Name</label>
            <div><code>X-WR-CALNAME:</code><input name="X-WR-CALNAME" value="<?= $_REQUEST[
              "X-WR-CALNAME"
            ] ?? "" ?>" /></div>
        </div>
        <div>Clicking <strong>Stylize</strong> below will take you to the styled version of the URL above. If you would like to return to this configuration page, add <code>&amp;edit=true</code> to the end of that URL.</div>
        <div>
            <button type="submit">Stylize</button>
        </div>
    </form>
</body>

</html>
<?php exit();}

$html = file_get_contents($_REQUEST["url"]);
if (!empty($_REQUEST["style"])) {
    $html = str_replace(
        "</head>",
        "<style>\n{$_REQUEST["style"]}\n</style>\n</head>",
        $html
    );
}
if (!empty($_REQUEST["script"])) {
    $html = str_replace(
        "</body>",
        "<script>\n{$_REQUEST["script"]}\n</script>\n</body>",
        $html
    );
}
if (!empty($_REQUEST["X-WR-CALNAME"])) {
    $html = preg_replace(
        "/(X-WR-CALNAME:).+\r\n/",
        "$1:{$_REQUEST["X-WR-CALNAME"]}",
        $html,
        1,
        $replaced
    );
    if (!$replaced) {
        $html = str_replace(
            "BEGIN:VCALENDAR",
            "BEGIN:VCALENDAR\r\nX-WR-CALNAME:{$_REQUEST["X-WR-CALNAME"]}",
            $html
        );
    }
    header("Content-Type: text/calendar");
    header(
        "Content-Disposition: attachment; filename={$_REQUEST["X-WR-CALNAME"]}.ics"
    );
}
echo $html;
