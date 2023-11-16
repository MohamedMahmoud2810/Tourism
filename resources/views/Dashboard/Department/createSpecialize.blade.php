@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div>
                <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <div>
                            <div class="container content-wrapper">
                                @include('partials._errors')
                                <form class="form" action="{{route('store.Specialize')}}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="card">
                                        <div class="top">
                                            <h3>اضافة التخصص</h3>
                                            <hr>
                                        </div>
                                        <div class="exp-cvv">
                                            <div class="card-details"><label>
                                                    <input type="text" name="name">
                                                </label> <span>التخصص</span>
                                            </div>
                                        </div>

                                        <div class="button mt-5">
                                            <button>اضافة</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


@endsection

