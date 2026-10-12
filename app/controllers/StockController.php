<?php
class StockController extends Controller {
    public function index(): void {
        $this->requireAccess('stocks');
        $model = new Stock();
        $this->view('stocks/index', ['stocks' => $model->all()]);
    }

    public function create(): void {
        $this->requireAccess('stocks');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Stock();
            $model->create([
                'item_name' => trim($_POST['item_name'] ?? ''),
                'quantity_available' => (int)($_POST['quantity_available'] ?? 0),
                'unit' => $_POST['unit'] ?? 'tons',
            ]);
            $this->redirect('stocks');
        }
        $this->view('stocks/create');
    }

    public function edit(): void {
        $this->requireAccess('stocks');
        $model = new Stock();
        $stock = $model->find((int)($_GET['id'] ?? 0));
        if (!$stock) {
            $this->redirect('stocks');
        }
        $this->view('stocks/edit', ['stock' => $stock]);
    }

    public function update(): void {
        $this->requireAccess('stocks');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Stock();
            $model->update((int)($_POST['id'] ?? 0), [
                'item_name' => $_POST['item_name'] ?? '',
                'quantity_available' => $_POST['quantity_available'] ?? '',
                'unit' => $_POST['unit'] ?? 'tons',
            ]);
        }
        $this->redirect('stocks');
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_POST['delete_id'] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Stock();
            $model->delete($id);
        }
        $this->redirect('stocks');
    }
}
