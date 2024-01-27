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
                                <form class="w-full bg-white p-8" action="{{route('store.Subjects')}}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @include('partials._errors')
                                    <div class="top flex justify-center p-8">
                                        <h1 class="text-3xl	">اضافة مقرر دراسى</h1>
                                    </div>
                                    <div class="flex justify-center flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">الفرقة الدراسية</label>
                                            <label for="groups-dropdown"></label>
                                            <select name="group_id" class="custom-select mb-3" id="groups-dropdown">
                                                <option selected disabled label="من فضلك أختر الفرقة ">من فضلك أختر
                                                    الفرقة
                                                </option>
                                                @if($groups && $groups -> count() > 0)

                                                    @foreach($groups as $group)
                                                        <option
                                                            value="{{$group -> id }}" @selected($group->id == old('group_id'))>{{$group -> name}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                الشعبة
                                            </label>
                                            <label for="department-dropdown"></label>
                                            <select name="departments_id" class="custom-select mb-3"
                                                    id="department-dropdown">
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                التخصص
                                            </label>
                                            <label for="specialize-dropdown"></label><select name="specialize_id"
                                                                                             class="custom-select mb-3"
                                                                                             id="specialize-dropdown">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                الفصل الدراسى
                                            </label>
                                            <label for="group-dropdown"></label>
                                            <select name="term" class="custom-select mb-3" id="group-dropdown">
                                                <option disabled selected label="من فضلك أختر الفصل الدراسي "> من فضلك
                                                    أختر الفصل الدراسي
                                                </option>
                                                <option value="اول">اول</option>
                                                <option value="ثاني">ثاني</option>
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                نوع المقرر
                                            </label>
                                            <select name="type_subject" class="custom-select mb-3" id="group-dropdown">
                                                <option selected disabled label="من فضلك أختر نوع المقرر ">من فضلك أختر
                                                    نوع المقرر
                                                </option>
                                                <option value="اجباري">اجباري</option>
                                                <option value="اختياري">اختياري</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">
                                                كود المادة
                                            </label>
                                            <label>
                                                <input type="text" name="code_subject"
                                                       class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                            </label>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                المقرر / المادة
                                            </label>
                                            <label>
                                                <input type="text" name="name"
                                                       class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">درجة الحريري الكلية</label>
                                            <label>
                                                <input type="text" value="{{old('max_written')}}" name="max_written"
                                                       class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                            </label>
                                        </div>
                                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-first-name">درجة اعمال السنه الكلية</label>
                                            <label>
                                                <input type="text" value="{{old('max_kpis')}}" name="max_kpis"
                                                       class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                            </label>
                                        </div>
                                        <div class="w-full md:w-1/3 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">درجة اعمال التطبيقي الكلية</label>
                                            <label>
                                                <input type="text" value="{{old('max_applied')}}" name="max_applied"
                                                       class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                            </label>
                                        </div>
                                    </div>


                                    <div class="flex justify-center flex-wrap-mx-3  mb-6">
                                        <div class="w-full flex justify-center md:w-1/2 px-3 mb-6 md:mb-0">
                                            <button
                                                class="shadow focus:shadow-outline mt-5 focus:outline-none text-white font-bold py-4 px-20 rounded"
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
        '{{url('get-subject-by-department')}}', '{{csrf_token()}}');
</script>
