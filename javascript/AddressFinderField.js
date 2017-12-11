
(function($) {
	$.entwine("ss", function($) {
        $('#$AddressFieldID').entwine({
            onmatch: function(){
                var widget,
                    initAF = function(){
                    widget = new AddressFinder.Widget(
                        document.getElementById('$AddressFieldID'),
                        '$Key',
                        'NZ',
                        {
                        }
                    );

                    widget.on('result:select', function(fullAddress, metaData) {
                        var selected = new AddressFinder.NZSelectedAddress(fullAddress, metaData);
                        //console.log(selected.metaData);
                        $('.addressfinderfield-metafield').each(function(index, obj){
                            var metaValue = $(this).attr('metatype');
                            $(this).val(selected.metaData[metaValue]);
                        });
                    });
                };

                // Initialise address finder field
                $.getScript('//api.addressfinder.io/assets/v3/widget.js', initAF);
            }
        });
    });
}(jQuery));
