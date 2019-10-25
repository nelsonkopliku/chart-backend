<?php

declare(strict_types=1);

namespace Acme\Tests\Dummy;

use Acme\Infrastructure\Repository\Csv\Row;

final class DummyRow
{
    public static function fromDummySource(string $source = 'source.csv'): array
    {
        # Similar code in tests/Dummy/DummyUser.php::fromDummySource
        # This should be refactored
        $rows = [];
        $row = 0;
        if (($handle = fopen(__DIR__ . '/samples/' . $source, 'rb')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                if (0 === $row) {
                    $row++;
                    continue;
                }

                $rows[] = new Row(new \DateTime($data[1]), $data[2]);
                $row++;
            }
            fclose($handle);
        }
        return $rows;
    }
}
