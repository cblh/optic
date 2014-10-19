<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <!-- <link href="http://cdn.bootcss.com/bootstrap-datetimepicker/0.0.11/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> -->
</head>

<body>
    <frame>
    <form>
    <div class="form-group">
        <div class="input-group date form_datetime col-md-5" data-date-format='yyyy-mm-dd hh:ii:ss' data-link-field="dtp_input1">
            <input class="form-control" size="16" type="text" value="" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        </div>
		<input type="hidden" id="dtp_input1" value=""/><br/>
    </div>
    </form>
    </frame>
<script src="http://cdn.bootcss.com/jquery/2.0.3/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./bootstrap-datetimepicker.js" charset="UTF-8"></script>
<!-- <script src="http://cdn.bootcss.com/bootstrap-datetimepicker/0.0.11/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script> -->
<script type="text/javascript" src="./bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
    //截止时间选择器
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        format: 'yyyy-mm-dd hh:ii:ss',
        autoclose: 1,
        todayBtn:  1,
        todayHighlight: 1,
        startView: 2,
        minView: 'day',
        language: 'zh-CN',
        pickerPosition: "bottom-left",
        showMeridian: 'true',
        startDate: "<?php date_default_timezone_set('Asia/Shanghai');echo (date('Y-m-d H:i:s'));?>",
    });
</script>


</body>
</html>