<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\NotificationText;
use App\Entity\User;
use Mammoth\Database\DB;
use function trim;

/**
 * Class NotificationRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class NotificationRepository implements Abstraction\INotificationRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(User $user, NotificationText $text, array $variables): void
    {
        $this->db->withoutResult(
            "INSERT INTO `notifications`(`user_id`, `notification_text_id`) VALUES(?, ?)",
            $user,
            $text->getId()
        );

        $notificationId = $this->db->getLastId();

        $addVariablesSql = "";
        $addVariablesData = [];
        foreach ($variables as $name => $value) {
            $addVariablesSql .= "(?, ?, ?), ";
            $addVariablesData[] = $name;
            $addVariablesData[] = $value;
            $addVariablesData[] = $notificationId;
        }

        $addVariablesSql = trim($addVariablesSql, ", ");

        $this->db->withoutResult(
            "INSERT INTO `notification_variables`(`variable`, `content`, `notification_id`) VALUES ({$addVariablesSql})",
            $addVariablesData
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->db->withoutResult("DELETE FROM `notifications` WHERE `notification_id` = ?", $id);
    }
}