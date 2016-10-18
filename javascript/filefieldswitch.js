(function($) {
	$.entwine('ss', function($){
		$('#Form_ItemEditForm_FileType input').entwine({
			onmatch: function() {
				var self = $(this);
				if(self.attr('checked')) this.toggle();
				this._super();
			},
			onunmatch: function() {
				this._super();
			},
			onclick: function() {
				this.toggle();
			},
			toggle: function() {
				if($(this).attr('value') == 'Image') {
					$('#Form_ItemEditForm_IconFile_Holder').hide();
					$('#Form_ItemEditForm_IconImage_Holder').show();
				} else {
					$('#Form_ItemEditForm_IconFile_Holder').show();
					$('#Form_ItemEditForm_IconImage_Holder').hide();
				}
			}
		});
	});
})(jQuery);

