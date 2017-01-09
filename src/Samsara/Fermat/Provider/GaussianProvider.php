<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;

class GaussianProvider
{
    public static function percentBelowX($x, $mean, $standardDev)
    {
        return stats_cdf_normal($x, $mean, $standardDev, 1);
    }

    public static function percentAboveX($x, $mean, $standardDev)
    {
        return (1-self::percentBelowX($x, $mean, $standardDev));
    }

    public static function xFromPercentBelow($prob, $mean, $standardDev)
    {
        $prob = self::formatPercent($prob);

        return stats_cdf_normal($prob, $mean, $standardDev, 2);
    }

    public static function xFromPercentAbove($prob, $mean, $standardDev)
    {
        $prob = self::formatPercent($prob);

        return self::xFromPercentBelow((1-$prob), $mean, $standardDev);
    }

    public static function meanFromPercentBelowX($prob, $x, $standardDev)
    {
        $prob = self::formatPercent($prob);

        return stats_cdf_normal($prob, $x, $standardDev, 3);
    }

    public static function meanFromPercentAboveX($prob, $x, $standardDev)
    {
        $prob = self::formatPercent($prob);

        return self::meanFromPercentBelowX((1-$prob), $x, $standardDev);
    }

    public static function sdFromPercentBelowX($prob, $x, $mean)
    {
        $prob = self::formatPercent($prob);

        return stats_cdf_normal($prob, $x, $mean, 4);
    }

    public static function sdFromPercentAboveX($prob, $x, $mean)
    {
        $prob = self::formatPercent($prob);

        return self::sdFromPercentBelowX((1-$prob), $x, $mean);
    }

    public static function random($mean, $standardDev)
    {
        return stats_rand_gen_normal($mean, $standardDev);
    }

    public static function valueAtX($x, $mean, $standardDev, $total)
    {
        return ($total*stats_dens_normal($x, $mean, $standardDev));
    }

    public static function formatPercent($percent)
    {
        if ($percent <= 1 && $percent > 0) {
            return $percent;
        } elseif ($percent <= 100) {
            return ($percent/100);
        } else {
            throw new IntegrityConstraint(
                '$percent must be between 1 and 0 (inclusive)',
                'Provide a valid percent'
            );
        }
    }
}