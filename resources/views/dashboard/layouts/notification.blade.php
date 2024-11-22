{{-- Success Notification --}}

<div id="success-notification-content" class="toastify-content hidden flex bg-danger"> <i class="text-success"
        data-lucide="check-circle"></i>
    <div class="ml-4 mr-4">
        <div class="font-medium">Success</div>
        <div class="text-slate-500 mt-1"> {{ session('success') }}</div>
    </div>
</div>
{{-- Error Notification --}}
<div id="error-notification-content" class="toastify-content hidden flex"> <i class="text-danger"
        data-lucide="alert-octagon"></i>
    <div class="ml-4 mr-4">
        <div class="font-medium">Error!</div>
        <div class="text-slate-500 mt-1">{{ session('error') }}</div>
    </div>
</div>
