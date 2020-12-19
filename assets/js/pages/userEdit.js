import $ from "jquery";

$(document).ready(function() {
    editInputsDisplay();

    $("#userSearchInput").on("keyup", function() {
        const value = $(this).val().toLowerCase();

        if (value.length > 1) {
            $("#usersList li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        } else {
            $("#usersList li").each(function () {
                $(this).hide();
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

        editInputsDisplay();
    });
});

function editInputsDisplay() {
    const topTypeInput = $('#avatar_topType');
    const accessoriesTypeInput = $('#avatar_accessoriesType');
    const hatColorInput = $('#avatar_hatColor');
    const clotheColorInput = $('#avatar_clotheColor');
    const clotheTypeInput = $('#avatar_clotheType');
    const facialHairColorInput = $('#avatar_facialHairColor');
    const facialHairTypeInput = $('#avatar_facialHairType');
    const graphicTypeInput = $('#avatar_graphicType');
    const hairColorInput = $('#avatar_hairColor');

    const coloredHats = ['Hijab', 'Turban', 'WinterHat1', 'WinterHat2', 'WinterHat3', 'WinterHat4'];
    const notColoredHairs = ['NoHair', 'Eyepatch', 'Hat', 'Hijab', 'Turban', 'WinterHat1', 'WinterHat2', 'WinterHat3', 'WinterHat4', 'LongHairFrida', 'LongHairShavedSides'];

    if (topTypeInput.val() !== 'Eyepatch') {
        enable([accessoriesTypeInput, hatColorInput]);
    } else {
        disable([accessoriesTypeInput, hatColorInput]);
    }

    if (coloredHats.includes(topTypeInput.val())) {
        enable(hatColorInput);
    } else {
        disable(hatColorInput);
    }

    if (!notColoredHairs.includes(topTypeInput.val())) {
        enable(hairColorInput);
    } else {
        disable(hairColorInput);
    }

    if (facialHairTypeInput.val() !== 'Blank') {
        enable(facialHairColorInput);
    } else {
        disable(facialHairColorInput);
    }

    if (clotheTypeInput.val() !== 'BlazerShirt' && clotheTypeInput.val() !== 'BlazerSweater') {
        enable(clotheColorInput);
    } else {
        disable(clotheColorInput);
    }

    if (clotheTypeInput.val() === 'GraphicShirt') {
        enable(graphicTypeInput);
    } else {
        disable(graphicTypeInput);
    }

    function enable(elements) {
        if (Array.isArray(elements)) {
            elements.forEach(function(element){
                element.prop('disabled', false);
            });
        } else {
            elements.prop('disabled', false);
        }
    }

    function disable(elements) {
        if (Array.isArray(elements)) {
            elements.forEach(function (element) {
                element.prop('disabled', true);
            });
        } else {
            elements.prop('disabled', true);
        }
    }
}