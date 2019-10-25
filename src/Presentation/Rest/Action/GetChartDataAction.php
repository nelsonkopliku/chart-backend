<?php

declare(strict_types=1);

namespace Acme\Presentation\Rest\Action;

use Acme\Domain\Report\Repository\ReportMetricsRepository;
use Acme\Presentation\Rest\Dto\HighChartsReportDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GetChartDataAction
{
    /**
     * @Route("/api/charts/onboarding", name="api_onboarding_chart_data")
     */
    public function __invoke(ReportMetricsRepository $reportRepository)
    {
        return new JsonResponse(
            HighChartsReportDto::fromReport($reportRepository->loadReport())->toArray()
        );
    }
}
