<?php

require_once 'pdo.php';

class Film extends FilmsPdo
{
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

    public function getFilmDetails($card_id)
    {
        $sql = "SELECT f.id, f.name AS film_name, f.date_create, f.discription, f.poster_url, f.source, u.name AS author_name 
                FROM films AS f INNER JOIN users AS u ON f.author_id = u.id 
                WHERE f.id = :id";

        $card = $this->films_pdo->prepare($sql);

        $card->bindValue(':id', $card_id, PDO::PARAM_INT);

        $card->execute();
        return $card;
    }

    public function addFilm($film_name, $author_id, $discription, $poster_url, $source)
    {
        $save_name = htmlspecialchars($film_name);
        $date_create = date("Y/m/d");
        $save_discriptions = htmlspecialchars($discription);
        $save_poster_url = htmlspecialchars($poster_url);
        $save_source = htmlspecialchars($source);

        try {
            $sql = "INSERT INTO films (`name`, `date_create`, `author_id`, `discription`, `poster_url`, `source`)
                    VALUES (:film_name, :date_create, :author_id, :discription, :poster_url, :source)";

            $film = $this->films_pdo->prepare($sql);

            $film->bindValue(":film_name", $save_name);
            $film->bindValue(":date_create", $date_create);
            $film->bindValue(":author_id", $author_id);
            $film->bindValue(":discription", $save_discriptions);
            $film->bindValue(":poster_url", $save_poster_url);
            $film->bindValue(":source", $save_source);

            $film->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    }
}
