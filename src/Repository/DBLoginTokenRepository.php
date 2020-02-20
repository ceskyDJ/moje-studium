<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use Mammoth\Database\DB;

/**
 * Class LoginTokenRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class LoginTokenRepository implements Abstraction\ILoginTokenRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(User $user, string $content): void
    {
        $this->db->withoutResult(
            "INSERT INTO `tokens`(`user_id`, `token`, `created`) VALUES(?, ?, NOW())",
            $user->getId(),
            $content
        );
    }

    /**
     * @inheritDoc
     */
    public function deactivate(int $id): void
    {
        $this->db->withoutResult("UPDATE `tokens` SET `valid` = FALSE WHERE `token_id` = ?", $id);
    }
}