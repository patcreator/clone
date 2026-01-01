 </div>
<!-- Emoji Picker (Hidden by default) -->
<div id="emojiPicker" class="emoji-picker hidden bg-white shadow dark:bg-gray-800"></div>
<div id="upload-document"></div>
<div id="photo-video-modal-container"></div>
<div id="ads-container"></div>
    <!-- Flowbite JS -->
<script src="<?= $path ?>app/system/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    const uiStack = [];
$(document).ready(function () {
    getPart('sm-sidebar','#sm-sidebar');
    getPart('upload-document','#upload-document');
    getPart('photo-video-modal','#photo-video-modal-container');
    // getPart('business-tools/ads','#ads-container');
    $("[data-get-part]").each(function () {
        let part = $(this).data('get-part');
        getPart(part,this);
    });

});

    function getPart(part, element = null) {
    if (element == null) {element = $("#aside-contents");}
    const $el = $(element);

    if (!$el.length) {
        console.error('Element not found:', element);
        return;
        
    }

     function fetch(){
         $.get('<?= $path ?>app/frontend/parts/' + part + '.html')
            .done(function (res) {
                $el.html(res);
            })
            .fail(function (xhr, error, msg) {
                console.error('Load failed:', error, msg);
            });
     }
    if ($.trim($el.html()) === '') {
        fetch();
    }else{
        uiStack.push($el.html());
        fetch();
    }
} 
function backToPart(){
    $("#aside-contents").html(uiStack.pop());
}
function killPart(element) {
    const $el = $(element);

    if (!$el.length) {
        console.error('Element not found:', element);
        return;
    }

    if ($.trim($el.html()) !== '') {
        $el.html('');
    }
}

  function getMyId() {
      $.post("@api/api", {
        action:'get-my-id'
      }, function(res) {return res.id}); 
  }
  




</script>
</body>
</html>