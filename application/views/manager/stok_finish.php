<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Finish Siap Jual</title>
    <link rel="stylesheet" href="<?=base_url();?>new_db/style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .autoComplete_wrapper input{
            width: 110%;
            transform: translateX(-10%);
        }
        .lds-ring {
        display: inline-block;
        position: relative;
        width: 20px;
        height: 20px;
        }
        .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 24px;
        height: 24px;
        margin: 8px;
        border: 8px solid #ccc;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #ccc transparent transparent transparent;
        }
        .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
        }
        .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
        }
        .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
        }
        @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
        }
        .card-kons {
            width: 100px;
            background: #FFFFFF;
            margin: 0 10px 10px 0;
            box-shadow: 2px 5px 10px #c2defc;
            display: flex;
            flex-direction: column;
            border-radius: 7px;
            border: 1px solid #edeeed;
            overflow: hidden;
        }
        .card-kons div {
            width: 100%;
            text-align: center;
        }
        .card-kons div:nth-child(1){
            background: #096cd6;
            color:#FFFFFF;
            padding: 5px 0;
            font-size:12px;
        }
        .card-kons div:nth-child(2){
            font-family: 'Noto Serif Vithkuqi', serif;
            font-size: 16px;
            padding: 10px 0;
        }
/* Style for the modal background */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
    z-index: 1; /* Sit on top */
    animation: fadeIn 0.3s ease-in-out;
    padding:10px;
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px 10px;
    border: 1px solid #888;
    border-radius: 10px;
    width: 100%; /* Could be more or less, depending on screen size */
    max-width: 500px;
    animation: slideIn 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* The Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Keyframes for fadeIn animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Keyframes for slideIn animation */
@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
/* HTML: <div class="loader"></div> */
.loader {
  width: 50px;
  aspect-ratio: 1;
  border-radius: 50%;
  background: 
    radial-gradient(farthest-side,#ffa516 94%,#0000) top/8px 8px no-repeat,
    conic-gradient(#0000 30%,#ffa516);
  -webkit-mask: radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);
  animation: l13 1s infinite linear;
}
@keyframes l13{ 
  100%{transform: rotate(1turn)}
}
div.tables {
    width: 100%;
    overflow-x: auto;
}
.tables table {
    width: 100%;
    border:1px solid #292a2b;
    border-collapse: collapse;
}
.tables table tr th, .tables table tr td {
    padding:6px;
    white-space: nowrap;
}
.tables table tr th{
    text-align:center;
    background:#5d5f61;
    color:#fff;
    border:1px solid #fff;
}
.autopop {
    width: 100%;
    min-height: 50px;
    background: #1955b5;
    color: #FFFFFF;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 9999;
    display: flex;
    text-align: center;
    padding:20px;
    position: fixed;
    display: none;
}
.closePop {
    width: 30px;
    height: 30px;
    background:rgb(231, 9, 9);
    color: #FFFFFF;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 10px;
    right: 10px;
    display: none;
    position: fixed;
    z-index: 99999;
}
    </style>
</head>
<body>
<input type="hidden" id="tampungan" value="0">
<textarea id="summaryText" rows="5" style="position:absolute; left:-9999px; top:0; visibility:hidden;" readonly ></textarea>
    <div class="closePop" id="klikMerah" onclick="closeModal()">x</div>
    <div class="autopop" id="modalBiru" onclick="copyToClipboard()">
        
    </div>
    <div class="topbar">
       Stok Kain Finish
    </div>
    
        <div class="konten-mobile2" style="margin-top:20px;" id="kontenStok">
            Loading...
        </div>
           
    
     <!-- The Modal -->
     <div id="myModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content" id="isiModals">
          <div class="loader"></div>
          Please Wait...
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        
        
        function loadData(){
            $('#owekloading').css({"display": "flex", "width": "100%", "justify-content": "center"});
            $.ajax({
                url:"<?=base_url();?>alldashboard/loaddatastok",
                type: "POST",
                data: {"jns" : "Finish"},
                cache: false,
                success: function(dataResult){
                    $('#kontenStok').html(dataResult);
                    $('#owekloading').hide();
                    //console.log('s'+tgl);
                }
            });
        }
        loadData();
        // Get the modal
var modal = document.getElementById("myModal");
var modalBiru = document.getElementById("modalBiru");
var klikMerah = document.getElementById("klikMerah");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];
function openModal(kons,jns,chto) { 
    $('#isiModals').html('<div class="loader"></div>Please Wait...');
    $.ajax({
        url:"<?=base_url();?>alldashboard/showDroll",
        type: "POST",
        data: {"jns" : jns, "kons" : kons, "chto":chto},
        cache: false,
        success: function(dataResult){
            setTimeout(() => {
                $('#isiModals').html(dataResult);
            }, 1400);
        }
    });
    modal.style.display = "block"; 
    modalBiru.style.display = "block"; 
    klikMerah.style.display = "flex"; 
}
function closeModal() {
    modal.style.display = "none";
    modalBiru.style.display = "none"; 
    klikMerah.style.display = "none"; 
    document.getElementById('tampungan').value = '0';
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        modalBiru.style.display = "none"; 
        klikMerah.style.display = "none"; 
        document.getElementById('tampungan').value = '0';
    }
}
function tesh(kons){
    $('#isiModals').html('<div class="loader"></div>Please Wait...');
    $.ajax({
        url:"<?=base_url();?>alldashboard/showPaket",
        type: "POST",
        data: {"kons" : kons},
        cache: false,
        success: function(dataResult){
            setTimeout(() => {
                $('#isiModals').html(dataResult);
            }, 1400);
        }
    });
    modal.style.display = "block"; 
    console.log('oke '+kons);
}
function kodeRoll(kodeRoll) {
            const tampungan = document.getElementById('tampungan');
            let currentValue = tampungan.value;
            let values = currentValue === "0" ? [] : currentValue.split(',');
            if (values.includes(kodeRoll)) {
                values = values.filter(value => value !== kodeRoll);
            } else {
                values.push(kodeRoll);
            }
            tampungan.value = values.length === 0 ? "0" : values.join(',');
            const tampungan2 = document.getElementById('tampungan').value;
            $('#modalBiru').html('Loading...');
            $.ajax({
                url:"<?=base_url();?>alldashboard/hitungPaket",
                type: "POST",
                data: {"tampungan2" : tampungan2},
                cache: false,
                success: function(dataResult){
                    $('#modalBiru').html(dataResult);
                    //console.log('Hasil : '+dataResult);
                }
            });
        }
        function copyToClipboard() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            let totalUkuran = 0;
            let totalRoll = 0;
            let output = '';
        
            checkboxes.forEach(cb => {
                const kode = cb.getAttribute('data-kode');
                const ukuran = parseFloat(cb.getAttribute('data-ukuran')) || 0;
                output += `Kode ${kode} ukuran ${ukuran},\n`;
                totalUkuran += ukuran;
                totalRoll++;
            });
        
            output += `Total Roll ${totalRoll}\nTotal Ukuran ${totalUkuran}`;
        
            // Gunakan Clipboard API
            navigator.clipboard.writeText(output).then(function() {
               
                Swal.fire({
                  icon: "success",
                  title: "Copy Success",
                  text: "Teks copy to clipboard"
                });
            }).catch(function(err) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Gagal menyalin teks: " + err+ ""
                });
            });
        }
        function updateSummary() {
            
        }
        window.onload = function() {
            // Kosongkan tampungan saat reload
            const tampungan = document.getElementById('tampungan');
            if (tampungan) {
                tampungan.value = '0';
            }
        
            // Uncheck semua checkbox
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
        }
    </script>
</body>
</html>