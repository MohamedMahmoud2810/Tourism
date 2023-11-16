@extends('layouts.dashboard.app')
@section('content')
 <div class="content-wrapper">

        <section class="content-header">
    <div class="content-wrapper container">


        <form class="form" action="{{route('storeYear')}}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('partials._errors')
            <div class="card">
                <div class="top">
                    <h3> انشاء سنه دراسية جديدة </h3>
                    <hr>
                </div>

                <div class="card-details">

                    <label>
                        <input type="text" value="{{$nextYear['year']}}" readonly class="custom-file-input mb-5 text-center"
                               name="year">
                    </label>
                </div>
                <div class="card-details">

                    <label>
                        <input type="text" value="{{$nextYear['semester']}}" readonly
                               class="custom-file-input mb-5 text-center" name="semester">
                    </label>
                </div>

                <div class="button mt-5">
                    <button>اضافة</button>
                </div>
            </div>
        </form>

    </div>
    </section>
    </div>
@endsection



