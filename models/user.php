<?php

require_once 'pdo.php';

class User extends FilmsPdo
{
    public function getUserById($user_id)
    {
        $sql = 'SELECT * 
                FROM `users` AS u
                WHERE u.user_id = :user_id';

        $user = $this->films_pdo->prepare($sql);
        $user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $user->execute();

        return $user->fetch(PDO::FETCH_ASSOC);
    }

    public function addUser($name, $email, $user_password, $phone_number)
    {
        $save_name = htmlspecialchars($name);
        $save_email = htmlspecialchars($email);
        $save_password = password_hash($user_password, PASSWORD_DEFAULT);
        $save_phone_number = htmlspecialchars($phone_number);

        try {
            $sql = "INSERT INTO `users`(`name`, `email`, `password`, `phone_number`) 
                            VALUES(:nameU, :email, :user_password, :phone_number)";

            $user = $this->films_pdo->prepare($sql);

            $user->bindValue(":nameU", $save_name);
            $user->bindValue(":email", $save_email);
            $user->bindValue(":user_password", $save_password);
            $user->bindValue(":phone_number", $save_phone_number);

            $user->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
            return json_encode($e->getMessage());
        }
        return json_encode($save_email);
    }

    public function getUserByEmail($user_email)
    {
        $save_email = htmlspecialchars($user_email);
        $sql = 'SELECT * 
                FROM `users` AS u
                WHERE u.email = :save_email';

        $user = $this->films_pdo->prepare($sql);
        $user->bindValue(':save_email', $save_email);
        $user->execute();

        return $user->fetch(PDO::FETCH_ASSOC);
    }

    public function isFreeEmail($user_email)
    {
        $save_email = htmlspecialchars($user_email);
        $user = $this->films_pdo->prepare("SELECT COUNT(email) AS c 
                                    FROM users
                                    WHERE `email` = :save_email ");
        $user->execute(array(":save_email" => $save_email));
        $count = (int) $user->fetch()['c'];
        if ($count === 0) {
            return true;
        }
        return false;
    }
}
