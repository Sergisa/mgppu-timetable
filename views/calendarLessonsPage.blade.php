<div class="row">
    <div class="col-12 col-md-8">
        @include('header')
        <div class="calendar p-1 loading" id="monthGrid"></div>
    </div>
    <div id="listDays" class="col-12 col-md-4">
        @include('menu',[
            'timeShow'=>true,
            'current' => true
        ])
        @include ('dayList')
    </div>
</div>
