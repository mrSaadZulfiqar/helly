@extends('vendors.layout')
@section('content')
<style>
    .msger {
  display: flex;
  flex-flow: column wrap;
  justify-content: space-between;
  width: 100%;
  max-width: 98%;
  margin: 25px 10px;
  height: calc(100% - 50px);
  border: var(--border);
  border-radius: 5px;
  background: var(--msger-bg);
  box-shadow: 0 15px 15px -5px rgba(0, 0, 0, 0.2);
}

.msger-header {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  border-bottom: var(--border);
  background: #eee;
  color: #666;
}

.msger-chat {
  height: 50vh;
  overflow-y: auto;
  padding: 10px;
}
.msger-chat::-webkit-scrollbar {
  width: 6px;
}
.msger-chat::-webkit-scrollbar-track {
  background: #ddd;
}
.msger-chat::-webkit-scrollbar-thumb {
  background: #bdbdbd;
}
.msg {
  display: flex;
  align-items: flex-end;
  margin-bottom: 10px;
}
.msg:last-of-type {
  margin: 0;
}
.msg-img {
  width: 50px;
  height: 50px;
  margin-right: 10px;
  background: #ddd;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  border-radius: 50%;
}
.msg-bubble {
  max-width: 450px;
  padding: 15px;
  border-radius: 15px;
  background: #ececec;
}
.msg-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.msg-info-name {
  margin-right: 10px;
  font-weight: bold;
}
.msg-info-time {
  font-size: 0.85em;
}

.left-msg .msg-bubble {
  border-bottom-left-radius: 0;
}

.right-msg {
  flex-direction: row-reverse;
}
.right-msg .msg-bubble {
  background: #4b38b3;
  color: #fff;
  border-bottom-right-radius: 0;
}
.right-msg .msg-img {
  margin: 0 0 0 10px;
}

.msger-inputarea {
  display: flex;
  padding: 10px;
  border-top: var(--border);
  background: #eee;
}
.msger-inputarea * {
  padding: 10px;
  border: none;
  border-radius: 3px;
  font-size: 1em;
}
.msger-input {
  flex: 1;
  background: #ddd;
}
.msger-send-btn {
  margin-left: 10px;
  background: rgb(0, 196, 65);
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.23s;
}
.msger-send-btn:hover {
  background: rgb(0, 180, 50);
}

.msger-chat {
  background-color: #fcfcfe;
}

label.sms_provider input:checked+span {
    background: #4b38b3;
    color: #fff;
}
span.btn.rounded-pill.sms_provider-btn {
    border: 1px solid #4b38b3;
}
label.sms_provider input {
    display: none;
}
</style>
<?php
$enabled_sms_api = $system_sms_service_provider;
$enabled_sms_sendor = 'voximplant_sms';
?>
<div class="msger-main-ag">
    <section class="msger">
      <header class="msger-header">
        <div class="msger-header-title">
          <i class="fas fa-comment-alt"></i> <b>{{$vendor->username}}</b>
        </div>
        <div class="msger-header-options">
            
            <?php if($enabled_sms_api == 'messagebird'){ 
			$enabled_sms_sendor = 'messagebird_sms';
			?>
            <label class="sms_provider" for="messagebird_sms">
                <input type="radio" name="sms_provider" id="messagebird_sms" value="messagebird_sms" <?php echo ($enabled_sms_api == 'messagebird')?'checked':''; ?>>
                <span class="btn rounded-pill sms_provider-btn" type="button">MessageBird</span>
            </label>
            <?php } ?>
			
			<?php if($enabled_sms_api == 'voximplant'){ 
			$enabled_sms_sendor = 'voximplant_sms';
			?>
            <label class="sms_provider" for="voximplant_sms">
                <input type="radio" name="sms_provider" id="voximplant_sms" value="voximplant_sms" <?php echo ($enabled_sms_api == 'voximplant')?'checked':''; ?>>
                <span class="btn rounded-pill sms_provider-btn" type="button">Voximplant</span>
            </label>
            <?php } ?>
            
          <!--<span><i class="fas fa-cog"></i></span>-->
        </div>
      </header>
    
      <main class="msger-chat">
        
        
      </main>
    
      <form class="msger-inputarea" id="vendor_chat_form" method="post">
          @csrf
        <input type="text" id="vendor_chat_form_msg" name="vendor_chat_form_msg" class="msger-input" placeholder="Enter your message...">
        
        <?php 
         $phone_ = str_replace(' ', '', $_GET['phone_']); // Replaces all spaces with hyphens.

         $phone_ = preg_replace('/[^A-Za-z0-9]/', '', $phone_); // Removes special chars.
        ?>
        <input type="hidden" id="vendor_chat_form_phone_" name="vendor_chat_form_phone_" class="msger-input" value="<?php echo $phone_; ?>">
        <input type="hidden" id="sms_provide_to_send" name="sms_provide_to_send" value="{{$enabled_sms_sendor}}">
        <button type="submit" class="msger-send-btn">Send</button>
      </form>
    </section>
</div>

@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


<script>
    $(document).ready(function(){
        
        const msgerChat = $(".msger-chat");
        const vendor_chat_form_phone_ = $('#vendor_chat_form_phone_').val();
        
        let fistMessageId = 0;
        let lastMessageId = 0;
        
        let sms_provider = $('input[name="sms_provider"]:checked').val();
        
        console.log(sms_provider);
        
        get_chat_history(vendor_chat_form_phone_);
        
        $('input[name="sms_provider"]').on('change', function(){
            sms_provider = $('input[name="sms_provider"]:checked').val();
            $('#sms_provide_to_send').val(sms_provider);
            fistMessageId = 0;
            lastMessageId = 0;
            msgerChat.html('');
        });
        
        $('.msger-chat').scroll(function() {
            if($('.msger-chat').scrollTop() == 0) {
                
                $.ajax({
                    url: "<?php echo route('customerchat.history'); ?>",
                    type: "GET",
                    data: {first_message_id: fistMessageId, 'vendor_number':vendor_chat_form_phone_,sms_provider:sms_provider},
                    dataType: "json",
                    success: function(data){
                        // Handle the response data
                        if(data.chats.length > 0){
                          fistMessageId = data.chats[data.chats.length-1].id;
                          
                        }
                      
                        $.each(data.chats, function( index, value ) {
                            var first_msg = msgerChat.find('.msg').first();
                             if(vendor_chat_form_phone_ == value.sender_number){
                                 var msgHTML_ = appendMessage_ajax('<?php echo $vendor->username; ?>', '', 'left', value.msg);
                             }
                              else{
                                  var msgHTML_ = appendMessage_ajax('Team', '', 'right', value.msg);
                              }
                              
                              first_msg.before(msgHTML_);
                        });
                    },
                    error: function(xhr, status, error){
                        // Handle the error
                        console.log(xhr.responseText);
                    }
                });
            }
        });
        
        
        setInterval(function(){
          get_chat_history(vendor_chat_form_phone_);
        }, 5000); // 5000 milliseconds = 5 seconds
        
        
        function get_chat_history(vendor_chat_form_phone_){
           
            $.ajax({
                url: "<?php echo route('customerchat.history'); ?>",
                type: "GET",
                data:{last_message_id: lastMessageId, 'vendor_number':vendor_chat_form_phone_,sms_provider:sms_provider},
                dataType: "json",
                success: function(data){
                  // Handle the response data
                  //console.log();
                  if(fistMessageId == 0 && data.chats.length > 0){
                      fistMessageId = data.chats[0].id;
                      
                  }
                  
                  if( data.chats.length > 0){
                      lastMessageId = data.chats[data.chats.length-1].id;
                  }
                  
                  $.each(data.chats, function( index, value ) {
                      
                     if(vendor_chat_form_phone_ == value.sender_number){
                         
                         var msgHTML_ = appendMessage_ajax('<?php echo $vendor->username; ?>', '', 'left', value.msg);
                     }
                      else{
                          var msgHTML_ = appendMessage_ajax('Team', '', 'right', value.msg);
                      }
                      
                       msgerChat.append( msgHTML_);
                        msgerChat.scrollTop(500);
                });
                },
                error: function(xhr, status, error){
                  // Handle the error
                  console.log(xhr.responseText);
                }
              });
        }
        
        $('#vendor_chat_form_msg').keypress(function (e) {
         var key = e.which;
         if(key == 13)  // the enter key code
          {
            $('#vendor_chat_form').submit(); 
            return false;
          }
        });
        

        $('#vendor_chat_form').submit(function(){
            var msg__ = $('#vendor_chat_form_msg').val();
            appendMessage('Team', '', 'right', msg__);
            $.ajax({
                url: '<?php echo route('customerchat.send'); ?>',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    if(response.status == 'sent'){
                        lastMessageId = response.sms_id;
                        $('.msg-status').text('');
                    }else{
                        $('.msg-status').text('Failed!');
                    }
                    
                    $('#vendor_chat_form_msg').val('');
                },
                error: function(response) {
                    $('.msg-status').text('Failed!');
                }
            });
            
            return false;    
        });
    
        function appendMessage(name, img, side, text) {
          //   Simple solution for small apps
          const msgHTML = `
            <div class="msg ${side}-msg">
              
              <div class="msg-bubble">
                <div class="msg-info">
                  <div class="msg-info-name">${name}</div>
                  <div class="msg-info-time">${formatDate(new Date())}</div>
                </div>
        
                <div class="msg-text">${text}</div>
                <div class="msg-status">Sending...</div>
              </div>
            </div>
          `;
        
          msgerChat.append( msgHTML);
          msgerChat.scrollTop(500);
        }
        
        function appendMessage_ajax(name, img, side, text) {
          //   Simple solution for small apps
          const msgHTML = `
            <div class="msg ${side}-msg">
              
              <div class="msg-bubble">
                <div class="msg-info">
                  <div class="msg-info-name">${name}</div>
                  <div class="msg-info-time">${formatDate(new Date())}</div>
                </div>
        
                <div class="msg-text">${text}</div>
                
              </div>
            </div>
          `;
        return msgHTML;
        //   msgerChat.append( msgHTML);
        //   msgerChat.scrollTop(500);
        }
        function formatDate(date) {
          const h = "0" + date.getHours();
          const m = "0" + date.getMinutes();
        
          return `${h.slice(-2)}:${m.slice(-2)}`;
        }   
    });
</script>
@endsection