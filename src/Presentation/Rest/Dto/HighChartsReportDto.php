<?php

declare(strict_types=1);

namespace Acme\Presentation\Rest\Dto;

use Acme\Domain\Report\Report;

final class HighChartsReportDto
{
    private static $chartConfig = [
        'chart' =>[
            'type'=> 'spline'
        ],
        'title' => [
            'text' => 'Onboarding process insights'
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Row percentage at a given step'
            ],
            'max' => 100
        ],
        'xAxis' => [
            'title' => [
                'text' => 'Onboarding process percentage'
            ],
            'max' => 100
        ],
        'plotOptions' => [
            'spline' => [
                'marker' => [
                    'enabled' => true
                ]
            ]
        ]
    ];

    private $report;

    private function __construct(Report $report)
    {
        $this->report = $report;
    }

    public static function fromReport(Report $report): self
    {
        return new self($report);
    }

    public function toArray(): array
    {
        $normalizedTuples = array_map(static function($item) {
            $tuples = [];
            foreach ($item as $step => $inThisStep) {
                $tuples[] = [$step, $inThisStep];
            }
            return $tuples;
        }, $this->report->generate());

        $chartSeries = [];

        foreach ($normalizedTuples as $week => $metrics) {
            $chartSeries[] = [
                'name' => "Week #$week",
                'data' => $metrics
            ];
        }

        return array_merge(self::$chartConfig, ['series' => $chartSeries]);
    }
}
