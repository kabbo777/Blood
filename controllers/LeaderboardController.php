<?php
require_once __DIR__ . '/../models/LeaderboardModel.php';

class LeaderboardController extends Controller {
    public function district() {
        $district = filter_input(INPUT_GET, 'district', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'Dhaka';

        $leaderboardModel = new LeaderboardModel();
        $rankings         = $leaderboardModel->getLeaderboardByDistrict($district);

        $this->render('leaderboard/district', [
            'district' => $district,
            'rankings' => $rankings
        ]);
    }
}
