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
                                <form class="w-full bg-white p-8" action="{{route('store.GrDepSp')}}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @include('partials._errors')
                                    <div class="top flex justify-center p-8">
                                        <h1 class="text-3xl	">اضافة فرقه بقسم بتخصص </h1>
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
                                            <select name="department_id" class="custom-select mb-3" id="department-dropdown">
                                                @foreach($departments as $department)
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2 px-3">
                                            <label
                                                class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2"
                                                for="grid-last-name">
                                                التخصص
                                            </label>
                                            <label for="specialize-dropdown"></label>
                                            <select name="specialize_id" class="custom-select mb-3" id="specialize-dropdown">
                                                @foreach($specializes as $specialize)
                                                    <option value="{{$specialize->id}}">{{$specialize->name}}</option>
                                                @endforeach
                                            </select>
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

