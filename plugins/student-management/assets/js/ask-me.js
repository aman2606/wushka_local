const ask = {
  confirm: {
    render: function (options) {
      options = Object.assign(
        {
          heading: "",
          message: "Are you sure to perform this action ?",
          okText: "Ok",
          fontSize: "18px",
          cancelText: "Cancel",
          onCancel: function () {},
          onConfirm: function () {},
        },
        options
      );

      //console.log(options);

      const confirmBox = `<div class="boot_wsm modal fade" id="ask-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="scp-label" aria-hidden="true">
            <div class="modal-dialog" style='margin-top:10%'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scp-label">${options.heading}</h3>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:${options.fontSize}">${options.message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" id='ask-confirm-modal__cancelled'>${options.cancelText}
                        </button>
                        <button class="btn btn-primary" id='ask-confirm-modal__confirmed'  type="button">${options.okText}
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

      if (jQuery("#ask-confirm-modal").length > 0) {
        jQuery("#ask-confirm-modal").remove();
      }

      jQuery("body").prepend(confirmBox);

      var askConfirmModal = jQuery("#ask-confirm-modal");

      askConfirmModal.modal("show");

      jQuery("#ask-confirm-modal__cancelled").on("click", function () {
        options.onCancel();
        askConfirmModal.modal("hide");
      });

      jQuery("#ask-confirm-modal__confirmed").on("click", function () {
        options.onConfirm();
        askConfirmModal.modal("hide");
      });
    },
  },
  flash: {
    render: function (options) {
      options = Object.assign(
        {
          heading: "",
          message: "Action done Successfully !",
          infoClass: "alert alert-info",
          afterElement: "body",
        },
        options
      );

      const infoBox = `<div id="confirm-flash-box" class="alert alert-${options.infoClass}">${options.message}</div>`;

      if (
        typeof options.afterElement !== "undefined" &&
        options.afterElement != ""
      ) {
        if (jQuery("#confirm-flash-box").length > 0) {
          jQuery("#confirm-flash-box").remove();
        }

        jQuery(infoBox).insertAfter(options.afterElement);

        setTimeout(function () {
          jQuery("#confirm-flash-box").remove();
        }, 4000);
      }
    },
  },
};
