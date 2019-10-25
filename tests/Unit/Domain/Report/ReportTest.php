<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\Report;

use Acme\Domain\Report\Report;
use Acme\Tests\Dummy\DummyUser;
use PHPUnit\Framework\TestCase;

final class ReportTest extends TestCase
{
    /**
     * @dataProvider provideSamples
     */
    public function test_it_generates_report($sample, $result): void
    {
        $report = Report::forUsersRows(DummyUser::fromDummySource($sample));
        self::assertEquals($result, $report->generate());
    }

    public function provideSamples(): array
    {
        return [
            [
                'source.csv',
                [
                    29 =>
                        [
                            0 => 100,
                            35 => 0.29,
                            40 => 11.5,
                            45 => 0.59,
                            50 => 0.59,
                            95 => 0.29,
                            99 => 2.36,
                            100 => 6.49,
                        ],
                    30 =>
                        [
                            0 => 100,
                            40 => 23.89,
                            45 => 0.59,
                            50 => 0.59,
                            55 => 0.88,
                            60 => 1.18,
                            65 => 0.29,
                            95 => 2.95,
                            99 => 5.6,
                            100 => 10.62,
                        ],
                    31 =>
                        [
                            0 => 100,
                            35 => 0.29,
                            40 => 5.9,
                            50 => 0.29,
                            55 => 0.59,
                            65 => 1.47,
                            95 => 2.06,
                            99 => 4.13,
                            100 => 3.54,
                        ],
                    32 =>
                        [
                            0 => 100,
                            40 => 2.95,
                            95 => 1.18,
                            99 => 5.31,
                            100 => 3.24,
                        ],
                ]
            ],
            // Possibility to add more cases
//            ['sample1.csv'],
//            ['sample2.csv'],
//            ['sample3.csv'],
//            ['sample4.csv']
        ];
    }
}
