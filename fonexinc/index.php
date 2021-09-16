<!DOCTYPE html>
<html>
<head>
  <title>Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css" crossorigin="anonymous">
  <link rel="stylesheet" href="js/intltelinput/build/css/intlTelInput.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="js/country/build/css/countrySelect.css">
  <!-- IntlTelInput-->
  <link rel="stylesheet" type="text/css" href="fontawesome/css/all.css">
  <link href="css/style.css" rel="stylesheet"> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body> 
	<!-- Multi step form --> 
<!-- <div class="container-fluid">
  <div class="row">
    <div class="col-md-6">
      <img src="https://static.pexels.com/photos/33972/pexels-photo.jpg" width="100%" height="1000"/>
    </div> -->

    <section class="multi_step_form col-lg-12">  
      <div id="msform"> 
        <!-- Tittle -->
        <div class="tittle">
          <!-- <img src="img/logo.png"> -->
          <h2>Sign up</h2>
          <p>Get started rignt after signing up with Fonexinc</p>
        </div>
        <!-- progressbar -->
        <ul id="progressbar">
          <li class="active progressbar2 " >Account Info<span id="dest"></span></li>   
          <li class="progressbar2 progressbar1">Pick a Phone Number<span id="amou"></span></li> 
          <li class="progressbar2 progressbar1">Call Flow</li>
          <li  class="progressbar2 progressbar1">SIP Phones & Mobile App</li>
        </ul>
        <!-- fieldsets -->
        <fieldset class="other-fieldset begin">
          <h3>Enter your info & click Next to pick your number.</h3>
          <form id="step1_form" method="POST" action="">
            <div class="form-row">
                  <div class="form-group toggle">
                    <input type="radio" id="choice1" class="choi" name="choice" value="free">
                    <label for="choice1" class="cho">Free Trial</label>
                    <input type="radio" id="choice2" class="choi" checked="true" name="choice" value="contract">
                    <label for="choice2" class="cho">Buy Now</label>
                    <div id="flap"><span class="content" id="selectedtxt"></span></div>
                 </div>
             </div><br>
             <div class="form-row">
                 <div class="form-group width50">
                  <label for="extension">Number of Users/Extensions</label>
                  <!----use slider instead---->
                  <input type="number" class="form-control" name="numberofexts" id="extension" placeholder="" required>
                 </div>
                 <div class="form-group width50">
                  <label for="package">Service Plan</label>
                  <select class="form-control" id="package" name="plan">
                    <option value="" selected>Choose a plan</option>
                    <option value="">Enter the number of users first</option>
                  </select>
                </div>
             </div>
             <div class="form-row">
              <div class="form-group col-md-6">
                <label for="names">First & Last Names</label>
                <input type="text" class="form-control" id="names" name="names" placeholder="" required>
              </div>
              <div class="form-group col-md-6">
                <label for="companyname">Company Name</label>
                <input type="text" class="form-control" id="companyname" name="companyname" placeholder="" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" name="email" placeholder="Email" required>
              </div>
              <div class="form-group col-md-6">
                <label for="phone1">Contact Phone</label>
                <input type="text" class="form-control" id="phone1" name="contact" placeholder="" required>
              </div>
            </div>
            <div class="form-group">
              <label for="inputAddress">Address</label>
              <input type="text" class="form-control" name="address" id="inputAddress" placeholder="1234 Main St" required>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputCity">City</label>
                <input type="text" class="form-control" name="city" id="inputCity" required>
              </div>
              <div class="form-group col-md-4">
                <label for="statess">State</label>
                <select  class="form-control" id="states" name="states">
                  
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="inputZip">Zip</label>
                <input type="text" class="form-control" name="zip" id="inputZip" required>
                <input type="hidden" class="form-control" name="i_time_zone" id="i_time_zone">
              </div>
            </div>
            <div id="step1error" class="alert alert-warning" style="display:none;"></div>
          </form>

          <img src="img/spinner.gif" id="firststepload" alt="Loading..." style="display:none;"><br>
          <button type="button" id="firstnext" class="next action-button step1button">Next</button>  
        </fieldset>
        <fieldset class="other-fieldset another-fieldset">
          <h3>You can choose a number or keep your current business number.</h3>
          <h6>Start with one and you can add more after signup.</h6>
          <form id="step2_form" method="POST" action="">
             <div class="form-check">
                  <div class="btn-group btn-toggle custom-btn-group-outline" data-toggle="buttons"> 
                      <a href="#" id="btn_local_pick" class="btn btn-lg btn-lg2 btn-uv-blue" role="button">Pick Number</a>
                      <a href="#" id="btn_local_keep" class="btn btn-lg btn-lg2 " role="button">Keep Number</a>
                  </div>
            </div><br>
            <div class="form-row form-check" id="keepnumb" style="display: none;">
              <div class="form-group">
                <label for="inputAddress">Enter Your Current Business Number</label>
                <input type="text" class="form-control" id="inputkeep" placeholder="718" required>
              </div>
            </div>
            <div class="form-check SearchDID Search">
                  <label>Search By:</label><br>
                  <div class="btn-group btn-toggle custom-btn-group-outline" data-toggle="buttons"> 
                      <a href="#" id="btn_local_search_by_areacode" class="btn btn-lg btn-lg1 btn-uv-blue" role="button" >Area Code</a>
                      <a href="#" id="btn_local_search_by_city" class="btn btn-lg btn-lg1 " role="button">City</a>
                  </div>
            </div><br>
            <div class="form-row form-check SearchDID" id="byarea" >
              <div class="form-group">
                <label for="inputAreacode">Enter Area Code</label>
                <input type="text" onkeyup="get_numbers_by_areacode()" class="form-control" id="inputAreacode" placeholder="718" value="" >
              </div>
            </div>
            <div class="form-row SearchDID" id="bycity" style="display: none;">
              <div class="form-group width50" id="stat2" style="display: none;">
                <label for="stat">State</label>
                <select  class="form-control" id="stat">
                </select>
              </div>
              <div class="form-group width50" id="city2" style="display: none;">
                <label for="city">City</label>
                <select  class="form-control" id="city1">
                  
                </select>
              </div>
            </div>
            <input type="hidden" name="didnumber" id="didnumber">
            <input type="hidden" name="i_customer" id="i_customer">
            <div class="number-result-item-container" style="text-align: center;margin: 0 auto;">
              
           </div>
           <div id="didload" style="display:none;margin: 0 auto;">
             <img src="img/spinner.gif" alt="Loading..."  ><br>
           </div>
         </form>
         <br><br>
         <form id="openhours_form">
           <div class="form-row" id="openhours" style="">

             <label style="margin: 0 auto;">Working Hours</label>
             <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">Weekdays</th>
                  <th scope="col"></th>
                  <th scope="col">Hours</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
                  <th scope="col"></th>
                  <th scope="col"><button class="btn" id="add_period">Add</button></th>
                </tr>
              </thead>
              <tbody id="period_body">

              </tbody>
            </table>
          </div>
          </form>
          <img src="img/spinner.gif" alt="Loading..." id="secondstepload" style="display: none;"><br>
          <div class="alert alert-warning" id="diderror" style="display: none;"></div>
          <button type="button" class="action-button previous previous_button">Back</button>
          <button type="button" class="next action-button" id="secondnext">Continue</button>  
        </fieldset>  
        <fieldset class="other-fieldset another-fieldset ">
            <form id="step3_form" method="POST" action="">
             <div class="form-check">
                  <label for="callflow">Choose a call flow</label>
                  <select class="form-control" id="callflow">
                    <option value="" selected>Select an option</option>
                    <option value="recepIVR">Incoming calls should go to the receptionist and then IVR</option>
                    <option value="ringall">Incoming calls should ring all extensions immediately</option>
                    <option value="IVR">Incoming calls should go to the IVR</option>
                    <option value="cellphone">Incoming calls should be forwarded to a cellphone</option>
                  </select>
            </div>
            <div class="form-row form-check" style="display:none;" id="cellphone">
              <div class="form-group">
                <label for="inputcell">Enter the cell phone</label>
                <input type="text" class="form-control" id="inputcell" placeholder="7182323232" required>
              </div>
            </div>
          </form><br><br>
          <form id="ivr_form">
            <div class="form-check" id="ivrlang"  style="display:none;">
                  <label for="lang">IVR Languages</label>
                  <select class="form-control" id="lang">
                    <option value="Disabled">Choose an option</option>
                    <option value="Disabled">English</option>
                    <option value="French IVR">English & French</option>
                    <option value="Spanish IVR">English & Spanish</option>
                    <option value="Chinese IVR">English & Chinese</option>
                  </select>
            </div>
            <div class="form-row" id="ivroptions"  style="display:none;">
             <label style="margin: 0 auto;">IVR Options</label>
             <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">Options</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
                  <th scope="col">Actions</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
                  <th scope="col">Destination</th>
                </tr>
              </thead>
              <tbody id="option_body">
                 <tr>
                  <td>1</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="1">
                      <option value="disabled" class="dis" selected >Disabled</option>
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" >All Extensions</option>
                      <option value="recepext" class="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="2">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" selected>All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" >Directory</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>3</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="3">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" selected>All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" >Directory</option>
                    </select>

                  </td>
                </tr>
                            <tr>
                  <td>4</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="4">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" selected>All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" >Directory</option>
                    </select>

                  </td>
                </tr>
                <tr>
                  <td>5</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="5">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" selected>All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" >Directory</option>
                    </select>

                  </td>
                </tr>
                <tr>
                  <td>6</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="6">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" >All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" selected>Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" >Directory</option>
                    </select>

                  </td>
                </tr>
                <tr>
                  <td>7</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="7">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" >All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" selected>Directory</option>
                    </select>

                  </td>
                </tr>
                <tr>
                  <td>8</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="8">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" >All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" selected>After-Hours & Schedule IVR</option>
                      <option value="directory" class="">Directory</option>
                    </select>

                  </td>
                </tr>
                <tr>
                  <td>9</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="9">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" >All Extensions</option>
                      <option value="recepext" id="">Receptionist Then Voicemail</option>
                      <option value="vm" selected>Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="">Directory</option>
                    </select>

                  </td>
                </tr>
                <tr>
                  <td>0</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">Transfer To</td>
                  <td scope="col"></td>
                  <td scope="col"></td>
                  <td scope="col">
                    <select  class="form-control" name="0">
                      <option value="lang" class="langhere" ></option>
                      <option value="ringall" >All Extensions</option>
                      <option value="recepext" id="" selected>Receptionist Then Voicemail</option>
                      <option value="vm" >Voicemail</option>
                      <option value="replay" >Replay IVR</option>
                      <option value="afterh" >After-Hours & Schedule IVR</option>
                      <option value="directory" class="" >Directory</option>
                    </select>

                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          </form>
          <img src="img/spinner.gif" alt="Loading..." id="thirdstepload" style="display: none;"><br>
          <div class="alert alert-warning" id="callflowerror" style="display: none;"></div>
          <button type="button" class="action-button previous previous_button">Back</button>
          <button type="button" class="next action-button" id="thirdnext" >Continue</button>
        </fieldset>  
        <fieldset class="final another-fieldset">
          <div class="form-row" id="">
             <label style="margin: 0 auto;">Choose IP phone or App for each extension</label>
             <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">User Agent</th>
                  <th scope="col"></th>
                  <th scope="col">Extension</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody id="ua_choose">
                   
              </tbody>
            </table>
          </div>
          <div class="alert alert-warning" id="finalerror" style="display: none;"></div>
          <img src="img/spinner.gif" alt="Loading..." id="fourthstepload" style="display: none;"><br>
          <button type="button" class="action-button previous previous_button">Back</button>
          <button type="button" class="next action-button" id="fourthnext" >Finish</button>
        </fieldset>
        </div>   
    </section> 
 <!--  </div>
</div> -->


<!-- End Multi step form -->   

<!--Products--->
<select id="singlefree" style="display: none;">
  <option value="30405">Standard Monthly Plan - $24.95/user</option>
  <option value="30507">Premium Monthly Plan - $40.95/user</option>
  <option value="30510">Enterprise Monthly Plan - $54.95/user</option>
</select>

<select id="singlecontract" style="display: none;">
  <option value="30504">Standard Monthly Plan - $21.83/user</option>
  <option value="30404">Standard Annual Plan - $18.71/user</option>
  <option value="30505">Premium Monthly Plan - $35.83/user</option>
  <option value="30506">Premium Annual Plan - $30.71/user</option>
  <option value="30509">Enterprise Monthly Plan - $48.08/user</option>
  <option value="30508">Enterprise Annual Plan - $41.21/user</option>
</select>

<select id="ext2-20free" style="display: none;">
  <option value="30406">Standard Monthly Plan - $22.95/user</option>
  <option value="30518">Premium Monthly Plan - $35.95/user</option>
  <option value="30514">Enterprise Monthly Plan - $49.95/user</option>
</select>

<select id="ext2-20contract" style="display: none;">
  <option value="30407">Standard Monthly Plan - $20.08/user</option>
  <option value="30402">Standard Annual Plan - $17.21/user</option>
  <option value="30516">Premium Monthly Plan - $31.46/user</option>
  <option value="30515">Premium Annual Plan - $26.97/user</option>
  <option value="30513">Enterprise Monthly Plan - $43.71/user</option>
  <option value="30511">Enterprise Annual Plan - $37.46/user</option>
</select>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
<script src="js/jquery.toggleinput.js"></script>
<script type="text/javascript" src="js/timezone.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>
