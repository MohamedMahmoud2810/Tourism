let url_d, url_s, url_sd, csrf, mode;

function define(url_1, url_2, url_3, cs, m) {
    url_d = url_1;
    url_s = url_2;
    url_sd = url_3;
    csrf = cs;
    mode = m;
}

$(document).ready(function () {
    $('#groups-dropdown').on('change', function () {
        let group_id = this.value;
        $('#department-dropdown').html('<option value="">اختر الفرقة اولا</option>');
        $.ajax({
            url: url_d,
            type: "POST",
            data: {
                group_id: group_id,
                _token: csrf
            },
            dataType: 'json',
            success: function (result) {
                $('#department-dropdown').html('<option value="">اختر الشعبة </option>');
                $.each(result.departments, function (key, value) {
                    $("#department-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $('#specialize-dropdown').html('<option value="">اختر الشعبة اولا</option>');
            },
            error: function (result) {
                console.log(result);
            }
        });
    });

    $('#department-dropdown').on('change', function () {
        let department_id = this.value;
        let group_id = $('#groups-dropdown').val();
        $("#specialize-dropdown").html('');
        $.ajax({
            url: url_s,
            type: "POST",
            data: {
                group_id: group_id,
                department_id: department_id,
                _token: csrf
            },
            dataType: 'json',
            success: function (result) {
                $('#specialize-dropdown').html('<option value="">اختر التخصص</option>');
                $.each(result.specializes, function (key, value) {
                    $("#specialize-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    });

    if (mode === 1) {
        $('#specialize-dropdown, #semester').on('change', function () {
            $("#subject-dropdown").html('');
            let specialize_id = $('#specialize-dropdown').val();
            let group_id = $('#groups-dropdown').val();
            let department_id = $('#department-dropdown').val();
            $.ajax({
                url: url_sd,
                type: "POST",
                data: {
                    group_id: group_id,
                    department_id: department_id,
                    specialize_id: specialize_id,
                    term: $('#semester').val(),
                    _token: csrf
                },
                dataType: 'json',
                success: function (result) {
                    $('#subject-dropdown').html('<option value=""> اختر التخصص اولا</option>');
                    $.each(result.subjects, function (key, value) {
                        $("#subject-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });
    } else if (mode === 2) {
        $('#specialize-dropdown').off().on('change', function () {
            let specialize_id = $('#specialize-dropdown').val();
            let group_id = $('#groups-dropdown').val();
            let department_id = $('#department-dropdown').val();
            $.ajax({
                url: url_sd,
                type: "POST",
                data: {
                    group_id: group_id,
                    department_id: department_id,
                    specialize_id: specialize_id,
                    _token: csrf
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    $("#table1 tbody").html('');
                    $.each(result.subjects, function (key, value) {
                        $("#table1 tbody").append(`<tr>
               <td><input type="text"  class="form-control" value="` + value.name + `" style="min-width:150px;font-size: 20px;"
                id="subject" name="subjects[` + value.id + `]" readonly ></td>
               <td><input type="text" class="form-control" placeholder='ادخل الدرجة الكلية' style="min-width:150px"
                    id="degree" name="degree[` + value.id + `]"></td>
            </tr>`);
                    });
                }
            });
        });
    }
});


