<!DOCTYPE html>
<html>
	<head>
		<?
			$q  = $_GET["q"];
			if( $q == "" ){ $q = "sct"; }
			
			$no = $_GET["no"];
			if( $no == "" ){ $no = 0; }
		?>
		<!--<meta http-equiv="refresh" content="10; url=rss.php?q=<? echo $q ?>&no=<? echo $no ?>">-->
		<script>
			var q  = "<? echo $q; ?>";
			var no = <? echo $no; ?>;
			
			function updateRSS()
			{
				if (window.XMLHttpRequest)
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function()
				{
					//if (xmlhttp.readyState==4 && xmlhttp.status==200 )
					if (xmlhttp.readyState==4 && xmlhttp.status==200 && xmlhttp.responseText.indexOf('silicium.dk') == -1 )
					{
						document.getElementById("rssOutput").innerHTML=xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","getrss.php?q="+q+"&no="+no,true);
				xmlhttp.send();
				
				switch(q)
				{
					case "alt": q="dtu"; break;
					case "dtu": q="ing"; break;
					case "ing": q="khu"; break;
					case "khu": q="nat"; break;
					case "nat": q="sci"; break;
					case "sci": q="scn"; break;
					case "scn": q="sct"; break;
					case "sct": q="spa"; break;
					case "spa": q="vid"; break;
					case "vid": q="alt"; no++; break;
				}     
				if( no==3 ){ no=0; }
			}
			function startTime() {
			    var today=new Date();
			    var h=today.getHours();
			    var m=today.getMinutes();
			    var s=today.getSeconds();
			    h = checkTime(h);
			    m = checkTime(m);
			    s = checkTime(s);
			    
			    document.getElementById('ur').innerHTML = h+":"+m+":"+s;
			    
			    if( s=="00" && sold=="59" )
			    {
			    	updateRSS();
			    }
			    //if( s=="30" && sold=="29" )
			    //{
			    //	updateRSS();
			    //}
			    sold=s;
			    
			    var t = setTimeout(function(){startTime()},200);
			}
			function checkTime(i) {
			    if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
			    return i;
			}
		</script>
		<link href="styles.css" rel="stylesheet" type="text/css"/>
	</head>
	<body onload="startTime()";>
		<div style="height:768px;width:1360px;overflow:hidden;">
			<div style="padding:20px;">
				<h1 style="font-size:18px;float:none;">Nyheder fra</h1>
				<div id="rssOutput"><div style="float:left;padding:20px 0 0 0;font-size:0.4em;">Waiting for RSS feed...</div></div>
				<script>
					updateRSS();
				</script>
			</div>
			<div id="footer" class="clock" style="display:none;"><!-- Choose between clock, marquee or breaking -->
				<div id="ur" style="width:140px;"></div>
			</div>
		</div>
	</body>
</html>
