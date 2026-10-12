<?php
class InspectionController extends Controller {
    public function index(): void {
        $this->requireAccess('inspections');
        $inspectionModel = new Inspection();
        $quarryModel = new Quarry();
        $this->view('inspections/index', [
            'inspections' => $inspectionModel->all(),
            'quarries' => $quarryModel->all(),
        ]);
    }

    public function create(): void {
        $this->requireAccess('inspections');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'quarry_id' => (int)($_POST['quarry_id'] ?? 0),
                'title' => trim($_POST['title'] ?? ''),
                'scheduled_date' => trim($_POST['scheduled_date'] ?? ''),
                'status' => $_POST['status'] ?? 'pending',
            ];
            $model = new Inspection();
            $model->create($data);
            $this->logActivity(
                'Submission - Inspection record',
                sprintf('User %s submitted inspection %s for quarry ID %d on %s.', $_SESSION['name'] ?? 'Unknown', $data['title'], $data['quarry_id'], $data['scheduled_date'])
            );
        }
        $this->redirect('inspections');
    }

    public function edit(int $id): void {
        $this->requireAccess('inspections');
        $inspectionModel = new Inspection();
        $inspection = $inspectionModel->find($id);
        if (!$inspection) {
            $this->redirect('inspections');
        }
        $quarryModel = new Quarry();
        $this->view('inspections/edit', [
            'inspection' => $inspection,
            'quarries' => $quarryModel->all(),
        ]);
    }

    public function update(): void {
        $this->requireAccess('inspections');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Inspection();
            $model->update((int)($_POST['id'] ?? 0), [
                'quarry_id' => (int)($_POST['quarry_id'] ?? 0),
                'title' => trim($_POST['title'] ?? ''),
                'scheduled_date' => trim($_POST['scheduled_date'] ?? ''),
                'status' => $_POST['status'] ?? 'pending',
            ]);
        }
        $this->redirect('inspections');
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Inspection();
            $model->delete($id);
        }
        $this->redirect('inspections');
    }
}
