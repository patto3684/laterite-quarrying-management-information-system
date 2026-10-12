<?php
class Customer extends Model {
    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM customers ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM customers WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $customer = $stmt->fetch();
        return $customer ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare('INSERT INTO customers (name, contact_person, phone, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'],
            $data['contact_person'],
            $data['phone'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('UPDATE customers SET name = ?, contact_person = ?, phone = ? WHERE id = ?');
        return $stmt->execute([
            $data['name'],
            $data['contact_person'],
            $data['phone'],
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM customers WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
