<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationSubItem;
use App\Models\QuotationItem;
use App\Models\QuotationTermsCondition;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class QuotationController extends Controller
{

    /* ================= INDEX ================= */
    public function index()
    {
        $quotations = Quotation::latest()->get();

        return view('admin_marine.quotation.index', compact('quotations'));
    }


    /* ================= CREATE ================= */
    public function create()
    {
        $quotation = new Quotation();

        return view('admin_marine.quotation.create', compact('quotation'));
    }


    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'mitra_name'  => 'required|string|max:255',
                'vessel_name' => 'required|string|max:255',
                'date'        => 'required|date',
            ]);

            $quotation = Quotation::create([
                'mitra_name'  => $request->mitra_name,
                'vessel_name' => $request->vessel_name,
                'attention'   => $request->attention,
                'quote_no'    => $this->generateQuoteNumber(),
                'date'        => $request->date,
                'project'     => $request->project,
                'place'       => $request->place,
            ]);

            $this->saveDetail(
                $quotation,
                $request->input('sub_items', []),
                $request->input('terms', [])
            );

            DB::commit();

            return redirect()
                ->route('quotations.index')
                ->with('success','Quotation berhasil dibuat');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    /* ================= EDIT ================= */
    public function edit(Quotation $quotation)
    {
        $quotation->load('subItems.items','termsConditions');

        return view('admin_marine.quotation.edit', compact('quotation'));
    }


    /* ================= UPDATE ================= */
    public function update(Request $request, Quotation $quotation)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'mitra_name'  => 'required|string|max:255',
                'vessel_name' => 'required|string|max:255',
                'date'        => 'required|date',
            ]);

            $quotation->update([
                'mitra_name'  => $request->mitra_name,
                'vessel_name' => $request->vessel_name,
                'attention'   => $request->attention,
                'project'     => $request->project,
                'place'       => $request->place,
                'date'        => $request->date,
            ]);

            // Hapus detail lama
            foreach ($quotation->subItems as $sub) {
                $sub->items()->delete();
            }

            $quotation->subItems()->delete();
            $quotation->termsConditions()->delete();

            $this->saveDetail(
                $quotation,
                $request->input('sub_items', []),
                $request->input('terms', [])
            );

            DB::commit();

            return redirect()
                ->route('quotations.index')
                ->with('success','Quotation berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    /* ================= SHOW ================= */
    public function show(Quotation $quotation)
    {
        $quotation->load('subItems.items','termsConditions');

        return view('admin_marine.quotation.show', compact('quotation'));
    }


    /* ================= PRINT ================= */
    public function print(Quotation $quotation)
    {
        $quotation->load('subItems.items','termsConditions');

        $quotationDate = $quotation->date
            ? Carbon::parse($quotation->date)
            : Carbon::parse($quotation->created_at);

        $grandTotal = $quotation->subItems
            ->sum(fn($s) => $s->items->sum('total'));

        $pdf = Pdf::loadView('admin_marine.quotation.print', [
            'quotation'     => $quotation,
            'quotationDate' => $quotationDate,
            'grandTotal'    => $grandTotal,
        ])->setPaper('A4','portrait');

        return $pdf->stream('quotation-'.$quotation->id.'.pdf');
    }


    /* ================= DELETE ================= */
    public function destroy(Quotation $quotation)
    {
        DB::beginTransaction();

        try {

            foreach ($quotation->subItems as $sub) {
                $sub->items()->delete();
            }

            $quotation->subItems()->delete();
            $quotation->termsConditions()->delete();
            $quotation->delete();

            DB::commit();

            return redirect()
                ->route('quotations.index')
                ->with('success','Quotation berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }


    /* ================= HELPER SAVE DETAIL ================= */
    private function saveDetail($quotation, $subItems = [], $terms = [])
    {
        if (!empty($subItems)) {

            foreach ($subItems as $sub) {

                if(empty($sub['name'])) continue;

                $subItem = QuotationSubItem::create([
                    'quotation_id' => $quotation->id,
                    'name'         => $sub['name'],
                    'item_type'    => $sub['item_type'] ?? 'basic',
                ]);

                if(!empty($sub['items'])) {

                    foreach ($sub['items'] as $item) {

                        if(empty($item['item'])) continue;

                        $price = (float) ($item['price'] ?? 0);
                        $qty   = (float) ($item['qty'] ?? 0);
                        $day   = (float) ($item['day'] ?? 0);
                        $hour  = (float) ($item['hour'] ?? 0);

                        switch($sub['item_type']){

                            case 'day':
                                $total = $price * $qty * $day;
                            break;

                            case 'hour':
                                $total = $price * $qty * $hour;
                            break;

                            case 'day_hour':
                                $total = $hour > 0
                                    ? $price * $qty * $day * $hour
                                    : $price * $qty * $day;
                            break;

                            default:
                                $total = $price * $qty;
                            break;
                        }

                        QuotationItem::create([
                            'sub_item_id' => $subItem->id,
                            'item'        => $item['item'],
                            'price'       => $price,
                            'qty'         => $qty,
                            'unit'        => $item['unit'] ?? null,
                            'day'         => $day ?: null,
                            'hour'        => $hour ?: null,
                            'total'       => $total,
                        ]);
                    }
                }
            }
        }

        if (!empty($terms)) {
            foreach ($terms as $term) {
                if(!empty($term)){
                    QuotationTermsCondition::create([
                        'quotation_id' => $quotation->id,
                        'description'  => $term,
                    ]);
                }
            }
        }
    }


    /* ================= GENERATE QUOTE NO ================= */
    private function generateQuoteNumber()
    {
        $year  = date('Y');
        $month = date('n');

        $count  = Quotation::whereYear('created_at',$year)->count()+1;
        $number = str_pad($count,3,'0',STR_PAD_LEFT);

        return $number.'/BJM/'.$this->romawi($month).'/'.$year;
    }

    private function romawi($bulan)
    {
        $map = [
            1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
            7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
        ];
        return $map[$bulan];
    }

}
