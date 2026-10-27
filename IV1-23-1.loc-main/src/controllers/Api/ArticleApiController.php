<?php

namespace src\controllers;

use Exception;
use src\models\Article;
use src\models\Users;
use src\exceptions\NotFoundException;
use src\exceptions\UnauthorizedException;
use src\exceptions\InvalidArgumentException;
use src\services\CsrfService;
use src\services\FileUploadService;

class ArticlesApiController extends Controller
{
    public function all()
    {
        $articles = Article::findAll();
        $this->view->displayJson('articles/index.php', ['articles' => $articles]);
    }
    public function view($id)
    {
        $article = Article::getById($id);
        if ($article !== null) {
            $this->view->renderHtml('articles/view.php', ['article' => $article]);
        } else {
            throw new NotFoundException();
        }
    }
    public function edit(string|int $id)
    {
        try {
            $article = Article::getById($id);
            if ($article === null) {
                throw new NotFoundException();
            }

            if ($this->user === null) {
                throw new UnauthorizedException();
            }

            if ($article->getAuthorId() !== $this->user->getId()) {
                throw new Exception('Вы не можете редактировать эту статью: она принадлежит другому пользователю');
            }

            if (!empty($_POST)) {
                CsrfService::validate();

                $imgFileName = null;
                if (!empty($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                    $baseDir = realpath(__DIR__ . '/../../');
                    $uploadDir = $baseDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'articles';
                    $imgFileName = FileUploadService::saveImage($_FILES['img'], $uploadDir);
                }

                $article->updateFromArray($_POST, $imgFileName);

                header("Location: /IV1-23-1.loc-main/article/{$article->getId()}");
                exit;
            }

            $id = $article->getId();

            $this->view->renderHtml("articles/edit.php", ['article' => $article]);

        } catch (NotFoundException $e) {
            http_response_code(404);
            echo "Статья не найдена";
        } catch (UnauthorizedException $e) {
            header("Location: /IV1-23-1.loc-main/user/login");
            exit;
        } catch (Exception $e) {

            header("Location: /IV1-23-1.loc-main/articles");
        }
    }


    public function add()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            CsrfService::validate();
            try {
                $imgFileName = null;

                if (!empty($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                    $baseDir = realpath(__DIR__ . '/../../');
                    if (!$baseDir) {
                        throw new \RuntimeException('Не найден корень проекта');
                    }

                    $uploadDir = $baseDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'articles';

                    $imgFileName = FileUploadService::saveImage($_FILES['img'], $uploadDir);
                }

                // Создаем статью
                $article = Article::create($_POST, $imgFileName, $this->user);

                header("Location: /iv1-23-1.loc-main/article/{$article->getId()}");
                exit;

            } catch (\InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            } catch (\RuntimeException $e) {
                error_log('Upload error: ' . $e->getMessage()); // Запишет в лог сервера
                $this->view->renderHtml('articles/add.php', ['error' => 'Произошла техническая ошибка при загрузке: ' . $e->getMessage()]);
                return;
            }
        }

        // Если просто зашли на страницу (GET запрос)
        $this->view->renderHtml('articles/add.php');
    }

    public function delete($id)
    {
        try {
          
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: /articles");
                exit;
            }

            CsrfService::validate();
            $article = Article::getById($id);
            if ($article === null) {
                throw new NotFoundException();
            }

            if ($this->user === null) {
                throw new UnauthorizedException();
            }

            if ($article->getAuthorId() !== $this->user->getId()) {
                // Используем UnauthorizedException, чтобы обработать это в одном блоке catch
                throw new UnauthorizedException('Вы не можете удалить эту статью: она принадлежит другому пользователю');
            }

            $article->delete();

            header("Location: /IV1-23-1.loc-main/articles");
            exit;

        } catch (NotFoundException $e) {
            http_response_code(404);
            echo "Статья не найдена";
            // Или: $this->view->renderHtml('errors/404', []);
        } catch (UnauthorizedException $e) {
            header("Location: /IV1-23-1.loc-main/articles");
            exit;
        } catch (Exception $e) {
            error_log('Delete error: ' . $e->getMessage());
            $this->view->renderHtml(
                'articles/index', // Путь к твоему шаблону списка статей
                ['error' => 'Произошла ошибка при удалении. Попробуйте позже.']
            );
        }
    }

    public function search()
    {
        if (empty($_GET['q'])) {
            $this->view->renderHtml('articles/search.php');
            return;
        }
        CsrfService::validate();   // <-- добавить
        $articles = Article::searchByName($_GET['q']);
        $this->view->renderHtml('articles/search.php', ['articles' => $articles]);
    }
}



