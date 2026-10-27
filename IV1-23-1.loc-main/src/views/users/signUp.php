<h1>Регистрация</h1>

<?php if (!empty($error)): ?>
    <p style="background-color: red"><?= $error ?></p>
<?php endif ?>

<form action="" method="POST">
    <?= \src\services\CsrfService::getHiddenInput() ?>
    <label>Логин <input type="text" name="nickname"
            value="<?= htmlspecialchars($_POST['nickname'] ?? '') ?>"></label><br>
    <label>Email <input type="text" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></label><br>
    <label>Пароль <input type="password" name="password"></label><br>
    <input type="submit" value="Зарегистрироваться">
</form>