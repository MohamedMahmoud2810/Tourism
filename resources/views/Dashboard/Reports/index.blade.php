@extends('layouts.dashboard.app')
@section('style')
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="py-12">
                <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="mb-5">
                                @include('partials._errors')
                                <form action="{{route('test')}}" method="get" id="filter">
                                    <div class="row mr-5 pr-5">
                                        <div class="card-details  col-md-4">
                                            <select name="group_id" class="custom-select mb-3" id="groups-dropdown">
                                                @foreach($groups as $group)
                                                    <option
                                                        value="{{$group->id}}" {{($data['group_id'] == $group->id)? 'selected':''}}>
                                                        {{$group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="card-details col-md-4">
                                            <select name="departments_id" class="custom-select mb-3"
                                                    id="department-dropdown">
                                                @foreach($departments as $department)
                                                    <option
                                                        value="{{$department->id}}"
                                                        {{($data['departments_id'] == $department->id)? 'selected':''}}>
                                                        {{$department->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="card-details col-md-4">
                                            <select name="specialize_id" class="custom-select mb-3"
                                                    id="specialize-dropdown">
                                                @foreach($specializes as $specialize)
                                                    <option
                                                        value="{{$specialize->id}}"
                                                        {{($data['specialize_id'] == $specialize->id)? 'selected':''}}>
                                                        {{$specialize->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="card-details  col-md-4">
                                            <select name="status_id" class="custom-select mb-3" id="status-dropdown">
                                                <option value="all" @selected($data['studystatuses_id'] == 'all')>الكل
                                                </option>
{{--                                                @foreach($status as $state)--}}
{{--                                                    <option--}}
{{--                                                        value="{{$state->id}}" @selected($data['studystatuses_id'] == $state->id)>--}}
{{--                                                        {{$state->name}}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                        <div class="card-details mt-2 col-md-4">
                                            <select name="year" id="year-dropdown" class="custom-select mb-3">
                                            </select>
                                        </div>
                                        <div class="card-details mt-2 col-md-4">
                                            <button class="btn btn-success btn-lg" type="submit">تنزيل</button>
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
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="{{asset('dashboard_files/js/filter.js')}}"></script>
    <script>
        define('{{url('get-department-by-group')}}', '{{url('get-specializes-by-department')}}',
            '{{url('get-subject-by-department')}}', '{{csrf_token()}}', 1);
    </script>
    <script>
        let year_semesters = @json($year_semester);
        let year = year_semesters.map(a => a.year);
        year = year.filter((item, index) => year.indexOf(item) === index);
        let year_semester = year_semesters.find((value) => value.id == @json($data['yearsemester_id']));
        let year_select = $('#year-dropdown');
        for (let i = 0; i < year.length; i++) {
            let selected = (year_semester.year === year[i]) ? 'selected' : '';
            year_select.append('<option value="' + year[i] + '" ' + selected + '>' + year[i] + '</option>');
        }

        $(document).ready(function () {
            $.ajax({
                    url: '{{route('test2')}}',
                    data: {
                        'department_id': $('#department-dropdown').val(),
                        'group_id': $('#groups-dropdown').val(),
                        'specialize_id': $('#specialize-dropdown').val(),
                        'studystatuses_id': $('#status-dropdown').val(),
                        'yearsemester_id': year_semesters.filter((value) => value.year === $('#year-dropdown').val())
                            .map(a => a.id),
                    }
            });
        });
        $('#filter').on('submit', function (e) {
            let ids = year_semesters.filter((value) => value.year === $('#year-dropdown').val()).map(a => a.id);
            $('#semester-dropdown').attr('disabled', true);
            $('#year-dropdown').attr('disabled', true);
            for (let i = 0; i < ids.length; i++) {
                $("<input />").attr("type", "hidden").attr("name", "yearsemester_id[]").attr("value", ids[i])
                    .appendTo("#filter");
            }
        })
    </script>
@endpush



