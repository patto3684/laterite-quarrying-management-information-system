<?php
class Quarry extends Model {
    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM quarries ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO quarries (name, district, sector, status, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$data['name'], $data['district'], $data['sector'], $data['status']]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM quarries WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function count(): int {
        $stmt = $this->db->query('SELECT COUNT(*) as count FROM quarries');
        return (int)$stmt->fetch()['count'];
    }
}
