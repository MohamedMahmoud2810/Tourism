@extends('layouts.dashboard.app')
@section('style')
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.3.0/css/dataTables.dateTime.min.css">--}}
{{--    <link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">--}}

{{--    #################  buttons #####################--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://www.misin.msu.edu/0/DataTables/Editor-PHP-1.9.0/css/editor.dataTables.min.css">

        <link rel="stylesheet" href="{{asset('data-table-editor/css/editor.dataTables.min.css')}}">
    <style>
        table.dataTable thead th {
            text-align: center !important;
        }
    </style>
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
                                <form action="{{route('tableResults')}}" method="get" id="filter">
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
                                                @foreach($status as $state)
                                                    <option
                                                        value="{{$state->id}}" @selected($data['studystatuses_id'] == $state->id)>
                                                        {{$state->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="card-details mt-2 col-md-4">
                                            <select name="year" id="year-dropdown" class="custom-select mb-3">
                                            </select>
                                        </div>
                                        <div class="card-details mt-2 col-md-4">
                                            <button class="btn btn-success btn-lg" type="submit">بحث</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table id="example" class="display" cellspacing="0" width="100%">
                                <thead>
                                @foreach($columns as $column)
                                    <tr>
                                        @foreach($column as $col)
                                            <th colspan="{{$col['col']}}"
                                                rowspan="{{$col['row']}}" class="border">
                                                <span
                                                    style="transform: rotateZ(270deg)!important;">{{$col['text']}}</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')

{{--    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/select/1.6.1/js/dataTables.select.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/datetime/1.3.1/js/dataTables.dateTime.min.js"></script>--}}
{{--    <script src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script>--}}
{{--    <script src="{{asset('data-table-editor/js/dataTables.editor.min.js')}}"></script>--}}

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://www.misin.msu.edu/0/DataTables/Editor-PHP-1.9.0/js/dataTables.editor.min.js"></script>



    <script src="{{asset('dashboard_files/js/filter.js')}}"></script>
    <script>
        define('{{url('get-department-by-group')}}', '{{url('get-specializes-by-department')}}',
            '{{url('get-subject-by-department')}}', '{{csrf_token()}}', 1);
    </script>
    <script>
        let year_semesters = @json($year_semester);
        let year = year_semesters.map(a => a.year);
        year = year.filter((item, index) => year.indexOf(item) === index);
        // let semester = year_semesters.map(a => a.semester);
        // semester = semester.filter((item, index) => semester.indexOf(item) === index);
        let year_semester = year_semesters.find((value) => value.id == @json($data['yearsemester_id']));
        let year_select = $('#year-dropdown');
        for (let i = 0; i < year.length; i++) {
            let selected = (year_semester.year === year[i]) ? 'selected' : '';
            year_select.append('<option value="' + year[i] + '" ' + selected + '>' + year[i] + '</option>');
        }
        // let semester_select = $('#semester-dropdown');
        // for (let i = 0; i < semester.length; i++) {
        //     let selected = (year_semester.semester === semester[i]) ? 'selected' : '';
        //     semester_select.append('<option value="' + semester[i] + '" ' + selected + '>' + semester[i] + '</option>');
        // }
        let editor;

        $(document).ready(function () {

            $('#example').DataTable({
                fields: [ {
                    label: "written:",
                    name: "name.written"
                }, {
                    label: "Kpis:",
                    name: "name.kpis"
                }, {
                    label: "applied:",
                    name: "name.applied"
                }
                ],
                ajax: {
                    url: '{{route('dataTableResultsStudents')}}',
                    data: {
                        'department_id': $('#department-dropdown').val(),
                        'group_id': $('#groups-dropdown').val(),
                        'specialize_id': $('#specialize-dropdown').val(),
                        'studystatuses_id': $('#status-dropdown').val(),
                        'yearsemester_id': year_semesters.filter((value) => value.year === $('#year-dropdown').val())
                            .map(a => a.id),
                    }
                },
                rowId: 'id',
                idSrc: 'id',
                scrollX: true,
                columns: [
                    {data: 'id'},
                    {data: 'nameStd', editField: 'nameStd'},
                    {data: 'code'},
                    {data: 'site_no'},
                    {data: 'status'},
                    {data: 'remaining_bonus'},
                    {data: 'total_bonus'},
                        @foreach($subjects_names as $name)
                    {data: '{{$name}}.written'},
                    {data: '{{$name}}.applied'},
                    {data: '{{$name}}.kpis'},
                    {data: '{{$name}}.bonus'},
                    {data: '{{$name}}.total'},
                    {data: '{{$name}}.grade'},
                    @endforeach
                ],
                // select: {
                //     style: 'os',
                //     selector: 'td:first-child'
                // },
                select: true,
                buttons: [
                    { extend: "edit",   editor: editor },
                    ]
            });

            $.fn.dataTable.ext.errMode = 'none';
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



