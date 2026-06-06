<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Abaikan jika kolom kode atau nama kosong
        if (empty($row['kode_mk']) || empty($row['nama_matakuliah'])) {
            return null;
        }

        // Cek jika data dengan kode mata kuliah ini sudah ada, lewati agar tidak duplikat
        $existingCourse = Course::where('code', trim($row['kode_mk']))->first();
        if ($existingCourse) {
            return null;
        }

        // Ambil urutan terakhir untuk plotting semester tersebut
        $semester = isset($row['semester']) ? (int)$row['semester'] : 0;
        $maxOrder = Course::where('semester', $semester)->max('sort_order') ?? 0;

        return new Course([
            'code'       => trim($row['kode_mk']),
            'name'       => trim($row['nama_matakuliah']),
            'sks'        => (int)$row['sks'],
            'category'   => trim($row['kategori']), // Harus sesuai: General Education, Basic Science & Math, atau Engineering Topics
            'semester'   => $semester,
            'sort_order' => $maxOrder + 1,
        ]);
    }
}