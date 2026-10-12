<?php
class SubmissionController extends Controller {
    public function index(): void {
        $this->requireAccess('submissions');

        $activityModel = new Activity();
        $submissions = $activityModel->submissionsToday();

        $summary = [
            'production' => 0,
            'equipment' => 0,
            'inspection' => 0,
            'finance' => 0,
            'customer' => 0,
            'other' => 0,
        ];

        foreach ($submissions as $submission) {
            $title = strtolower($submission['title']);
            if (strpos($title, 'production') !== false) {
                $summary['production']++;
            } elseif (strpos($title, 'equipment') !== false) {
                $summary['equipment']++;
            } elseif (strpos($title, 'inspection') !== false) {
                $summary['inspection']++;
            } elseif (strpos($title, 'finance') !== false) {
                $summary['finance']++;
            } elseif (strpos($title, 'customer') !== false) {
                $summary['customer']++;
            } else {
                $summary['other']++;
            }
        }

        $this->view('submissions/index', [
            'submissions' => $submissions,
            'summary' => $summary,
        ]);
    }
}
