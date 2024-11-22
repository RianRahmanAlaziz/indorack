<!-- BEGIN: Modal Edit Content -->
@foreach ($files as $file)
    <div id="edit-{{ $file->id }}" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Edit Files</h2>
                    <div class="dropdown sm:hidden">
                        <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false"
                            data-tw-toggle="dropdown">
                            <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i>
                        </a>
                    </div>
                </div>
                <!-- END: Modal Header -->
                <form action="/dashboard/file-manager/{{ $file->id }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-12">
                            <label for="nfile" class="block text-sm font-medium text-gray-700 mt-2">Nama File</label>
                            <input id="nfile" name="nfile" type="text" class="form-control"
                                placeholder="Nama File" value="{{ old('nfile', $file->nfile) }}">
                            @error('nfile')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <div class="mt-3">
                                <div class="flex flex-col sm:flex-row mt-2">
                                    <div class="form-check mr-2">
                                        <input id="gambar-{{ $file->id }}" class="form-check-input" type="radio"
                                            name="category" value="gambar"
                                            {{ old('category', $file->category) == 'gambar' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gambar-{{ $file->id }}">Gambar</label>
                                    </div>
                                    <div class="form-check mr-2 mt-2 sm:mt-0">
                                        <input id="video-{{ $file->id }}" class="form-check-input" type="radio"
                                            name="category" value="video"
                                            {{ old('category', $file->category) == 'video' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="video-{{ $file->id }}">Video</label>
                                    </div>
                                    @error('category')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div id="url-input-container-{{ $file->id }}"
                            class="col-span-12 sm:col-span-12 {{ old('category', $file->category) == 'video' ? '' : 'hidden' }}">
                            <label for="url-{{ $file->id }}"
                                class="block text-sm font-medium text-gray-700 mt-2">URL Video</label>
                            <input type="text" id="url-{{ $file->id }}" name="url" class="form-control"
                                placeholder="Masukkan URL video" value="{{ old('url', $file->url) }}">
                        </div>
                        <div class="col-span-12 sm:col-span-12">
                            <label for="gambar-{{ $file->id }}" class="form-label">Upload Image</label>
                            <div class="border-2 border-dashed dark:border-darkmode-400 rounded-md pt-4">
                                <div class="flex flex-wrap px-4 cursor-pointer" id="preview-{{ $file->id }}">
                                    <!-- Pratinjau Gambar Akan Ditambahkan di Sini Secara Dinamis -->
                                    @if ($file->gambar)
                                        <div class="w-24 h-24 relative image-fit mb-5 mr-5 cursor-pointer zoom-in">
                                            <img class="rounded-md" alt="Preview Image"
                                                src="/assets/images/file-manager/{{ $file->gambar }}">
                                            <div title="Remove this image?"
                                                class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"
                                                onclick="remove({{ $file->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 cursor-pointer"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="px-4 pb-4 flex items-center relative cursor-pointer">
                                    <i data-lucide="image" class="w-4 h-4 mr-2 cursor-pointer"></i>
                                    <span class="text-primary mr-1 cursor-pointer">Upload a file</span>
                                    or drag and drop
                                    <input id="gambar-input-{{ $file->id }}" name="gambar" type="file"
                                        accept="image/*"
                                        class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer"
                                        onchange="preview(event, {{ $file->id }})">
                                </div>
                            </div>
                            @error('gambar')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-20">Send</button>
                    </div>
                    <!-- END: Modal Footer -->
                </form>
            </div>
        </div>
    </div>
@endforeach
<!-- END: Modal Content -->

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fungsi untuk menampilkan input URL jika kategori "Video" dipilih
            document.querySelectorAll('[id^="video-"]').forEach((radioVideo) => {
                const fileId = radioVideo.id.split('-')[1]; // Ambil ID file dari ID radio
                const radioGambar = document.getElementById('gambar-' + fileId);
                const urlInputContainer = document.getElementById('url-input-container-' + fileId);

                radioGambar.addEventListener('change', () => toggleUrlInput(fileId));
                radioVideo.addEventListener('change', () => toggleUrlInput(fileId));

                function toggleUrlInput(fileId) {
                    if (radioVideo.checked) {
                        urlInputContainer.classList.remove('hidden');
                    } else {
                        urlInputContainer.classList.add('hidden');
                    }
                }
            });

            document.querySelectorAll('[id^="gambar-input-"]').forEach((inputFile) => {
                const fileId = inputFile.id.split('-')[2]; // Ambil ID file dari ID input file
                const previewContainer = document.getElementById('preview-' + fileId);

                // Menambahkan event listener untuk onchange input file
                inputFile.addEventListener('change', (event) => {
                    preview(event, fileId); // Panggil fungsi preview dengan fileId yang sesuai
                });

                // Fungsi untuk menampilkan pratinjau gambar
                function preview(event, fileId) {
                    previewContainer.innerHTML = ""; // Bersihkan pratinjau sebelumnya

                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const imagePreview = document.createElement("div");
                            imagePreview.className =
                                "w-24 h-24 relative image-fit mb-5 mr-5 cursor-pointer zoom-in";
                            imagePreview.innerHTML = `
                        <img class="rounded-md" alt="Preview Image" src="${e.target.result}">
                        <div title="Remove this image?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2" onclick="remove(${fileId})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 cursor-pointer" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </div>
                    `;
                            previewContainer.appendChild(imagePreview); // Menambahkan pratinjau gambar
                        };

                        reader.readAsDataURL(file); // Mengubah file gambar menjadi data URL untuk preview
                    }
                }
            });

            // Fungsi untuk menghapus pratinjau gambar
            window.remove = function(fileId) {
                const previewContainer = document.getElementById("preview-" + fileId);
                previewContainer.innerHTML = ""; // Hapus pratinjau gambar

                const fileInput = document.getElementById("gambar-input-" + fileId);
                fileInput.value = ""; // Reset input file
            }

            // Inisialisasi ikon lucide
            lucide.createIcons();
        });
    </script>
@endpush
