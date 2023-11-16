<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="top text-center mt-5 mb-5" style="font-size: 20px">
                    <h3 class="mb-5">رفع نتائج الطلاب المحولين </h3>
                    <hr>
                </div>
                <div class="content-wrapper container bg-white">
                    <form class="w-full bg-white p-8" action="{{route('storeTransferStudentResults')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @include('partials._errors')
                        <div class="row">
                            <div class="top text-center mt-5 mb-5" style="font-size: 20px">
                                <h3 class="mb-5">رفع نتائج الطلاب المحولين </h3>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="card-details pb-4 pt-4">
                                    <label for="groups-dropdown"></label>
                                    <select name="" class="custom-select mb-3" id="groups-dropdown">
                                        <option selected disabled label=" أختر الفرقة "> أختر الفرقة
                                        </option>
                                        @if($groups && $groups -> count() > 0)
                                            @foreach($groups as $group)
                                                <option
                                                    value="{{$group -> id }}">{{$group -> name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-details pb-4 pt-4">
                                    <label for="department-dropdown"></label>
                                    <select name="" class="custom-select mb-3" id="department-dropdown">
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="card-details pb-4 pt-4">
                                    <label for="specialize-dropdown"></label>
                                    <select name="" class="custom-select mb-3" id="specialize-dropdown">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-details pb-4 pt-4">
                                    <input name="year" class="text-center" type="number" value="2020" min="2000" step="1">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group pb-4 pt-4">
                                    <select name="students_id" class="form-control select2"
                                            style="width: 100%;">
                                        <option hidden> اختر الطالب المحول</option>
                                        @foreach($transfer_students as $transfer_student)
                                            <option
                                                value="{{$transfer_student->id}}">{{$transfer_student->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-md-2"></div>
                            <div class="col-md-10">
                                <table class="table table-striped" id="table1">
                                    <thead style="font-size: 16px; color: black;">
                                    <tr>
                                        <th class="text-center">
                                            المقرر الدراسي
                                        </th>
                                        <th class="text-center">
                                            الدرجة الكلية
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="button">
                                    <button>اضافة</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection


<script src="{{asset('dashboard_files/js/filter.js')}}"></script>
<script>
    define('{{url('get-department-by-group')}}', '{{url('get-specializes-by-department')}}',
        '{{url('get-subject-by-department')}}', '{{csrf_token()}}', 2);
</script>
