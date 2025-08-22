<?php
namespace App\Exports;

use App\Models\Car;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class CarsExport implements FromCollection, WithHeadings
{
    protected $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Car::query();

        if ($this->request->filled('search')) {
            $query->where('registration_number', 'like', "%{$this->request->search}%")
                  ->orWhere('model', 'like', "%{$this->request->search}%");
        }
        if ($this->request->filled('brand')) {
            $query->where('brand', $this->request->brand);
        }

        return $query->select('registration_number','brand','model','year','mileage')->get();
    }

    public function headings(): array
    {
        return ['Registration', 'Brand', 'Model', 'Year', 'Mileage'];
    }
}
