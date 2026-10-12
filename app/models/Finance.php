<?php
class Finance extends Model {
    public function all(): array {
        $stmt = $this->db->query(
            'SELECT ft.*, c.name AS customer_name FROM financial_transactions ft LEFT JOIN customers c ON ft.customer_id = c.id ORDER BY ft.transaction_date DESC'
        );
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare(
            'SELECT ft.*, c.name AS customer_name FROM financial_transactions ft LEFT JOIN customers c ON ft.customer_id = c.id WHERE ft.id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $transaction = $stmt->fetch();
        return $transaction ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO financial_transactions (type, description, amount, transaction_date, customer_id, payment_status, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['type'],
            $data['description'],
            $data['amount'],
            $data['transaction_date'],
            $data['customer_id'],
            $data['payment_status'],
            $data['payment_method'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('UPDATE financial_transactions SET type = ?, description = ?, amount = ?, transaction_date = ?, customer_id = ?, payment_status = ?, payment_method = ? WHERE id = ?');
        return $stmt->execute([
            $data['type'],
            $data['description'],
            $data['amount'],
            $data['transaction_date'],
            $data['customer_id'],
            $data['payment_status'],
            $data['payment_method'],
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM financial_transactions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getSummaryTotals(): array {
        $stmt = $this->db->query(
            "SELECT
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) AS total_revenue,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS total_expenses,
                COALESCE(SUM(CASE WHEN type = 'income' AND payment_status = 'paid' THEN amount ELSE 0 END), 0) AS total_paid_income,
                COALESCE(SUM(CASE WHEN type = 'income' AND payment_status = 'pending' THEN amount ELSE 0 END), 0) AS total_pending_income,
                COALESCE(SUM(CASE WHEN type = 'income' AND payment_status = 'overdue' THEN amount ELSE 0 END), 0) AS total_overdue_income
            FROM financial_transactions"
        );
        return $stmt->fetch();
    }

    public function totalRevenue(): int {
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount),0) as total FROM financial_transactions WHERE type = 'income'");
        return (int)$stmt->fetch()['total'];
    }

    public function totalExpenses(): int {
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount),0) as total FROM financial_transactions WHERE type = 'expense'");
        return (int)$stmt->fetch()['total'];
    }
}
