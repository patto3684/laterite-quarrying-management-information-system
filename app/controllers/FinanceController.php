<?php
class FinanceController extends Controller {
    public function index(): void {
        $this->requireAccess('finance');
        $financeModel = new Finance();
        $customerModel = new Customer();
        $this->view('finance/index', [
            'transactions' => $financeModel->all(),
            'customers' => $customerModel->all(),
        ]);
    }

    public function create(): void {
        $this->requireAccess('finance');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeFinanceInput([
                'type' => $_POST['type'] ?? 'income',
                'description' => trim($_POST['description'] ?? ''),
                'amount' => $_POST['amount'] ?? 0,
                'transaction_date' => trim($_POST['transaction_date'] ?? ''),
                'customer_id' => $_POST['customer_id'] ?? null,
                'payment_status' => $_POST['payment_status'] ?? 'paid',
                'payment_method' => $_POST['payment_method'] ?? 'cash',
            ]);

            $model = new Finance();
            $model->create($data);
            $customerName = '';
            if (!empty($data['customer_id'])) {
                $customer = (new Customer())->find($data['customer_id']);
                $customerName = $customer ? ' for customer ' . $customer['name'] : '';
            }
            $this->logActivity(
                'Submission - Finance record',
                sprintf('User %s added %s transaction of %s on %s%s.', $_SESSION['name'] ?? 'Unknown', $data['type'], number_format($data['amount'], 2), $data['transaction_date'], $customerName)
            );
        }
        $this->redirect('finance');
    }

    public function edit(int $id): void {
        $this->requireAccess('finance');
        $model = new Finance();
        $transaction = $model->find($id);
        if (!$transaction) {
            $this->redirect('finance');
        }
        $customerModel = new Customer();
        $this->view('finance/edit', [
            'transaction' => $transaction,
            'customers' => $customerModel->all(),
        ]);
    }

    public function update(): void {
        $this->requireAccess('finance');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeFinanceInput([
                'type' => $_POST['type'] ?? 'income',
                'description' => trim($_POST['description'] ?? ''),
                'amount' => $_POST['amount'] ?? 0,
                'transaction_date' => trim($_POST['transaction_date'] ?? ''),
                'customer_id' => $_POST['customer_id'] ?? null,
                'payment_status' => $_POST['payment_status'] ?? 'paid',
                'payment_method' => $_POST['payment_method'] ?? 'cash',
            ]);
            $model = new Finance();
            $model->update((int)($_POST['id'] ?? 0), $data);
        }
        $this->redirect('finance');
    }

    private function sanitizeFinanceInput(array $data): array {
        $allowedTypes = ['income', 'expense'];
        $allowedStatuses = ['paid', 'pending', 'overdue'];
        $allowedMethods = ['cash', 'bank_transfer', 'mobile_money', 'other'];

        $type = in_array($data['type'], $allowedTypes, true) ? $data['type'] : 'income';
        $description = trim(preg_replace('/[^A-Za-z0-9\s\.,#\-\/]/', '', $data['description']));
        $amount = max(0, (float)$data['amount']);
        $transaction_date = $data['transaction_date'] ?: date('Y-m-d');
        $customer_id = !empty($data['customer_id']) ? (int)$data['customer_id'] : null;
        $payment_status = in_array($data['payment_status'], $allowedStatuses, true) ? $data['payment_status'] : 'paid';
        $payment_method = in_array($data['payment_method'], $allowedMethods, true) ? $data['payment_method'] : 'cash';

        return [
            'type' => $type,
            'description' => $description,
            'amount' => $amount,
            'transaction_date' => $transaction_date,
            'customer_id' => $customer_id,
            'payment_status' => $payment_status,
            'payment_method' => $payment_method,
        ];
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Finance();
            $model->delete($id);
        }
        $this->redirect('finance');
    }
}
