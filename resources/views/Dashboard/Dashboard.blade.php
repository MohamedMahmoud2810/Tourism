@extends('layouts.dashboard.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>الصفحة الرئيسية</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $subjects }}</h3>
                            <p>المقررارات الدراسية</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-book"></i>
                        </div>
                        <a href="{{ route('Subjects') }}" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>{{ $departments }}</h3>

                            <p>الاقسام</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-th"></i>
                        </div>
                        <a href="{{ route('Departments') }}" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $students }}</h3>
                            <p>الطلاب</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ route('Students') }}" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3> <i class="fa fa-signal"></i></h3>
                            <p>درجات التيسير</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-signal"></i>
                        </div>
                        <a href="{{ route('bonusDegree',1) }}" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </section><!-- end of content -->
    </div>
@endsection

