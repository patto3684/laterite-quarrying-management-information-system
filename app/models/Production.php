<?php
class Production extends Model {
    public function all(): array {
        $stmt = $this->db->query('SELECT p.*, q.name as quarry_name FROM productions p JOIN quarries q ON p.quarry_id = q.id ORDER BY p.recorded_on DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM productions WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $production = $stmt->fetch();
        return $production ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO productions (quarry_id, quantity_tonnes, recorded_on, note, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['quarry_id'],
            $data['quantity_tonnes'],
            $data['recorded_on'],
            $data['note'] ?: null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('UPDATE productions SET quarry_id = ?, quantity_tonnes = ?, recorded_on = ?, note = ? WHERE id = ?');
        return $stmt->execute([
            $data['quarry_id'],
            $data['quantity_tonnes'],
            $data['recorded_on'],
            $data['note'] ?: null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM productions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function totalProduced(): int {
        $stmt = $this->db->query('SELECT COALESCE(SUM(quantity_tonnes),0) as total FROM productions');
        return (int)$stmt->fetch()['total'];
    }
}
