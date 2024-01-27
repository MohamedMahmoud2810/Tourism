@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            @include('partials._errors')
            <div class="content-wrapper container">
                <div class="row">
                    <div class="col-md-6">
                <form class="form" action="{{route('upgrade.bonus')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="top">
                            <h3>اضافة درجات التيسير</h3>
                            <hr>
                        </div>
                        <div class="button mt-5">
                            <button>اضافة التيسير</button>
                        </div>
                    </div>
                </form>
                    </div>
                    <div class="col-md-6">
                <form class="form" action="{{route('upgrade.groups')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="top">
                            <h3>تحديث الفرق الدراسية</h3>
                            <hr>
                        </div>
                        <div class="button mt-5">
                            <button>تحديث الفرق </button>
                        </div>
                    </div>
                </form>
                </div>
                </div>
            </div>
        </section>
    </div>
@endsection



