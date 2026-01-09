<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Generate Sale Receipt PDF
     */
    public function saleReceipt(Sale $sale)
    {
        $sale->load('saleItems.product', 'customer');

        $pdf = Pdf::loadView('pdf.sale-receipt', [
            'sale' => $sale,
        ]);

        $pdf->setPaper('a5', 'portrait');

        return $pdf->download('receipt-' . $sale->id . '.pdf');
    }

    /**
     * View Sale Receipt PDF in browser
     */
    public function viewSaleReceipt(Sale $sale)
    {
        $sale->load('saleItems.product', 'customer');

        $pdf = Pdf::loadView('pdf.sale-receipt', [
            'sale' => $sale,
        ]);

        $pdf->setPaper('a5', 'portrait');

        return $pdf->stream('receipt-' . $sale->id . '.pdf');
    }

    /**
     * Generate Purchase Order PDF
     */
    public function purchaseOrder(Purchase $purchase)
    {
        $purchase->load('purchaseItems.product', 'supplier');

        $pdf = Pdf::loadView('pdf.purchase-order', [
            'purchase' => $purchase,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('purchase-order-' . $purchase->id . '.pdf');
    }

    /**
     * View Purchase Order PDF in browser
     */
    public function viewPurchaseOrder(Purchase $purchase)
    {
        $purchase->load('purchaseItems.product', 'supplier');

        $pdf = Pdf::loadView('pdf.purchase-order', [
            'purchase' => $purchase,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('purchase-order-' . $purchase->id . '.pdf');
    }

    /**
     * Generate Invoice PDF
     */
    public function invoice(Sale $sale)
    {
        $sale->load('saleItems.product', 'customer');

        $pdf = Pdf::loadView('pdf.invoice', [
            'sale' => $sale,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $sale->id . '.pdf');
    }
}
