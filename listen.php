<html >
	<head>
	  	<title>CRM Listen</title>
	 	<meta charset="utf-8">
	 	<meta name="viewport" content="width=device-width, initial-scale=1">
	 	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	 	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	 	<style>
		.hero-widget { text-align: center; padding-top: 5px; padding-bottom: 10px;height: 200px;}
		.hero-widget .icon { display: block; font-size: 36px; line-height: 36px; margin-bottom: 10px; text-align: center; }
		.container0{margin-top:15px;}
		.name{font-stretch: condensed;}
		body{background:#333333e8}
		</style>
	</head>
<progress style="width: 100%" value="0" max="10" id="progressBar"></progress>
<div class="container0"></div>

<script type="text/javascript">
	function getData(){
		$.ajax({
			url: 'json.php',
			dataType: 'json',
		})
		.done(function(response) {
			$('.container0').html('');
			if (response=='401') {
				window.location.href='login.php';
			}
			response.forEach(parseJson)
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			//console.log("complete");
		});

		document.getElementById("progressBar").value = 0;
		var timeleft = 10;
		var downloadTimer = setInterval(function(){
			document.getElementById("progressBar").value = 10 - --timeleft;
		  	if(timeleft <= 0)
		    	clearInterval(downloadTimer);
		},1000);

	}

	function parseJson(item,index){
		var dateNow = new Date();
		
		if (item[3]!='') {
			var calledDate=item[3].split('.')[0];
			calledDate= new Date(calledDate*1000);
			var duration=convertMS(dateNow-calledDate);

			if (calledDate<=dateNow) {
				$('.container0').append(`
					<div class="col-sm-2">
						<div class="hero-widget well well-sm">
							</br>
							<div class="icon">
								 <i class="glyphicon glyphicon-user"></i>
							</div>
								 <div class="text">
								<label class="text-muted"><a href="${item[1]}"><span>${item[0]}</span></a></label></br>
								<label class="text-muted">
									<span class="name">${item[2]}</span>
									</br>
									<span>${item[4]}</span>
									</br></br>
									<span style="font-size:larger;color:#337ab7">${duration}</span>
								</label>
							</div>
						</div>
					</div>							
				`);
			}
		}
	};

	getData();
	setInterval(getData, 10000);
	function convertMS(ms) {
	    var d, h, m, s;
	    s = Math.floor(ms / 1000);
	    m = Math.floor(s / 60);
	    s = s % 60;
	    h = Math.floor(m / 60);
	    m = m % 60;
	    d = Math.floor(h / 24);
	    h = h % 24;
	    h += d * 24;
	    if (String(h).length==1) h=`0${h}`;
	    if (String(m).length==1) m=`0${m}`;
	    if (String(s).length==1) s=`0${s}`;
	    return h + ':' + m + ':' + s;
	}
</script>

<footer>
	<center>
		<div>
				2018 &copy; NEXT  	
			</br>
				 
				Adalen Vladi&nbsp;&nbsp;&nbsp;
				Marsel Halilaj
			</br>
					
		</div>
	</center>
</footer>
<style>
	footer{
		bottom: 0;
		left: 0;
		width: 100%;
		background: #bababa;
		color: black;
		position: fixed;
	}
</style>
</html>
