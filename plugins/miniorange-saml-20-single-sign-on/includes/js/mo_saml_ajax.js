jQuery(document).ready(function($) {

    function syncLicense() {
        $.ajax({
            url: moSamlAjax.ajax_url,
            type: "POST",
            data: {
                action: "mo_saml_sync_license_on_expiry",
                nonce: moSamlAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    document.getElementById("mo-saml-license-sync-loader").style.display = "none";
                    if(response.data.remaining_days !== 'undefined' && response.data.remaining_days > 0) {
                        hideNoticeAndUpdateExpiryDate(response.data.expiry_date);
                    }
                }
            }
        });
    }

    if ( parseInt(moSamlAjax.remaining_days) < 0 && moSamlAjax.current_tab === 'account_info' ) {
        document.getElementById("mo-saml-license-sync-loader").style.display = "block";
        syncLicense();
    }
});

function hideNoticeAndUpdateExpiryDate(expiry_date) {
    document.getElementById("mo_saml_license_expiry").textContent = expiry_date;
    document.getElementById("mo_saml_license_expiry_notice").style.display = "none";
    document.getElementById("mo_saml_profile_box_expiry_notice").style.display = "none";
}