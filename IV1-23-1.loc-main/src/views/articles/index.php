<h1>Статьи</h1>
<?php if ($user): ?>
    <p><a href="/IV1-23-1.loc-main/articles/add">Добавить статью</a></p>
<?php endif ?>

<ul class="row list-unstyled">
    <?php foreach ($articles as $article): ?>
        <li class="col-sm-6 col-md-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title"><?= htmlspecialchars($article->getName()) ?></h2>

                    <?php if ($article->getImg() !== null): ?>
                        <img class="img-fluid" src="<?= $article->getImg() ?>" alt="">
                    <?php endif; ?>

                    <p class="card-text"><?= htmlspecialchars($article->getText()) ?></p>
                    <p>Автор: <?= htmlspecialchars($article->getAuthor()->getNickname()) ?></p>
                    <a href="article/<?= $article->getId() ?>">Подробнее</a>
                    <a href="article/<?= $article->getId() ?>/edit">Редактировать</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>