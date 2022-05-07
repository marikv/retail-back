<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        @font-face {
            font-family: 'Times New Roman Bold';
            src: url({{ storage_path('fonts/Times New Roman Bold.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Times New Roman';
            src: url({{ storage_path('fonts/times-newer-roman-regular.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'AbhayaLibre';
            src: url({{ storage_path('fonts/AbhayaLibre-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        body {
            /*font-family: DejaVu Sans;*/
            /*font-family: "Times New Roman Bold";*/
            font-family: 'Times New Roman';
            font-size: 12px;
        }
        .strong {
            font-family: 'Times New Roman Bold';
        }
        .title-contract {
            margin-top: 13px;
        }
        .underline {
            text-decoration: underline;
        }
        .title {
            margin-top: 5px;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .title2 {
            font-size: 13px;
        }
        .subtitle {
            margin-top: 25px;
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .subsubtitle {
            font-weight: bold;
            font-size: 13px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .w-100 {
            width: 100%;
        }
        .w-50 {
            width: 50%;
        }
        .w-25 {
            width: 25%;
        }
        .bb {
            border-bottom: 1px solid #000000;
        }
        .bt {
            border-top: 1px solid #000000;
        }
        .bl {
            border-left: 1px solid #000000;
        }
        .br {
            border-right: 1px solid #000000;
        }
        .ba {
            border: 1px solid #000000;
        }
        .mt-20 {
            margin-top: 20px !important;
        }
        .mt-32 {
            margin-top: 32px;
        }
        .mr-10 {
            margin-right: 10px !important;
        }
        .ml-10 {
            margin-left: 10px !important;
        }
        .pr-10 {
            padding-right: 10px !important;
        }
        .pl-10 {
            padding-left: 10px !important;
        }
        .mb-10 {
            margin-bottom: 10px !important;
        }
        .mb-15 {
            margin-bottom:15px !important;
        }
        .mb-20 {
            margin-bottom: 20px !important;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table td{
            padding: 3px;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000000;
        }
        .logo {
            height: 25px;
            min-height: 25px;
            max-height: 25px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url('data:image/png;base64, <?php echo $data['logo_base64'] ?? ''; ?>');
        }
        .dae-formula {
            height: 180px;
            min-height: 180px;
            max-height: 180px;
            width: 290px;
            min-width: 290px;
            max-width: 290px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url('data:image/png;base64, <?php echo $data['dae-formula_base64'] ?? ''; ?>');
        }
        .stampila-semnatura {
            height: 235px;
            min-height: 235px;
            max-height: 235px;
            width: 235px;
            min-width: 235px;
            max-width: 235px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url('data:image/png;base64, <?php echo $data['stampila-semnatura'] ?? ''; ?>');
        }
        hr {
            border: none;
            background: transparent;
            border-bottom: 1px solid #000000;
        }
        #header {
            position: fixed;
            left: 0px;
            top: 0px;
            right: 0px;
            height: 120px;
            background-color: orange;
            text-align: center;
        }
        #footer {
            position: fixed;
            left: 0;
            bottom: 0;
            right: 0;
            height: 90px;
            width: 100%;
            background-color: transparent;
            padding: 0;
        }
        #footer .page:after {
            content: counter(page);
        }
        .page_break {
            page-break-before: always;
        }
    </style>
</head>
<body>
{{--<div style="text-align: center;">--}}
{{--    <div class="logo"></div>--}}
{{--    <div style="margin-top: 5px;font-size: 9px; color: #5a7c92; line-height: 8px;">--}}
{{--        juridical_name--}}
{{--        <div>--}}
{{--            addresa c/f idno--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            tel: 0231494949, 0231494949;--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
