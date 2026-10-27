<h1><?= htmlspecialchars($article->getName()) ?></h1>

<?php if ($article->getImg() !== null): ?>
    <img class="img-fluid" src="<?= $article->getImg() ?>" alt="">
<?php endif; ?>

<p><?= htmlspecialchars($article->getText()) ?></p>

<p>Автор: <?= htmlspecialchars($article->getAuthor()->getNickname()) ?></p>

<p>
    <a href="article/<?= $article->getId() ?>/edit" class="btn btn-primary">Редактировать</a>
   <form action="article/<?= $article->getId() ?>/delete" method="POST" style="display:inline;">
    <?= \src\services\CsrfService::getHiddenInput() ?>
    <button type="submit" class="btn btn-primary" onclick="return confirm('Удалить статью?')">Удалить</button>
</form>
</p>