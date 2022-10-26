<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Base\Traits\LogNativeTrait;
use Samsara\Fermat\Core\Types\Base\Traits\LogScaleTrait;
use Samsara\Fermat\Core\Types\Base\Traits\LogSelectionTrait;

/**
 * @package Samsara\Fermat\Core
 */
trait SimpleLogTrait
{
    use LogNativeTrait;
    use LogScaleTrait;
    use LogSelectionTrait;

    /**
     * Returns the result of e^this
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function exp(?int $scale = null, bool $round = true): Decimal
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = $this->expSelector($scale);

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * Returns the natural log of this number. The natural log is the inverse of the exp() function.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function ln(?int $scale = null, bool $round = true): Decimal
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = $this->lnSelector($scale);

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * Returns the log base 10 of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function log10(?int $scale = null, bool $round = true): Decimal
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = $this->log10Selector($scale);

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

}