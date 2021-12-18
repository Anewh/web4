<?php


class FilmsPdo
{
    protected $films_pdo;

    public function __construct()
    {
        if (!($pdoConfig = parse_ini_file('config\\pdo.ini'))) {
            throw new Exception("Ошибка парсинга файла конфигурации", 1);
        }
        $this->films_pdo = new PDO('mysql:host='. $pdoConfig['host'] .';dbname='. $pdoConfig['dbname'], $pdoConfig['login'], $pdoConfig['password']);
    }

    public function getPreviews($last_id, $count)
    {
        if ($last_id != 0) {
            $sql = "SELECT `id`, `name`, `date_create`, `poster_url` FROM `films` WHERE id < :last_id ORDER BY id DESC LIMIT :count";
            $film_cards = $this->films_pdo->prepare($sql);
            $film_cards->bindValue(':last_id', $last_id, PDO::PARAM_INT);
        } else {
            $sql = 'SELECT `id`, `name`, `date_create`, `poster_url` FROM `films` ORDER BY id DESC LIMIT :count';
            $film_cards = $this->films_pdo->prepare($sql);
        }

        $film_cards->bindValue(':count', $count, PDO::PARAM_INT);
        $film_cards->execute();
        return $film_cards;
    }
}
