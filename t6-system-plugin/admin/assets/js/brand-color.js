jQuery(function($) {
  initMinicolors();

  $('body').on('subform-row-add', initMinicolors);

  function initMinicolors(event, container) {
    container = container || document;
    $(".themeConfigModal").append($('<div class="t6-theme-color"></div>'));
    $(container).find('.t6-custom-color-spec').each(function() {
      var $this = $(this);
      $this.spectrum({
        type: "color",
        showPalette: false,
        showInput: true,
        allowEmpty:false,
        showInitial: true,
        color:true,
        appendTo: ".t6-theme-color",
        preferredFormat: "hex6",
        palette: [],
        hide: function(color){
          $this.trigger('change');
        },
        beforeShow: function(color){
          if($this.hasClass('t6-palette-color-spec')){
            return false;
          }
          if($('.t6-theme-color').is(":hidden")){
            $('.t6-theme-color').show();
          }
        }
      });
      $this.on("dragstop.spectrum", function(e, color) {
          $this.trigger('change'); // #ff0000
      });
    });
  }
});