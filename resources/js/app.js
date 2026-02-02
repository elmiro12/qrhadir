console.log('app.js executed at', document.readyState);

import './bootstrap';
import '../css/app.css';
import 'flowbite';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


//SweetAlert
import Swal from 'sweetalert2';
window.Swal = Swal;

//HTML5 QR-Code
//HTML5 QR-Code
import { Html5QrcodeScanner, Html5Qrcode } from "html5-qrcode";
window.Html5QrcodeScanner = Html5QrcodeScanner;
window.Html5Qrcode = Html5Qrcode;

// Simple Datatables
import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css";
window.DataTable = DataTable;

document.addEventListener("DOMContentLoaded", function () {
    const dataTables = document.querySelectorAll(".datatable, #default-table, #export-table");
    dataTables.forEach(table => {
        if (table) {
            new DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 20,
            });
        }
    });
});

