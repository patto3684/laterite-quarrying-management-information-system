<?php
class Activity extends Model {
    public function latest(): array {
        $stmt = $this->db->query('SELECT * FROM activities ORDER BY created_at DESC LIMIT 8');
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO activities (title, detail, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([
            $data['title'],
            $data['detail'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function submissionsToday(): array {
        $stmt = $this->db->prepare("SELECT * FROM activities WHERE title LIKE 'Submission - %' AND DATE(created_at) = CURDATE() ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
