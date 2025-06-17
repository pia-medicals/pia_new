

<div class=" login_area">
    <div class="container-login100">
        <div class="wrap-login100  p-b-20">
        	<?php
				$this->alert();
			?>

            <form action="" class="container login100-form validate-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                <div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
                    <input class="input100" type="password" name="password" placeholder="Password" id="psw">
                <!--  <span class="focus-input100" data-placeholder="Password" ></span> -->
                </div>

                <div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
                    <input class="input100" type="password" name="cpassword" placeholder="Confirm Password" id="cpsw">
                <!--  <span class="focus-input100" data-placeholder="Password" ></span> -->
                </div>

                <div class="m-b-50" >
                    <div id="message" style="display: none">
                      <h6>Password must contain the following:</h6>
                      <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                      <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                      <p id="number" class="invalid">A <b>number</b></p> 
                      <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                    </div>
                </div>

                <div class="container-login100-form-btn">
                    <input type="hidden" name="xval" id="xval" value="invalid">
                    <input type="submit" name="submit" value="Reset" class="login100-form-btn">
                </div>

            </form>
        </div>
    </div>
</div>

<style type="text/css">
  /* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>

<script type="text/javascript">
    var myInput = document.getElementById("psw");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    myInput.onfocus = function() {
      document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function() {
      document.getElementById("message").style.display = "none";
    }

    $('#psw').on('keyup, paste, change, input', function(){
        //myInput.onkeyup = function() {
          // Validate lowercase letters
          var lowerCaseLetters = /[a-z]/g;
          if(myInput.value.match(lowerCaseLetters)) {  
            letter.classList.remove("invalid");
            letter.classList.add("valid");
            $('#xval').val('valid');
          } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
            $('#xval').val('invalid');
          }
          
          // Validate capital letters
          var upperCaseLetters = /[A-Z]/g;
          if(myInput.value.match(upperCaseLetters)) {  
            capital.classList.remove("invalid");
            capital.classList.add("valid");
            $('#xval').val('valid');
          } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
            $('#xval').val('invalid');
          }

          // Validate numbers
          var numbers = /[0-9]/g;
          if(myInput.value.match(numbers)) {  
            number.classList.remove("invalid");
            number.classList.add("valid");
            $('#xval').val('valid');
          } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
            $('#xval').val('invalid');
          }
          
          // Validate length
          if(myInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
            $('#xval').val('valid');
          } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
            $('#xval').val('invalid');
          }
        })
</script>