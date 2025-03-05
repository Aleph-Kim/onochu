<?
$controller = new HomeController();
$users = $controller->index();
?>


<? foreach ($users as $user): ?>
    <li>
        <?= htmlspecialchars($user['username']) ?>
    </li>
<? endforeach; ?>