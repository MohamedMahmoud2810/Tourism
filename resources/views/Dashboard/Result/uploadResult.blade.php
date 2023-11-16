<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div>
                <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <div>
                            <div class="content-wrapper container">

                                <form class="w-full bg-white p-8" action="{{route('storeResults')}}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @include('partials._errors')
                                    <div class="top flex justify-center p-8">
                                        <h1 class="text-3xl	">رفع نتائج الطلاب </h1>
                                    </div>

                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                الفرقة الدراسية
                                            </label>
                                            <label for="groups-dropdown"></label><select class="custom-select mb-3" id="groups-dropdown">
                                                <option selected disabled label="من فضلك أختر الفرقة ">من فضلك أختر
                                                    الفرقة
                                                </option>
                                                @if($groups && $groups -> count() > 0)
                                                    @foreach($groups as $group)
                                                        <option
                                                            value="{{$group -> id }}">{{$group -> name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                االشعبة
                                            </label>
                                            <label for="department-dropdown"></label><select class="custom-select mb-3" id="department-dropdown">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                التخصص
                                            </label>
                                            <label for="specialize-dropdown"></label><select class="custom-select mb-3" id="specialize-dropdown">
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                السنة الدراسية
                                            </label>
                                                <select name="year" class="custom-select mb-3">
                                                    <option selected disabled hidden label="من فضلك أختر السنة ">من فضلك
                                                        أختر السنة
                                                    </option>
                                                    @foreach($years as $year)
                                                        <option value="{{$year->year}}">{{$year->year}}
                                                        </option>
                                                    @endforeach

                                                </select>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                اختر الفصل الدراسى
                                            </label>
                                            <label for="semester"></label><select name="semester" id="semester" class="custom-select mb-3">
                                                <option selected disabled hidden label="من فضلك أختر الفصل الدراسي ">من
                                                    فضلك أختر الفصل الدراسي
                                                </option>

                                                @foreach($semesters as $semester)
                                                    <option
                                                        value="{{$semester->semester}}">{{$semester->semester}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                مواد التخصص
                                            </label>
                                            <label for="subject-dropdown"></label><select name="subjects_id" class="custom-select mb-3" id="subject-dropdown">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex justify-center flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                اختر الملف
                                            </label>
                                            <input type="file"
                                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                                   name="file">
                                        </div>
                                    </div>

                                    <div class="flex justify-center flex-wrap -mx-3 mb-6">
                                        <div class="w-full flex justify-center md:w-1/2 px-3 mb-6 md:mb-0">
                                            <button
                                                class="shadow focus:shadow-outline focus:outline-none text-white font-bold py-4 px-20 rounded"
                                                style="background:#144935;">اضافة
                                            </button>
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
<script src="{{asset('dashboard_files/js/filter.js')}}"></script>
<script>
    define('{{url('get-department-by-group')}}', '{{url('get-specializes-by-department')}}',
        '{{url('get-subject-by-department')}}', '{{csrf_token()}}', 1);
</script>



