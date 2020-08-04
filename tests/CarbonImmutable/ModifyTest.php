<?php
declare(strict_types=1);

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CarbonImmutable;

use Carbon\CarbonImmutable as Carbon;
use Tests\AbstractTestCase;

class ModifyTest extends AbstractTestCase
{
    public function testSimpleModify()
    {
        $a = new Carbon('2014-03-30 00:00:00');
        $b = $a->addHours(24);
        $this->assertSame(24, $a->diffInHours($b));
    }

    public function testTimezoneModify()
    {
        // For daylight saving time reason 2014-03-30 0h59 is immediately followed by 2h00

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addHours(24);
        $this->assertSame(23.0, $a->diffInHours($b, false));
        $this->assertSame(23.0, $b->diffInHours($a));
        $this->assertSame(-23.0, $b->diffInHours($a, false));
        $this->assertSame(-23.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-23.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-23.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-23.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealHours(24);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));
        $b->subRealHours(24);
        $this->assertSame(0.0, $b->diffInHours($a, false));
        $this->assertSame(0.0, $b->diffInHours($a, false));

        $a = new Carbon('2014-03-30 00:59:00', 'Europe/London');
        $a->addRealHour();
        $this->assertSame('02:59', $a->format('H:i'));
        $a->subRealHour();
        $this->assertSame('00:59', $a->format('H:i'));

        $a = new Carbon('2014-03-30 00:59:00', 'Europe/London');
        $a->addRealMinutes(2);
        $this->assertSame('02:01', $a->format('H:i'));
        $a->subRealMinutes(2);
        $this->assertSame('00:59', $a->format('H:i'));

        $a = new Carbon('2014-03-30 00:59:30', 'Europe/London');
        $a->addRealMinute();
        $this->assertSame('02:00:30', $a->format('H:i:s'));
        $a->subRealMinute();
        $this->assertSame('00:59:30', $a->format('H:i:s'));

        $a = new Carbon('2014-03-30 00:59:30', 'Europe/London');
        $a->addRealSeconds(40);
        $this->assertSame('02:00:10', $a->format('H:i:s'));
        $a->subRealSeconds(40);
        $this->assertSame('00:59:30', $a->format('H:i:s'));

        $a = new Carbon('2014-03-30 00:59:59', 'Europe/London');
        $a->addRealSecond();
        $this->assertSame('02:00:00', $a->format('H:i:s'));
        $a->subRealSecond();
        $this->assertSame('00:59:59', $a->format('H:i:s'));

        $a = new Carbon('2014-03-30 00:59:59.990000', 'Europe/London');
        $a->addRealMilliseconds(20);
        $this->assertSame('02:00:00.010000', $a->format('H:i:s.u'));
        $a->subRealMilliseconds(20);
        $this->assertSame('00:59:59.990000', $a->format('H:i:s.u'));

        $a = new Carbon('2014-03-30 00:59:59.999990', 'Europe/London');
        $a->addRealMicroseconds(20);
        $this->assertSame('02:00:00.000010', $a->format('H:i:s.u'));
        $a->subRealMicroseconds(20);
        $this->assertSame('00:59:59.999990', $a->format('H:i:s.u'));

        $a = new Carbon('2014-03-30 00:59:59.999999', 'Europe/London');
        $a->addRealMicrosecond();
        $this->assertSame('02:00:00.000000', $a->format('H:i:s.u'));
        $a->subRealMicrosecond();
        $this->assertSame('00:59:59.999999', $a->format('H:i:s.u'));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealDay();
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealWeeks(1 / 7);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealMonths(1 / 30);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealQuarters(1 / 90);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealYears(1 / 365);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealDecades(1 / 3650);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealCenturies(1 / 36500);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));

        $a = new Carbon('2014-03-30 00:00:00', 'Europe/London');
        $b = $a->copy();
        $b->addRealMillennia(1 / 365000);
        $this->assertSame(-24.0, $b->diffInHours($a, false));
        $this->assertSame(-24.0 * 60, $b->diffInMinutes($a, false));
        $this->assertSame(-24.0 * 60 * 60, $b->diffInSeconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000, $b->diffInMilliseconds($a, false));
        $this->assertSame(-24.0 * 60 * 60 * 1000000, $b->diffInMicroseconds($a, false));
    }

    public function testNextAndPrevious()
    {
        Carbon::setTestNow('2019-06-02 13:27:09.816752');

        $this->assertSame('2019-06-02 14:00:00', Carbon::now()->next('2pm')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-01 14:00:00', Carbon::now()->previous('2pm')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-02 14:00:00', Carbon::now()->next('14h')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-01 14:00:00', Carbon::now()->previous('14h')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-03 09:00:00', Carbon::now()->next('9am')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-02 09:00:00', Carbon::now()->previous('9am')->format('Y-m-d H:i:s'));

        $this->assertSame('2019-06-02 14:00:00', Carbon::parse('next 2pm')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-01 14:00:00', Carbon::parse('previous 2pm')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-02 14:00:00', Carbon::parse('next 14h')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-01 14:00:00', Carbon::parse('previous 14h')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-03 09:00:00', Carbon::parse('next 9am')->format('Y-m-d H:i:s'));
        $this->assertSame('2019-06-02 09:00:00', Carbon::parse('previous 9am')->format('Y-m-d H:i:s'));
    }
}
