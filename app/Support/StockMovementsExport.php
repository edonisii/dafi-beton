<?php

namespace App\Support;

use App\Models\StockMovement;
use Illuminate\Contracts\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StockMovementsExport
{
    /**
     * Ndërton një skedar Excel (.xlsx) nga lëvizjet e stokut dhe e kthen si download.
     */
    public static function download(?Builder $query = null): BinaryFileResponse
    {
        $query ??= StockMovement::query();
        $query->with(['material', 'supplier', 'customer'])->orderByDesc('occurred_on');

        $path = tempnam(sys_get_temp_dir(), 'stok_') . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($path);

        $header = (new Style())->setFontBold();
        $writer->addRow(Row::fromValues([
            'Data',
            'Lloji',
            'Materiali',
            'Sasia',
            'Njësia',
            'Furnitori',
            'Klienti',
            'Çmimi për njësi (€)',
            'Totali (€)',
            'Shënime',
        ], $header));

        foreach ($query->cursor() as $m) {
            $writer->addRow(Row::fromValues([
                optional($m->occurred_on)->format('d.m.Y'),
                $m->type === StockMovement::TYPE_IN ? 'Hyrje' : 'Dalje',
                $m->material?->name,
                (float) $m->quantity,
                $m->material?->unit,
                $m->supplier?->name,
                $m->customer?->name,
                $m->unit_price !== null ? (float) $m->unit_price : null,
                $m->total_price !== null ? (float) $m->total_price : null,
                $m->note,
            ]));
        }

        $writer->close();

        $filename = 'levizjet-e-stokut-' . now()->format('Y-m-d') . '.xlsx';

        return response()
            ->download($path, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])
            ->deleteFileAfterSend(true);
    }
}
