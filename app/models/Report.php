<?php
class Report extends Model {
    public function getDashboardMetrics(string $dateRange, string $quarryLocation, ?string $customStart, ?string $customEnd): array {
        $range = $this->resolveDateRange($dateRange, $customStart, $customEnd);
        $quarryFilter = $this->resolveQuarryFilter($quarryLocation);

        $productionTotals = $this->getProductionTotals($range['start'], $range['end'], $quarryFilter);
        $financeTotals = $this->getFinanceTotals($range['start'], $range['end'], $quarryFilter);

        return [
            'production' => [
                'total' => (int)$productionTotals['total_produced'],
                'period' => (int)$productionTotals['period_produced'],
            ],
            'finance' => [
                'revenue' => (float)$financeTotals['total_revenue'],
                'expenses' => (float)$financeTotals['total_expenses'],
            ],
            'range' => $range,
            'quarry_location' => $quarryLocation,
        ];
    }

    private function resolveDateRange(string $dateRange, ?string $customStart, ?string $customEnd): array {
        $today = new DateTimeImmutable('today');
        $start = $today;
        $end = $today;

        switch ($dateRange) {
            case 'today':
                break;
            case 'week':
                $start = $today->modify('monday this week');
                $end = $today->modify('sunday this week');
                break;
            case 'month':
                $start = $today->modify('first day of this month');
                $end = $today->modify('last day of this month');
                break;
            case 'custom':
                $customStart = $this->sanitizeDate($customStart);
                $customEnd = $this->sanitizeDate($customEnd);
                if ($customStart !== null && $customEnd !== null) {
                    $start = new DateTimeImmutable($customStart);
                    $end = new DateTimeImmutable($customEnd);
                }
                break;
        }

        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }

        return [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ];
    }

    private function sanitizeDate(?string $date): ?string {
        if (empty($date)) {
            return null;
        }
        $parsed = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $parsed ? $parsed->format('Y-m-d') : null;
    }

    private function resolveQuarryFilter(string $quarryLocation): array {
        $map = [
            'jabana' => 'Jabana Site',
            'ndera' => 'Ndera Site',
            'gatsata' => 'Gatsata Site',
        ];

        if ($quarryLocation === 'all' || !isset($map[$quarryLocation])) {
            return ['sql' => '', 'params' => []];
        }

        return ['sql' => 'AND q.name = ?', 'params' => [$map[$quarryLocation]]];
    }

    private function getProductionTotals(string $startDate, string $endDate, array $quarryFilter): array {
        $sql = <<<SQL
SELECT
    COALESCE(SUM(p.quantity_tonnes), 0) AS total_produced,
    COALESCE(SUM(CASE WHEN p.recorded_on BETWEEN ? AND ? THEN p.quantity_tonnes ELSE 0 END), 0) AS period_produced
FROM productions p
JOIN quarries q ON p.quarry_id = q.id
WHERE p.recorded_on BETWEEN ? AND ?
SQL;
        if ($quarryFilter['sql']) {
            $sql .= " {$quarryFilter['sql']}";
        }

        $params = [$startDate, $endDate, $startDate, $endDate];
        $params = array_merge($params, $quarryFilter['params']);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    private function getFinanceTotals(string $startDate, string $endDate, array $quarryFilter): array {
        // The financial_transactions table has no direct quarry_id relationship in the schema.
        // Apply only date-range filtering for revenue and expense totals.
        $sql = <<<SQL
SELECT
    COALESCE(SUM(CASE WHEN ft.type = 'income' THEN ft.amount ELSE 0 END), 0) AS total_revenue,
    COALESCE(SUM(CASE WHEN ft.type = 'expense' THEN ft.amount ELSE 0 END), 0) AS total_expenses
FROM financial_transactions ft
WHERE ft.transaction_date BETWEEN ? AND ?
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetch();
    }
}
