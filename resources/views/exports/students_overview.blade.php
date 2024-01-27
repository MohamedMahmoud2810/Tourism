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

    .tg .tg-0lax {
        text-align: left;
        vertical-align: top;
        width: 100px;
    }
</style>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-0lax" rowspan="2">عدد الطلاب الراسبون</th>
        <th class="tg-0lax" colspan="8">الناجحون</th>
        <th class="tg-0lax" rowspan="2">عدد الطلاب الموقوف قيدهم</th>
        <th class="tg-0lax" rowspan="2">عدد الطلاب الغائبون</th>
        <th class="tg-0lax" rowspan="2">عدد الطلاب الحاضرون</th>
        <th class="tg-0lax" rowspan="2">عدد الطلاب المتقدمون</th>
        <th class="tg-0lax" rowspan="2">عدد الطلاب المقيدون</th>
    </tr>
    <tr>
        <th class="tg-0lax">النسبة المئوية للنجاح</th>
        <th class="tg-0lax">المجموع</th>
        <th class="tg-0lax">مادتين</th>
        <th class="tg-0lax">مادة</th>
        <th class="tg-0lax">مقبول</th>
        <th class="tg-0lax">جيد</th>
        <th class="tg-0lax">جيد جدا</th>
        <th class="tg-0lax">ممتاز</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="tg-0lax">{{$overview['failedStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['totalSuccessPercentage']}}%</td>
        <td class="tg-0lax">{{$overview['succeededStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['twoSubjectFailedStudentCount']}}</td>
        <td class="tg-0lax">{{$overview['oneSubjectFailedStudentCount']}}</td>
        <td class="tg-0lax">{{$overview['passStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['goodStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['veryGoodStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['excellentStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['suspendedStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['absentStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['presentStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['appliedStudentsCount']}}</td>
        <td class="tg-0lax">{{$overview['enrolledStudentsCount']}}</td>
    </tr>
    </tbody>
</table>
