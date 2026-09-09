<?php
class ScreenerModel {
    public function evaluateEligibility($age, $weightKg, $hemoglobin, $hasInfection, $recentTattoo, $medication) {
        $reasons = [];

        if ($age < 18 || $age > 65) $reasons[] = "Age must be between 18 and 65 years.";
        if ($weightKg < 50) $reasons[] = "Weight must be at least 50 kg.";
        if ($hemoglobin < 12.5) $reasons[] = "Hemoglobin level must be at least 12.5 g/dL.";
        if ($hasInfection) $reasons[] = "Active infections or cold symptoms disqualify donation.";
        if ($recentTattoo) $reasons[] = "Must wait 6 months after getting a tattoo or piercing.";
        if ($medication) $reasons[] = "Certain active antibiotics or medications defer donation.";

        return [
            'eligible' => empty($reasons),
            'reasons'  => $reasons
        ];
    }
}