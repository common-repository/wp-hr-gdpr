<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e("Support", "wphrgdpr"); ?></h1>
    <hr class="wp-header-end">
    <div class="metabox-holder support_page">
        <div id="postbox-container-1" class="postbox-container big-container wphrgdpr_support_firstDiv">
            <div class="meta-box-sortables">
                <div id="" class="postbox " >
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Getting Started</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class='hndle'><span><?php _e("Quick Start Guide", "wphrgdpr") ?></span></h2>
                    <div class="inside">
                        <div class="main">

                            <p>
                                <?php
                                _e("Please note that WP-HR GDPR is intended as a set of tools to assist you with the management of your GDPR responsibilities towards potential, current and past employees.  It does not support GDPR related activities for other groups, such as customers, suppliers etc.", "wphrgdpr");
                                ?>
                            </p>
                            <p>
                                <?php
                                _e("The first things you need to do to get the plugin up and running are:", "wphrgdpr");
                                ?>
                            </p>
                            <ul>
                                <li>
                                    <?php
                                    _e("The plugin assumes anyone with an Admin role in WordPress is acting either formally or informally as your Admin/DPO and they have full access to all the information, capabilities and screens in the plugin.", "wphrgdpr", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("First, the Admin/DPO should then identify the types or data you hold on employees and how this is processed (called a Data Audit).  They can then use this information to complete the Data Privacy Notice Creation questionnaire. Once completed this will automatically create a page to display the notice – DO NOT DELETE THIS PAGE.", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("The DPO can also create a form for employees to consent to having their data processed.  If you decide not to use consent as the legal basis for processing data the same form can be used to ask them to confirm they have read and understood your Data Privacy Notice.", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("The DPO should send a general announcement to all employees asking employees to review the Data Privacy Notice and acknowledge they have done so via the Consent form.", "wphrgdpr");
                                    ?>
                                </li>
                            </ul>


                            <p>
                                <strong>
                                    <?php
                                    _e("Pages and Shortcodes", "wphrgdpr");
                                    ?>    
                                </strong>
                                <label class="single_label">
                                    <?php
                                    _e("The plugin will create three pages when activated:", "wphrgdpr");
                                    ?> 

                                </label>
                            <ul>
                                <li>
                                    <?php _e("Privacy Policy", "wphrgdpr") ?>
                                </li>
                                <li>
                                    <?php _e("Consent Form", "wphrgdpr") ?>
                                </li>
                                <li>
                                    <?php _e("Subject Access Request Summary (Pro version)", "wphrgdpr") ?>
                                </li>
                            </ul>

                            </p>
                            <p>
                                <?php
                                _e("Do not delete these pages as they are used in various parts of the plugin.  If you do, need to recreate the pages, add a new page with exactly the same name as the ones listed above and insert the following shortcode:", "wphrgdpr");
                                ?>
                            <ul>
                                <li>
                                    <?php _e("Privacy Policy", "wphrgdpr") ?> &nbsp; [privacy_policy title="{<?php _e("Here, you can set the title", "wphrgdpr") ?>}"]
                                </li>
                                <li>
                                    <?php _e("Consent Form", "wphrgdpr") ?>&nbsp; [consent_form title="{<?php _e("Here, you can set the title", "wphrgdpr") ?>}"]
                                </li>
                                <li>
                                    <?php _e("Subject Access Request Summary", "wphrgdpr") ?>&nbsp; [subject_access_request title="{<?php _e("Here, you can set the title", "wphrgdpr") ?>}"]
                                </li>
                            </ul>

                            </p>

                        </div>
                    </div>
                </div>
                <div id="" class="postbox " >
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Getting Started</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class='hndle'><span><?php _e("Link to full Support documentation", "wphrgdpr") ?></span></h2>
                    <div class="inside">
                        <div class="main">

                            <p><?php
                                echo sprintf( __("You can find a full plugin support document <a href='%s' target='_blank'>here</a>.", "wphrgdpr" ), 'http://www.wphrmanager.com/docs/wp-hr-gdpr/' );
                                ?></p>

                        </div>
                    </div>
                </div>
                <div id="" class="postbox " >
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Getting Started</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class='hndle'><span><?php _e("A legal disclaimer", "wphrgdpr") ?></span></h2>
                    <div class="inside">
                        <div class="main">

                            <p>
                                <?php
                                _e("The WP-HR GDPR plugin provides tools to assist you in managing your GDPR responsibilities.  However, we strongly recommend you seek professional advice when completing the Data Privacy Notice and on other aspects of your GDPR manager.  Black and White Digital Ltd (the plugin author) accepts no responsibility for the correct implementation of GDPR regulations in your organisation or for the configuration and use of this plugin by your organisation, employees, contractors or other representatives.", "wphrgdpr");
                                ?>
                            </p>

                        </div>
                    </div>
                </div>
                <div id="" class="postbox wphrgdpr_support_forthDiv" >
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Getting Started</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class='hndle'><span><?php _e("Upgrade to WP-HR GDPR Pro for More Great Features", "wphrgdpr") ?></span></h2>
                    <div class="inside">
                        <div class="main">

                           
                            <p>
                                <?php
                                echo sprintf(__("Our Pro version is designed to integrate with the WP-HR Manager plugin <a href='%s' target='_blank'>WP-HR Manager</a>.  When both plugins are installed you benefit from all the employee management features of WP-HR Manager plus enhanced GDPR tools, including:", "wphrgdpr"), esc_url("https://wordpress.org/plugins/wphrgdpr-manager"));
                                ?>
                            <ul>
                                <li>
                                    <?php
                                    _e("Nominate Data Protection Officers who can access plugin features and restrict access to certain data for other user types", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Link Consents and other GDPR activities to employee profiles", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Create a Subject Access Request page and allow logged in users to submit without the need to submit additional ID", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Create a Register to log all Subject Access Requests and manage how they are processed", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Record GDPR training for each employee", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Automatically send an email to people submitting Consent and Subject Access Request forms as a record of their submission", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Automatically notify Data Protection Officers about new Consent and Subject Access Request submissions", "wphrgdpr");
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    _e("Integrate your Data Privacy Notice and Consent forms within Job Applications created using the WP-HR Recruitment extension", "wphrgdpr");
                                    ?>
                                </li>
                            </ul>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="postbox-container-2" class="postbox-container small-container">
            <div class="meta-box-sortables">
                <div id="" class="postbox " >
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Barbara says:</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class='hndle'><span>Barbara says:</span></h2>
                    <div class="inside">
                        <div class="main">
                            <div class="half-container-1">
                                <h3 class='green'>Barbara says:</h3>
                                <p class="green text-center medium-font">
                                    <i>"Keep in Touch!  Join our newsletter mailing list to receive news, ideas and offers from WPHR Manager"</i>
                                </p>
                            </div>
                            <div class="half-container-2 text-right">
                                <img src="<?php
                                echo WPHRGDPR_PLUGIN_URL."/assets/images/Barbara-Podium-600px-Trans.png";?>"/><br/>
                            </div>
                            <style type="text/css">
                                #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
                                /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                                   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                            </style>
                            <div id="mc_embed_signup">
                                <form action="//wphrmanager.us16.list-manage.com/subscribe/post?u=56983662fe5653288deefe738&amp;id=abf470c4f8" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                    <div id="mc_embed_signup_scroll" class="margin-cls">
                                        <label for="mce-EMAIL">Email</label>
                                        <input type="email" value="" name="EMAIL" class="email support-input" id="mce-EMAIL" placeholder="email address" required>
                                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_56983662fe5653288deefe738_abf470c4f8" tabindex="-1" value=""></div>
                                        <div class="clear text-center"><input type="submit" value="Sign Me Up" name="subscribe" id="mc-embedded-subscribe" class="button button-primary greenbtn"></div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>	

                <div id="" class="postbox " >
                    <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Donate</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class='hndle'><span>Donate</span></h2>
                    <div class="inside">
                        <div class="main text-center">
                            <p>If you like this plugin and find it useful, help keep it free and actively developed by clicking the donate button</p>
                            <div class="paypal_form">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <input type="hidden" name="hosted_button_id" value="GLKGN964GRZJW">
                                    <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                                </form>
                            </div>
                        </div>		
                    </div>
                </div>
                <?php
                ?>
            </div>
        </div>
    </div>
</div>