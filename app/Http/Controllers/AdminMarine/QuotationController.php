<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationSubItem;
use App\Models\QuotationItem;
use App\Models\QuotationTermsCondition;
use App\Models\MitraMarine;
use App\Models\VesselMitra;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with(['mitra','vessel','subItems.items'])
            ->latest()
            ->get();

        return view('admin_marine.quotation.index', compact('quotations'));
    }

    public function create()
    {
        $mitras = MitraMarine::all();
        $vessels = VesselMitra::all();

        return view('admin_marine.quotation.create', compact('mitras','vessels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mitra_id'  => 'required',
            'vessel_id' => 'required',
            'date'      => 'required|date',
        ]);

        $quotation = Quotation::create([
            'mitra_id'  => $request->mitra_id,
            'vessel_id' => $request->vessel_id,
            'attention' => $request->attention,
            'quote_no'  => $this->generateQuoteNumber(),
            'date'      => $request->date,
            'project'   => $request->project,
            'place'     => $request->place,
        ]);

        return redirect()->route('quotations.edit', $quotation->id);
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('mitra','vessel','subItems.items','termsConditions');

        return view('admin_marine.quotation.edit', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $quotation->update([
            'attention' => $request->attention,
            'project'   => $request->project,
            'place'     => $request->place,
            'date'      => $request->date,
        ]);

        return back()->with('success','Header updated');
    }

    /*
    |--------------------------------------------------------------------------
    | BULK SAVE (NO RELOAD ADD)
    |--------------------------------------------------------------------------
    */
    public function bulkSave(Request $request, Quotation $quotation)
    {
        DB::beginTransaction();

        try {

            // hapus lama
            $quotation->subItems()->delete();
            $quotation->termsConditions()->delete();

            if ($request->sub_items) {

                foreach ($request->sub_items as $sub) {

                    $subItem = QuotationSubItem::create([
                        'quotation_id' => $quotation->id,
                        'name'         => $sub['name'],
                        'item_type'    => $sub['item_type'],
                    ]);

                    foreach ($sub['items'] as $item) {

                        $price = $item['price'] ?? 0;
$qty   = $item['qty'] ?? 0;
$day   = $item['day'] ?? 0;
$hour  = $item['hour'] ?? 0;

$total = 0;

if($sub['item_type'] == 'basic'){
    $total = $price * $qty;
}

if($sub['item_type'] == 'day'){
    $total = $price * $qty * $day;
}

if($sub['item_type'] == 'hour'){
    $total = $price * $qty * $hour;
}

if($sub['item_type'] == 'day_hour'){
    if($hour > 0){
        $total = $price * $qty * $day * $hour;
    } else {
        $total = $price * $qty * $day;
    }
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

            if ($request->terms) {
                foreach ($request->terms as $term) {
                    QuotationTermsCondition::create([
                        'quotation_id' => $quotation->id,
                        'description'  => $term,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['success'=>true]);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

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

   public function show(Quotation $quotation)
    {
        $quotation->load([
            'mitra',
            'vessel',
            'subItems.items',
            'termsConditions' // ✅ SESUAI MODEL
        ]);

        return view('admin_marine.quotation.show', compact('quotation'));
    }

    public function destroy(Quotation $quotation)
{
    DB::beginTransaction();

    try {

        // Hapus semua items di dalam subItems
        foreach ($quotation->subItems as $sub) {
            $sub->items()->delete();
        }

        // Hapus subItems
        $quotation->subItems()->delete();

        // Hapus terms & conditions
        $quotation->termsConditions()->delete();

        // Hapus quotation
        $quotation->delete();

        DB::commit();

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation berhasil dihapus');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}


}
