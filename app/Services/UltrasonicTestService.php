<?php

namespace App\Services;

use App\Models\UltrasonicTest;
use App\Repositories\UltrasonicTestRepositoryInterface;
use Throwable;

class UltrasonicTestService
{
    private const DEFECT_LIMITS = [
        'A' => [
            'A' => ['max_panjang_cacat' => 3.00, 'max_echo_ratio' => 0.60],
            'B' => ['max_panjang_cacat' => 4.00, 'max_echo_ratio' => 0.70],
        ],
        'B' => [
            'A' => ['max_panjang_cacat' => 4.00, 'max_echo_ratio' => 0.70],
            'B' => ['max_panjang_cacat' => 5.00, 'max_echo_ratio' => 0.80],
        ],
        'C' => [
            'A' => ['max_panjang_cacat' => 2.00, 'max_echo_ratio' => 0.50],
            'B' => ['max_panjang_cacat' => 3.00, 'max_echo_ratio' => 0.60],
        ],
        'D' => [
            'A' => ['max_panjang_cacat' => 2.00, 'max_echo_ratio' => 0.50],
            'B' => ['max_panjang_cacat' => 3.00, 'max_echo_ratio' => 0.60],
        ],
    ];

    public function __construct(
        private readonly UltrasonicTestRepositoryInterface $ultrasonicTestRepository
    ) {
    }

    public function store(int $idInspeksi, array $data): UltrasonicTest
    {
        try {
            $tMinHitung = $this->calculateMinimumThickness((float) $data['t_origin'], (string) $data['metode_t_min']);
            $batasEfektif = max((float) $data['batas_standar'], $tMinHitung);
            $echoRatio = (float) $data['amplitudo_gema'] / (float) $data['dac_referensi'];
            $limits = $this->getDefectLimits((string) $data['level_pengujian'], (string) $data['kelas_area']);

            $thicknessFail = (float) $data['nilai_ketebalan'] < $batasEfektif;
            $defectFail = (float) $data['panjang_cacat'] > $limits['max_panjang_cacat'] || $echoRatio > $limits['max_echo_ratio'];

            $data['t_min_hitung'] = round($tMinHitung, 2);
            $data['status_hasil'] = $thicknessFail || $defectFail ? 'Fail' : 'Pass';
            $data['catatan_analisis'] = $this->buildAnalysisNote($thicknessFail, $defectFail, $batasEfektif, $echoRatio, $limits);
            $data['grafik_ultrasonik'] = $data['grafik_ultrasonik'] ?? '-';

            return $this->ultrasonicTestRepository->create($idInspeksi, $data);
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    private function calculateMinimumThickness(float $tOrigin, string $method): float
    {
        if ($method === 'bki') {
            return $tOrigin - (0.5 * sqrt($tOrigin));
        }

        return 0.9 * $tOrigin;
    }

    /**
     * @return array{max_panjang_cacat: float, max_echo_ratio: float}
     */
    private function getDefectLimits(string $levelPengujian, string $kelasArea): array
    {
        return self::DEFECT_LIMITS[$levelPengujian][$kelasArea];
    }

    /**
     * @param array{max_panjang_cacat: float, max_echo_ratio: float} $limits
     */
    private function buildAnalysisNote(bool $thicknessFail, bool $defectFail, float $batasEfektif, float $echoRatio, array $limits): string
    {
        $notes = [];

        if ($thicknessFail) {
            $notes[] = 'Ketebalan di bawah batas minimum efektif '.number_format($batasEfektif, 2).' mm';
        }

        if ($defectFail) {
            $notes[] = 'Indikasi cacat melampaui toleransi: panjang maks '.number_format($limits['max_panjang_cacat'], 2).' mm, echo ratio maks '.number_format($limits['max_echo_ratio'], 2).', aktual echo ratio '.number_format($echoRatio, 2);
        }

        if (empty($notes)) {
            $notes[] = 'Ketebalan dan indikasi cacat masih dalam batas toleransi.';
        }

        return implode(' | ', $notes);
    }
}
