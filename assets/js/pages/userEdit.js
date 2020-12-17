import $ from "jquery";

$(document).ready(function(){
    $("#userSearchInput").on("keyup", function() {
        const value = $(this).val().toLowerCase();

        if (value.length > 1) {
            $("#usersList li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
    });

    $("form[name='avatar'] select").on("change", function () {
        const accessoriesType = $('#avatar_accessoriesType').val();
        const clotheColor = $('#avatar_clotheColor').val();
        const clotheType = $('#avatar_clotheType').val();
        const eyeType = $('#avatar_eyeType').val();
        const eyebrowType = $('#avatar_eyebrowType').val();
        const facialHairColor = $('#avatar_facialHairColor').val();
        const facialHairType = $('#avatar_facialHairType').val();
        const graphicType = $('#avatar_graphicType').val();
        const hairColor = $('#avatar_hairColor').val();
        const hatColor = $('#avatar_hatColor').val();
        const mouthType = $('#avatar_mouthType').val();
        const skinColor = $('#avatar_skinColor').val();
        const topType = $('#avatar_topType').val();

        const avatarUrl = `https://avataaars.io/?avatarStyle=Transparent&topType=${topType}&accessoriesType=${accessoriesType}&hatColor=${hatColor}&hairColor=${hairColor}&facialHairType=${facialHairType}&facialHairColor=${facialHairColor}&clotheType=${clotheType}&clotheColor=${clotheColor}&graphicType=${graphicType}&eyeType=${eyeType}&eyebrowType=${eyebrowType}&mouthType=${mouthType}&skinColor=${skinColor}`;

        $('#avatar-container img').attr('src', avatarUrl);
    });
});