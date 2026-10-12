<?php
class QuarryController extends Controller {
    public function index(): void {
        $this->requireAccess('quarries');
        $model = new Quarry();
        $this->view('quarries/index', ['quarries' => $model->all()]);
    }

    public function create(): void {
        $this->requireAccess('quarries');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Quarry();
            $model->create([
                'name' => trim($_POST['name'] ?? ''),
                'district' => trim($_POST['district'] ?? ''),
                'sector' => trim($_POST['sector'] ?? ''),
                'status' => $_POST['status'] ?? 'active',
            ]);
            $this->redirect('quarries');
        }
        $this->view('quarries/create');
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Quarry();
            $model->delete($id);
        }
        $this->redirect('quarries');
    }
}
