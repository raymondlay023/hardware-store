<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductAlias;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;
use Illuminate\Support\Str;

class ProductsImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    public $importedCount = 0;

    public $errors = [];

    public $failures = [];

    public function model(array $row)
    {
        // normalize keys (trim and lower) just in case
        $originalName = trim($row['nama'] ?? $row['name'] ?? '');
        if (empty($originalName)) {
            return null;
        }

        $supplierId = null;
        if (! empty($row['supplier'])) {
            $supplier = Supplier::where('name', 'like', '%'.trim($row['supplier']).'%')->first();
            $supplierId = $supplier?->id;
        }

        $stock = isset($row['stok']) ? intval($row['stok']) : (isset($row['stock']) ? intval($row['stock']) : 0);
        
        $unit = trim($row['uom'] ?? $row['unit'] ?? 'piece');
        $unit = strtolower($unit);
        if (empty($unit)) $unit = 'piece';

        $category = trim($row['kategori'] ?? $row['category'] ?? 'General');
        if (empty($category)) $category = 'General';

        $price = isset($row['price']) ? floatval($row['price']) : 0;
        
        // Handle brand
        $brand = !empty($row['brand']) ? trim($row['brand']) : null;

        // Auto-reorder defaults
        $autoReorder = filter_var($row['auto_reorder'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // --- TRANSFORMATION LOGIC START ---
        $transformation = $this->transformNameAndAliases($originalName, $row['keterangan'] ?? '');
        $finalName = $transformation['name'];
        $generatedAliases = $transformation['aliases'];
        // --- TRANSFORMATION LOGIC END ---

        $product = new Product([
            'name' => $finalName,
            'brand' => $brand,
            'category' => $category,
            'unit' => $unit,
            'price' => $price,
            'current_stock' => $stock,
            'supplier_id' => $supplierId,
            'low_stock_threshold' => intval($row['low_stock_threshold'] ?? 10),
            'critical_stock_threshold' => intval($row['critical_stock_threshold'] ?? 5),
            'auto_reorder_enabled' => $autoReorder,
            'reorder_quantity' => ! empty($row['reorder_quantity']) ? intval($row['reorder_quantity']) : null,
        ]);

        $product->save();

        // Handle Aliases
        // Merge generated aliases with any explicit 'aliases' column from CSV
        if (!empty($row['aliases'])) {
            $cols = explode(',', $row['aliases']);
            foreach ($cols as $alias) {
                $generatedAliases[] = trim($alias);
            }
        }

        $uniqueAliases = array_unique(array_filter($generatedAliases));

        foreach ($uniqueAliases as $alias) {
            // Don't add alias if it's exactly the same as the name
            if (strcasecmp($alias, $finalName) === 0) continue;

            ProductAlias::create([
                'product_id' => $product->id,
                'alias' => $alias,
            ]);
        }

        $this->importedCount++;

        return $product;
    }

    /**
     * Transforms local name to Industrial Standard and generates aliases.
     */
    private function transformNameAndAliases(string $name, ?string $description): array
    {
        $lowerName = strtolower($name);
        $aliases = [$name]; // Always keep original name as alias
        
        if (!empty($description)) {
            // Split description by comma and add to aliases
            $parts = explode(',', $description);
            foreach($parts as $part) $aliases[] = trim($part);
        }

        $newName = $name; // Default to original

        // --- LOGIC RULES ---
        
        // 1. Steel / Besi
        if (str_contains($lowerName, 'besi')) {
            if (str_contains($lowerName, 'ulir')) {
                // besi diameter 13mm ulir -> Deformed Bar 13mm
                $newName = preg_replace('/besi.*diameter\s*(\d+(\.\d+)?\s*mm).*ulir/i', 'Deformed Bar $1 (Ulir)', $name);
                $aliases[] = 'Besi Ulir';
                $aliases[] = 'Besi Sirip';
            } elseif (str_contains($lowerName, 'diameter')) {
                // besi diameter 10mm -> Steel Rebar 10mm (Polos)
                $newName = preg_replace('/besi.*diameter\s*(\d+(\.\d+)?\s*mm)/i', 'Steel Rebar $1 (Polos)', $name);
                $aliases[] = 'Besi Beton';
                $aliases[] = 'Besi Polos';
            }
        }

        // 2. Bondek / Spandek
        if (str_contains($lowerName, 'bondek')) {
            $newName = preg_replace('/bondek/i', 'Flooring Deck', $name); // bondek 6 meter -> Flooring Deck 6 meter
            $aliases[] = 'Bondek';
            $aliases[] = 'Penyangga Cor';
        }
        if (str_contains($lowerName, 'spandek')) {
            if (str_contains($lowerName, 'pasir')) {
                $newName = str_ireplace('spandek pasir', 'Sand-Coated Metal Roofing', $name);
                $aliases[] = 'Atap Pasir';
            } else {
                $newName = str_ireplace('spandek', 'Metal Roofing', $name);
            }
            $aliases[] = 'Spandek';
            $aliases[] = 'Galvalum';
        }

        // 3. Atap PVC / Alderon-like
        if (str_contains($lowerName, 'atap pvc')) {
            $newName = str_ireplace('atap pvc', 'UPVC Roofing', $name);
            $aliases[] = 'Alderon'; 
            $aliases[] = 'Atap Dingin';
        }

        // 4. Baja Ringan / Reng
        if (str_contains($lowerName, 'baja ringan')) {
            $newName = str_ireplace('baja ringan', 'Light Steel Truss C75', $name);
            $aliases[] = 'Kanal C';
            $aliases[] = 'Baja Ringan';
            $aliases[] = 'Kasau';
        }
        if (str_contains($lowerName, 'reng baja')) {
            $newName = str_ireplace('reng baja', 'Light Steel Batten (Reng)', $name);
            $aliases[] = 'Reng Baja';
            $aliases[] = 'Profil R';
        }

        // 5. Pipa Rucika / PVC
        if (str_contains($lowerName, 'pipa') && (str_contains($lowerName, 'rucika') || str_contains($lowerName, 'maspion') || str_contains($lowerName, 'pvc') || str_contains($lowerName, 'vertu'))) {
            // Extract Type D or AW
            $type = '';
            if (preg_match('/\b(aw|d)\b/i', $name, $matches)) {
                $type = strtoupper($matches[1]);
            }
            
            $baseName = 'PVC Pipe';
            if ($type === 'AW') $baseName .= ' AW (Pressure)';
            elseif ($type === 'D') $baseName .= ' D (Drainage)';
            else $baseName .= ' (Standard)';

            // Clean up old name parts to construct new one is hard strictly via regex replacement
            // Use simple string append for specific dimension if possible, or just replace key terms
            // Let's just prepend "PVC Pipe" and keep specs
            
            // Try to standardize "pipa rucika D 4 inch" -> "PVC Pipe D 4 inch"
            $newName = preg_replace('/pipa\s+\w+\s+/i', 'PVC Pipe ', $name);
            $aliases[] = 'Pralon';
            $aliases[] = 'Pipa Air';
        }

        // 6. Bata Ringan / Hebel
        if (str_contains($lowerName, 'bata ringan') || str_contains($lowerName, 'hebel')) {
            $newName = preg_replace('/(bata ringan\/hebel|bata ringan|hebel)/i', 'AAC Block', $name);
            $aliases[] = 'Hebel';
            $aliases[] = 'Bata Putih';
            $aliases[] = 'Bata Ringan';
        }

        // 7. Paints
        if (str_starts_with($lowerName, 'cat tembok')) {
            $newName = str_ireplace('cat tembok', 'Wall Paint', $name);
            $aliases[] = 'Cat Dinding';
        } elseif (str_starts_with($lowerName, 'cat ')) {
            $newName = str_ireplace('cat ', 'Paint ', $name);
        }

        // 8. Thinner
        if (str_contains($lowerName, 'thinner')) {
             $newName = str_ireplace('thinner', 'Paint Thinner', $name);
             $aliases[] = 'Pengencer Cat';
        }
        
        // 9. MCB
        if (str_contains($lowerName, 'mcb')) {
            $newName = str_ireplace('mcb', 'Miniature Circuit Breaker (MCB)', $name);
            $aliases[] = 'Sekring';
            $aliases[] = 'Pemutus Arus';
        }
        
        // 10. Fischer / Wall Plug
        if (str_contains($lowerName, 'fischer') || str_contains($lowerName, 'viser')) {
             $newName = str_ireplace(['fischer', 'viser'], 'Wall Plug (Fischer)', $name);
             $aliases[] = 'Paku Tembok Plastik';
        }

        return [
            'name' => trim($newName),
            'aliases' => $aliases
        ];
    }


    public function rules(): array
    {
        return [
            // Accept either 'name' or 'nama'
            '*.nama' => 'required_without:*.name|nullable|string|max:255',
            '*.name' => 'required_without:*.nama|nullable|string|max:255',
            
            'brand' => 'nullable|string|max:100',
            
            // Category optional (default provided)
            'category' => 'nullable|string|max:100',
            'kategori' => 'nullable|string|max:100', // alias
            
            // Unit optional
            'unit' => 'nullable|string',
            'uom' => 'nullable|string',
            
            // Price optional
            'price' => 'nullable|numeric|min:0',
            
            'stock' => 'nullable|integer|min:0',
            'stok' => 'nullable|integer|min:0', // alias
            
            'supplier' => 'nullable|string',
        ];
    }
    
    // Allow broader set of units implicitly, or we can enforce enum if rigorous validation needed. 
    // For now, removing the strict 'in:...' validation to allow 'batang', 'lembar', etc.

    public function customValidationMessages()
    {
        return [
            '*.nama.required_without' => 'Product name (nama) is required',
            'price.numeric' => 'Price must be a number',
        ];
    }

    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }
    }
}
