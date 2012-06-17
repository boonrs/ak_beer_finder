<html>
	<head>
    	<style type="text/css">
        	html { height: 100% }
        	body { height: 100%; margin: 0; padding: 0; font-family: sans-serif; }
        	#map_canvas { height: 100% }
			div.title { font-weight: bold; }
			div.type { }
        </style>
        <script type="text/javascript"
          src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBb3MwPLAaHBqroCJrcEH-Fh5hfjYT7KfA&sensor=true">
        </script>
        <script type="text/javascript">
          function initialize() {
	
			infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(10,10) });
			
            var myOptions = {
              center: new google.maps.LatLng(61.164437,-149.655762),
              zoom: 8,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_canvas"),
                myOptions);

			for(var i in pointArray)
			{
				var marker = new google.maps.Marker({
					position: pointArray[i].position,
				    map: map,
					title: pointArray[i].title,
					clickable: true
				});		
				
				(function(marker, info){
					google.maps.event.addListener(marker, 'click', function() {
						infowindow.setContent('<div class="title">' + info.title + '</div><div class="type">' + info.type + '</div>');
						infowindow.open(map, marker);
					});
				})(marker, pointArray[i]);		
			}
			
			if (navigator.geolocation) 
			{   
			    navigator.geolocation.getCurrentPosition(function(results)
				{
			        var center = new google.maps.LatLng(
			            results.coords.latitude,
			            results.coords.longitude
			        );
					
					new google.maps.Marker({
						position: center,
						map: map,
						icon: 'star.png',
						title: "You are here"
					});

			        map.setCenter(center);
					map.setZoom(12);
				});
			}
          }
        </script>
      </head>
      <body onload="initialize()">
        <div id="map_canvas" style="width:100%; height:100%"></div>
      	<script type="text/javascript">
			pointArray = [];
		<?
			$link = mysql_connect('localhost', 'root', 'root');
			if (!$link) {
			    die('Could not connect: ' . mysql_error());
			}

			// make foo the current db
			$db_selected = mysql_select_db('ak_beer_finder', $link);
			if (!$db_selected) {
			    die ('Can\'t use foo : ' . mysql_error());
			}

			$result = mysql_query("SELECT * FROM bars limit 20");
			while ($row = mysql_fetch_assoc($result)) 
				echo 'pointArray.push({
					position: new google.maps.LatLng(' . $row['lat'] . ', ' . $row['long'] . '), 
					title: "' . $row['name'] . '",
					type: "' . $row['type'] . '"});'."\n";
		?>
		</script>
      	</body>
    </head>
</html>