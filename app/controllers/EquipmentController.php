<?php
class EquipmentController extends Controller {
    public function index(): void {
        $this->requireAccess('equipment');
        $model = new Equipment();
        $this->view('equipment/index', ['equipment' => $model->all()]);
    }

    public function create(): void {
        $this->requireAccess('equipment');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'status' => $_POST['status'] ?? 'available',
                'last_maintenance' => trim($_POST['last_maintenance'] ?? null),
            ];
            $model = new Equipment();
            $model->create($data);
            $this->logActivity(
                'Submission - Equipment record',
                sprintf('User %s added equipment %s with status %s.', $_SESSION['name'] ?? 'Unknown', $data['name'], $data['status'])
            );
        }
        $this->redirect('equipment');
    }

    public function edit(int $id): void {
        $this->requireAccess('equipment');
        $model = new Equipment();
        $equipment = $model->find($id);
        if (!$equipment) {
            $this->redirect('equipment');
        }
        $this->view('equipment/edit', ['equipment' => $equipment]);
    }

    public function update(): void {
        $this->requireAccess('equipment');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Equipment();
            $model->update((int)($_POST['id'] ?? 0), [
                'name' => trim($_POST['name'] ?? ''),
                'status' => $_POST['status'] ?? 'available',
                'last_maintenance' => trim($_POST['last_maintenance'] ?? null),
            ]);
        }
        $this->redirect('equipment');
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Equipment();
            $model->delete($id);
        }
        $this->redirect('equipment');
    }
}
