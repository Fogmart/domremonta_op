<?php echo $header; ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>

        <?php if (!$logged ) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $text_checkout_account; ?></h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-checkout-option">
                <div class="panel-body"></div>
            </div>
        </div>
        <?php } else { ?>


      <div class="container" >
          <div class="row">
              <div class="col-md-12">
                <h4 class="panel-title">Способ получения</h4>
                      <input type="radio" id="dost1" name="dost" value="0"
                             checked
                             onclick="updshipViz()"><label for="dost1">Самовывоз</label>
                      <input type="radio" id="dost2" name="dost" value="1" onclick="updshipViz()"><label for="dost2">Доставка</label>
              </div>
          </div>
          <div class="row">
              <div class="col-md-2" id="collapse-date-pref">
                  <label class="slf"> Желаемая дата самовывоза </label>
                  <label class="shiplbl"> Желаемая дата доставки </label>
                  <input type="text" id="date_pref" name="date_pref" class="form-control" />
              </div>
          </div>

          <div class="row ship">
              <div class="col-md-6" id="collapse-shipping-address">
                <div class="panel-body"></div>
              </div>
              <div class="col-md-6" id="collapse-shipping-method">
                <div class="panel-body"></div>
              </div>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-method">
            <div class="panel-body"></div>
          </div>
          <div class="panel-collapse collapse" id="collapse-checkout-confirm">
            <div class="panel-body"></div>
          </div>
          <div class="buttons">
              <input type="button"
                     value="Оформить заказ" id="button-cnfrm"
                      class="btn btn-primary" />
          </div>
          <?php }  ?>

      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--

    <?php if (!$logged) { ?>
        $(document).ready(function() {
            $.ajax({
                url: 'index.php?route=checkout/login',
                dataType: 'html',
                success: function(html) {
                    $('#collapse-checkout-option .panel-body').html(html);

                    $('#collapse-checkout-option').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_option; ?> <i class="fa fa-caret-down"></i></a>');

                    $('a[href=\'#collapse-checkout-option\']').trigger('click');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    <?php } else { ?>
$(document).ready(function() {
    $.ajax({
        url: 'index.php?route=checkout/shipping_address',
        dataType: 'html',
        success: function(html) {
            $('#collapse-shipping-address').html(html);
            $('#collapse-shipping-address').show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

    $.ajax({
      url: 'index.php?route=checkout/shipping_method',
      dataType: 'html',
      complete: function() {
        $('#button-shipping-address').button('reset');
      },
      success: function(html) {
        $('#collapse-shipping-method').html(html);
        $('#collapse-shipping-method').show();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

    $.ajax({
      url: 'index.php?route=checkout/payment_method',
      dataType: 'html',
      complete: function() {
        $('#button-guest').button('reset');
      },
      success: function(html) {
        $('#collapse-payment-method').html(html);
        $('#collapse-payment-method').show();

      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
    $(".ship").hide()
    $(".shiplbl").hide()

    $('#date_pref').datetimepicker({
        pickTime: false,
    });

});
    <?php } ?>
function updshipViz() {
    var v = $("[name=dost]:checked").val()
    if (v == 1){
        $(".ship").slideDown()
        $(".shiplbl").show()
        $(".slf").hide()
    } else {
        $(".ship").slideUp()
        $(".shiplbl").hide()
        $(".slf").show()
    }
}


    $(document).delegate('#button-cnfrm', 'click', function() {
        var go = true

        if ($("[name=dost]:checked").val() == 1) {
            $.ajax({
                url: 'index.php?route=checkout/shipping_address/save',
                type: 'post',
                async: false,
                data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
                dataType: 'json',
                beforeSend: function () {
                    $('#button-cnfrm').button('loading');
                },
                success: function (json) {
                    console.log(json)
                    $('.alert, .text-danger').remove();

                    if (json['redirect']) {
                        location = json['redirect'];
                    } else if (json['error']) {
                        $('#button-cnfrm').button('reset');

                        if (json['error']['warning']) {
                            $('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }

                        for (i in json['error']) {
                            var element = $('#input-shipping-' + i.replace('_', '-'));

                            if ($(element).parent().hasClass('input-group')) {
                                $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                            } else {
                                $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                            }
                        }

                        // Highlight any found errors
                        $('.text-danger').parent().parent().addClass('has-error');
                        go = false
                    } else {

                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }

        if (!go) return
        $.ajax({
            url: 'index.php?route=checkout/payment_method/save',
            type: 'post',
            data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea, #collapse-date-pref input'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-method').button('loading');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-payment-method').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-payment-method .panel-body').prepend('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/confirm',
                        dataType: 'html',
                        complete: function() {
                            $('#button-payment-method').button('reset');
                        },
                        success: function(html) {
                            $('#collapse-checkout-confirm').html(html);
                            $('#collapse-checkout-confirm').show();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $(document).delegate('#button-login', 'click', function() {
        $.ajax({
            url: 'index.php?route=checkout/login/save',
            type: 'post',
            data: $('#collapse-checkout-option :input'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-login').button('loading');
            },
            complete: function() {
                $('#button-login').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#collapse-checkout-option .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    // Highlight any found errors
                    $('input[name=\'email\']').parent().addClass('has-error');
                    $('input[name=\'password\']').parent().addClass('has-error');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Register
    $(document).delegate('#button-account', 'click', function() {
        document.location = 'index.php?route=account/register';
    });


//--></script>


<?php echo $footer; ?>
