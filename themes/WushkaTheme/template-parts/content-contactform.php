<?php 
    $retURL = site_url();
    if( is_page_template('contactus.php') ){
        $retURL = home_url('/').'contact-us/';
    }

    $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);

    $school_search = false;
    $disabled = '';
    if($extension != 'nz'){
        $school_search = true;
        $disabled = 'disabled';
    }
?>
<form id="contact-us-form2" action="https://api.modernstar.com/form" method="POST">
    <input type="hidden" name="orgid" value="00D90000000Ixox" disabled="">
    <input type="hidden" name="retURL" value="<?= $retURL; ?>" disabled="">

    <input type="hidden" name="debug" value="1" disabled="">
    <input type="hidden" name="debugEmail" value="mtuckey@modernstar.com" disabled="">

    <p class="tip">*Required Fields</p>
    <div class="row">
        <div class="col-md-12">
            <h2 class="form-title">Personal Details</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
            <label for="Cat_First_Name__c2">First Name<span class="mandatory">*</span></label>
            <input id="Cat_First_Name__c2" class="form-control" maxlength="30" name="Cat_First_Name__c" size="20"
                type="text" required />
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
            <label for="Cat_Last_Name__c2">Last Name<span class="mandatory">*</span></label>
            <input id="Cat_Last_Name__c2" class="form-control" maxlength="50" name="Cat_Last_Name__c" size="20"
                type="text" required />
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
            <label for="Cat_Email__c2">Email Address<span class="mandatory">*</span></label>
            <input id="Cat_Email__c2" class="form-control" maxlength="80" name="Cat_Email__c" size="20" type="text"
                required />
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
            <label for="Cat_Phone__c2">Mobile Number<span class="mandatory">*</span></label>
            <input id="Cat_Phone__c2" class="form-control" maxlength="40" name="Cat_Phone__c" size="20" type="text"
                required />
        </div> 
    </div> 
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
            <label for="Cat_Title__c2">Job Title<span class="mandatory">*</span></label>
            <input id="Cat_Title__c2" class="form-control" maxlength="20" name="Cat_Title__c" size="100" type="text"
                required />
        </div>
    </div> 


    <div class="row mt30">
        <div class="col-md-12">
            <h2 class="form-title">School Details</h2>
        </div>
    </div>


    <?php if( $school_search ){ ?>
    <div class="contact-school-search">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
                <label for="school-name-input2">Name of School<span class="mandatory">*</span></label>
                <input id="school-name-input2" maxlength="100" name="school-name-input" size="20" type="text"
                    class="contact-no-send form-control">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
                <label for="post-code-input2">Postcode<span class="mandatory">*</span></label>
                <input id="post-code-input2" class="contact-no-send form-control" maxlength="20" name="post-code-input" size="20"
                    type="text">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group clear-both">
                <a href="#" class="btn btn-danger red white" id="js-school-search2">Search for school</a>
                <a href="#" class="btn btn-default" id="js-school-search-clear2">Clear Search</a>
            </div>
            <div id="hidden-select-school2" class="col-md-12 mb20">
                <p class="contact-school-select-title hidden">Select your school below</p>

                <div id="js-select-school2" class="hidden">
                </div>

                <a href="#" class="contact-js-no-school">Can't find your school? Click here. </a>
            </div>
        </div>
    </div>
    <?php } ?>

    <div id="hidden-details2" class="<?=  ($school_search)? 'hidden': '' ; ?>">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group clear-both">
                <label for="00N90000008kFY72">Name of School<span class="mandatory">*</span></label>
                <input id="00N90000008kFY72" maxlength="100" class="form-control" name="Cat_Account_Name__c" size="20"
                    <?= $disabled; ?> type="text">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
                <label for="Cat_Postcode__c2">Postcode<span class="mandatory">*</span></label>
                <input id="Cat_Postcode__c2" maxlength="20" class="form-control" name="Cat_Postcode__c" size="20"
                    type="text" <?= $disabled; ?>>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group clear-both">
                <label for="00N90000008kFXY2">School Address<span class="mandatory">*</span></label>
                <input id="00N90000008kFXY2" maxlength="200" class="form-control" name="Cat_Address_1__c" size="20"
                    <?= $disabled; ?> type="text">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
                <label for="00N90000008kFXd2">School Address 2<span class="mandatory">*</span></label>
                <input id="00N90000008kFXd2" maxlength="200" class="form-control" name="Cat_Address_2__c" size="20"
                    type="text" <?= $disabled; ?>>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group clear-both">
                <label for="Cat_Suburb__c2">School Suburb<span class="mandatory">*</span></label>
                <input id="Cat_Suburb__c2" maxlength="100" class="form-control" name="Cat_Suburb__c" size="20"
                    <?= $disabled; ?> type="text">
            </div>
            <?php if($school_search){ ?>
            <div class="col-md-6 col-sm-6 col-xs-6 col-xsp-12 form-group">
                <label for="Cat_State__c2">School State<span class="mandatory">*</span></label>
                <select id="Cat_State__c2" name="Cat_State__c" class="form-control" title="School State" <?= $disabled; ?>>
                    <option value="">--None--</option>
                    <option value="NSW" name="NSW">NSW</option>
                    <option value="VIC" name="VIC">VIC</option>
                    <option value="QLD" name="QLD">QLD</option>
                    <option value="ACT" name="ACT">ACT</option>
                    <option value="WA" name="WA">WA</option>
                    <option value="SA" name="SA">SA</option>
                    <option value="NT" name="NT">NT</option>
                    <option value="TAS" name="TAS">TAS</option>
                </select>
            </div>
            <?php } ?>
        </div>
        <?php if($school_search){ ?>
        <div class="row">
            <div class="col-md-12 form-group clear-both contact-manual">
                <a href="#" id="hide-manual2">Hide manual entry</a>
            </div>
        </div>
        <?php } ?>
    </div>


    <div class="row mt30">
        <div class="col-md-12">
            <h2 class="form-title">Ask us a question, we are here to help</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group">
            <label for="Cat_Description">Description<span class="mandatory">*</span></label>
            <textarea id="Cat_Description" name="Description" class="form-control" rows="8"></textarea>
        </div>
    </div>


    <select id="recordType2" name="RecordTypeId" class="hidden" type="hidden" aria-label="Wushka Contact Us" hidden="">
        <option value="01290000000nZ26AAE" name="01290000000nZ26AAE" selected="">Wushka Contact Us</option>
    </select>
    <?php
        $country = substr(site_url(), -2);
        $country = strtoupper($country);
    ?>
    <select id="Country__c2" name="Country__c" title="Country" aria-labelledby="<?=  $country;  ?>" hidden>
        <option value="<?php echo $country; ?>" id="<?=  $country;  ?>2"><?=  $country;  ?>
        </option>
    </select>

    <input type="hidden" id="subject2" name="subject" value="Wushka Contact Us" class="contact-no-clear">
    <input type="hidden" id="00N9000000FAizC2" name="Modern_Star_Division__c" title="Modern Star Division"
        class="contact-no-clear" value="Education">
    <!-- From website -->
    <input id="00N90000006jGwn2" name="From_Website__c" type="hidden" class="contact-no-clear" value="<?= site_url(); ?>">

    <!-- Account -->
    <input id="account2" name="account" type="hidden" class="contact-no-clear" value="unknown">

    <input type="hidden" id="reason2" name="Reason" value="Wushka Contact Us" class="contact-no-clear">
    <!-- input type="hidden" id="external" name="external" value="1"> -->


    <div class="row contact-submit-container text-center">
        <div class="col-md-12">
            <button type="submit" id="form-submit2"
                class="contact-us-btn btn btn-primary spacious m-t-md">
                    Submit
                    <span id="ajax-loader-placeholder2" style="display:none">
                        <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> 
                    </span>
            </button>
        </div>
    </div>

</form>
 
