/**
 * Плагины, написанные как оболочка на существуещие плагины,
 * для удобства использования со своими переменными.
 */
// Wrap class Noty plugin
// ------------------------------
class Alert {

    constructor(header, text, type, timeout, confirm_text_success, confirm_text_error) {
        // Static
        this.seconds = 5000;
        this.layout = 'topRight';

        if (Number.isInteger(header)) {
            this.timeout = header;
            this.header = '';
        } else {
            this.header = (header === undefined || header === null)
                ? '' : '<div class="noty-header">' + header + '</div>';
            this.timeout = (timeout === undefined) ? this.seconds : timeout;

        }

        //Dinamic
        this.text = (text === undefined) ? 'Текст оповещения' : text;
        this.type = (type === undefined) ? 'success' : type;
        this.confirm_text_success = (confirm_text_success === undefined) ? 'Вы нажали "ОК"' : confirm_text_success;
        this.confirm_text_error = (confirm_text_error === undefined) ? 'Вы нажали "Отмена"' : confirm_text_error;
    }

    get() {
        noty({
            width: 200,
            text: this.header + this.text,
            type: this.type,
            dismissQueue: true,
            timeout: this.timeout,
            layout: this.layout,
            buttons: (this.type != 'confirm') ? false : [
                {
                    addClass: 'btn btn-primary btn-xs',
                    text: 'Ok',
                    onClick: ($noty) => { //this = button element, $noty = $noty element
                        $noty.close();
                        noty({
                            force: true,
                            text: this.confirm_text_success,
                            type: 'success',
                            layout: this.layout
                        });
                    }
                },
                {
                    addClass: 'btn btn-danger btn-xs',
                    text: 'Отмена',
                    onClick: ($noty) => {
                        $noty.close();
                        noty({
                            force: true,
                            text: this.confirm_text_error,
                            type: 'error',
                            layout: this.layout
                        });
                    }
                }
            ]
        });
    }
}//Class Alert

var a = {
    error: function (text, header, timeout) {
        new Alert(header, text, 'error', timeout).get();
    },
    success: function (text, header, timeout) {
        new Alert(header, text, 'success', timeout).get();
    },
    warning: function (text, header, timeout) {
        new Alert(header, text, 'warning', timeout).get();
    },
    info: function (text, header, timeout) {
        new Alert(header, text, 'info', timeout).get();
    },
    alert: function (text, header, timeout) {
        new Alert(header, text, 'alert', timeout).get();
    },
    confirm: function (header, text, type, timeout, confirm_text_success, confirm_text_error) {
        new Alert(header, text, 'confirm', timeout, confirm_text_success, confirm_text_error).get();
    }
};

// === END Noty ===========================================














































