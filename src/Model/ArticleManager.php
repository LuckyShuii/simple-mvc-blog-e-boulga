<?php

namespace App\Model;

use App\UploadFile;
use PDO;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'article';
    public const CLASSNAME = "App\Model\ArticleModel";

    public function getArticle(int $id): ArticleModel|false
    {
        return $this->selectOneById($id);
    }

    public function getMainArticle()
    {
        $mainArticle = $this->getMainArticleId();

        $statement = $this->pdo->query('SELECT * FROM article WHERE id = ' . $mainArticle->getIdArticle());
        $statement->setFetchMode(PDO::FETCH_CLASS, static::CLASSNAME);

        return $statement->fetch();
    }

    public function getAllArticles()
    {
        $statement = $this->pdo->query('SELECT * FROM article WHERE status = 2 ORDER BY releaseDate DESC LIMIT 15');
        $statement->setFetchMode(PDO::FETCH_CLASS, static::CLASSNAME);
        return $statement->fetchAll();
    }

    public function createArticle(array $form)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . "
            (
            `authorId`,
            `categoryId`,
            `articleTitle`,
            `homeTitle`,
            `imgSrc`,
            `altImg`,
            `homePreview`,
            `introduction`,
            `detail`,
            `description`,
            `shadowColor`,
            `status`,
            `releaseDate`,
            `updatedat`)
            VALUES (
            :authorId,
            :categoryId,
            :articleTitle,
            :homeTitle,
            :imgSrc,
            :altImg,
            :homePreview,
            :introduction,
            :detail,
            :description,
            :shadowColor,
            :status,
            NOW(),
            NOW()
            )"
        );
        $statement->bindValue('authorId', $form['author'], PDO::PARAM_INT);
        $statement->bindValue('categoryId', $form['category'], PDO::PARAM_INT);
        $statement->bindValue('articleTitle', $form['title'], PDO::PARAM_STR);
        $statement->bindValue('homeTitle', $form['hometitle'], PDO::PARAM_STR);
        $statement->bindValue('imgSrc', $form['imgSrc'], PDO::PARAM_STR);
        $statement->bindValue('altImg', $form['title'], PDO::PARAM_STR);
        $statement->bindValue('homePreview', $form['homepreview'], PDO::PARAM_STR);
        $statement->bindValue('introduction', $form['introduction'], PDO::PARAM_STR);
        $statement->bindValue('detail', $form['detail'], PDO::PARAM_STR);
        $statement->bindValue('description', $form['description'], PDO::PARAM_STR);
        $statement->bindValue('status', $form['status'], PDO::PARAM_INT);
        $statement->bindValue('shadowColor', '', PDO::PARAM_STR);

        $statement->execute();
    }

    public function setMainArticle(int $id)
    {
        $statement = $this->pdo->prepare('UPDATE mainArticle SET idArticle = :id WHERE id = 1');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function getMainArticleId(): MainArticleModel
    {
        $statement = $this->pdo->query('SELECT idArticle FROM mainArticle WHERE id = 1');
        $statement->setFetchMode(PDO::FETCH_CLASS, 'App\Model\MainArticleModel');
        return $statement->fetch();
    }
}
