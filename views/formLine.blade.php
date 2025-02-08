<div class="row align-content-between">
    <form action="{{ $link }}.php" class="mb-3 col-12 col-md-5 col-sm-6 col-xs-12 mx-auto" id="{{ $identifier }}-form">
        <label for="{{ $identifier }}-select" class="text-primary fs-4">{!! $slot !!}</label>
        <div class="select-box mb-1 col-12 d-flex flex-column flex-md-row align-items-end align-items-md-center justify-content-between">
            <select name="{{ $identifier }}" id="{{ $identifier }}-select" class="d-none"></select>
            <button class="mt-2 mt-md-0 btn btn-primary">Перейти</button>
        </div>
    </form>
</div>