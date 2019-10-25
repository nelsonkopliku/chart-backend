<?php

declare(strict_types=1);

namespace Acme\Domain\Report\Repository;

use Acme\Domain\Report\Report;

interface ReportMetricsRepository
{
    public function loadReport(): Report;
}
