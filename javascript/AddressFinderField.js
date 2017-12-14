
(function($) {
	$.entwine("ss", function($) {
        $('.address-finder-field').entwine({
            onmatch: function(){
                $('.address-finder-field').each(function(e){
                    var widget,
                        key =  $(this).attr('key'),
                        fieldID = $(this).attr('id'),
                        initAF = function(){
                            widget = new AddressFinder.Widget(
                                document.getElementById(fieldID),
                                key,
                                'NZ',
                                {
                                }
                            );

                            widget.on('result:select', function(fullAddress, metaData) {
                                var selected = new AddressFinder.NZSelectedAddress(fullAddress, metaData);
                                $('.addressfinderfield-metafield').each(function(index, obj){
                                    var metaValue = $(this).attr('metatype');
                                    $(this).val(selected.metaData[metaValue]);
                                });
                            });
                        };

                    // Initialise address finder field
                    $.getScript('//api.addressfinder.io/assets/v3/widget.js', initAF);
                });
            }
        });
    });
}(jQuery));
