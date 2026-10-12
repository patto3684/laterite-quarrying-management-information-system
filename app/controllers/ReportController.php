<?php
class ReportController extends Controller {
    public function index(): void {
        $this->requireAccess('reports');
        $this->view('reports/index');
    }

    public function production(): void {
        $this->requireAccess('reports');
        $productionModel = new Production();
        $data = [
            'productions' => $productionModel->all(),
            'title' => 'Production Report',
        ];
        $this->view('reports/production_detail', $data);
    }

    public function finance(): void {
        $this->requireAccess('reports');
        $financeModel = new Finance();
        $data = [
            'transactions' => $financeModel->all(),
            'summary' => $financeModel->getSummaryTotals(),
            'title' => 'Finance Report',
        ];
        $this->view('reports/finance_detail', $data);
    }

    public function logistics(): void {
        $this->requireAccess('reports');
        $data = [
            'title' => 'Logistics & Dispatch Report',
        ];
        $this->view('reports/logistics_detail', $data);
    }

    public function safety(): void {
        $this->requireAccess('reports');
        $inspectionModel = new Inspection();
        $data = [
            'inspections' => $inspectionModel->all(),
            'title' => 'Safety & Environmental Report',
        ];
        $this->view('reports/safety_detail', $data);
    }

    public function filter(): void {
        $this->requireAccess('reports');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid payload']);
            return;
        }

        $dateRange = $payload['date_range'] ?? 'week';
        $quarryLocation = $payload['quarry_location'] ?? 'all';
        $customStart = $payload['custom_start'] ?? null;
        $customEnd = $payload['custom_end'] ?? null;

        $reportModel = new Report();
        $reportData = $reportModel->getDashboardMetrics($dateRange, $quarryLocation, $customStart, $customEnd);

        header('Content-Type: application/json');
        echo json_encode($reportData);
    }
}
