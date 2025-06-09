

<div class=" login_area">
    <div class="container-login100">
        <div class="wrap-login100  p-b-20">
        	<?php
				$this->alert();
			?>

            <form action="" class="container login100-form validate-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                <div class="wrap-input100 validate-input m-t-85 m-b-35" data-validate="Enter username">
                    <input class="input100" type="text" name="username" placeholder="Username">
                <!--  <span class="focus-input100" data-placeholder="Username" ></span> -->
                </div>

                <div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
                    <input class="input100" type="password" name="password" placeholder="Password">
                <!--  <span class="focus-input100" data-placeholder="Password" ></span> -->
                </div>

                <div class="container-login100-form-btn">

                    <input type="submit" name="submit" value="Login" class="login100-form-btn">
                </div>

            </form>
        </div>
    </div>
</div>