<div class="content-wrapper" style="height: 1000px">
    <section class="content-header">
        <div class="py-12">
            <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-5">
                            @include('partials._errors')
                            <form id="myForm" method="POST">
                                @csrf
                                @php
                                    $data = $data ?? [];
                                @endphp
                                <div class="row mr-5 pr-5">
                                    <div class="card-details  col-md-4">
                                        <select name="group_id" class="custom-select mb-3" id="groups-dropdown">
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ isset($data['group_id']) && $data['group_id'] == $group->id ? 'selected' : '' }}>
                                                    {{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-details col-md-4">
                                        <select name="department_id" class="custom-select mb-3"
                                                id="department-dropdown">
                                            @foreach($departments as $department)
                                                <option
                                                    value="{{$department->id}}"

                                                    {{(isset($data['department_id']) && $data['departments_id'] == $department->id)? 'selected':''}}>
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
                                                    {{(isset($data['specialize_id']) && $data['specialize_id'] == $specialize->id)? 'selected':''}}>
                                                    {{$specialize->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-details  col-md-4">
                                        <select name="studystatuses_id" class="custom-select mb-3" id="status-dropdown">
                                            <option value="all" @selected(isset($data['studystatuses_id']) && $data['studystatuses_id'] == 'all')>الكل
                                            </option>
                                            @foreach($status as $state)
                                                <option
                                                    value="{{$state->id}}" @selected(isset($data['studystatuses_id']) && $data['studystatuses_id'] == $state->id)>
                                                    {{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-details mt-2 col-md-4">
                                        <select name="yearsemester_id" id="year-dropdown" class="custom-select mb-3">
                                            @foreach($years as $year)
                                                <option
                                                    value="{{$year->id}}"
                                                >
                                                    {{$year->year}} {{$year->semester}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-details mt-2 col-md-4">
                                        <select name="report_type" class="custom-select mb-3">
                                            @foreach($reports as $report)
                                                <option
                                                    value="{{$report->value}}"
                                                >
                                                    {{$report->label()}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-details mt-2 col-md-4">
                                        <button class="btn btn-success btn-lg" onclick="setFormAction('{{route('filter-students') }}')" type="button">تحميل</button>
                                        <button class="btn btn-success btn-lg" onclick="setFormAction('{{route('student-result-pdf')}}')" type="button"> pdf تحميل</button>
                                        <button type="submit" style="display: none;">Submit</button>

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


<script>
    function setFormAction(action) {
        document.getElementById('myForm').action = action;
        document.getElementById('myForm').submit()
    }
</script>
