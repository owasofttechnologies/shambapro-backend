<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
            .row-1 {
              display: flex;
              justify-content: space-between;
              align-items: flex-end;
              margin-top: 55px;
            }

            .row-2 {
              display: flex;
              justify-content: space-between;
              padding-top: 30px;
              border-bottom: 1px solid black;
            }

            .footer-logo {
              border: 1px solid black;
              
            }

            @font-face {
              font-family: Volkswagen-DemiBold;
              src: url("../fonts/Volkswagen-DemiBold Regular.ttf");
            }
            @font-face {
              font-family: Volkswagen-Medium;
              src: url("../fonts/Volkswagen-Medium Regular.ttf");
            }
            @font-face {
              font-family: Volkswagen-Regular;
              src: url("../fonts/Volkswagen-Regular Regular.ttf");
            }
            @font-face {
              font-family: AGaramondExp-Semibold;
              src: url("../fonts/AGaramondExp-Semibold.ttf");
            }
            .sunfarm h1 {
              font-size: 30px;
              font-family: AGaramondExp-Semibold;
              color: #3a8b38 !important;
              margin: 0%;
            }
            .sunfarm h2 {
              font-size: 16px;
              font-family: Volkswagen-Regular;
              color: #3a8b38 !important;
              margin-top: 5px;
              margin-bottom: 5px;
            }
            .sunfarm h4 {
              font-size: 22px;
              font-family: AGaramondExp-Semibold;
              color: #3a8b38 !important;
              margin-top: 5px;
              margin-bottom: 5px;
            }
            .sunfarm p {
              font-size: 14px;
              font-family: Volkswagen-Regular;
              color: #61797d;
              margin: 0%;
            }

            .invoice2 th {
              font-size: 14px;
              font-family: Volkswagen-Regular;
            }
            .invoice2 td {
              font-size: 14px;
              font-family: Volkswagen-Regular;
            }

            .table-group-divider {
              border-top: 2px solid #3A8B38 !important;
            }

            .dashed-border {
              border-bottom: 1px solid #3A8B38 !important;  
              border-right: 1px solid #3A8B38 !important;  

              /*background-image: linear-gradient(to right, #61797D 44%, transparent 0%);
              background-position: bottom;
              background-size: 8px 0.5px;
              background-repeat: repeat-x;*/
            }

            .TableHeading {
              line-height: 2 !important;
            }

            .transparentcolor {
              color: rgba(255, 255, 255, 0);
            }

            .footer-border {
              border-bottom: solid 3px #3A8B38;
            }

            .table-borderless th {
              font-size: 14px;
              font-family: Volkswagen-Regular;
            }
            .table-borderless td {
              color: #61797d;
              font-size: 12px;
              font-family: Volkswagen-Regular;
            }
            .table-borderless .color-dark {
              color: #273133;
            }
            .table-borderless .font-dark {
              color: #273133;
              font-family: Volkswagen-Medium;
            }

            .table-responsive p {
              font-family: Volkswagen-Regular;
              font-size: 12px;
              color: #61797D;
            }
            .table-responsive .farmstock {
              font-family: Volkswagen-DemiBold;
              font-size: 18px;
              border-bottom: none;
              line-height: 2.5;
              text-align: center;
            }

            .TableHeader {
              border: 1px solid black;
            }
            .TableHeader tr {
              background-color: #F8F8F8;
            }

            .TableBody {
              border: 1px solid black;
              border-top: 0px solid transparent !important;
            }
            .TableBody tr {
              border-bottom: none !important;
              border-top: none !important;
              color: #61797D;
            }
            .TableBody .dashed-border {
              border-bottom: 1px solid black !important;   
              border-right: 1px solid black !important; 
              /*background-image: linear-gradient(to right, #61797D 44%, transparent 0%);
              background-position: top;
              background-size: 8px 0.5px;
              background-repeat: repeat-x;*/
            }

            table tr:first-child {
              background-size: 8px 0px !important;
            }

            .paragraph {
              font-family: Volkswagen-Regular;
              font-size: 14px;
              color: #61797D;
              text-align: justify;
            }

            .report-details {
              font-family: Volkswagen-Regular;
              font-size: 14px;
            }
            .report-details p {
              color: #61797D;
            }
            .report-details span {
              color: #273133;
            }

            .table {
              background-color: transparent;
              width: 100%;
              margin-bottom: 1rem;
              vertical-align: top;
              border-color: black;
            }

            .table > :not(caption) > * > * {
              padding: 0.6rem 0.6rem;
              box-shadow: inset 0 0 0 9999px transparent;
            }

            .table > tbody {
              vertical-align: inherit;
            }

            .table > thead {
              vertical-align: bottom;
            }

            table {
              caption-side: bottom;
              border-collapse: collapse;
            }

            th {
              text-align: inherit;
              border-right: 1px solid black !important; 
            }

            td {
              
              border-right: 1px solid black !important; 
            }

            thead,
            tbody,
            tfoot,
            tr,
            td,
            th {
              border-color: inherit;
              border-style: solid;
              border-width: 0;
            }

            .table-bordered > :not(caption) > * > * {
              border-width: 0 1px;
            }

            .table-bordered > :not(caption) > * {
              border-width: 1px 0;
            }

            @media (min-width: 1400px) {
              .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
                max-width: 1320px;
                margin: auto;
              }
            }/*# sourceMappingURL=shamba.css.map */   
    </style>
    <title>ShambaPro</title>
  </head>
  <body>
    <div class="container-xxl">
      <div class="sunfarm py-4">
        <div class="row-1">
            <div class="col-1">
        <h1>SUNSHINE FARM LTD</h1>
        <h2 class="mb-1" style="font-size: 22px !important;">Herd Register</h2>
        <?php
    $day = Carbon\Carbon::now()->format('j');
    $symbol = Carbon\Carbon::now()->format('S');
    $year = Carbon\Carbon::now()->format('F Y');
    
    ?>
    <p class="mb-1" style="font-size: 14px !important;">FOR THE YEAR ENDED 31<sup>st</sup> DECEMBER {{Carbon\Carbon::now()->format('Y')}}</p>
    <p>Generated On {{$day}}<sup>{{$symbol}}</sup> {{$year}} </p>
    </div>
    <div class="col-2">
    
        
    </div>
    </div>
    </div>
      <div class="table-responsive">
        <!-- <div class="text-center farmstock">
            Herd Register
        </div> -->
        <table class="table table-bordered border-dark invoice2 mb-0">
          <thead class="TableHeader">
            <th>Herd Type</th>
            <th>Quantity</th>
          </thead>
          <tbody class="TableBody">
            @foreach($herd_report as $hr)
            <tr class="dashed-border">
              <td>{{ $hr->purpose }}</td>
              <td>{{ $hr->quantity }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <table class="table table-bordered border-dark invoice2 mb-0">
        <tbody class="footer-logo">  
          <tr>
            <td style="border: 1px solid;border-color: #273133;">
              <?php
              $image = public_path('src/imgs/logos/shambapro.webp');
              ?>
              <img src="{{ $image }}" alt="" style="margin-top:10px;margin-left: 10px;">
              <br/>
              <a href="www.shambapro.com">www.shambapro.com</a>
            </td>
            <td>
              <p class="py-4" style="color: #273133;">The data presented in this report is the sole property of the farm owner and is not to be shared or distributed to third parties without their written permission. For inquiries, please write to <a href="mailto:hello@shambapro.com"> hello@shambapro.com</a></p>
            </td>
          </tr>
        </tbody>
      </table> 
    </div>
  </body>
</html>
