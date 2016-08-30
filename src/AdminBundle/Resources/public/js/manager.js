/**
 * Created by francois on 30/08/16.
 */
/**
 * Created by francois on 25/08/16.
 */
var Manager = {
    init: function () {
        $(".btn.btn-danger").click(function () {
            var button = $(this);
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: 'Supprimer'
                    },
                    cancel: {
                        label: 'Annuler'
                    }
                },
                message: 'Cette action est irréversible.',
                callback: function (result) {
                    if (result) {
                        Manager.removeCategory(button.data('id'), button.data('type'));
                    }
                },
                title: "Voulez-vous supprimer cet élément ?"
            });
        })
    },

    removeCategory: function (id, type) {
        $.ajax({
            url: Routing.generate('admin_delete'),
            type: 'POST',
            data: {id: id, type: type},
            success: function (result) {
                window.location.reload();
            },
            error: function (result) {
                if (result.status == 401) {
                    window.location.href = Routing.generate('fos_user_security_login');
                }
            }
        });
    }
};

$(document).ready(function () {
    Manager.init();
});