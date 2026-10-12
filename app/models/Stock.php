<?php
class Stock extends Model {
    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM stock_items ORDER BY item_name');
        return $stmt->fetchAll();
    }

    public function availableStock(): int {
        $stmt = $this->db->query('SELECT COALESCE(SUM(quantity_available),0) as total FROM stock_items');
        return (int)$stmt->fetch()['total'];
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM stock_items WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $stock = $stmt->fetch();
        return $stock ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO stock_items (item_name, quantity_available, unit) VALUES (?, ?, ?)');
        $stmt->execute([$data['item_name'], $data['quantity_available'], $data['unit']]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [];
        if (!empty($data['item_name'])) {
            $fields[] = 'item_name = ?';
            $params[] = trim($data['item_name']);
        }
        if (isset($data['quantity_available'])) {
            $fields[] = 'quantity_available = ?';
            $params[] = (int)$data['quantity_available'];
        }
        if (!empty($data['unit'])) {
            $fields[] = 'unit = ?';
            $params[] = $data['unit'];
        }
        if (empty($fields)) {
            return false;
        }
        $params[] = $id;
        $stmt = $this->db->prepare('UPDATE stock_items SET ' . implode(', ', $fields) . ' WHERE id = ?');
        return $stmt->execute($params);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM stock_items WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
