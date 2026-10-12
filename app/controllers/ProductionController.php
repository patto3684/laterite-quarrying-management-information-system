<?php
class ProductionController extends Controller {
    public function index(): void {
        $this->requireAccess('productions');
        $productionModel = new Production();
        $quarryModel = new Quarry();
        $productions = $productionModel->all();
        $quarries = $quarryModel->all();
        $this->view('productions/index', [
            'productions' => $productions,
            'quarries' => $quarries,
        ]);
    }

    public function create(): void {
        $this->requireAccess('productions');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'quarry_id' => (int)($_POST['quarry_id'] ?? 0),
                'quantity_tonnes' => (float)($_POST['quantity_tonnes'] ?? 0),
                'recorded_on' => trim($_POST['recorded_on'] ?? ''),
                'note' => trim($_POST['note'] ?? ''),
            ];
            $productionModel = new Production();
            $productionModel->create($data);
            $this->logActivity(
                'Submission - Production record',
                sprintf('User %s submitted production for quarry ID %d: %s tonnes on %s.', $_SESSION['name'] ?? 'Unknown', $data['quarry_id'], number_format($data['quantity_tonnes'], 2), $data['recorded_on'])
            );
        }
        $this->redirect('productions');
    }

    public function edit(int $id): void {
        $this->requireAccess('productions');
        $productionModel = new Production();
        $production = $productionModel->find($id);
        if (!$production) {
            $this->redirect('productions');
        }
        $quarryModel = new Quarry();
        $this->view('productions/edit', [
            'production' => $production,
            'quarries' => $quarryModel->all(),
        ]);
    }

    public function update(): void {
        $this->requireAccess('productions');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productionModel = new Production();
            $productionModel->update((int)($_POST['id'] ?? 0), [
                'quarry_id' => (int)($_POST['quarry_id'] ?? 0),
                'quantity_tonnes' => (float)($_POST['quantity_tonnes'] ?? 0),
                'recorded_on' => trim($_POST['recorded_on'] ?? ''),
                'note' => trim($_POST['note'] ?? ''),
            ]);
        }
        $this->redirect('productions');
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $productionModel = new Production();
            $productionModel->delete($id);
        }
        $this->redirect('productions');
    }
}
