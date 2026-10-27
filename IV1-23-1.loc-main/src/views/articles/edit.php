<h1>Редактирование статьи: <?= $article->getName() ?></h1>

<div class="m-3 col-md-6">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <?= \src\services\CsrfService::getHiddenInput() ?>
        <div class="mb-3">
            <label for="inputName" class="form-label">Название статьи</label>
            <input type="text" id="inputName" name="name" class="form-control"
                value="<?= htmlspecialchars($_POST['name'] ?? $article->getName()) ?>">
        </div>
        <div class="mb-3">
            <label for="inputText" class="form-label">Текст статьи</label>
            <textarea id="inputText" name="text"
                class="form-control"><?= htmlspecialchars($_POST['text'] ?? $article->getText()) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="inputImg" class="form-label">Изображение (JPG, PNG, WebP, до 2 МБ)</label>
            <input type="file" id="inputImg" class="form-control" name="img" accept="image/jpeg,image/png,image/webp">
        </div>

        <input type="submit" class="btn btn-primary" value="Отправить">
        <a href="<?= $_SERVER['HTTP_REFERER'] ?? "article/{$article->getId()}" ?>" class="btn btn-primary">Отменить</a>
    </form>
</div>