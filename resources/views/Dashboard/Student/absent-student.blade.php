@extends('layouts.dashboard.app')
@section('style')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.3.0/css/dataTables.dateTime.min.css">
        <link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">

    {{--    #################  buttons #####################--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">--}}
{{--    <link rel="stylesheet" href="https://www.misin.msu.edu/0/DataTables/Editor-PHP-1.9.0/css/editor.dataTables.min.css">--}}

{{--    <link rel="stylesheet" href="{{asset('data-table-editor/css/editor.dataTables.min.css')}}">--}}
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
                            <form id="searchForm">
                                <div class="input-group m-5">
                                    <input type="text" class="form-control " placeholder="Enter site_num" id="searchInput">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary m-3" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                            <div class="mb-5">
                                @include('partials._errors')
                                <table class="dataTable display" id="resultsTable">
                                    <thead>
                                    @foreach($columns as $column)
                                        <tr>
                                            @foreach($column as $col)
                                                <th colspan="{{$col['col']}}"
                                                    rowspan="{{$col['row']}}" class="border">
                                                <span style="transform: rotateZ(270deg)!important;">{{$col['text']}}</span>
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
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#resultsTable').hide();
            $('#searchForm').submit(function (e) {
                e.preventDefault();
                let site_num = $('#searchInput').val();
                $.ajax({
                    url: '{{ route('searchStudentsBySiteNum') }}', // Laravel route for the search functionality
                    type: 'GET',
                    data: { site_num: site_num }, // Send the site_num as a parameter
                    dataType: 'json',
                    success: function (response) {
                        $('#resultsTable').DataTable().clear().destroy();
                        let student = response.data;
                        console.log(student);
                            let newRow = '<tr>' +
                                        // '<td>' + student.name  + '</td>'+
                                        '<td>' + student.code  +'</td>'+
                                        '<td>' +student.site_no + '</td>'+
                                        // '<td>' + student.Studystatus.name + '</td>'+
                                        // '<td>' + student.results.written + '</td>'+
                                        // '<td>' + student.results.kpis + '</td>'+

                                        // <td>${student.results.kpis}</td>
                                        // <td>${student.results.applied}</td>
                                        // <td>${student.results.bonus}</td>
                                        // <td>${student.results.total}</td>
                                        // <td>${student.results.grade}</td>
                                     '</tr>';
                        $('#students').html(newRow);
                        $('#resultsTable').DataTable();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>


@endpush
