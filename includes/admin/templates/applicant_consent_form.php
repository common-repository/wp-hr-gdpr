                                        <section id="section-consent-form" class="postbox">
                                            <span class="hndle-toogle-button"></span>
                                            <div class="section-header"><h2 class="hndle"><span><?php _e('Data Consent', 'wp-hr-gdpr'); ?></span></h2></div>
											
                                            <div class="section-content toggle-metabox-show">
												<?php
												if( !$consent_data ){
												?>
                                                <h3 class="no-interview-todo-caption"><?php _e('No Data Consent', 'wp-hr-gdpr');?></h3>
												<?php
											}else{
												foreach( $consent_data as $key => $value ){
													echo sprintf('<p><input type="checkbox" disabled="disabled" %s /> %s</p>' , $value ? 'checked' : '', $key );	
												}
											}
                                                ?>
                                            </div>
                                        </section>