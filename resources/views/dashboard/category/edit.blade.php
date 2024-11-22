<!-- BEGIN: Modal Edit Content -->
@foreach ($categories as $category)
    <div id="edit-{{ $category->id }}" class="modal" tabindex="-1" aria-hidden="true">
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
                <form action="/dashboard/category/{{ $category->id }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <!-- Name Category Field -->
                        <div class="col-span-12">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name
                                Category</label>
                            <input id="name" name="name" type="text" class="form-control"
                                value="{{ old('name', $category->name) }}" placeholder="Enter category name">
                            @error('name')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Parent Category Field -->
                        <div class="col-span-12">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category
                            </label>
                            <select id="parent_id" name="parent_id" class="form-control">
                                <option value=""
                                    {{ old('parent_id', $item->parent_id ?? '') == '' ? 'selected' : '' }}>
                                    No Parent
                                </option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('parent_id', $category->parent_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>

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
