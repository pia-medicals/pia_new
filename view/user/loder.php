<!DOCTYPE html>
<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body{
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.btn{
  position: relative;
  text-align: center;
  cursor: pointer;
}

.btn span{
  position: relative;
  display: inline-block;
  color: #fff;
  font-size: 1.2em;
  font-weight: 500;
  text-decoration: none;
  width: 240px;
  padding: 18px 0;
  margin: 35px;
  border-radius: 8px;
  box-shadow: 0 5px 25px rgba(1 1 1 / 15%);
  transition: transform 0.15s linear;
}

.color-01 span{
  background: linear-gradient(90deg, #EC5C1A, #F6CE61);
}

.color-02 span{
  background: linear-gradient(90deg, #0165CD, #55E6FB);
}

.color-03 span{
  background: linear-gradient(90deg, #259844, #6FF192);
}

h1{
  color: #222;
  text-align: center;
  margin-bottom: 50px;
  font-size: 2.8em;
  font-weight: 800;
}
      
</style>
</head>
<body>
<link rel="stylesheet" href="style.css">
     <div class="container mt-5">
  <div class="row">
    <div class="col-sm-3">
      
    </div>
    <div class="col-sm-6">
       <h4 align="center" id="ld">LODING.....</h4>
     <div class="container">
  <div class="progress" id="loder" style="width:50%">
     <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    </div>
  </div>
</div>
    </div>
    <div class="col-sm-3">
    </div>
  </div>
</div>

   <!-- <h1>Magnetic Buttons On Mousemove</h1>-->

    <!--<a href="#" class="btn color-01">
      <span>Hover Me</span>
    </a>-->
    <a href="#" class="btn color-02" id="dash">
      <span><i class="fa fa-dashboard" style="font-size:20px"></i>     DASHBOARD</span>
    </a>
    <a href="<?=SITE_URL ?>" class="btn color-03" id="back">
      <span><i class="fa fa-backward" style="font-size:20px"></i>     BCACK TO LOGIN</span>
    </a>


  

   <!-- <script type="text/javascript">
    const btns = document.querySelectorAll(".btn");

    btns.forEach((btn) => {
      btn.addEventListener("mousemove", function(e){
        const position = btn.getBoundingClientRect();
        const x = e.pageX - position.left - position.width / 2;
        const y = e.pageY - position.top - position.height / 2;

        btn.children[0].style.transform = "translate(" + x * 0.3 + "px, " + y * 0.5 + "px)";
      });
    });

    btns.forEach((btn) => {
      btn.addEventListener("mouseout", function(e){
        btn.children[0].style.transform = "translate(0px, 0px)";
      });
    });
    </script> -->


<script type="text/javascript">
   $('#loder').hide();
   $('#ld').hide();
  $('#dash').click(function(){
      $('#back').hide();
        $('#dash').hide();
        $('#loder').show();
         $('#ld').show();
            window.location.href = 'admin';
  });
</script>
</body>
</html>
