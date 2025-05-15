<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Kain di Pusatex</title>
    <link rel="stylesheet" href="<?=base_url();?>new_db/style.css?version=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
.tables table tr td{
    text-align:center;
    border:1px solid #000;
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
    <?php $ur = $this->uri->segment(2); ?>
    <div class="topbar">
       Stok Kain di Pusatex <?=ucfirst($ur);?>
    </div>
    
    <div class="konten-mobile2">
        <div class="kotaknewpkg">
            <span>Pengiriman kain</span>
            <div style="width: 100%;display: flex;flex-direction: column;">
                <div class="form-label">
                    <label for="jnsid">Tanggal</label>
                    <input type="date" class="ipt" name="dates" id="jnsid" value="<?=date('Y-m-d');?>">
                </div>
                
                
                <div style="display:none;" id="owekloading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="fortable has2">
                    <table id="tableStok">
                        <tr>
                            <td colspan="2">Loading...</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="konten-mobile2">
        <div class="kotaknewpkg">
            <span>Stok <?=ucfirst($ur);?> di Pusatex</span>
            <div style="width: 100%;display: flex;flex-direction: column;">
                <div class="konten-mobile2" style="margin-top:20px;" id="kontenStok">
                    Loading...
                </div>
            </div>
        </div>
    </div>
    
    <div class="closePop" id="klikMerah" onclick="closeModal()">x</div>
    <div class="autopop" id="modalBiru">
        
    </div>
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
        
        var jns = $('#jnsid').val();
        //var kons = $('#autoComplete').val();
        //console.log('tgl :'+tgl);
        //console.log('kons :'+kons);
        function loadData(){
            $('#owekloading').css({"display": "flex", "width": "100%", "justify-content": "center"});
            $.ajax({
                url:"<?=base_url();?>alldashboard/loaddatastokonpusatex",
                type: "POST",
                data: {"jns" : "jns", "tipe": "<?=ucfirst($ur);?>"},
                cache: false,
                success: function(dataResult){
                    $('#kontenStok').html(dataResult);
                    $('#owekloading').hide();
                    //console.log('s'+tgl);
                }
            });
        }

        loadData();
        $( "#jnsid" ).on( "change", function() { 
            var jns = $('#jnsid').val();
            loadDataPengiriman(jns);
        });

        function loadDataPengiriman(jns){
            
            $('#owekloading').css({"display": "flex", "width": "100%", "justify-content": "center"});
            $.ajax({
                url:"<?=base_url();?>alldashboard/loadkirimanbarang",
                type: "POST",
                data: {"jns" : jns},
                cache: false,
                success: function(dataResult){
                    console.log('Open this Date : '+jns);
                    $('#tableStok').html(dataResult);
                    $('#owekloading').hide();
                    //console.log('s'+tgl);
                }
            });
        }
        
        loadDataPengiriman(jns);
        var modal = document.getElementById("myModal");
        var modalBiru = document.getElementById("modalBiru");
        var klikMerah = document.getElementById("klikMerah");
        var btn = document.getElementById("openModalBtn");
        var span = document.getElementsByClassName("close")[0];
        function openModal(kons, proses) { 
            console.log('open modal 1');
            $('#isiModals').html('<div class="loader"></div>Please Wait...');
            modal.style.display = "block"; 
            modalBiru.style.display = "block"; 
            klikMerah.style.display = "flex"; 
            if(proses == "num"){
                var urlTo = "<?=base_url();?>alldashboard/lihatRollSb";
            } else {
                var urlTo = "<?=base_url();?>fake/lihatRollSb2";
            }
            $.ajax({
                url: urlTo,
                type: "POST",
                data: {"kons" : kons, "proses" : proses},
                cache: false,
                success: function(dataResult){
                    setTimeout(() => {
                        $('#isiModals').html(dataResult);
                    }, 1400);
                }
            });
            
        }
        function openModal2(kons, proses) { 
            $('#isiModals').html('<div class="loader"></div>Please Wait...');
            modal.style.display = "block"; 
            modalBiru.style.display = "block"; 
            console.log('open modal 2');
            klikMerah.style.display = "flex"; 
                var urlTo = "<?=base_url();?>alldashboard/lihatRollSb";
            $.ajax({
                url: urlTo,
                type: "POST",
                data: {"kons" : kons, "proses" : proses},
                cache: false,
                success: function(dataResult){
                    setTimeout(() => {
                        $('#isiModals').html(dataResult);
                    }, 1400);
                }
            });
            
        }
        function closeModal() {
            modal.style.display = "none";
            modalBiru.style.display = "none"; 
            klikMerah.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                modalBiru.style.display = "none"; 
                klikMerah.style.display = "none";
            }
        }
    </script>
</body>
</html>