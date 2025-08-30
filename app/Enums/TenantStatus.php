<?php declare(strict_types=1);

namespace App\Enums;

use App\Enums\BaseEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TenantStatus extends BaseEnum
{
    const Inactive  = 10;
    const Active    = 20;

    public function getPillsSeverity()
    {
        return match ($this->value) {
            self::Active    => 'success',
            self::Inactive  => 'secondary',
            default         => 'secondary',
        };
    }
}
