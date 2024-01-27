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

                                <form class="w-full bg-white p-8" action="{{route('store.updated.students')}}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @include('partials._errors')
                                    <div class="top flex justify-center p-8">
                                        <h1 class="text-3xl	">اضافة التدريب والتربيه العسكرية للطلاب </h1>
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
        '{{url('get-subject-by-department')}}', '{{csrf_token()}}');
</script>
