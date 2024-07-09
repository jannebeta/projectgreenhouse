<?php
require_once("config.php");
?>
<!DOCTYPE html> 
<html lang="en"> 
  <head> 
 
  
    <link href= 
 "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    /> 
	

	  <script src="js/jquery-3.7.1.min.js"></script>
    <script src= 
"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"> 
    </script> 
	

	
	<title>Projekti Kasvihuone</title>
	<link rel="icon" type="image/x-icon" href="images/greenhouse.png">
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head> 
  
  <body> 
  
    <script>
	var apiURL = '<?php echo $apiUrl; ?>';
	var firstMeasurementDateF = 0;
		
		function toTimestamp(strDate){
   var datum = Date.parse(strDate);
   return datum/1000;
}

function pressureToEmoji(pressure)
{
	if (pressure < 1000)
	{
		return "☁️";
	}
	else
	{
		return "🌞";
	}
}

$( document ).ready(function() {
 
 $('body').tooltip({
    selector: '[data-toggle="tooltip"]'
});

  $.getJSON( apiURL + "get_measurements", {
    amount: "12",
  })
    .done(function( data ) {
      $.each( data.measurements, function( i, item ) {
		  var measurementDate = new Date(item.timestamp * 1000).toLocaleString([], { dateStyle: 'short', timeStyle: 'short' });
		  $( "#tblMeasurements tbody" ).append( "<tr><td><b> " + measurementDate + "</td><td>" + item.temperatureInhouse + " &degC " + item.humidityInhouse + " % </td><td> " + item.temperatureOutdoor + " &degC  <label data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ilmanpaine (meren päällä / natiivi): " + item.pressureOutdoor_sealevel + " hPa / " + item.pressureOutdoor + " hPa\">" + pressureToEmoji(item.pressureOutdoor) + "</label></td></tr>" );
        
      });
    });
	
  $.getJSON( apiURL + "get_last_measurement", {
  })
    .done(function( data ) {


		  var lastMeasurementDate = new Date(data.timestamp * 1000).toLocaleString([], { dateStyle: 'short', timeStyle: 'short' });
		  var firstMeasurementDate = new Date(data.firstMeasurement * 1000).toLocaleString([], { dateStyle: 'short'});
				  
		  $( "#lastMeasurementDateTxt" ).html("<i>" + lastMeasurementDate + "</i>");
		  $( "#lastInhouseMeasurement" ).html("<i>" + data.temperatureInhouse + " &degC " + data.humidityInhouse + " %</i>");
		  $( "#lastOutdoorMeasurement" ).html("<i>" + data.temperatureOutdoor + " &degC <label data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ilmanpaine (natiivi): " + data.pressureOutdoor + " hPa\">" + pressureToEmoji(data.pressureOutdoor) + "</label></i>");
		  $( "#measurementsStarted" ).html("Mittaukset alkaneet " + firstMeasurementDate);
		  
		
		   
		  firstMeasurementDateF = new Date(data.firstMeasurement * 1000).toISOString().substr(0, 10);
			
		  $('#datepickerFrom').attr({"min" : firstMeasurementDateF}); 
		   $('#datepickerFrom').attr({"value" : firstMeasurementDateF}); 
		    $('#datepickerTo').attr({"min" : firstMeasurementDateF}); 
	  

    });
	
	
	
	$.getJSON( apiURL + "get_averages", {
  })
    .done(function( data ) {

		  $( "#temperaturesAverageInhouse" ).html("<i>Kasvihuone: " + data.temperatureInhouseAverage + " &degC " + data.humidityInhouseAverage + " %</i>");
		    $( "#temperaturesAverageOutdoor" ).html("<i>Ulkoilma: " + data.temperatureOutdoorAverage + " &degC <label data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ilmanpaine (natiivi): " + data.pressureOutdoorAverage + " hPa\">" + pressureToEmoji(data.pressureOutdoorAverage) + "</label></i>");
        
    });
	  $(document).on("click","#timespanDropdown ul.dropdown-menu li a", function(){
      
       var timespan = this.getAttribute('data-timespan');
	   
	   $.getJSON( apiURL + "get_averages?timespan=" + timespan, {
  })
    .done(function( data ) {

		$( "#timespanPrettyText" ).html(data.timespanPretty + " keskiarvo");
		  $( "#temperaturesAverageInhouse" ).html("<i>Kasvihuone: " + data.temperatureInhouseAverage + " &degC " + data.humidityInhouseAverage + " %</i>");
		    $( "#temperaturesAverageOutdoor" ).html("<i>Ulkoilma: " + data.temperatureOutdoorAverage + " &degC <label data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ilmanpaine (natiivi): " + data.pressureOutdoorAverage + " hPa\">" + pressureToEmoji(data.pressureOutdoorAverage) + "</label></i>");

        
    });
	

    });
	
	 $("#oldMeasurements").submit(function (event) {
    var formData = {
      from: toTimestamp($("#datepickerFrom").val()),
      to: toTimestamp(($("#datepickerTo").val())) + 84924, // end of day?
      amount: $("#resultAmount").val(),
	  order: $("#resultOrder").val()
    };

    $.ajax({
      type: "GET",
      url: apiURL + "get_measurements",
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
		
     $( "#tblMeasurements tbody" ).empty();
	 
	 $.each( data.measurements, function( i, item ) {
		  var measurementDate = new Date(item.timestamp * 1000).toLocaleString([], { dateStyle: 'short', timeStyle: 'short' });
		  $( "#tblMeasurements tbody" ).append( "<tr><td><b> " + measurementDate + "</td><td>" + item.temperatureInhouse + " &degC " + item.humidityInhouse + " % </td><td> " + item.temperatureOutdoor + " &degC  <label data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ilmanpaine (meren päällä / natiivi): " + item.pressureOutdoor_sealevel + " hPa / " + item.pressureOutdoor + " hPa\">" + pressureToEmoji(item.pressureOutdoor) + "</label></td></tr>" );
        
      });
	  
    });

    event.preventDefault();
  });
  
})();

</script>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand  
                navbar-dark bg-success"> 
      <div class="container-fluid"> 
        <a class="navbar-brand" href="./"> 
          Projekti Kasvihuone 
        </a> 
         <div class="navbar-nav">
		  <a class="nav-link" id="releasenoteslink" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#lastAdditions">Viimeisimmät lisäykset</a>
        <a class="nav-link" href="https://janne.ankkaverkko.com">Kotisivuilleni</a>
      </div>
      </div> 
    </nav> 
  
  <div class="modal fade" id="lastAdditions" tabindex="-1" aria-labelledby="lastAdditionsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lastAdditions">Viimeisimmät lisäykset</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="latestAdditionsContent">
      <?php 
	  echo file_get_contents('static/changelog.txt', true);
	  ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Selvä juttu!</button>
      </div>
    </div>
  </div>
</div>

    <div class="container mt-5"> 
      <div class="row"> 
        <div class="col-sm-2"><p><b>Viimeisimmät arvot</b></p><p id="lastMeasurementDateTxt">Lataan..</p><p><b>Kasvihuoneen arvot:</b></p><p id="lastInhouseMeasurement"><p><b>Ulkoilman arvot:</b></p><p id="lastOutdoorMeasurement"></p><p>
<div id="timespanDropdown" class="dropdown">
  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" style="color: black; text-decoration: none;" title="Vaihda aikamäärettä">
    <b id="timespanPrettyText">Vuoden keskiarvo</b>
  </a>

  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <li><a data-timespan="0" class="dropdown-item" href="javascript:void(0)">Vuoden keskiarvo</a></li>
    <li><a data-timespan="1" class="dropdown-item" href="javascript:void(0)">Kuukauden keskiarvo</a></li>
    <li><a data-timespan="2"class="dropdown-item" href="javascript:void(0)">Viikon keskiarvo</a></li>
	<li><a data-timespan="3" class="dropdown-item" href="javascript:void(0)">Päivän keskiarvo</a></li>
  </ul>
</div>
  </p>
  <p id="temperaturesAverageInhouse"></p><p id="temperaturesAverageOutdoor"></p></div> 
        <div class="col-sm-10"> 
          <h2>Kasvihuoneen mittaustaulukko</h2> 
          <h5 id="measurementsStarted">Lataan..</h5> 
        

<form id="oldMeasurements" method="get">

 <div class="row">
   
       <div class="col">
    <label for="datepickerFrom">Mistä</label>
         <input type="date" id="datepickerFrom" class="form-control" name="datepickerFrom" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>"/>
</div>
    <div class="col">
    <label for="datepickerTo">Mihin</label>

            <input type="date" id="datepickerTo" class="form-control" name="datepickerTo" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>"/>
      
  </div>
      <div class="col">
	    <label for="resultAmount">Määrä</label>
    <select id="resultAmount" class="form-select">
	 <option value="1">1</option>
      <option value="10" selected>10</option>
	  <option value="25">25</option>
      <option value="50">50</option>
	  <option value="100">100</option>
	  <option value="150">150</option>
    </select>

</div>
<div class="col">
	    <label for="resultAmount">Järjestys</label>
    <select id="resultOrder" class="form-select">
      <option value="oton" selected>Vanhimmasta uusimpaan</option>
	  <option value="ntoo">Uusimmasta vanhimpaan</option>
    </select>

</div>
    <div class="col">
	<br>
    <button type="submit" class="btn btn-success">Hae</button>
</div>
</div>
</form>

	

 


		
		

	
<p>



</p>     
<p> 
          <table class="table" id="tblMeasurements">
		  <thead class="thead-dark">
		  <tr>
		  <td>Ajankohta</td>
		  <td>Kasvihuone</td>
		  <td>Ulkoilma</td>
		  </tr>
		  </thead>
		<tbody>
		 </tbody>
		  </table>
          </p> 
  
        </div> 
      </div> 
  
 
    </div> 
  



  </body> 
</html>