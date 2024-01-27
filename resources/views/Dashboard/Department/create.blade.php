@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div>
                <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <div>
                            <div class="container content-wrapper">


                                <form class="form" action="{{route('store.Department')}}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf

                                    @include('partials._errors')
                                    <div class="card">
                                        <div class="top">
                                            <h3>اضافة قسم (شعبة)</h3>
                                            <hr>
                                        </div>
                                        <div class="exp-cvv">
                                            <div class="card-details"><label>
                                                    <input type="text" name="name">

                                                </label> <span>القسم / الشعبة</span>
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
            </section>
    </div>
@endsection

