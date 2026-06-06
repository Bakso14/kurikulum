<div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Simulasi Plotting & Urutan Mata Kuliah</h1>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-5 rounded-lg shadow-md h-fit lg:sticky lg:top-6 space-y-6">
            <div>
                <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Tambah Matakuliah</h2>
                <form wire:submit.prevent="addCourse" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Kode MK</label>
                        <input type="text" wire:model="code" class="w-full p-2 border rounded mt-1 text-sm focus:outline-blue-500">
                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Nama Matakuliah</label>
                        <input type="text" wire:model="name" class="w-full p-2 border rounded mt-1 text-sm focus:outline-blue-500">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600">SKS</label>
                            <input type="number" wire:model="sks" class="w-full p-2 border rounded mt-1 text-sm focus:outline-blue-500">
                            @error('sks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600">Plot Awal Sem</label>
                            <select wire:model="semester" class="w-full p-2 border rounded mt-1 text-sm focus:outline-blue-500">
                                <option value="0">Belum diplot</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Kategori</label>
                        <select wire:model="category" class="w-full p-2 border rounded mt-1 text-sm focus:outline-blue-500">
                            <option value="General Education">General Education</option>
                            <option value="Basic Science & Math">Basic Science & Math</option>
                            <option value="Engineering Topics">Engineering Topics</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded shadow transition text-sm">
                        + Simpan Matakuliah
                    </button>
                </form>
            </div>

            <div class="border-t pt-4">
                <h2 class="text-md font-bold mb-2 text-gray-700 flex items-center justify-between">
                    <span>Import dari Excel</span>
                    <span class="text-xs text-blue-500 font-normal italic">Format: .xlsx / .xls</span>
                </h2>
                
                <form wire:submit.prevent="importExcel" class="space-y-3">
                    <div>
                        <input type="file" wire:model="excelFile" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        @error('excelFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded shadow transition text-xs">
                        Unggah & Proses Data
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
            
            @foreach(range(0, 8) as $sem)
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
                     
                    <div class="flex justify-between items-center mb-3 border-b pb-2">
                        <h3 class="font-bold text-gray-700">
                            {{ $sem === 0 ? 'Daftar Antrean / Peminatan' : 'Semester ' . $sem }}
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            Total: {{ $courses->where('semester', $sem)->sum('sks') }} SKS
                        </span>
                    </div>

                    <div data-semester="{{ $sem }}" 
                         class="native-sortable-list space-y-2 min-h-[150px] border-2 border-dashed border-gray-200 rounded-lg p-2 bg-gray-100/50"
                         x-init="
                            new Sortable($el, {
                                group: 'shared-kurikulum',
                                animation: 150,
                                ghostClass: 'opacity-30',
                                dragClass: 'shadow-2xl',
                                onEnd: function (evt) {
                                    let serializedItems = [];
                                    document.querySelectorAll('.native-sortable-list').forEach(listContainer => {
                                        let currentSemester = listContainer.getAttribute('data-semester');
                                        listContainer.querySelectorAll('[data-course-id]').forEach((card, index) => {
                                            serializedItems.push({
                                                id: card.getAttribute('data-course-id'),
                                                semester: currentSemester,
                                                sort_order: index + 1
                                            });
                                        });
                                    });
                                    $wire.updateCourseOrderNative(serializedItems);
                                }
                            });
                         ">
                        
                        @foreach($courses->where('semester', $sem) as $course)
                            <div data-course-id="{{ $course->id }}" 
                                 @class([
                                     'p-3 bg-white rounded-md shadow-sm border-l-4 cursor-grab active:cursor-grabbing hover:shadow-md transition duration-150',
                                     'border-green-500' => $course->category == 'General Education',
                                     'border-orange-500' => $course->category == 'Basic Science & Math',
                                     'border-blue-500' => $course->category == 'Engineering Topics',
                                 ])>
                                 
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 block font-mono">{{ $course->code }}</span>
                                        <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $course->name }}</h4>
                                    </div>
                                    <span class="text-xs font-extrabold px-2 py-0.5 bg-gray-200 text-gray-700 rounded">
                                        {{ $course->sks }} SKS
                                    </span>
                                </div>
                                <div class="mt-2 text-[11px] text-gray-500 italic">
                                    Category: {{ $course->category }}
                                </div>
                            </div>
                        @endforeach

                        @if($courses->where('semester', $sem)->count() == 0)
                            <p class="text-center text-xs text-gray-400 py-8 pointer-events-none">Kosong. Seret ke sini.</p>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>