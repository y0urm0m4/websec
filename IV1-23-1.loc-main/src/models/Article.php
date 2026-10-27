<?php
namespace src\models;

use InvalidArgumentException;
use Override;
use src\services\Db;
class Article extends ActiveRecordEntity
{
    protected $author_id;
    protected $name;
    protected $text;
    protected $created_at;
    protected $img = null;

    protected static function getTableName(): string
    {
        return 'articles';
    }
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function getImg(): ?string
    {
        return $this->img;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setText($text)
    {
        $this->text = $text;
    }
    public function setAuthorId($authorId)
    {
        $this->author_id = $authorId;
    }

    public function getAuthor(): User
    {
        return User::getById($this->author_id);
    }

    public static function create(array $fields, ?string $imgFileName, User $author): Article
    {
        // Валидация обязательных полей
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new self();
        $article->name = $fields['name'];
        $article->text = $fields['text'];
        $article->author_id = $author->getId();

        // Если передали имя файла, сохраняем путь. 
        // Путь относительный, так как папка public/uploads доступна из браузера
        if ($imgFileName !== null) {
            $article->img = 'uploads/articles/' . $imgFileName;
        } else {
            // Если картинки нет, можно оставить поле пустым или поставить заглушку
            $article->img = null;
        }

        $article->save();
        return $article;
    }

    public function updateFromArray(array $fields, ?string $imgFileName): Article
    {
        if (empty($fields['name'])) {
            throw new \InvalidArgumentException('Не передано название статьи');
        }
        if (empty($fields['text'])) {
            throw new \InvalidArgumentException('Не передан текст статьи');
        }

        $this->name = $fields['name'];
        $this->text = $fields['text'];

        // Если загрузили новый файл, обновляем путь. 
        // Путь храним относительно public: uploads/articles/имя.jpg
        if ($imgFileName !== null) {
            $this->img = 'uploads/articles/' . $imgFileName;
        }

        // Если файл не загружали, поле img не трогаем (старая картинка останется)

        $this->save();
        return $this;
    }


    public static function searchByName(string $searchString): ?array
    {
        return parent::search('name', $searchString);
    }
}