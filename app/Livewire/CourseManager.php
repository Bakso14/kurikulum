<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithFileUploads; // 1. Tambahkan ini
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CourseImport;

class CourseManager extends Component
{
    use WithFileUploads; // 2. Gunakan trait ini

    // Form State Tambah Manual
    public $code, $name, $sks = 2, $category = 'Engineering Topics', $semester = 0;

    // State untuk file excel
    public $excelFile;

    protected $rules = [
        'code' => 'required|unique:courses,code',
        'name' => 'required|min:3',
        'sks' => 'required|numeric|min:1|max:6',
        'category' => 'required',
        'semester' => 'required|integer|between:0,8',
    ];

    public function addCourse()
    {
        $this->validate();

        Course::create([
            'code' => $this->code,
            'name' => $this->name,
            'sks' => $this->sks,
            'category' => $this->category,
            'semester' => $this->semester,
            'sort_order' => Course::where('semester', $this->semester)->max('sort_order') + 1
        ]);

        $this->reset(['code', 'name', 'sks', 'category', 'semester']);
        session()->flash('message', 'Mata kuliah berhasil ditambahkan!');
    }

    // 3. FUNGSI BARU: Untuk memproses upload Excel
    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|mimes:xlsx,xls|max:10240', // Maksimal 10MB
        ]);

        try {
            Excel::import(new CourseImport, $this->excelFile->getRealPath());
            
            $this->reset('excelFile');
            session()->flash('message', 'Data Excel berhasil diimport ke Database!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengimport file. Pastikan format header template sudah sesuai.');
        }
    }

    public function updateCourseOrder($groups)
    {
        foreach ($groups as $group) {
            $semesterId = $group['value'];
            foreach ($group['items'] as $item) {
                Course::where('id', $item['value'])->update([
                    'semester' => $semesterId,
                    'sort_order' => $item['order']
                ]);
            }
        }
    }

    public function render()
    {
        $courses = Course::orderBy('sort_order')->get();

        return view('livewire.course-manager', [
            'courses' => $courses
        ])->layout('layouts.app');
    }

    // Fungsi Baru untuk Sinkronisasi Drag & Drop Native
    public function updateCourseOrderNative($items)
    {
        foreach ($items as $item) {
            \App\Models\Course::where('id', $item['id'])->update([
                'semester' => $item['semester'],
                'sort_order' => $item['sort_order']
            ]);
        }
        
        // Refresh data tanpa reload halaman
        $this->dispatch('smooth-refresh');
    }
    
    // Fungsi untuk menghapus satu mata kuliah tertentu
    public function deleteCourse($id)
    {
        \App\Models\Course::destroy($id);
        
        // Opsional: berikan pesan sukses
        session()->flash('message', 'Mata kuliah berhasil dihapus.');
    }

    // Fungsi untuk menghapus seluruh data mata kuliah (Reset)
    public function deleteAllCourses()
    {
        \App\Models\Course::truncate(); // Menghapus semua data di tabel
        
        session()->flash('message', 'Semua data mata kuliah telah dibersihkan.');
    }
}