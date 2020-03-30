<?php

declare(strict_types = 1);

namespace App\Model;

use Mammoth\DI\DIClass;
use Mammoth\Security\Abstraction\IPermissionManager;
use Mammoth\Security\Abstraction\IUserManager;
use Mammoth\Security\Entity\IRank;
use Mammoth\Security\Entity\Permission;

/**
 * App's own permission manager
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class PermissionManager implements IPermissionManager
{
    use DIClass;

    /**
     * @inject
     */
    private IUserManager $userManager;

    /**
     * @inheritDoc
     */
    public function verifyPermission(string $subject, string $level = Permission::LEVEL_ALL): bool
    {
        // There is very easy permission system in this application
        // => every permissions will be solved manually by if statements

        return false;
    }

    /**
     * @inheritDoc
     */
    public function verifyAccessToComponent(string $component): bool
    {
        if ($component === "presentation") {
            return true;
        } else if ($component === "application") {
            if (($user = $this->userManager->getUser()) !== null) {
                return ($user->isLoggedIn());
            } else {
                return false;
            }
        } else if ($component === "admin") {
            return ($this->userManager->isAnyoneLoggedIn() && $this->userManager->getUser()->getRank()->getType() === IRank::ADMIN);
        } else {
            return false;
        }
    }
}