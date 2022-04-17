<?php

    session_start();

    include 'functions.php';
    include 'database/connect.php';
    include 'langCheck.php';

    protect();
    user_counter();

    $query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
    $query->bind_param("s", $_SESSION['user']);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();

    $availableTags = array();

    $query = $con->prepare("SELECT DISTINCT tags FROM tags_snippets WHERE user_id = ?");
    $query->bind_param("s", $user_id);
    $query->execute();
    $query->bind_result($tags);
    while($query->fetch()){
        $availableTags[] = "'" . $tags . "'";
    }
    $query->close();

    $gNames = array();
    $gIds = array();
    $query = $con->prepare("SELECT id, name FROM groups WHERE user_id = ?");
    $query->bind_param("s", $user_id);
    $query->execute();
    $query->bind_result($i, $n);
    while($query->fetch()){
        array_push($gIds, $i);
        array_push($gNames, $n);
    }
    $query->close();

	if( isset( $_COOKIE['lang'] ) ) {
		$_SESSION['lang'] = $_COOKIE['lang'];
	}

    $isLangDE = ( $_SESSION['lang'] == 'de' ) ? 'active' : '';
	$isLangEN = ( $_SESSION['lang'] == 'de' ) ? '' : 'active';

?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo 'en'; }?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?=$lang['pageTitle']?></title>

	<link rel="icon" href="favicon.ico">
	<link rel="icon" href="favicon.svg" type="image/svg+xml">

    <link rel="stylesheet" href="lib/jquery/jquery-ui.rawsta.min.css">
    <link rel="stylesheet" href="lib/jquery/jquery.tagit.css">
    <link rel="stylesheet" href="lib/line-awesome/line-awesome.min.css">
    <link rel="stylesheet" href="lib/sweetalert2/borderless.css">
    <link rel="stylesheet" href="lib/prism/prism.css">

    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="assets/css/main.css">

	<script src="lib/jquery/jquery-3.6.0.min.js"></script>
	<script src="lib/jquery/jquery.form.min.js"></script>
	<script src="lib/jquery/jquery-ui.min.js"></script>
	<script src="lib/jquery/tag-it.js"></script>
    <script src="lib/sweetalert2/sweetalert2.min.js"></script>
    <script src="lib/prism/prism.js"></script>
	<script src="lib/clipboard.min.js"></script>

    <script src="assets/js/script.js"></script>

    <!-- set as meta and :root / TODO: add light mode - maybe -->
    <!-- <meta name="color-scheme" content="dark light"> -->
    <meta name="color-scheme" content="dark">

    <!-- TODO: add manifest -->
    <meta name="theme-color" content="#830000">

    <!-- TODO: replace TagIt? -->
	<script>
        $(function(){
            $('#myTags').tagit({
                availableTags: [<?php echo implode(", ", $availableTags); ?>],
                placeholderText : "<?=$lang['snippetTags']?>"
            });
        });
    </script>

</head>
<body class="my-little-snippet-storage">

<!-- MAIN CONTENT starts here -->
<div class="container">

    <div id="blur" class="blur full modal-exit"></div>
    <!-- HEADER BAR -->
    <header class="title-area">
        <a href="<?=$pageRoot?>" id="sitePath-holder">
            <h1 class="appTitle"><i class="lar la-file-code"></i> <?=$lang['pageTitle']?></h1>
        </a>

        <div class="header-options">
            <input type="search" class="search-bar" id="search-input" placeholder="<?=$lang['search']?>" aria-label="<?=$lang['search']?>">
            <button type="reset" class="search-button" id="search-button" aria-label="Submit Search"></button>
        </div>

        <nav class="header-actions" aria-label="Mainmenu">
            <span class="user-options">
                <a class="username" href="#"><i class="lar la-user la-lg la-fw"></i> <?=$lang['welcome'] .', '. $_SESSION['user']; ?></a>
                <a class="signout" href="logout.php"><i class="las la-sign-out-alt la-lg la-fw"></i> <?=$lang['signOut']; ?></a>
            </span>
            <a class="menu-icon"><span></span></a>
            <ul class="user-nav">
                <li id="export-label" class="option-labels has-sub "><i class="las la-file-export la-lg la-fw"></i><a href="#"> <?=$lang['exportAll']?></a>
                    <ul class="export-options">
                        <li id="export-all-sublime" title="<?=$lang['exportAllSublime']?>"><a href="export-sublime.php">Sublime text</a></li>
                        <li id="export-all-json" title="<?=$lang['exportAllJson']?>"><a href="export-json.php">JSON</a></li>
                        <li id="export-all-plain" title="<?=$lang['exportPlain']?>"><a href="export-plain.php"><?=$lang['plainText']?></a></li>
                    </ul>
                </li>
                <li id="import-label" class="option-labels"><i class="las la-file-import la-lg la-fw"></i><a href="#"> <?=$lang['import']?></a></li>
                <li class="option-labels" data-modal="settings"><a href="#" data-modal="settings"><i class="las la-cog la-lg la-fw"></i> <?=$lang['settings']?></a></li>
                <li class="option-labels"><a class="signout" href="logout.php"><i class="las la-sign-out-alt la-lg la-fw"></i> <?=$lang['signOut']?></a></li>
            </ul>
        </nav>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" aria-label="SnippetSelection">
        <div class="upperOptions">
            <span id="groupsTrigger" onclick="showGroups();" class="upperOptionsActive"><i class="lar la-file-code la-lg"></i> <?=$lang['groups']; ?></span>
            <span id="tagsTrigger" onclick="showTags();"><i class="las la-tags la-lg"></i> <?=$lang["tags"]; ?></span>
        </div>

        <div class="tag-list" style="display:none;">
            <?php
                $tagsArray = array();
                $query = $con->prepare("SELECT DISTINCT tags FROM tags_snippets WHERE user_id = '$user_id' ORDER BY tags");

                $query->execute();
                $query->bind_result($tag);

                while($query->fetch()) {
                    $tagsArray[] = $tag;
                }
                $query->close();

                $countArray = array();
                $countSnippet = $con->prepare("SELECT COUNT(*) FROM tags_snippets WHERE tags = ? AND user_id = ?");
                foreach($tagsArray as $tag1){
                    $countSnippet->bind_param("ss", $tag1, $user_id);
                    $countSnippet->execute();
                    $countSnippet->bind_result($count);
                    $countSnippet->fetch();
                    $countArray[] = $count;
                }

                $countSnippet->close();
                for($i = 0; $i < sizeof($tagsArray); $i++){ ?>
                    <div class="tag1" onclick="findSnippets('<?php echo $tagsArray[$i]; ?>', 0);">
                        <span># <?php echo $tagsArray[$i]; ?></span><span class="count-tag"><?php echo $countArray[$i]; ?></span>
                    </div>
            <?php } ?>
        </div>

        <div class="snippets" style="display: none;">
        </div>

        <div class="groups">
            <?php
                    $groupsArray = array();
                    $groupIds = array();
                    $query = $con->prepare("SELECT id, name FROM groups WHERE user_id = ?");
                    $query->bind_param("s", $user_id);
                    $query->execute();
                    $query->bind_result($ids, $groupNames);
                    while( $query->fetch() ){
                        array_push( $groupIds, $ids );
                        array_push( $groupsArray, $groupNames );
                    }
                    $query->close();

                    $countGroups = array();
                    $query = $con->prepare("SELECT * FROM snippets WHERE group_id in (SELECT id FROM groups WHERE name = ?)");
                    for( $i = 0; $i < sizeof($groupsArray); $i++ ) {
                        $query->bind_param("s", $groupsArray[$i]);
                        $query->execute();
                        $query->store_result();
                        $n = $query->num_rows;
                        array_push( $countGroups, $n );
                    }
                    $query->close();

                    for( $i = 0; $i < sizeof( $groupsArray); $i++ ) {
                ?>
                <div class="group" onclick="findSnippetsFromGroups('<?php echo $groupIds[$i]; ?>', 0);">
                    <span class="groupName"><i class="lar la-folder-open"></i> <?php echo $groupsArray[$i]; ?></span>
                    <i data-id="<?php echo $groupIds[$i]; ?>" class="lar la-trash-alt trash-over group-delete"></i>
                    <span class="count-tag-group"><?php echo $countGroups[$i]; ?></span>
                </div>
            <?php } ?>
        </div>

        <div class="bottom-options">
            <span data-modal="addsnip" title="<?=$lang['addNewSnippet']; ?>" class="bottom-button bottom-add-snippet"><i class="las la-file la-2x"></i></span>
            <span data-modal="addgroup" title="<?=$lang['addGroup']; ?>" class="bottom-button bottom-add-group"><i class="lar la-folder-open la-2x"></i></span>
        </div>

    </aside>

    <!-- MAIN WINDOW / CODE STUFF && FOOTER-->
    <main class="window">

        <!-- Snippet details -->
        <section class="details-window-top" style="display: none">
        <!-- TODO: position reltive? has to push content down -->
            <h3 class="detail-title"></h3>
            <span id="date-label"></span>
            <span id="details-button" title="<?=$lang['showMoreDetails']; ?>"><i class="chevron down"></i></span>
        </section>
        <section class="details-window-under">
            <h4><?=$lang['description']; ?></h4>
            <p class="detail-desc"></p>
            <span id="detail-tags"></span>
        </section>



        <!-- Code/Snippets -->
        <pre class="pre-code <?php if( $value === 1 ) { echo 'line-numbers'; } else { echo 'no-line-numbers'; }?> language-php" style="font-family: <?php echo $font; ?>; font-size: 0.<?php echo $size; ?>em;">
            <code id="code-block" class="language-php"></code>
        </pre>
        <pre hidden class="raw-code"></pre>

        <!-- FOOTER -->
        <footer class="code-footer">
            <div class="footer-options">
                <span class="copyright"><a href="//www.rawsta.de/" class="link-to-rawsta" >&copy;2022 | RawSnippets&trade;</a></span>
                <!-- SNIPPET OPTIONS lower right on snippet view -->
                <nav class="snippet-options" aria-label="Options">
                    <!-- TODO: change to svg  -->
                    <div class="fancy" tabindex="-1">
                        <div class="balken"></div>
                        <div class="balken"></div>
                        <div class="balken"></div>
                    </div>
                    <ul class="snippet-option-wrap">
                        <li style="--animation-order: 4;" class="option-item" id="share-label" title="<?=$lang['snippetPrivate'] ?>"><span><?=$lang['private']; ?></span> <i class="las la-share-square la-lg la-fw"></i></li>
                        <li style="--animation-order: 3;" class="option-item" id="sublime-label" title="<?=$lang['exportAsSublime'] ?>"><span>Sublime Text</span> <i class="las la-file-code la-lg la-fw"></i></li>
                        <li style="--animation-order: 2;" class="option-item" id="code-label" title="<?=$lang['rawCode']; ?>"><span>&lt;/<?=$lang['code']; ?>&gt;</span> <i class="las la-file-alt la-lg la-fw"></i></li>
                        <li style="--animation-order: 1;" class="option-item" id="copy-label" data-clipboard-target=".raw-code" title="<?=$lang['copyClipboard']; ?>"><span><?=$lang['copy']; ?></span> <i class="las la-copy la-lg la-fw"></i></li>
                    </ul>
                </nav>

            </div>
        </footer>
    </main>
<!-- ---//--- MODALS AND STUFF ---//--- -->

<!-- ADD NEW SNIPPET -->
    <section id="addsnip" class="add-snippet-window">
        <!-- TODO: Strings in lang.php auslagern! -->
        <h3 class="modal-title"><?=$lang['addNewSnippet']?></h3>

        <label for="name">Titel des neuen Snippets</label>
        <input type="text" placeholder="<?=$lang['name']?>" name="name" id="name">

        <!-- TODO: Fix this dropdown -->
        <label data-id="" id="groupSelect"><?=$lang['selectGroup']?></label>
        <ul class="groupDropDown">
            <?php for( $i=0; $i<sizeof( $gNames ); $i++ ) { ?>
                <li id="<?=$gIds[$i]?>"><?=$gNames[$i]?></li>
            <?php } ?>
        </ul>

        <!-- TODO: Links erkennen und verlinken -->
        <label for="description">Kurze Beschreibung des Snippets <span class="small">(Optional)</span></label>
        <textarea placeholder="<?=$lang['description']?>" name="description" id="description" spellcheck="false"></textarea>

        <label for="snippetArea">Snippet einf&uml;gen</label>
        <textarea placeholder="<?=$lang['snippet']?>" name="snippet" id="snippetArea" spellcheck="false"></textarea>

        <label for="myTags">Tags <span class="small">(mit autocomplete)</span></label>
        <input type="text" name="tags" id="myTags">
        <label for="myTags"><span class="small">(erleichtern die Suche und helfen bei der &Uuml;bersicht)</span></label>

        <button class="accept" id="save-snippet"><?=$lang['save']?></button>
        <button class="cancel" id="snippet-cancel"><?=$lang['cancel']?></button>
            <span id="snippet-error"></span>
            <span hidden data-type="save" class="check-label"></span>
            <span hidden class="id-holder"></span>
    </section>

<!-- SUBLIME SNIPPET EXPORT -->
    <form class="sublime-snippet-window" method="post" action="sublime-snippet.php" style="display:none;">
        <input type="text" name="tabTrigger" id="sublime-snippet-input" placeholder="<?=$lang['tabTrigger']; ?>">
        <input type="submit" id="submit-sublime-snippet" value="<?=$lang['download']; ?>">
        <button id="sublime-snippet-cancel"><?=$lang['cancel']; ?></button>
        <p id="sublime-instructions"><span style="font-weight: bold;"><?=$lang['instructions']; ?>: </span><?=$lang['smallInstructions']; ?></p>
        <textarea id="sublime-code" type="text" name="code" hidden></textarea>
        <input id="sublime-title" type="text" name="title" hidden>
    </form>

<!-- SHARE WINDOW -->
    <div class="share-window" style="display:none;">
        <p><?=$lang['wantShareSnippet'] ?> <span id="share-option" style="background-color: #27AE60"><?=$lang['yes']; ?></span></p>
        <label id="share-close">X</label>
        <input type="text" id="share-link">
    </div>

<!-- IMPORT/UPLOAD -->
    <form id="upload-form" method="post" action="import.php" enctype="multipart/form-data" style="display:none;">
        <p id="upload-message"><?=$lang['selectJson']; ?></p>
        <input type="file" name="file">
        <button id="upload-cancel"><?=$lang['cancel']; ?></button>
        <input type="submit" value="<?=$lang['import']; ?>" id="upload-import">
    </form>

<!-- ADD NEW GROUP -->
    <div id="addgroup" class="add-group">
        <form id="addGroupForm" action="add-group.php" method="post">
            <input type="text" name="name" placeholder="<?=$lang['groupName']; ?>">
            <input id="addGroupSubmit" type="submit" value="<?=$lang['save']; ?>">
            <span id="addGroupCancel">X</span>
        </form>
    </div>
    <span id="addGroupError"></span>

<!-- SETTINGS FORM -->
    <article id="settings" class="settings-form">

        <aside class="settings-sidebar">
            <ul>
                <li class="setting-active"><?=$lang['mainSettings']?></li>
                <li><?=$lang['email']?></li>
                <li><?=$lang['password']?></li>
            </ul>
        </aside>

        <?php
            $query = $con->prepare("SELECT line_nums, font, size FROM users WHERE user_id = ?");
            $query->bind_param("s", $user_id);
            $query->execute();
            $query->bind_result($value, $font, $size);
            $query->fetch();
            $query->close();
        ?>

        <section class="main-settings">
            <h3><?=$lang['settings']?></h3>

            <div class="language-wrap">
                <label for="language-wrap"><?=$lang['language']?></label>
                <span class="row-full-flex">
                    <button id="de" class="button <?=$isLangDE?>" title="<?=$lang['langDeLong']?>"><i class="las la-beer la-lg la-fw"></i> <?=$lang['langDeLong']; ?></button>
                    <button id="en" class="button <?=$isLangEN?>" title="<?=$lang['langEnLong']?>"><i class="las la-glass-whiskey la-lg la-fw"></i> <?=$lang['langEnLong']; ?></button>
                </span>
            </div>
            <div class="line-numbers">
                <label for="line-numbers"><?=$lang['lineNumbers']?></label>
                <?php if( $value == 1 ){ ?>
                    <span style="background-color: var(--raw-color_success)" id="line-num-span" data-value="1"><?=$lang['enabled']?></span>
                <?php } else { ?>
                    <span style="background-color: var(--raw-color_error)" id="line-num-span" data-value="0"><?=$lang['disabled']?></span>
                <?php } ?>
            </div>
            <label for="choose-font"><?=$lang['fontFamily']?></label>
            <div class="font-chooser" id="choose-font">
                <span class="current-font" data-font="<?=$font?>"><?=$font?></span>
                <ul style="display: none">
                    <li style="font-family: 'JetBrains Mono'">JetBrains Mono</li>
                    <li style="font-family: 'Courier Sans'">Courier Sans</li>
                    <li style="font-family: 'monospace'">monospace</li>
                </ul>
            </div>
            <label for="font-size"><?=$lang['fontSize']?></label>
            <div class="font-size" name="font-size" data-size="<?=$size?>">
                <?php if( $size === '70' ) { ?>
                    <span class="active-size"><?=$lang['small']?></span>
                    <span><?=$lang['medium']?></span>
                    <span><?=$lang['large']?></span>
                <?php } else if( $size === '80' ) { ?>
                    <span><?=$lang['small']?></span>
                    <span class="active-size"><?=$lang['medium']?></span>
                    <span><?=$lang['large']?></span>
                <?php } else if( $size === '90' ) {?>
                    <span><?=$lang['small']?></span>
                    <span><?=$lang['medium']?></span>
                    <span class="active-size"><?=$lang['large']?></span>
                <?php } ?>
            </div>
        </section>

        <section class="mail-settings" style="display: none;">
            <h3><?=$lang['changeEmail']?></h3>
            <label for="s-new-email"><?=$lang['newEmail']?></label>
            <input type="email" name="new-email" id="s-new-email" placeholder="<?=$lang['newEmail']?>" >
            <label for="s-rep-email"><?=$lang['repeatEmail']?></label>
            <input type="email" name="rep-email" id="s-rep-email" placeholder="<?=$lang['repeatEmail']?>" >
            <span id="s-notification"></span>
        </section>

        <section class="password-settings" style="display: none;">
            <h3><?=$lang['changePassword']?></h3>
            <label for="s-old-pass"><?=$lang['oldPassword']?></label>
            <input type="password" name="old-pass" id="s-old-pass" placeholder="<?=$lang['oldPassword']?>" autocomplete="current-password">
            <label for="s-new-pass"><?=$lang['newPassword']?></label>
            <input type="password" name="new-pass" id="s-new-pass" placeholder="<?=$lang['newPassword']?>" autocomplete="new-password">
            <label for="s-rep-pass"><?=$lang['repeatPassword']?></label>
            <input type="password" name="rep-pass" id="s-rep-pass" placeholder="<?=$lang['repeatPassword']?>" autocomplete="new-password">
            <span id="s-notification-pass"></span>
        </section>

        <div class="main-setting-buttons">
            <button class="accept setting-ok tiny" data-current="main"><?=$lang['apply']; ?></button>
            <button class="cancel setting-close tiny modal-exit"><?=$lang['close']; ?></button>
        </div>

    </article>

</div><!-- /div container -->
<!-- TODO: Is this still needed? only used in sharing? -->
<var id="sitePath-holder" hidden><?=$pageRoot?></var>
</body>
</html>
