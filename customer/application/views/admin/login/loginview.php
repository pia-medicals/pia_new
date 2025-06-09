<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piament Customer Portal | Login</title>
    <link href="<?php echo base_url('static/admin/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('static/admin/font-awesome/css/font-awesome.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('static/admin/css/animate.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('static/admin/css/style.css')?>" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">Piament Customer Portal </h2>
                <p>
                    <small>The aim of this document is to gather and analyze and give an in-depth insight of the complete Piament Customer Portal by defining the problem statement in detail</small>
                </p>
                <p>
                    <small> Nevertheless, it also concentrates on the capabilities required by stakeholders and their needs while defining high-level product features.</small>
                </p>
                <p>
                    <small>The detailed requirements of Piament Customer Portal are provided in this document.</small>
                </p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" action="<?php echo base_url('login');?>" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" name="txtemail" id="txtemail" placeholder="User Email" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="txtPassword" placeholder="Password" required="">
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                          
                    </form>
                    <p class="m-t">
                        <small>Piament Customer Portal &copy; 2019</small>
                    </p>
                </div>
            </div>
        </div>
        <?php if($value=='er'){?>
		<div class="alert alert-danger alert-dismissable">User name and password are incorrect&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
        <?php } ?>
        <hr/>
        <div class="row">
            <div class="col-md-10">
            </div>
            <div class="col-md-2 text-right">
               <small>© 2019-2020</small>
            </div>
        </div>
    </div>
</body>
</html>
