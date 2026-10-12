<?php
class Equipment extends Model {
    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM equipment ORDER BY name');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM equipment WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $equipment = $stmt->fetch();
        return $equipment ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO equipment (name, status, last_maintenance) VALUES (?, ?, ?)');
        $stmt->execute([
            $data['name'],
            $data['status'],
            $data['last_maintenance'] ?: null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('UPDATE equipment SET name = ?, status = ?, last_maintenance = ? WHERE id = ?');
        return $stmt->execute([
            $data['name'],
            $data['status'],
            $data['last_maintenance'] ?: null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM equipment WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
