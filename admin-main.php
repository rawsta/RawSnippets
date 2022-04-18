<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$query = $con->prepare("SELECT id FROM admin WHERE username = ?");
    $query->bind_param("s", $_SESSION['admin']);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();
?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$lang['pageTitle']; ?> - <?=$lang['adminPanel']; ?></title>

	<link rel="icon" href="/favicon.ico"  sizes="any">
	<link rel="icon" href="/favicon.svg" type="image/svg+xml">

	<meta name="theme-color" content="#830000">

    <link href="lib/jquery/jquery-ui.rawsta.min.css" rel="stylesheet" type="text/css">
    <link href="lib/jquery/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="lib/line-awesome/line-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="lib/prism/prism.css" rel="stylesheet" type="text/css">

    <link href="assets/css/fonts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/admin-main.css" rel="stylesheet" type="text/css">

	<script src="lib/jquery/jquery-3.6.0.min.js" type="text/javascript"></script>
	<script src="lib/jquery/jquery.form.min.js" type="text/javascript"></script>
	<script src="lib/jquery/jquery-ui.min.js" type="text/javascript"></script>
	<script src="lib/jquery/tag-it.js" type="text/javascript"></script>
    <script src="lib/prism/prism.js" type="text/javascript"></script>
	<script src="lib/clipboard.min.js" type="text/javascript"></script>

    <script src="assets/js/admin-script.js" type="text/javascript"></script>

    <script>
        $(function(){
            $('#myTags').tagit();
        });
    </script>
</head>
<body>
	<!-- TODO: Fix this horrible structure! -->
<label hidden id="siteHolder"><?=$pageRoot?></label>

<div class="admin-wrap">

	<aside class="admin-sidebar">
		<h2 id="admin-title"><?=$lang['adminPanel']?></h2>
		<ul class="admin-menu">
			<li class="active" id="dashboard-button"><?=$lang['dashboard']?></li>
			<li id="snippets-button"><?=$lang['snippets']?></li>
			<li id="users-button"><?=$lang['usersList']?></li>
			<li id="banned-users-button"><?=$lang['bannedUsers']?></li>
			<li id="settings-button"><?=$lang['settings']?></li>
			<li id="admin-logout"><?=$lang['logout']?></li>
		</ul>
	</aside>

	<main class="admin-content">

		<div id="admin-blur"></div>

		<div class="edit-snippet-window">
			<input type="text" placeholder="<?=$lang['name'] ?>" name="name" id="name"><br>
			<textarea placeholder="<?=$lang['description'] ?>" name="description" id="description"></textarea><br>
			<textarea placeholder="<?=$lang['snippet'] ?>" name="snippet" id="snippetArea"></textarea>
			<input type="text" name="tags" id="myTags">
			<label id="save-snippet"><?=$lang['save']; ?></label>
			<label id="snippet-cancel"><?=$lang['cancel']; ?></label><label class="tags-label"><?=$lang['snippetTags']; ?></label>
			<label id="snippet-error"></label>
			<label hidden data-type="save" class="check-label"></label>
			<label hidden class="id-holder"></label>
		</div>

		<?php
			$query = $con->prepare("SELECT count(*) AS count FROM users");
			$query->bind_result($regUsers);
			$query->execute();
			$query->fetch();
			$query->close();

			$query = $con->prepare("SELECT count(*) AS count FROM users WHERE active = 0");
			$query->bind_result($inactiveUsers);
			$query->execute();
			$query->fetch();
			$query->close();

			$query = $con->prepare("SELECT count(*) AS count FROM users WHERE banned = 1");
			$query->bind_result($bannedUsers);
			$query->execute();
			$query->fetch();
			$query->close();

			$query = $con->prepare("SELECT count(*) AS count FROM snippets");
			$query->bind_result($numSnippets);
			$query->execute();
			$query->fetch();
			$query->close();

			$query = $con->prepare("SELECT count(*) AS count FROM snippets WHERE public = 1");
			$query->bind_result($publicSnippets);
			$query->execute();
			$query->fetch();
			$query->close();

			$query = $con->prepare("SELECT count(*) FROM user_online");
			$query->bind_result($userCount);
			$query->execute();
			$query->fetch();
			$query->close();
		?>

		<div class="dashboard page">
			<h2 class="page-title"><?=$lang['dashboard']; ?></h2>
			<div class="dash-info">
				<div id="online-users"><?=$lang['onlineUsers']?>: <?=$userCount?></div>
				<div id="registered-users"><?=$lang['registeredUsers']?>: <?=$regUsers?></div>
				<div id="inactive-users"><?=$lang['inactiveUsers']?>: <?=$inactiveUsers?></div>
				<div id="banned-users"><?=$lang['bannedUsers']?>: <?=$bannedUsers?></div>
				<div id="snippets-num"><?=$lang['numberOfSnippets']?>: <?=$numSnippets?></div>
				<div id="snippets-public"><?=$lang['publicSnippets']?>: <?=$publicSnippets?></div>
			</div>
		</div>

		<div class="snippets page" style="display:none;">
			<label class="page-title"><?=$lang['snippets']; ?></label><br>
			<input type="text" id="searchSnippet" placeholder="<?=$lang['search']; ?>">

			<div class="snippet-options">
				<label id="edit-snippet" title="<?=$lang['editSnippet']; ?>"><?=$lang['edit']; ?></label>
				<label id="delete-snippet" title="<?=$lang['deleteSelectedSnippet']; ?>"><?=$lang['delete']; ?></label>
				<label class="visible" id="select-all-snippets" title="<?=$lang['selectAllSnippets']; ?>"><?=$lang['selectAll']; ?></label>
			</div>
			<div class="snippets-table-wrap">
				<table>
					<caption><?=$lang['snippets']; ?></caption>
					<tr id="snippet-table-header">
						<th scope="col"><?=$lang['owner']; ?></th>
						<th scope="col"><?=$lang['title']; ?></th>
						<th scope="col"><?=$lang['description']; ?></th>
						<th scope="col"><?=$lang['public']; ?></th>
						<th scope="col"><?=$lang['select']; ?></th>
					</tr>
					<?php
						$query = $con->prepare("SELECT id, user_id, title, syntax, description, public FROM snippets LIMIT ?");
						$query->bind_param("s", $numSnip);
						$query->execute();
						$query->store_result();
						$query->bind_result($snippet_id, $user_id, $title, $syntax, $description, $public);
						$q = $con->prepare("SELECT username FROM users WHERE user_id = ?");

						while($query->fetch()){
							$q->bind_param("s", $user_id);
							$q->execute();
							$q->bind_result($username);
							$q->fetch();
						?>
						<tr class="snippet-row">

							<td class="owner-holder" onclick="previewSnippet('<?=$snippet_id; ?>');" data-username="<?=$username; ?>"><?=$username; ?></td>
							<td class="title-holder" onclick="previewSnippet('<?=$snippet_id; ?>');"><?=$title; ?></td>
							<td><?=$description; ?></td>
							<td class='publicStatus' data-publicStatus="1"><?php if($public == 0) echo 'No'; else echo 'Yes';?></td>
							<td><input type="checkbox" class="snippet-checker"><label hidden data-snippet-id = "<?=$snippet_id; ?>" class="snippet-id-holder"><?=$snippet_id; ?></label></td>
						</tr>
					<?php } $q->close(); ?>
				</table>

				<?php
					$query = $con->prepare("SELECT count(*) FROM snippets");
					$query->execute();
					$query->bind_result($snippetsNum);
					$query->fetch();
					$query->close();

					$pages = ceil($snippetsNum / $numSnip);
					if( $pages > 1 ) { ?>
					<div class="snippet-pagination">
					<?php for($i = 1; $i <= $pages; $i++){ ?>
					<label <?php if($i == 1) echo "class='activePage'";?>><?=$i; ?></label>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="settings page" style="display: none;">
			<label class="page-title"><?=$lang['settings']; ?></label>
			<div class="settings-panel">
			<?php
				$query = $con->prepare("SELECT email FROM admin WHERE username = 'admin'");
				$query->bind_result($adminEmail);
				$query->execute();
				$query->fetch();
				$query->close();
			?>
				<div id="setting-wrap">
					<p id="admin-email"><?=$lang['currentAdminEmail']; ?>: <?=$adminEmail; ?></p>

					<button id="setting-pass-button"><?=$lang['changePassword']; ?></button>
					<button id="setting-mail-button"><?=$lang['changeEmail']; ?></button>

					<div class="setting-pass-form">
						<input type="password" name="oldpass" id="setting-oldpass" placeholder="<?=$lang['oldPassword']; ?>"><br>
						<input type="password" name="newpass" id="setting-newpass" placeholder="<?=$lang['newPassword']; ?>"><br>
						<input type="password" name="reppas" id="setting-reppass" placeholder="<?=$lang['repeatPassword']; ?>">
						<button id="setting-submit"><?=$lang['change']; ?></button>
						<button id="setting-close"><?=$lang['close']; ?></button>
					</div>
					<span id="setting-error"></span>

					<div class="setting-mail-form">
						<input type="text" name="newmail" id="setting-newmail" placeholder="<?=$lang['newEmail']; ?>"><br>
						<input type="text" name="repmail" id="setting-repmail" placeholder="<?=$lang['repeatEmail']; ?>"><br>
						<button id="setting-submit-mail"><?=$lang['change']; ?></button>
						<button id="setting-close-mail"><?=$lang['close']; ?></button>
					</div>
					<span id="setting-error-mail"></span>
				</div>
			</div>
		</div>

		<div class="users page" style="display: none;">
			<h3 class="page-title"><?=$lang['users']; ?></h3><br>
			<input type="text" id="searchBox" placeholder="<?=$lang['search']; ?>">

			<div class="user-options">
				<button class="visible" id="add-user" title="<?=$lang['manualAddNewUser']; ?>"><?=$lang['add']; ?></button>
				<button id="edit-user" title="<?=$lang['editUser']; ?>"><?=$lang['edit']; ?></button>
				<button id="delete-user" title="<?=$lang['deleteSelectedUser']; ?>"><?=$lang['delete']; ?></button>
				<button id="ban" title="<?=$lang['banUsers'] ?>"><?=$lang['ban']; ?></button>
				<button class="visible" id="select-all" title="<?=$lang['selectAllUsers']; ?>"><?=$lang['selectAll']; ?></button>
			</div>
			<div class="table-wrap">
				<table>
					<caption><?=$lang['users']; ?></caption>
					<tr id="table-header">
						<th scope="col"><?=$lang['id']; ?></th>
						<th scope="col"><?=$lang['username']; ?></th>
						<th scope="col"><?=$lang['email']; ?></th>
						<th scope="col"><?=$lang['joined']; ?></th>
						<th scope="col"><?=$lang['active']; ?></th>
						<th scope="col"><?=$lang['select']; ?></th>
					</tr>

					<?php
						$query = $con->prepare("SELECT user_id, username, email, joined, active FROM users WHERE banned = 0 LIMIT ?");
						$query->bind_param("s", $numUsers);
						$query->execute();
						$query->bind_result($user_id, $username, $email, $joined, $active);
						while($query->fetch()){ ?>
						<tr class="user-row">
							<td data-id="<?=$user_id; ?>" class="userId"><?=$user_id; ?></td>
							<td class="username-holder" data-username="<?=$username; ?>"><?=$username; ?></td>
							<td class="email-holder"><?=$email; ?></td>
							<td><?=$joined; ?></td>
							<td class='activeStatus' data-activeStatus="<?=$active; ?>"><?php if($active == 1) echo $lang['yes']; else echo $lang['no']; ?></td>
							<td><input type="checkbox" class="checker"></td>
						</tr>
					<?php } ?>

				</table>
				<?php
					$query = $con->prepare("SELECT count(*) FROM users WHERE banned = 0");
					$query->execute();
					$query->bind_result($usersNum);
					$query->fetch();
					$query->close();

					$pages = ceil($usersNum / $numUsers);
					if($pages > 1){ ?>
					<div class="pagination">
					<?php for($i = 1; $i <= $pages; $i++){ ?>
					<span <?php if($i == 1) echo "class='activePage'";?>><?=$i; ?></span>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="add-user-window">
			<input type="text" name="username" placeholder="<?=$lang['username']; ?>" id="manual-username">
			<input type="password" name="password" placeholder="<?=$lang['password']; ?>" id="manual-password"><br>
			<input type="text" name="email" placeholder="<?=$lang['email']; ?>" id="manual-email">
			<div class="manual-buttons-wrap">
				<span id="manual-error">e</span>
				<label id="manual-save"><?=$lang['save']; ?></label>
				<label id="manual-close"><?=$lang['close']; ?></label>
			</div>
		</div>

		<div class="edit-user-window">
			<input type="text" name="username" placeholder="<?=$lang['username']; ?>" id="edit-username">
			<input type="password" name="password" placeholder="<?=$lang['password']; ?>" id="edit-password"><br>
			<input type="text" name="email" placeholder="<?=$lang['email']; ?>" id="edit-email">
			<span id="edit-activate"></span>
			<var hidden id="edit-id-holder"></var>
			<var hidden id="edit-activate-holder"></var>
			<div class="edit-buttons-wrap">
				<span id="edit-error">e</span>
				<button id="edit-save"><?=$lang['save']; ?></button>
				<button id="edit-close"><?=$lang['close']; ?></button>
			</div>
		</div>

		<div class="banned-users page" style="display: none;">
			<h3 class="page-title"><?=$lang['bannedUsers']; ?></h3><br>
			<input type="text" id="banned-searchBox" placeholder="<?=$lang['search']; ?>">

			<div class="banned-user-options">
				<button id="unban" title="<?=$lang['removeBan']; ?>"><?=$lang['unban']; ?></button>
				<button class="visible" id="banned-select-all" title="Select all users"><?=$lang['selectAll']; ?></button>
			</div>
			<div class="banned-table-wrap">
				<table>
					<caption><?=$lang['bannedUsers']; ?></caption>
					<tr id="banned-table-header">
						<th scope="col"><?=$lang['id']; ?></t>
						<th scope="col"><?=$lang['username']; ?></t>
						<th scope="col"><?=$lang['email']; ?></t>
						<th scope="col"><?=$lang['joined']; ?></t>
						<th scope="col"><?=$lang['active']; ?></t>
						<th scope="col"><?=$lang['select']; ?></t>
					</tr>

					<?php
						$query = $con->prepare("SELECT user_id, username, email, joined, active FROM users WHERE banned = 1 LIMIT ?");
						$query->bind_param("s", $numUsers);
						$query->execute();
						$query->bind_result($user_id, $username, $email, $joined, $active);
						while($query->fetch()) {
					?>
						<tr class="banned-user-row">
							<td data-id="<?=$user_id; ?>" class="userId"><?=$user_id; ?></td>
							<td data-busername="<?=$username; ?>"><?=$username; ?></td>
							<td><?=$email; ?></td>
							<td><?=$joined; ?></td>
							<td><?php if($active == 1) echo $lang['yes']; else echo $lang['no']; ?></td>
							<td><input type="checkbox" class="banned-checker"></td>
						</tr>
					<?php } ?>

				</table>
				<?php
					$query = $con->prepare("SELECT count(*) FROM users WHERE banned = 1");
					$query->execute();
					$query->bind_result($usersNum);
					$query->fetch();
					$query->close();

					$pages = ceil($usersNum / $numUsers);
					if($pages > 1){ ?>
					<div class="banned-pagination">
					<?php for($i = 1; $i <= $pages; $i++){ ?>
					<span <?php if($i === 1) echo "class='activePage'";?>><?=$i; ?></span>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>

	</main>

</div>
</body>
</html>
