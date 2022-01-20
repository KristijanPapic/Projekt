<?php
  include "connection.php" ;
  ?>
  <!DOCTYPE html>

  <html>
    <head>
      <title>SwiftConversions</title>
      <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
      <link rel="stylesheet" href="/Projekt/style.css">
      <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
      <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css">
      <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
      <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
        
      </script>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
      <script>
      
       async function currency(){
      const API_URL = "https://open.er-api.com/v6/latest/USD";
      const api = await fetch(API_URL);
      const data = await api.json();
      const rates = data.rates;
      const next_update_time = data.time_next_update_unix;
      console.log(data);

      $.ajax({
        url: '/Projekt/Conversions/Currency/update-rate.php',
        type: "post",
        data: {rates: rates, update_time: next_update_time },
        success: function(res){
          console.log(res);
        }
      })
      console.log("update"); 
       }

      const saved_next_update_time =  <?php
      $sql = "SELECT * FROM `rate_update_time`";
      $rez = mysqli_query($conn,$sql) or die(mysqli_error($conn));
      $row = mysqli_fetch_assoc($rez);
      $tm = $row["time"];
      echo $tm;?>;
       
      let current_time = Date.now() / 1000;
      if(current_time > saved_next_update_time){
        currency();
      }
      var rate;
      $.ajax({
    type: "POST",
    url: 'send-rate.php',
    dataType: 'json',
    async: false,
    data: {"data":"check"},
    success: function(data){
        rate = data;
        console.log(rate);
        console.log(rate['HRK']);
        console.log("radi");
        console.log(data.HRK);
    }
 });
 console.log(rate.USD);
          
      let previous_left_select = "";
      let previous_right_select = "";
      function left_change(select){
       if(previous_left_select != ""){
        $('#right-select option[value ="' + previous_left_select + '"]').prop("disabled", false);
      }
        $('#right-select option[value ="' + select.value + '"]').prop("disabled", true);
          previous_left_select = select.value;
        }

        function right_change(select){
          if(previous_right_select != ""){
            $('#left-select option[value ="' + previous_right_select + '"]').prop("disabled", false);
          }
          $('#left-select option[value ="' + select.value + '"]').prop("disabled", true);
          previous_right_select = select.value;
        }

          function convert(){
            let left_value = $('#left_input').val();
            let left_select = $('#left-select').val();
            let right_select = $('#right-select').val();
            if(left_select == "USD"){
              $('#right_input').val((left_value * rate[right_select]).toFixed(3));
            }
            else{
              left_value = left_value/rate[left_select];
              $("#right_input").val((left_value * rate[right_select]).toFixed(3));
            }
          }

          function reverse(){
            let left_select = $('#left-select').val();
            let right_select = $('#right-select').val();
            $('#left-select').val(right_select).trigger("change");
            $('#right-select').val(left_select).trigger("change");
            let left_value = $('#left_input').val();
            let right_value = $('#right_input').val();
            $('#left_input').val(right_value);
            $('#right_input').val(left_value);            

          }

          $(document).ready(function(){
            $('#right-select').select2({
              theme: "bootstrap-5",
            });
            $('#left-select').select2({
              theme: "bootstrap-5",
            });
            fill_table();            
          });
          
        </script>
    </head>
    <body>
      <div id="page-container">
        <div id="page-content">
          <div id="nav-placeholder"></div>

          <script>
      $(function(){
        $("#nav-placeholder").load("/Projekt/nav.html");
      });
    </script>
          <div class="row">
            <div class="col text-center">
              <h3 id="con_title">Currency conversion</h3>
            </div>
          </div>
          <div class="container-sm mx-auto" id="conversion-block">
            <form autocomplete="off" id="con_form">
              <div class="row">
                <div class="col-sm-5" align="center">
                  <label class="control-label" for="left_input" id="left_input_label">From</label>
                  <input type="number" class="form-control-lg mb-4" id="left_input">

                  <select class="form-select form-select-lg" id="left-select" onchange="left_change(this);">
                  </select>

                </div>
                <div class="col-sm-2 text-center" id="glyph_col">
                  <button type="button" onclick="reverse();" class="btn btn-lg btn-outline-dark rounded-circle mt-3 ms-3 me-2"><i class="bi bi-arrow-left-right" id="con_glyph"></i></button>
                </div>

                <div class="col-sm-5" align="center">
                  <label for="right_input" id="right_input_label">To</label>
                  <input type="number" class="form-control-lg mb-4" id="right_input"
                    readonly step=".01">

                  <select class="form-select form-select-lg" id="right-select" onchange="right_change(this);">
                  </select>

                  <script>
                    $(function(){
                      $("#right-select").load("select.html")
                      $("#left-select").load("select.html")
                    });
                    </script>
                </div>



              </div>



              <div class="text-center" id="buttons">
                <button type="button" class="btn btn-dark btn-lg"
                  onclick="convert()">Convert</button>
                <button type="reset" class="btn btn-dark btn-lg">Clear</button>
              </div>
            </form>
          </div>
          <div class="container-fluid pt-5 border border-dark" id="con-desc">
            <div class="row p-5">
      <h3 class="text-center">Current rates to USD</h3>
    <table class="table table-bordered text-center" id="con_table">
      
        <tr>
          <th class="w-50" id="table_from">Currency</th>
          <th class="w-50" id="table_to">Rate</th>
        </tr>
        <tr>
          <td>EUR-Euro</td>
          <td id="table_eur_rate"></td>
        </tr>
        <tr>
          <td>GBP-British Pound</td>
          <td id="table_gbp_rate"></td>
        </tr>
        <tr>
          <td>INR-Indian Rupee</td>
          <td id="table_inr_rate"></td>
        </tr>
        <tr>
          <td>AUD-Australian Dollar</td>
          <td id="table_aud_rate"></td>
        </tr>
        <tr>
          <td>CAD-Canadian Dollar</td>
          <td id="table_cad_rate"></td>
        </tr>
        <tr>
          <td>SGD-Singapore Dollar</td>
          <td id="table_sgd_rate"></td>
        </tr>
        <tr>
          <td>CHF-Swiss Franc</td>
          <td id="table_chf_rate"></td>
        </tr>
        <tr>
          <td>JPY-Japanese Yen</td>
          <td id="table_jpy_rate"></td>
        </tr>



    
    </table>
    <script>
      function fill_table(){
        $('#table_eur_rate').text(rate["EUR"]);
        $('#table_gbp_rate').text(rate["GBP"]);
        $('#table_inr_rate').text(rate["INR"]);
        $('#table_aud_rate').text(rate["AUD"]);
        $('#table_cad_rate').text(rate["CAD"]);
        $('#table_sgd_rate').text(rate["SGD"]);
        $('#table_chf_rate').text(rate["CHF"]);
        $('#table_jpy_rate').text(rate["JPY"]);
      }
    </script>
    </div>
          </div>
        </div>
      </div>



      <div id="footer-placeholder"></div>

      <script>
      $(function(){
        $("#footer-placeholder").load("/Projekt/footer.html");
      });
    </script>
    </body>
  </html>
