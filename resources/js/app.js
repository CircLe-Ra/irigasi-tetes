import { animate, inView } from "motion";
import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

window.animate = animate;
window.inView = inView;
window.FilePond = FilePond;
window.FilePondPluginFileValidateType = FilePondPluginFileValidateType;
window.FilePondPluginFileValidateSize = FilePondPluginFileValidateSize;
window.FilePondPluginImagePreview = FilePondPluginImagePreview;


window.formatNumber = (value) => {
    const numericValue = value.replace(/\D/g, '');
    return parseInt(numericValue || '0', 10);
}

window.formatPrice = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(value);
}
