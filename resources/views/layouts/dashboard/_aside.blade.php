<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dashboard_files/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
{{--                    {{ Redirect::to('/login') }}--}}
                <p class="m-4"> {{Auth::user()->name ?? 'Admin'}}</p>
            </div>
        </div>

          <ul class="sidebar-menu">
              <li class="mega">
                    <a href="#" data-toggle="collapse" data-target="#dep"><i class="fa fa-th"></i> الاقسام / الشعب <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="dep" class="collapse">
                        <li><a href="{{route('Departments')}}"><i class="fa fa-th"></i> <span> الاقسام / الشعب </span></a></li>
                        <li><a href="{{route('add.Department')}}"><i class="fa fa-th"></i><span> اضافة قسم / شعبة </span></a></li>
                        <li><a href="{{route('add.specialize')}}"><i class="fa fa-th"></i><span> اضافة التخصص </span></a></li>
                        <li><a href="{{route('add.GrDepSp')}}"><i class="fa fa-th"></i><span> اضافة فرقه بشعبة بتخصص </span></a></li>
                    </ul>
                </li>
                <li class="mega">
                    <a href="#" data-toggle="collapse" data-target="#sub"><i class="fa fa-th"></i> االمقررات الدراسية <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="sub" class="collapse">
                       <li><a href="{{route('Subjects')}}"><i class="fa fa-book" ></i> <span>المقررات الدراسية  </span></a></li>
                       <li><a href="{{route('add.Subjects')}}"><i class="fa fa-book"></i> <span> اضافة مقرر دراسى </span></a></li>
                    </ul>
                </li>
                <li class="mega">
                    <a href="#" data-toggle="collapse" data-target="#stu"><i class="fa fa-th"></i> الطلاب <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="stu" class="collapse">
                       <li><a href="{{route('Students')}}"><i class="fa fa-users"></i><span> الطلاب </span></a></li>
                       <li><a href="{{route('add.Students')}}"><i class="fa fa-user-plus"></i><span> اضافة طلاب </span></a></li>
                       <li><a href="{{route('update.students')}}"><i class="fa fa-user-plus"></i><span> التدريب و التربية العسكرية </span></a></li>
                       <li><a href="{{route('update.students.seatNo')}}"><i class="fa fa-user-plus"></i><span> تحديث ارقام الجلوس</span></a></li>
                    </ul>
                </li>
                 <li class="mega">
                    <a href="#" data-toggle="collapse" data-target="#res"><i class="fa fa-th"></i> النتائج <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="res" class="collapse">
                       <li><a href="{{route('getResults')}}"><i class="fa fa-user-plus"></i><span> النتائج </span></a></li>
                       <li><a href="{{route('tableResults')}}"><i class="fa fa-user-plus"></i><span> جدول النتايج </span></a></li>
                        <li><a href="{{route('uploadResults')}}"><i class="fa fa-user-plus"></i><span> رفع نتيجة </span></a></li>
                        <li><a href="{{route('TransferStudentResults')}}"><i class="fa fa-user-plus"></i><span> رفع نتيجة المحولين </span></a></li>
                        <li><a href="{{route('getTransferStudentResults')}}"><i class="fa fa-user-plus"></i><span> نتيجة المحولين </span></a></li>
                        <li><a href="{{route('createYear')}}"><i class="fa fa-user-plus"></i><span> اضافة سنة دراسيه جديدة </span></a></li>
                    </ul>
                </li>
              <li class="mega">
                    <a href="#" data-toggle="collapse" data-target="#bonus"><i class="fa fa-th"></i> درجات التيسير  <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="bonus" class="collapse">
                        <li><a  href="{{route('bonusDegree',1)}}"><i class="fa fa-signal"></i><span> درجات التيسير </span></a></li>
                        <li><a  href="{{route('add.bonus')}}"><i class="fa fa-signal"></i><span> تحديث الفرق والتيسير </span></a></li>
                       </ul>
              </li>

              <li class="mega">
                  <a href="#" data-toggle="collapse" data-target="#rep"><i class="fa fa-th"></i> تقارير <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                  <ul id="rep" class="collapse">
                      <li><a href="{{route('student-reports')}}"><i class="fa fa-users"></i><span>  تقارير الطلاب </span></a></li>
                  </ul>
              </li>



         </ul>


    </section>

</aside>
