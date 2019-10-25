<?php

declare(strict_types=1);

namespace Acme\Infrastructure\Repository\Csv;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Acme\Domain\Report\Report;
use Acme\Domain\Report\Repository\ReportMetricsRepository;
use Acme\Domain\Report\User;
use Acme\Domain\Report\Users;
use Acme\Domain\Report\ValueObject\CreatedAt;
use Acme\Domain\Report\ValueObject\OnboardingPercentage;

final class CsvReportMetricsRepository implements ReportMetricsRepository
{
    private static $source = __DIR__ . '/source.csv';

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function loadReport(): Report
    {
        $dataSource = $this->denormalizeCsvContent();

        return Report::forUsersRows(
            Users::fromArray(array_map(static function(Row $row) {
                return User::create(
                    CreatedAt::fromDateTime($row->createdAt()), OnboardingPercentage::fromInteger($row->onboardingPercentage())
                );
            }, $dataSource))
        );
    }

    private function denormalizeCsvContent(): array
    {
        return $this->serializer->deserialize(
            file_get_contents(self::$source),
            Row::class . '[]',
            'csv',
            [CsvEncoder::DELIMITER_KEY => ';']
        );
    }
}
