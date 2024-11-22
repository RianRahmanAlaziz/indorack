<!-- BEGIN: Modal Content -->
<div id="header-footer-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">New Files</h2>
                <div class="dropdown sm:hidden"> <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"
                        aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal"
                            class="w-5 h-5 text-slate-500"></i> </a>
                </div>
            </div> <!-- END: Modal Header -->
            <form action="/dashboard/file-manager" method="post" enctype="multipart/form-data">
                @csrf
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 sm:col-span-12"> <label for="nfile"
                            class="block text-sm font-medium text-gray-700 mt-2">Nama
                            File</label>
                        <input id="nfile" name="nfile" type="text" class="form-control"
                            value="{{ old('nfile') }}" placeholder="Nama File">
                        @error('nfile')
                            <div class="text-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <div class="mt-3">
                            <div class="flex flex-col sm:flex-row mt-2">
                                <div class="form-check mr-2">
                                    <input id="gambar2" class="form-check-input" type="radio" name="category"
                                        value="gambar">
                                    <label class="form-check-label" for="gambar2">Gambar</label>
                                </div>
                                <div class="form-check mr-2 mt-2 sm:mt-0">
                                    <input id="video2" class="form-check-input" type="radio" name="category"
                                        value="video">
                                    <label class="form-check-label" for="video2">Video</label>
                                </div>
                                @error('category')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div id="url-input-container" class="col-span-12 sm:col-span-12 hidden">
                        <label for="url" class="block text-sm font-medium text-gray-700 mt-2">URL
                            Video</label>
                        <input type="text" id="url" name="url" class="form-control"
                            value="{{ old('url') }}" placeholder="Masukkan URL video">
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <label for="gambar" class="form-label">Upload Image</label>
                        <div class="border-2 border-dashed dark:border-darkmode-400 rounded-md pt-4">
                            <div class="flex flex-wrap px-4 cursor-pointer" id="preview-container3">
                                <!-- Pratinjau Gambar Akan Ditambahkan di Sini Secara Dinamis -->
                            </div>
                            <div class="px-4 pb-4 flex items-center relative cursor-pointer">
                                <i data-lucide="image" class="w-4 h-4 mr-2 cursor-pointer"></i>
                                <span class="text-primary mr-1 cursor-pointer">Upload a file</span>
                                or drag and
                                drop
                                <input id="input" name="gambar" type="file" accept="image/*"
                                    class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer"
                                    onchange="previewImage(event)" value="{{ old('gambar') }}">
                            </div>
                        </div>
                        @error('gambar')
                            <div class="text-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
                <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer"> <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 mr-1">Cancel</button> <button type="submit"
                        class="btn btn-primary w-20">Send</button>
                </div>
                <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div>
<!-- END: Modal Content -->

@push('script')
    <script>
        // Referensi elemen radio dan kontainer input URL
        const radioGambar = document.getElementById('gambar2');
        const radioVideo = document.getElementById('video2');
        const urlInputContainer = document.getElementById('url-input-container');

        // Dengarkan perubahan pada input radio
        radioGambar.addEventListener('change', toggleUrlInput);
        radioVideo.addEventListener('change', toggleUrlInput);

        function toggleUrlInput() {
            // Tampilkan input URL jika "Video" dipilih, sembunyikan jika "Gambar" dipilih
            if (radioVideo.checked) {
                urlInputContainer.classList.remove('hidden');
            } else {
                urlInputContainer.classList.add('hidden');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons(); // Ini akan memuat ikon dengan atribut data-lucide
        });
        // Fungsi untuk menampilkan pratinjau gambar yang diunggah
        function previewImage(event) {
            const previewContainer1 = document.getElementById("preview-container3");
            previewContainer1.innerHTML = ""; // Bersihkan pratinjau sebelumnya

            const file1 = event.target.files[0];
            if (file1) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imagePreview = document.createElement("div");
                    imagePreview.className = "w-24 h-24 relative image-fit mb-5 mr-5 cursor-pointer zoom-in";

                    imagePreview.innerHTML = `
        <img class="rounded-md" alt="Preview Image" src="${e.target.result}">
        <div title="Remove this image?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2" onclick="removePreview()">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 cursor-pointer" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        </div>
    `;

                    previewContainer1.appendChild(imagePreview);
                };

                reader.readAsDataURL(file1);
            }
        }

        // Fungsi untuk menghapus pratinjau
        function removePreview() {
            const previewContainer1 = document.getElementById("preview-container3");
            previewContainer1.innerHTML = ""; // Hapus pratinjau gambar

            // Menghapus elemen input file dan menambahkannya kembali
            const fileInput = document.getElementById("file");
            fileInput.value = ""; // Jika id input file adalah "file"
            const newFileInput = fileInput.cloneNode(true);
            fileInput.parentNode.replaceChild(newFileInput, fileInput);

            // Tambahkan event listener ke elemen baru
            newFileInput.addEventListener("change", previewImage);
        }
    </script>
@endpush
