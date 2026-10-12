<?php
class CustomerController extends Controller {
    public function index(): void {
        $this->requireAccess('customers');
        $model = new Customer();
        $this->view('customers/index', ['customers' => $model->all()]);
    }

    public function create(): void {
        $this->requireAccess('customers');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'contact_person' => trim($_POST['contact_person'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
            ];

            $errors = $this->validateCustomerData($data);
            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_old'] = $data;
                $this->redirect('customers');
                return;
            }

            $data['phone'] = preg_replace('/\D/', '', $data['phone']);
            $model = new Customer();
            $model->create($data);
            $this->logActivity(
                'Submission - Customer record',
                sprintf('User %s added customer %s (contact %s).', $_SESSION['name'] ?? 'Unknown', $data['name'], $data['contact_person'])
            );
        }
        $this->redirect('customers');
    }

    public function edit(int $id): void {
        $this->requireAccess('customers');
        $model = new Customer();
        $customer = $model->find($id);
        if (!$customer) {
            $this->redirect('customers');
        }
        $this->view('customers/edit', ['customer' => $customer]);
    }

    public function update(): void {
        $this->requireAccess('customers');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'contact_person' => trim($_POST['contact_person'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
            ];

            $errors = $this->validateCustomerData($data);
            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_old'] = $data;
                $this->redirect('customers/edit?id=' . $id);
                return;
            }

            $data['phone'] = preg_replace('/\D/', '', $data['phone']);
            $model = new Customer();
            $model->update($id, $data);
        }
        $this->redirect('customers');
    }

    public function delete(): void {
        $this->requireRole(['administrator']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Customer();
            $model->delete($id);
        }
        $this->redirect('customers');
    }

    private function validateCustomerData(array $data): array {
        $errors = [];

        $name = trim($data['name'] ?? '');
        if (strlen($name) < 3) {
            $errors['name'] = 'Customer name must be at least 3 characters long.';        } elseif (!preg_match('/[A-Za-z]/', $name)) {
            $errors['name'] = 'Customer name must include at least one letter.';        } elseif (!preg_match('/^[A-Za-z0-9\s\-&\'\.]+$/', $name)) {
            $errors['name'] = 'Customer name may only include letters, numbers, spaces, hyphens, &, apostrophes, and periods.';
        }

        $contactPerson = trim($data['contact_person'] ?? '');
        $normalizedContact = preg_replace('/\s+/', ' ', $contactPerson);
        $contactParts = array_filter(explode(' ', $normalizedContact));
        if (count($contactParts) < 2) {
            $errors['contact_person'] = 'Contact person must include a first and last name.';
        } elseif (!preg_match('/^[A-Za-z\s\.\-\']+$/', $contactPerson)) {
            $errors['contact_person'] = 'Contact person may only include letters, spaces, periods, hyphens, and apostrophes.';
        }

        $phone = preg_replace('/\D/', '', $data['phone'] ?? '');
        $validPrefixes = ['078', '079', '073', '072'];
        if (strlen($phone) !== 10) {
            $errors['phone'] = 'Phone must be exactly 10 digits.';
        } elseif (!in_array(substr($phone, 0, 3), $validPrefixes, true)) {
            $errors['phone'] = 'Phone must start with 078, 079, 073, or 072.';
        }

        return $errors;
    }
}
