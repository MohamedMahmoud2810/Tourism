<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        .tg {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .tg td {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg th {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg .tg-0pky {
            border-color: inherit;
            text-align: left;
            vertical-align: top
        }

        .tg .excel-heading th {
            background-color: #F2F2F2; /* Light gray background */
            font-size: 16px;
            font-weight: bold;
            padding: 15px 5px; /* Increased padding for emphasis */
        }
    </style>
</head>
<body>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-0pky excel-heading" colspan="3"> نتيجة {{$group->name}} شعبة {{$department->name }}</th>
        <th class="tg-0pky excel-heading" colspan="3"> قسم {{$specialize->name}}</th>
        <th class="tg-0pky excel-heading" colspan="3"> دور {{$year->year}} {{$year->semester}}</th>
    </tr>
    </thead>
    <thead>
    <tr>
        <th class="tg-0pky">الاسم</th>
        <th class="tg-0pky">المادة</th>
        @foreach($subjectNames as $name)
            <th class="tg-0pky" colspan="3">{{$name}}</th>
        @endforeach
        <th class="tg-0lax" rowspan="3">مجموع الدرجات</th>
        <th class="tg-0lax" rowspan="3">النسبة المئوية</th>
        <th class="tg-0lax" rowspan="3">التقدير العام</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="tg-0pky" rowspan="2">رقم الجلوس</td>
        <td class="tg-0pky">توزيع الدرجات</td>
        @foreach($subjectDistributions as $subjectDistribution)
            <td class="tg-0pky">{{$subjectDistribution['max_written']}}</td>
            <td class="tg-0pky">{{$subjectDistribution['max_kpis']}}</td>
            <td class="tg-0pky">{{$subjectDistribution['max_applied']}}</td>
        @endforeach
    </tr>
    <tr>
        <td class="tg-0pky">العظمي/الصغري</td>
        @foreach($subjectDistributions as $subjectDistribution)
            <td class="tg-0pky" colspan="2">{{$subjectDistribution['max_grade']}}</td>
            <td class="tg-0pky">{{$subjectDistribution['min_grade']}}</td>
        @endforeach
    </tr>
    @foreach($students as $student)
        <tr>
            <td class="tg-0pky" rowspan="4">{{$student->name}}</td>
            <td class="tg-0pky">اس</td>
            @foreach($student->result as $result)
                <td class="tg-0pky" colspan="3">{{$result->kpis}}</td>
            @endforeach
            <td class="tg-0lax" rowspan="6">{{$student->studentResults[0]->grade ?? ''}}</td>
            <td class="tg-0lax" rowspan="6">{{$student->studentResults[0]->percentage ?? ''}}%</td>
                <td class="tg-0lax" rowspan="6">{{$student->studentResults[0]->sum_grade ?? ''}}</td>
        </tr>
        <tr>
            <td class="tg-0pky">تطبيقي</td>
            @foreach($student->result as $result)
                <td class="tg-0pky" colspan="3">{{$result->applied}}</td>
            @endforeach
        </tr>
        <tr>
            <td class="tg-0pky">النظري</td>
            @foreach($student->result as $result)
                <td class="tg-0pky" colspan="3">{{$result->written}}</td>
            @endforeach
        </tr>
        <tr>
            <td class="tg-0pky">المجموع</td>
            @foreach($student->result as $result)
                <td class="tg-0pky" colspan="3">{{$result->kpis + $result->written + $result->applied}}</td>
            @endforeach
        </tr>
        <tr>
            <td class="tg-0pky" rowspan="2">{{$student->code}}</td>
            <td class="tg-0pky">بعد التيسير</td>
        </tr>
        <tr>
            <td class="tg-0pky">التقدير</td>
            @foreach($student->result as $result)
                <td class="tg-0pky" colspan="3">{{$result->grade}}</td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>
</body>
</html>
