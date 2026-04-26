<?php
declare(strict_types=1);

function bids_stats(array $bids): array {
    if ($bids === []) return ['min'=>0,'max'=>0,'avg'=>0,'median'=>0,'rows'=>[]];
    $prices = array_map(fn($b)=>(float)$b['price'],$bids);
    sort($prices);
    $count = count($prices);
    $median = $count % 2 ? $prices[intdiv($count,2)] : (($prices[$count/2-1]+$prices[$count/2])/2);
    $avg = array_sum($prices)/$count;
    $rows = [];
    foreach ($bids as $b) {
        $p=(float)$b['price'];
        $label = $p > $avg*1.2 ? 'Per brangu' : ($p < $avg*0.8 ? 'Pigu' : 'Normalu');
        $rows[] = $b + ['delta_avg'=>round($p-$avg,2),'indicator'=>$label];
    }
    return ['min'=>min($prices),'max'=>max($prices),'avg'=>round($avg,2),'median'=>round($median,2),'rows'=>$rows];
}

function ai_analyze_bids(array $project, array $bids): string {
    $stats = bids_stats($bids);
    if (!config('openai_key')) {
        return "Fallback analizė: min {$stats['min']}€, max {$stats['max']}€, avg {$stats['avg']}€, median {$stats['median']}€";
    }
    return "AI analizė įjungta (MVP): rekomenduojama tikrinti garantiją, terminą ir kainos skirtumą nuo medianos {$stats['median']}€.";
}
