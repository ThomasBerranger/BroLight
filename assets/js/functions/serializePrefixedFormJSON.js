(function ($) {
    $.fn.serializePrefixedFormJSON = function() {
        let o = {};
        let array = this.serializeArray();

        $.each(array, function () {
            let name = this.name;
            const value = this.value;

            name = name.substring(
                name.lastIndexOf("[") + 1,
                name.lastIndexOf("]")
            );

            o[name] = value;
        });

        return JSON.stringify(o);
    };
})(jQuery);