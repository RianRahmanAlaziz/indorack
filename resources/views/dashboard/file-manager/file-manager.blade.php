@extends('dashboard.layouts.app')
@section('container')
    <div class="grid grid-cols-12 gap-6 mt-8">
        <div class="col-span-12 lg:col-span-3 2xl:col-span-2">
            <h2 class="intro-y text-lg font-medium mr-auto mt-2">
                File Manager
            </h2>
            <!-- BEGIN: File Manager Menu -->
            <div class="intro-y box p-5 mt-6">
                <div class="border-t border-slate-200 dark:border-darkmode-400 mt-4 pt-4"></div>
                <div class="mt-1">
                    <a href="/dashboard/file-manager"
                        class="flex items-center px-3 py-2 rounded-md {{ request('category') == null ? 'bg-primary text-white font-medium' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-images w-4 h-4 mr-2">
                            <path d="M18 22H4a2 2 0 0 1-2-2V6" />
                            <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" />
                            <circle cx="12" cy="8" r="2" />
                            <rect width="16" height="16" x="6" y="2" rx="2" />
                        </svg> ALL </a>
                    <a href="/dashboard/file-manager?category=gambar"
                        class="flex items-center px-3 py-2 rounded-md {{ request('category') == 'gambar' ? 'bg-primary text-white font-medium' : '' }}">
                        <i class="w-4 h-4 mr-2" data-lucide="image"></i> Images </a>
                    <a href="/dashboard/file-manager?category=video"
                        class="flex items-center px-3 py-2 mt-2 rounded-md {{ request('category') == 'video' ? 'bg-primary text-white font-medium' : '' }}">
                        <i class="w-4 h-4 mr-2" data-lucide="video"></i> Videos </a>
                </div>
                <div class="border-t border-slate-200 dark:border-darkmode-400 mt-4 pt-4"></div>
            </div>
            <!-- END: File Manager Menu -->
        </div>

        <div class="col-span-12 lg:col-span-9 2xl:col-span-10">
            <!-- BEGIN: File Manager Filter -->
            <div class="intro-y flex flex-col-reverse sm:flex-row items-center">

                <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-slate-500" data-lucide="search"></i>
                    <form action="{{ url()->current() }}" method="get">
                        <input type="text" name="search" id="search" class="form-control w-full sm:w-64 box px-10"
                            placeholder="Search files" value="{{ old('search', request('search')) }}">
                    </form>
                </div>

                <div class="w-full sm:w-auto flex">
                    <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                        data-tw-target="#header-footer-modal-preview">Upload New Files</button>
                </div>
            </div>
            <!-- END: File Manager Filter -->

            <!-- BEGIN: Directory & Files -->
            <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                @forelse ($files as $file)
                    <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 2xl:col-span-2">
                        <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                            <a class="w-3/5 file__icon file__icon--image mx-auto">
                                <div class="file__icon--image__preview image-fit">
                                    <img alt="{{ $file->nfile }}" src="/assets/images/file-manager/{{ $file->gambar }}">
                                </div>
                            </a>
                            <a class="block font-medium mt-4 text-center truncate">{{ $file->gambar }}</a>
                            <div class="text-slate-500 text-xs text-center mt-0.5"></div>
                            <div class="absolute top-0 right-0 mr-2 mt-3 dropdown ml-auto">
                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false"
                                    data-tw-toggle="dropdown"> <i data-lucide="more-vertical"
                                        class="w-5 h-5 text-slate-500"></i> </a>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#edit-{{ $file->id }}" class="dropdown-item">
                                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#delete-modal-preview-{{ $file->id }}"
                                                class="dropdown-item">
                                                <i data-lucide="trash" class="w-4 h-4 mr-2"></i>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="intro-y col-span-12">
                        <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative text-center">
                            <p class="text-slate-500">Tidak ada file yang tersedia.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- END: Directory & Files -->
            <!-- BEGIN: Pagination -->
            <div class="intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                <nav class="w-full sm:w-auto sm:mr-auto items-center">
                    <ul class="pagination">
                        @if ($files->onFirstPage())
                            <li class="page-item disabled">
                                <a class="page-link">
                                    <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                                </a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link">
                                    <i class="w-4 h-4" data-lucide="chevron-left"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $files->url(1) }}">
                                    <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $files->url($files->currentPage() - 1) }}">
                                    <i class="w-4 h-4" data-lucide="chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        <li class="page-item disabled"> <a class="page-link">...</a> </li>
                        @for ($i = 1; $i <= $files->lastPage(); $i++)
                            <li class="page-item {{ $i == $files->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $files->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item disabled"> <a class="page-link">...</a> </li>

                        <!-- Cek jika ada halaman berikutnya -->
                        @if ($files->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $files->url($files->currentPage() + 1) }}">
                                    <i class="w-4 h-4" data-lucide="chevron-right"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $files->url($files->lastPage()) }}">
                                    <i class="w-4 h-4" data-lucide="chevrons-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link">
                                    <i class="w-4 h-4" data-lucide="chevron-right"></i>
                                </a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link">
                                    <i class="w-4 h-4" data-lucide="chevrons-right"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <!-- END: Pagination -->
        </div>
    </div>
    @include('dashboard.file-manager.add')
    @include('dashboard.file-manager.edit')
    <!-- BEGIN: Modal delete -->
    @foreach ($files as $file)
        <div id="delete-modal-preview-{{ $file->id }}" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Apa Kamu Yakin?</div>
                            <div class="text-slate-500 mt-2">
                                Benar-benar ingin menghapusnya?<br>Proses ini
                                tidak dapat dibatalkan.
                            </div>
                        </div>
                        <div class="px-5 pb-8 text-center">

                            <form action="/dashboard/file-manager/{{ $file->id }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                <button type="submit" class="btn btn-danger w-24">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END: Modal Content -->
@endsection
