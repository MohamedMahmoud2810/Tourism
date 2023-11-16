@extends('layouts.dashboard.app')
@section('style')
    <style>




    </style>
@endsection
@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container content-wrapper">

                <form class="form" action="{{route('StoreBonusDegree',$degrees->id)}}" method="POST" enctype="multipart/form-data">
                    @include('partials._errors')
                    <div class="card">
                        @csrf
                <div class="top">
                    <h3>درجات التيسير </h3>
                    <hr>
                </div>
          <div class="row align-items-center pb-3 d-flex justify-content-center">

                  <div class="col-md-5 text-center">
                      <label for="name" class="labl">
                          الفرقة الأولى
                          <i class="fa fa-arrow-left"></i>
                      </label>
                  </div>
                  <div class="col-md-5">
                      <label>
                          <input type="text" value="{{$degrees->degree_group1}}" class="form-control input-outline" placeholder="ادخل الدرجة  " name="degree_group1">
                      </label>
                  </div>
                </div>
            <div class="row align-items-center pb-3 pt-3 d-flex justify-content-center">

                  <div class="col-md-5 text-center">
                      <label for="name" class="labl">
                          الفرقة الثانية
                          <i class="fa fa-arrow-left"></i>
                      </label>
                  </div>
                  <div class="col-md-5">
                      <label>
                          <input type="text" value="{{$degrees->degree_group2}}" class="form-control input-outline" placeholder="ادخل الدرجة  " name="degree_group2">
                      </label>
                  </div>
                </div>
                <div class="row align-items-center pb-3 pt-3 d-flex justify-content-center">

                  <div class="col-md-5 text-center">
                      <label for="name" class="labl">
                          الفرقة الثالثة
                          <i class="fa fa-arrow-left"></i>
                      </label>
                  </div>
                  <div class="col-md-5">
                      <label>
                          <input type="text" class="form-control input-outline" value="{{$degrees->degree_group3}}" placeholder="ادخل الدرجة  " name="degree_group3">
                      </label>
                  </div>
                </div>
                <div class="row align-items-center pb-3 pt-3 d-flex justify-content-center">
                  <div class="col-md-5 text-center">
                      <label for="name" class="labl">
                          الفرقة الرابعة
                          <i class="fa fa-arrow-left"></i>
                      </label>
                  </div>
                  <div class="col-md-5">
                      <label>
                          <input type="text" value="{{$degrees->degree_group4}}" class="form-control input-outline" placeholder="ادخل الدرجة  " name="degree_group4">
                      </label>
                  </div>
                </div>

                <div class="row align-items-center pb-3 pt-3  d-flex justify-content-center">
                 <div class="col-md-5">
                  <button type="submit" class="btn btn-success" >حفظ</button>
                 </div>
                </div>
            </div>
         </form>

        </div>
        </section>


    </div><!-- end of content wrapper -->

@endsection

<style>
    .container{
        width: 50%!important;
    }
    input {
        text-align: right;
    }
    .btn{
        width: 100%;
    }
</style>

