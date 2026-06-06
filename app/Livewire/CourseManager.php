<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CourseManager extends Component
{
    // Form State untuk Tambah Mata Kuliah Baru
    public $code, $name, $sks = 2, $category = 'Engineering Topics', $semester = 0;

    protected $rules = [
        'code' => 'required|unique:courses,code',
        'name' => 'required|min:3',
        'sks' => 'required|numeric|min:1|max:6',
        'category' => 'required',
        'semester' => 'required|integer|between:0,8',
    ];

    // Fungsi untuk menambah item baru
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

        // Reset Form
        $this->reset(['code', 'name', 'sks', 'category', 'semester']);
        
        session()->flash('message', 'Mata kuliah berhasil ditambahkan!');
    }

    // Fungsi vital untuk menangani Drag and Drop perubahan urutan & semester
    public function updateCourseOrder($groups)
    {
        foreach ($groups as $group) {
            $semesterId = $group['value']; // Ini mewakili nomor semester (0-8)
            
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
        // Ambil semua data matkul yang sudah diurutkan
        $courses = Course::orderBy('sort_order')->get();

        return view('livewire.course-manager', [
            'courses' => $courses
        ])->layout('layouts.app'); // Menggunakan master layout
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
}