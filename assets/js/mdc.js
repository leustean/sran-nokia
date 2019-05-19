import {MDCSelect} from '@material/select';

[].map.call(document.querySelectorAll('.mdc-select'), function(el) {
    return new MDCSelect(el);
});

import {MDCTextField} from '@material/textfield';

[].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
    return new MDCTextField(el);
});