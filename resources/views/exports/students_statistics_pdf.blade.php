<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="credit hour system for abu-qir institute">
    <meta name="developer" content="Eng. Kirollous Victor">
    <title>طباعة النتائج</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('images/lo.jpg')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <style>
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-size:14px;
        overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-size:14px;
        font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg .tg-0lax{text-align:left;vertical-align:top}
</style>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-0lax" colspan="2">إحصائيات التراكمي</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="tg-0lax">0</td>
        <td class="tg-0lax">عدد امتياز مع مرتبة الشرف</td>
    </tr>
    <tr>
        <td class="tg-0lax">0</td>
        <td class="tg-0lax">عدد جيد جدا مع مرتبة&nbsp;&nbsp;الشرف</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['excellentStudentsCount'] ?? 0}}</td>
        <td class="tg-0lax">عدد امتياز</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['veryGoodStudentsCount'] ?? 0}}</td>
        <td class="tg-0lax">عدد جيد جدا</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['goodStudentsCount']?? 0}}</td>
        <td class="tg-0lax">عدد جيد</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['passStudentsCount']?? 0}}</td>
        <td class="tg-0lax">عدد مقبول</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['failedStudentsCount']?? 0}}</td>
        <td class="tg-0lax">عدد راسبون</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['absentStudentsCount']?? 0}}</td>
        <td class="tg-0lax">عدد غائبون</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['suspendedStudentsCount']?? 0}}</td>
        <td class="tg-0lax">عدد موقوف قيدهم</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['oneSubjectFailedStudentCount']?? 0}}</td>
        <td class="tg-0lax">مادة</td>
    </tr>
    <tr>
        <td class="tg-0lax">{{$overview['twoSubjectFailedStudentCount']?? 0}}</td>
        <td class="tg-0lax">مادتين</td>
    </tr>
    </tbody>
</table>

<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.0.4/popper.js"></script>
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('js/adminlte.js')}}"></script>
<script src="{{asset('js/demo.js')}}"></script>
<script>
    $(window).on('load', function () {
        $('#f-height').height($('footer').height() + 30);
    });
    window.print();
</script>
</body>
</html>