/* 
 * EasyTransac oneclick.js
 */
jQuery(function($) {

    // Double load failsafe.
    var session_id = 'easytransac-oneclick' + Date.now();

    // Creates workspace.
    $('#easytransac-namespace').append($('<div id="' + session_id + '" class="payment_box payment_method_easytransac">'));

    // Unified OneClick loader
    // Requires : listcards_url
    //
    var listcards_url = Drupal.settings.basePath + 'easytransac/listcards';

    $('#' + session_id).html('<span id="etocloa001">OneClick loading ...</span>');

    // JSON Call
    $.getJSON(listcards_url, {}, buildFromJson);

    // Build  OneClick form from JSON.
    function buildFromJson(json) {

        $('#etocloa001').fadeOut().remove();

        if (!json.status || json.packet.length === 0) {

            // No cards available.
            $('#' + session_id).remove();
            return;
        }

        // Namespace
        var _space = $('#' + session_id);

        // Label
        _space.append($('<span style="width:100px;" title="Direct credit card payment">OneClick : </span>'));

        // Dropdown
        _space.append($('<select id="etalcadd001" name="oneclick_alias" style="width:200px; margin-left:10px;">'));
        $.each(json.packet, function(i, row) {
            $('#etalcadd001')
                .append($('<option value="' + row.Alias + '">' + row.CardNumber + '</option>'));
        });

        // Button
        _space.append($(' <input type="submit" id="etocbu001" class="button alt" style="width:150px; margin-left:15px;" value="OneClick pay">'));

        // Button click/*
        $('#etocbu001').click(function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            // Set Drupal Commerce fields.
            $('#easytransac_is_oneclick').val('yes');
            $('#easytransac_oneclick_card').val($('#etalcadd001>option:selected').val());
            $(this).parents('form').submit();
        });
    }
});