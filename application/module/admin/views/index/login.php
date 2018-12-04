<?php
  $linkAction = URL::createLink('admin', 'index', 'login');
  $linkHome = URL::createLink('default', 'index', 'index');
  $imageURL = $this->_dirImg;
?>
<div class="login">
    <div class="login-background"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="login-form">
                <div class="login-form-background"></div>
                <div class="welcome">
                    <p class="text-color-white">WELCOME TO<br>BOOK MANAGEMENT</p>
                </div>
                <!-- ERROR -->
			          <?php echo isset($this->errors) ? $this->errors : '';?>
                <div class="tab-content clearfix">
                    <!--  LOGIN TAB -->
                    <form action="<?php echo $linkAction; ?>" method="post" id="form-login">
                      <div id="login" class="tab-pane fade in active clearfix">
                          <div class="col-lg-offset-2 col-lg-8">
                              <div class="form-horizontal">
                                  <div class="form-group">
                                      <div class="col-lg-offset-1 col-lg-1">
                                          <img src="<?php echo $imageURL; ?>/email-icon.png" class="icon-email" alt="">
                                      </div>
                                      <div class="col-lg-9">
                                          <input type="text" name="form[username]" class="form-control form-login-detail" placeholder="Username">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-lg-offset-1 col-lg-1">
                                          <img src="<?php echo $imageURL; ?>/lock-icon.png" class="icon-lock" alt="">
                                      </div>
                                      <div class="col-lg-9">
                                          <input type="password" name="form[password]" class="form-control form-login-detail" placeholder="Password">
                                      </div>
                                      <!-- TOKEN -->
                                      <input name="form[token]" type="hidden" value="<?php echo time(); ?>" />
                                  </div>
                                  <div class="form-group">
                                      <a href="#" onclick="document.getElementById('form-login').submit();" class="col-lg-offset-1 col-lg-10 btn btn-primary btn-login">ログイン</a>
                                  </div>
                                  <div class="form-group">
                                      <a data-toggle="tab" href="#forgot" class="">パスワードを忘れたらこちら</a>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div id="forgot" class="tab-pane fade">
                          <div class="col-lg-offset-2 col-lg-8">
                              <div class="form-horizontal">
                                  <div class="form-group">
                                      <div class="col-lg-offset-1 col-lg-1">
                                          <img src="<?php echo $imageURL; ?>/email-icon.png" class="icon-email" alt="">
                                      </div>
                                      <div class="col-lg-9">
                                          <input type="password" class="form-control form-login-detail" placeholder="パスワード">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <a href="#" class="col-lg-offset-1 col-lg-5 btn btn-primary btn-submit">OK</a>
                                      <a data-toggle="tab" href="#login" class="col-lg-5 btn btn-danger btn-cancel" id="#btn-cancel">キャンセル</a>
                                  </div>
                              </div>
                          </div>

                      </div>
                    </form>
                </div>
            </div>
        </div>
        <!--  LOGIN TAB -->
        <!-- FORGOT TAB -->
        <div class="row">

        </div>
        <!-- FORGOT TAB -->
    </div>
</div>