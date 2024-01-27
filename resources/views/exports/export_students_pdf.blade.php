<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css" type="text/css"
        rel="stylesheet" media="mpdf">
    <link rel="stylesheet" href="css/custom.css" type="text/css" rel="stylesheet" media="mpdf">

    <title>طباعة النتائج</title>
    <style type="text/css" media="mpdf">

      
        .tg {
            border-collapse: collapse;
            border-spacing: 0;
        }

        td {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            /* add font 'Amiri-Regular.tff' from asset{'fonts/amiri'} as font-family */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            overflow: hidden;
            padding: 2px 2px;
            word-break: normal;
            text-align: right;
        }

        .tg th {
            border-color: black;
            border-style: double;
            border-width: 2px;
            font-family: 'Helvetica';
            font-size: 8pt;
            font-weight: bold;
            overflow: hidden;
            padding: 2px 2px;
            word-break: normal;
        }

        .tg th>div {
            /* background-color: #F2F2F2; */
            /* Light gray background */
            font-size: 12px;
            font-weight: bold;
            padding: 5px 3px;
            text-align: center;
            font-family: 'Helvetica';
            font-weight: bold;
            /* Increased padding for emphasis */
        }
        @page { size: landscape }
        @media print {
        html, body {
            width: 210mm;
            height: 297mm;
        }
    }
    /* if table cell is empty make it's backcolor gray */
    .tg td:empty {
        background-color: #f2f2f294;
    }
    </style>
</head>

<body class="A3 landscape">

    <table class="tg">
        <thead style="border: none;">
            
            <tr class="row justify-content-between">
                <th colspan="5" style="border:none;font-family: Arial, sans-serif;"  class="text-center font-weight-bolder text-blue">
                    وزارة التعليم العالى <br>المعهد العالى
                        للسياحةوالفنادق <br>ابو قير الاسكندرية
                </th>
            
                <th colspan="35" style="border: none;" class="text-center font-weight-bolder text-danger">
                <div> نتيجة {{ $group->name }} شعبة {{ $department->name }}</div>
                <div> قسم {{ $specialize->name }}</div>
                <div> دور {{ $year->year }} {{ $year->semester }}</div>
                </th>
                <th colspan="10" style="border: none;" class="text-left">
                    <img class="img-fluid" src="images/lo2.png" alt="logo" width="100" height="100">
                </th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>المادة</th>
                @foreach ($subjectNames as $name)
                    <th colspan="3">{{ $name }}</th>
                @endforeach
                <th rowspan="3">مجموع الدرجات</th>
                <th rowspan="3">النسبة المئوية</th>
                <th rowspan="3">التقدير العام</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="2">رقم الجلوس</td>
                <td>توزيع الدرجات</td>
                @foreach ($subjectDistributions as $subjectDistribution)
                    <td>{{ $subjectDistribution['max_written'] }}</td>
                    <td>{{ $subjectDistribution['max_kpis'] }}</td>
                    <td>{{ $subjectDistribution['max_applied'] }}</td>
                @endforeach
            </tr>
            <tr>
                <td>العظمي/الصغري</td>
                @foreach ($subjectDistributions as $subjectDistribution)
                    <td colspan="2">{{ $subjectDistribution['max_grade'] }}</td>
                    <td>{{ $subjectDistribution['min_grade'] }}</td>
                @endforeach
            </tr>
            @foreach ($students as $student)
                <tr>
                    <td rowspan="4">{{ $student->name }}</td>
                    <td>اس</td>
                    @foreach ($student->result as $result)
                        <td colspan="3">{{ $result->kpis }}</td>
                    @endforeach
                    <td rowspan="6">{{ $student->total_result ?? '' }}</td>
                    <td rowspan="6">{{ $student->total_percentage ?? '' }}%</td>
                    <td rowspan="6">{{ $student->overall_grade ?? '' }}</td>
                </tr>
                <tr>
                    <td>تطبيقي</td>
                    @foreach ($student->result as $result)
                        <td colspan="3">{{ $result->applied }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>النظري</td>
                    @foreach ($student->result as $result)
                        <td colspan="3">{{ $result->written }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>المجموع</td>
                    @foreach ($student->result as $result)
                        <td colspan="3">{{ $result->kpis + $result->written + $result->applied }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td rowspan="2">{{ $student->code }}</td>
                    <td>بعد التيسير</td>
                </tr>
                <tr>
                    <td>التقدير</td>
                    @foreach ($student->result as $result)
                        <td colspan="3">{{ $result->grade }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            
        </tfoot>
    </table>
</body>

</html>
