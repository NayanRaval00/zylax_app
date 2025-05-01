<h4>Get in touch with us</h4>
<p>If you have any repair related question, Please don’t hesitate to send us a message</p>

<?php if (session()->getFlashdata('success')): ?>
    <div style="color: green;">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->has('validation')): ?>
    <div style="color: red;">
    <?= session('validation')->listErrors(); ?>
    </div>
<?php endif; ?>

<div class="form-contact-content box-contact">
    <form class="repairPcForm" action="<?= base_url('form-submit') ?>" method="post" enctype="multipart/form-data">
        <div class="form-box one-half name-contact"><label for="exampleInputTitle">Repair Type *</label>
            <div class="dropdown-container">
                <select name="os_type" style="color:#000000;">
                    <option selected="selected" value="Laptop Repairs">Laptop Repairs</option>
                </select>
            </div>
        </div>

        <div class="form-box one-half name-contact"><label for="exampleInputTitle">Brand *</label>
            <div class="dropdown-container"><select name="os_brand" style="color:#000000;">
                    <option selected="selected" value="">Please Select</option>
                    <option value="HP" <?php if(old('os_brand') && old('os_brand') == 'HP'){ echo "selected"; } ?>>HP</option>
                    <option value="Toshiba" <?php if(old('os_brand') && old('os_brand') == 'Toshiba'){ echo "selected"; } ?>>Toshiba</option>
                    <option value="Lenovo" <?php if(old('os_brand') && old('os_brand') == 'Lenovo'){ echo "selected"; } ?>>Lenovo</option>
                    <option value="Asus" <?php if(old('os_brand') && old('os_brand') == 'Asus'){ echo "selected"; } ?>>Asus</option>
                    <option value="Acer" <?php if(old('os_brand') && old('os_brand') == 'Acer'){ echo "selected"; } ?>>Acer</option>
                    <option value="Dell" <?php if(old('os_brand') && old('os_brand') == 'Dell'){ echo "selected"; } ?>>Dell</option>
                    <option value="Gigabyte" <?php if(old('os_brand') && old('os_brand') == 'Gigabyte'){ echo "selected"; } ?>>Gigabyte</option>
                </select></div>
        </div>

        <div class="form-box one-half name-contact"><label for="exampleInputName">Model/Part Number&nbsp;

                <a class="" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Your model number or part number is usualy written underneath (reverse side) of your laptop or sometime under the battery.">
                    <i class="bi bi-info-circle"></i>
                </a>


            </label> <input id="exampleInputName" name="os_model_no" placeholder="" type="text" value="<?= old('os_model_no') ?>"></div>
        <div class="form-box one-half name-contact"><label for="exampleInputName">Serial Number&nbsp;

                <a class="" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Serial number usually in the same area where the model number is. Serial number sometime help us with finding the right part and in case of Dell it is their service tags.">
                    <i class="bi bi-info-circle"></i>
                </a>

            </label> <input id="exampleInputName" name="os_serial_no" placeholder="" type="text" value="<?= old('os_serial_no') ?>"></div>

        <div class="form-box one-half name-contact"><label for="exampleInputName">Year Purchased&nbsp;*</label> <input id="exampleInputName" name="os_year_purchased" placeholder="YYYY" min="1900" max="2099"  type="number" step="1" value="2025"></div>
        <div class="form-box one-half name-contact"><label for="exampleInputName">Problem&nbsp;*</label>
            <div class="dropdown-container">
                <select id="os_problem" name="os_problem" style="color:#000000;" >
                    <option selected="selected" value="">Please Select</option>
                    <option value="Screen Broken" <?php if(old('os_problem') && old('os_problem') == 'Screen Broken'){ echo "selected"; } ?>>Screen Broken</option>
                    <option value="Hinges Broken" <?php if(old('os_problem') && old('os_problem') == 'Hinges Broken'){ echo "selected"; } ?>>Hinges Broken</option>
                    <option value="Hard Drive Slow" <?php if(old('os_problem') && old('os_problem') == 'Hard Drive Slow'){ echo "selected"; } ?>>Hard Drive Slow</option>
                    <option value="Keyboard not working" <?php if(old('os_problem') && old('os_problem') == 'Keyboard not working'){ echo "selected"; } ?>>Keyboard not working</option>
                    <option value="Others" <?php if(old('os_problem') && old('os_problem') == 'Others'){ echo "selected"; } ?>>Others</option>
                </select>
            </div>
        </div>
        <div id="other_reason" class="form-box clearfix" style="display: none;">
            <div class="form-box"><label for="exampleInputComments">Enter the Problem you are facing&nbsp;*</label><textarea style="width:98.5%;" id="os_problem_text" name="os_problem_text"  disabled=""></textarea></div>
        </div>

        <div class="form-box one-half name-contact"><label for="exampleInputName">First Name&nbsp;*</label> <input id="exampleInputName" name="os_fname" placeholder=""  type="text" value="<?= old('os_fname') ?>"></div>
        <div class="form-box one-half name-contact"><label for="exampleInputName">Last Name&nbsp;*</label> <input id="exampleInputName" name="os_lname" placeholder=""  type="text" value="<?= old('os_lname') ?>"></div>
        <div class="form-box one-half name-contact"><label for="exampleInputName">Your Suburb or Postcode&nbsp;*</label> <input id="exampleInputName" name="os_suburb_postcode" placeholder=""  type="text" value="<?= old('os_suburb_postcode') ?>"></div>

        <div class="form-box one-half phone-contact"><label for="exampleInputEmail1">Email&nbsp;*</label> <input id="exampleInputEmail1" name="os_email" placeholder=""  type="email" value="<?= old('os_email') ?>"></div>

        <div class="form-box one-half phone-contact"><label for="tel">Phone Number&nbsp;*</label> <input id="contact_no" name="os_contact_no" placeholder=""  type="text" value="<?= old('os_contact_no') ?>"></div>

        <div class="form-box one-half phone-contact"><label for="tel">Image ( Upload Print Screen or Invoice or Product info)&nbsp;</label> <input class="form-control" name="file" type="file" placeholder=""></div>


        <div class="form-box clearfix">
            <div class="form-box phone-contact"><label for="exampleInputComments">Message&nbsp;*</label><textarea style="width:98.5%;" id="os_msg" name="os_msg" ><?= old('os_msg') ?></textarea></div>
        </div>
        <div class="form-box clearfix">
            <div class="g-recaptcha" data-sitekey="6LeW_kYpAAAAAOayqC842A3S52cUwqWEbO5WjSlK" data-callback="correctCaptcha">
                <div style="width: 304px; height: 78px;">
                    <div><iframe title="reCAPTCHA" width="304" height="78" role="presentation" name="a-spm5b94ouj8h" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation" src="https://www.google.com/recaptcha/api2/anchor?ar=2&amp;k=6LeW_kYpAAAAAOayqC842A3S52cUwqWEbO5WjSlK&amp;co=aHR0cHM6Ly93d3cuenlsYXguY29tLmF1OjQ0Mw..&amp;hl=en&amp;v=J79K9xgfxwT6Syzx-UyWdD89&amp;size=normal&amp;cb=id897m4scoy1"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea>
                </div><iframe style="display: none;"></iframe>
            </div>
            <span class="errorcaptcha" style="color:red;font-size:12px"></span>
        </div>

        <div class="form-box clearfix">
            <input type="submit" value="Submit" class="form-control" style="background:#2d2d2d;/*#ed3833;*/;color:#fff;">
            <input type="hidden" name="act" value="save">
            <input type="hidden" name="slug_url" value="<?= $activePage ?>">
        </div>

        <!--
    <div class="form-box"><label for="exampleInputComments">Select a Choice&nbsp;*</label>
    <div class="choiceDiv"><span><input id="choice1" name="service"  type="radio" value="Walk-In Service" /> <label for="choice1">Walk-In Service</label></span> <span><input id="choice2" name="service"  type="radio" value="Call Out Service" /> <label for="choice2">Call Out Service</label></span> <span><input id="choice3" name="service"  type="radio" value="Advice, Query, or Question" /> <label for="choice3">Advice, Query, or Question</label></span> <span><input id="choice4" name="service"  type="radio" value="Mail-In Repair Query" /> <label for="choice4">Mail-In Repair Query</label></span></div>
    </div>
    -->
    </form>
</div>