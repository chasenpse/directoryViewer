<?php
class group {
    public $name;
    public $val;

    function __construct($c) {
        $this->name = $c;
        $this->val = array();
    }
}

$alphas = range('a','z');
array_unshift($alphas, '#');
$alphaGroups = array();

foreach ($alphas as $t) {
    $temp = new group($t);
    array_push($alphaGroups, $temp);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <title>Projects</title>
    <link rel="stylesheet" href="style.css" type="text/css" media="all" />
</head>
<body>
<div id="mainContainer">
    <div id="items">
        <?php
        $dirs = array_filter(glob('*'), 'is_dir');
        natcasesort($dirs);
        foreach ($dirs as $i => $f) {
            if (ctype_alpha(substr($f,0,1))) {
                array_push($alphaGroups[array_search(strtolower(substr($f,0,1)), $alphas)]->val, $f);
            } else {
                array_push($alphaGroups[0]->val, $f);
            }
        }
        foreach ($alphaGroups as $i => $g) {
            if (sizeof($g->val)) {
                $projectLength = sizeof($g->val);
                echo '<div id="agl-' . $g->name . '" class="alphaGroupLabel">' . $g->name . '<span class="alphaGroupCount">' . $projectLength;
                switch ($projectLength) {
                    case 1:
                        echo ' project</span></div>';
                        break;
                    default:
                        echo ' projects</span></div>';
                        break;
                }
                echo '<div class="alphaGroupContents">';
                foreach ($g->val as $v) {
                    $url = parse_url($_SERVER['REQUEST_URI'])['path'] . $v;
                    echo '<a href="'.$url .'" id="item-'.$i.'" class="item">'.$v.'</a>';
                }
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
<footer>
    <span>Directory Viewer <span class="version">v1.1</span></span>
    <span class="copy">&copy; <?php echo date("Y"); ?> <a href="https://www.scardino.dev">Nick Scardino</a></span>
</footer>
<script>
    const el = document.getElementsByClassName("alphaGroupLabel");
    document.getElementById(el[0].id).classList.add('sticky');
    let currentGroup = 0;
    window.addEventListener('scroll', function() {
        if (window.pageYOffset >= document.getElementById(el[currentGroup + 1].id).offsetTop + el[0].offsetHeight) {
            currentGroup++;
            document.getElementsByClassName("sticky")[0].classList.remove('sticky');
            document.getElementById(el[currentGroup].id).classList.add('sticky');
        } else if (currentGroup != 0 && window.pageYOffset < document.getElementsByClassName("alphaGroupContents")[currentGroup-1].offsetTop + document.getElementsByClassName("alphaGroupContents")[currentGroup-1].offsetHeight) {
            currentGroup--;
            document.getElementsByClassName("sticky")[0].classList.remove('sticky');
            document.getElementById(el[currentGroup].id).classList.add('sticky');
        }
    });
    window.addEventListener('keyup', function(e) {
        if (document.getElementById("agl-" + e.key) && e.key != '#') {
            window.scrollTo({
                top: document.getElementById("agl-" + e.key).offsetTop + document.getElementById("agl-" + e.key).offsetHeight,
                behavior: "smooth"
            });
        } else if ((!isNaN(parseFloat(e.key)) && isFinite(e.key)) || e.key == '#') {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
    })
</script>
</body>
</html>