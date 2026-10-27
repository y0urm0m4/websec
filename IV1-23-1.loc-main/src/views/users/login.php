<h1>Вход</h1>

<?php if (!empty($error)): ?>
    <p style="background-color: red"><?= $error ?></p>
<?php endif ?>
<div class="m-3 col-md-6">
    <form action="" method="POST">
        <?= \src\services\CsrfService::getHiddenInput() ?>

        <div class="mb-3">
            <label class="form-label" for="inputName">Логин </label>
            <input type="text" id="inputName" class="form-control" name="nickname"
                value="<?= htmlspecialchars($_POST['nickname'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="inputPassword">Пароль </label>
            <input type="password" id="inputPassword" class="form-control" name="password">
        </div>
        <input type="submit" value="Войти">
    </form>
</div>