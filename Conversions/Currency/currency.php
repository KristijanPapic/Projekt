<?php
  include "spoj.php" ;
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
      <?php  include "send-rate.php" ; ?>
      
      
      const rate = <?php  echo json_encode($_SESSION['rate']); ?>;
          
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
            <div>
              <h3>Meter</h3>
              <p>
                <b>Definition: </b>A meter, or metre (symbol: m), is the base
                unit of length and distance in the International System of Units
                (SI). The meter is defined as the distance traveled by light in
                1/299 792 458 of a second. This definition was slightly modified
                in 2019 to reflect changes in the definition of the second.
              </p>
              <p>
                <b>History/origin: </b>Originally, in 1793, the meter was
                defined as one ten-millionth of the distance from the equator to
                the North Pole. This changed in 1889, when the International
                prototype metre was established as the length of a prototype
                meter bar (made of an alloy of 90% platinum and 10% iridium)
                measured at the melting point of ice. In 1960, the meter was
                again redefined, this time in terms of a certain number of
                wavelengths of a certain emission line of krypton-86. The
                current definition of the meter is effectively the same as the
                definition that was adopted in 1983, with slight modifications
                due to the change in definition of the second.
              </p>
              <p>
                <b>Current use: </b>Being the SI unit of length, the meter is
                used worldwide in many applications such as measuring distance,
                height, length, width, etc. The United States is one notable
                exception in that it largely uses US customary units such as
                yards, inches, feet, and miles instead of meters in everyday
                use.
              </p>
            </div>
            <div>
              <h3>Mile</h3>
              <p>
                <b>Definition: </b>A mile (symbol: mi or m) is a unit of length
                in the imperial and US customary systems of measurement. It is
                currently defined as 5,280 feet, 1,760 yards, or exactly
                1,609.344 meters.
              </p>
              <p>
                <b>History/origin: </b>The mile is an English unit (predecessor
                of Imperial units and United States Customary Units) of length.
                The use of the abbreviation "m" for mile was common before the
                widespread adoption of the metric system; after the adoption of
                the metric system, "mi" became the preferred abbreviation in
                order to avoid confusion between miles and meters. The
                definition of a mile as 5,280 feet (or 8 furlongs), sometimes
                referred to as the statute mile, international mile, or land
                mile, which differs from the Roman mile of 5,000 feet, developed
                due to the importance of the furlong in England, back in 1593.
                There also exist a number of units based on, or closely related
                to, the Roman mile, including the nautical mile (1.852 km
                exactly), Italian mile (~1.852 km), and the Chinese mile (500m
                exactly).
              </p>
              <p>
                <b>Current use: </b> Although most countries have adopted the
                metric system and use kilometers, rather than miles, to express
                land distance, the mile is still prevalently used in countries
                such as the United States (US), United Kingdom (UK), Myanmar,
                and Liberia, as well as some other countries which are either US
                or UK territories, or have historical ties with either country.
              </p>
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
