<?php
class Inspection extends Model {
    public function all(): array {
        $stmt = $this->db->query('SELECT i.*, q.name as quarry_name FROM inspections i JOIN quarries q ON i.quarry_id = q.id ORDER BY i.scheduled_date DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM inspections WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $inspection = $stmt->fetch();
        return $inspection ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO inspections (quarry_id, title, scheduled_date, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['quarry_id'],
            $data['title'],
            $data['scheduled_date'],
            $data['status'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('UPDATE inspections SET quarry_id = ?, title = ?, scheduled_date = ?, status = ? WHERE id = ?');
        return $stmt->execute([
            $data['quarry_id'],
            $data['title'],
            $data['scheduled_date'],
            $data['status'],
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM inspections WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function pendingCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM inspections WHERE status = 'pending'");
        return (int)$stmt->fetch()['count'];
    }

    public function completedCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM inspections WHERE status = 'completed'");
        return (int)$stmt->fetch()['count'];
    }
}
