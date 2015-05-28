var cadmin = {
    init: function(){
        cadmin.addressCollection = $('.addresses');
        cadmin.addressCollection.data('index', cadmin.addressCollection.find(':input').length)
        cadmin.observer()
    },
    observer: function(){
        cadmin.observeAddAddress();
    },
    observeAddAddress: function(){
        $('.add-address').unbind('click').click(function(e){
            e.preventDefault();
            cadmin.addAddress();
        })
    },
    addAddress: function($image_proto_form){
        var proto = cadmin.addressCollection.data('prototype');
        var index = cadmin.addressCollection.data('index');
        var newForm = proto.replace(/__name__/g, index);

        $newAddress = $(newForm);
       

        cadmin.addressCollection.append($newAddress);
        cadmin.addressCollection.data('index', index + 1);
    }
}

cadmin.init();