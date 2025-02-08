<div class="row">
    <div class="col-12 col-lg-8">
        @include('header')
        <div class="calendar p-1 loading" id="monthGrid"></div>
    </div>
    <div id="listDays" class="col-12 col-lg-4">
        @include('menu',[
            'toggle'=>false,
            'timeShow'=>true,
            'current' => true
        ])
        @include ('dayList')
    </div>
</div>
