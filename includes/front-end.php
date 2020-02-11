<div class="wrap" data-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>">
<h1>WP Broadcasts</h1>

<style>
.wrap h1{
    margin-bottom:15px;
}
.alert {
    background: #FAFAFA;
    padding: 10px;
    border-radius: 5px;
    margin-top: 15px;
}
.white-popup {
  position: relative;
  background: #FFF;
  padding: 20px;
  width: auto;
  max-width: 500px;
  margin: 20px auto;
}
body .mfp-bg {
    z-index: 9999;
}
body .mfp-wrap {
    z-index: 999999;
}

.broadcast-action-container a {
    display: inline-block;
    background: #d5d5d5;
    color: black;
    padding: 0px 10px;
}
</style>

<div id="wp-broadcast-status-container"></div>

<button id="wp-broadcast-add" data-mfp-src="#form-broadcast-container" class="open-popup-link">ADD NEW BROADCAST</button>
<div id="wp-broadcasts-container"></div>


<style>
.form-container label{
    display:block;
    margin-bottom:5px;
}
.form-container .form-control {
    width: 100%;
    margin-bottom:15px;
}
.form-container header {
    font-size: 20px;
    display: block;
    font-weight: bold;
    margin-bottom: 10px;
}
.form-action{
    text-align:right;
}
.form-action button {
    background: #2980b9;
    color: white;
    padding: 5px 15px;
    border: none;
}
.broadcast-action-column {
    width: 200px;
}

#wp-broadcast-status-container .wp-status.wp-success {
    background: #00b894;
    color: white;
}
#wp-broadcast-status-container .wp-status {
    background: #FAFAFA;
    padding: 10px;
    margin-bottom: 15px;
}
#wp-broadcast-status-container  span.wp-status-close {
    float: right;
    font-size: 23px;
    font-weight: bold;
    position: relative;
    top: -3px;
    cursor:pointer;
}
</style>

<div class="form-broadcast-container white-popup mfp-hide" id="form-broadcast-container">
    <form id="wp-broadcast-form" class="form-container form-broadcast">

        <header>New Broadcast</header>
        <label>Title</label>
        <input type="text" name="broadcast[title]" class="form-control"/>

        <label>Subject</label>
        <input type="text" name="broadcast[subject]" class="form-control"/>

        <label>Description</label>
        <textarea name="broadcast[description]" class="form-control"></textarea>

        <div class="form-action">
            <button id="wp-broadcast-save" type="button">Submit</button>
        </div>
    </form>
</div>

<script>

jQuery(document).ready(function($){
    
    var adminAjaxURL = $('[data-ajax-url]').attr('data-ajax-url');

    console.log(document);

    
    $('.open-popup-link').magnificPopup({
        type:'inline',
        midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    });

    var wpBroadcast = {
        init : function(){

            wpBroadcast.events.getBroadcasts();
            
            // wpBroadcast.elem.addBroadcast.click(function(){
            //     wpBroadcast.events.showAddBroadcast();
            // });


            // Add Save Broadcast Event
            $(document).on('click','#wp-broadcast-save', function(){
                wpBroadcast.events.saveBroadcast();
            });

            $(document).on('click','.wp-status-close', function(){
                wpBroadcast.elem.broadcastStatusContainer.html('');
            });

            $(document).on('click','.wp-broadcast-delete', function(){
                
                var r = confirm("Are you sure you want to delete this broadcast?\nAll broadcasts queue will be also deleted.");
                if (r == true) {
                    wpBroadcast.events.deleteBroadcast($(this));
                }
               
            });

            $(document).on('click','.wp-broadcast-queue', function(){
                
                var r = confirm("Are you sure you want this broadcast to queue?");
                if (r == true) {
                    wpBroadcast.events.queueBroadcast($(this));
                }
               
            });
            
        },
        elem : {
            broadcastsContainer : $('#wp-broadcasts-container'),
            broadcastStatusContainer : $('#wp-broadcast-status-container'),
            addBroadcast : $('#wp-broadcast-add'),
            saveBroadcast : $('#wp-broadcast-save')
        },
        events : {

            getBroadcasts : function(){

                var data = {
                    action : 'wp_broadcast_get_broadcasts'
                }
                $.get(adminAjaxURL, data, function(broadcastsHTML){
                    wpBroadcast.elem.broadcastsContainer.html(broadcastsHTML);
                });
                
            },
            deleteBroadcast : function(button){
                
                var id = button.attr('data-id');
                var data = {
                    action : 'wp_broadcast_delete_broadcast',
                    id : id
                }
                $.post(adminAjaxURL, data, function(broadcastsHTML){
                   wpBroadcast.events.getBroadcasts();
                });
                
            },
            queueBroadcast : function(button){
                
                var id = button.attr('data-id');
                var data = {
                    action : 'wp_broadcast_queue_broadcast',
                    id : id
                }
                $.post(adminAjaxURL, data, function(broadcastsHTML){
                   wpBroadcast.events.getBroadcasts();
                });
                
            },
            showAddBroadcast : function(){

                $.magnificPopup.open({
                    items: {
                        src: '<div class="white-popup">' + $('.form-broadcast-container').html() + '</div>', // can be a HTML string, jQuery object, or CSS selector
                        type: 'inline'
                    }
                });

                

            },

            saveBroadcast : function(){

                var broadcastForm  = $('.mfp-wrap #wp-broadcast-form');


                var formdata = broadcastForm.serializeArray();
                var data = {};
                $(formdata ).each(function(index, obj){
                    data[obj.name] = obj.value;
                });
                data['action'] = 'wp_broadcast_post_broadcasts';


                $.post(adminAjaxURL, data, function(response){
                    broadcastForm.find('input,textarea').val("");
                    $.magnificPopup.close();
                    wpBroadcast.events.getBroadcasts();

                    wpBroadcast.setNotification('success','Successfully added broadcast!');
                });

            }

        },
        setNotification : function (status, message){
            wpBroadcast.elem.broadcastStatusContainer.html('<div class="wp-status wp-' + status + '">' + message + '<span class="wp-status-close">×</span></div>');
        }
    }

    wpBroadcast.init();


});
</script>

<!-- <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form> -->

</div>