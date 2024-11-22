@extends('dashboard.layouts.app')
@section('container')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Info
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#modal-info">Add New
                Info</button>
        </div>
    </div>
    <div class="intro-y
                grid grid-cols-12 gap-6 mt-5">
        <!-- BEGIN: Blog Layout -->
        @forelse ($info as $item)
            <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 box">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 px-5 py-4">
                    <div class=" mr-auto">
                        <a href="" class="block font-medium text-base ">{{ $item->title }}</a>
                    </div>
                    <div class="dropdown ml-3">
                        <a href="javascript:;" class="dropdown-toggle w-5 h-5 text-slate-500" aria-expanded="false"
                            data-tw-toggle="dropdown"> <i data-lucide="more-vertical" class="w-4 h-4"></i> </a>
                        <div class="dropdown-menu w-40">
                            <ul class="dropdown-content">
                                <li>
                                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#edit-{{ $item->id }}"
                                        class="dropdown-item">
                                        <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i> Edit </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#delete-modal-preview-{{ $item->id }}" class="dropdown-item">
                                        <i data-lucide="trash" class="w-4 h-4 mr-2"></i>
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-40 2xl:h-56 image-fit">
                        <img alt="Midone - HTML Admin Template" class="rounded-md"
                            src="/assets/images/info/{{ $item->gambar }}">
                    </div>

                    <a href="" class="block font-medium text-base mt-5">Description</a>
                    <div class="text-slate-600 dark:text-slate-500 mt-3">
                        {{ $item->description }}
                    </div>
                </div>
            </div>
        @empty
            <div class="intro-y col-span-12 p-5">
                <div class="file box rounded-md px-5 pt-8 pb-8 px-3 sm:px-5 relative text-center">
                    <p class="text-slate-500">Tidak ada Info yang tersedia.</p>
                </div>
            </div>
        @endforelse
        <!-- END: Blog Layout -->

    </div>
    <!-- BEGIN: Pagination -->
    <div class="intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
        <nav class="w-full sm:w-auto sm:mr-auto items-center">
            <ul class="pagination">
                @if ($info->onFirstPage())
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
                        <a class="page-link" href="{{ $info->url(1) }}">
                            <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ $info->url($info->currentPage() - 1) }}">
                            <i class="w-4 h-4" data-lucide="chevron-left"></i>
                        </a>
                    </li>
                @endif

                <li class="page-item disabled"> <a class="page-link">...</a> </li>
                @for ($i = 1; $i <= $info->lastPage(); $i++)
                    <li class="page-item {{ $i == $info->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $info->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item disabled"> <a class="page-link">...</a> </li>

                <!-- Cek jika ada halaman berikutnya -->
                @if ($info->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $info->url($info->currentPage() + 1) }}">
                            <i class="w-4 h-4" data-lucide="chevron-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ $info->url($info->lastPage()) }}">
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
    @include('dashboard.info.add')
    @include('dashboard.info.edit')
    <!-- BEGIN: Modal delete -->
    @foreach ($info as $item)
        <div id="delete-modal-preview-{{ $item->id }}" class="modal" tabindex="-1" aria-hidden="true">
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

                            <form action="/dashboard/info/{{ $item->id }}" method="post">
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
@endsection
