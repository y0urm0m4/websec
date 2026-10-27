<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/IV1-23-1.loc-main/">
    <title>НОВОСТИ - НЕДЕЛИ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"
        defer></script>
    <link rel="shortcut icon" href="src/assets/css/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="main.html"><img src="img/favicon.svg" alt="Логотип">
                    <h2>НОВОСТИ</h2>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link" href="/IV1-23-1.loc-main/articles">Статьи</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">О нас</a>
                        </li>
                        <li class="nav-item dropdown">
                            <?php if ($user): ?>
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Пользователь: <?= htmlspecialchars($user ? $user->getNickname() : '') ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="/IV1-23-1.loc-main/user/logout" method="POST" class="d-inline">
                                            <?= \src\services\CsrfService::getHiddenInput() ?>
                                            <button type="submit" class="dropdown-item">Выход</button>
                                        </form>
                                    </li>
                                </ul>
                            <?php else: ?>
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Профиль <?= htmlspecialchars($user ? $user->getNickname() : '') ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/IV1-23-1.loc-main/user/register">Регистрация</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/IV1-23-1.loc-main/user/login">Авторизация</a></li>
                                </ul>
                            <?php endif ?>


                        </li>
                    </ul>
                    <form class="d-flex" role="search" action="article/search" method="GET">
                        <?= \src\services\CsrfService::getHiddenInput() ?>
                        <input class="form-control me-2" type="search" placeholder="Поиск..." aria-label="Search"
                            name="q" />
                        <button class="btn btn-dark" type="submit">Найти</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="container pt-3 pb-3">
        <?= $content ?>
    </main>

    <footer>
        <h2>Контакты:</h2>
        <h3>+7(123)-456-78-90</h3>
        <h3>example@gmail.com</h3>
        <h3>2026 &copy;</h3>
    </footer>
</body>

</html>