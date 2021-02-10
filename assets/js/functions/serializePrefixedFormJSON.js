(function ($) {
    $.fn.serializePrefixedFormJSON = function() {
        const o = {};
        const array = this.serializeArray();

        $.each(array, function () {
            let name = this.name;
            const value = this.value;

            name = name.substring(
                name.lastIndexOf("[") + 1,
                name.lastIndexOf("]")
            );

            o[name] = value;
        });

        $('input[type="checkbox"]:not(:checked)').each(function(){
            let name = this.name;

            name = name.substring(
                name.lastIndexOf("[") + 1,
                name.lastIndexOf("]")
            );

            o[name] = '0';
        });

        return JSON.stringify(o);
    };
})(jQuery);