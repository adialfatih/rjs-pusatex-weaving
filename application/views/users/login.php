<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="google" content="notranslate">
    
	<link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Samatex Data">
    <meta name="apple-mobile-web-app-title" content="Samatex Data">
    <meta name="theme-color" content="#246cd1">
    <meta name="msapplication-navbutton-color" content="#246cd1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="https://sm.rindangjati.com/users">
    <link rel="apple-touch-icon" sizes="180x180" href="https://sm.rindangjati.com/logo/logo.png"/>
		<link rel="icon" type="image/png" sizes="32x32" href="https://sm.rindangjati.com/logo/logo.png"/>
		<link rel="icon" type="image/png" sizes="16x16" href="https://sm.rindangjati.com/logo/logo.png"/>

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    
    <title>Login Sesi Operator</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body>
    <div class="full-device">
        <div class="rows center" id="judul">
            <h2>PT. Salam Mandiri Textile</h2>
        </div>
        <div class="kotakan">
            <div class="input-kotak" id="ig">
                INSPECT GREY
            </div>
            <div class="input-kotak" id="fg">
                FOLDING GREY
            </div>
        </div>
        <div class="kotakan">
            <div class="input-kotak" id="iff">
                INSPECT FINISH
            </div>
            <div class="input-kotak" id="ff">
                FOLDING FINISH
            </div>
        </div>
        <div class="kotakan">
            <div class="input-kotak" id="pjj">
                PENJUALAN
            </div>
            <div class="input-kotak" id="pst">
                KIRIMAN BARANG
            </div>
        </div>
        <div class="rows center">
            <div class="ipt-user">
                <label for="iduser">Masukan Nama Anda</label>
                <input type="text" name="iduser" class="iduser" id="iduser" placeholder="........" autofocus>
            </div>
        </div>
        <input type="hidden" name="proses" id="proses" value="0">
        <div class="rows center">
            <button class="btn-login" id="btn-submit">Submit</button>
        </div>
    </div>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var button1 = document.getElementById("ig");
        var button2 = document.getElementById("fg");
        var button3 = document.getElementById("iff");
        var button4 = document.getElementById("ff");
        var button5 = document.getElementById("pjj");
        var button6 = document.getElementById("pst");
        var submit = document.getElementById("btn-submit");
  
        button1.addEventListener("click", function() {
            button1.classList.add("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button4.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'insgrey';
        });
        button2.addEventListener("click", function() {
            button2.classList.add("active");
            button1.classList.remove("active");
            button3.classList.remove("active");
            button4.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'folgrey';
        });
        button3.addEventListener("click", function() {
            button3.classList.add("active");
            button2.classList.remove("active");
            button1.classList.remove("active");
            button4.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'insfinish';
        });
        button4.addEventListener("click", function() {
            button4.classList.add("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button1.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'folfinish';
        });
        button5.addEventListener("click", function() {
            button4.classList.remove("active");
            button6.classList.remove("active");
            button5.classList.add("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button1.classList.remove("active");
            document.getElementById("proses").value = 'penjualan';
        });
        button6.addEventListener("click", function() {
            button4.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.add("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button1.classList.remove("active");
            document.getElementById("proses").value = 'kirimpst';
        });
        submit.addEventListener("click", function() {
            //peringatan('oke bos jadi');
            var proses = document.getElementById("proses").value;
            var namaUser = document.getElementById("iduser").value;
            if(proses==0){
                peringatan('Anda belum memilih proses produksi.!')
            }
            if(namaUser==""){
                peringatan('Masukan nama anda ...!!')
            }
            if(proses!=0 && namaUser!=""){
                loading('Loading...');
                $.ajax({
                    url:"<?=base_url();?>users/cekopt",
                    type: "POST",
                    data: {"proses" : proses, "namaUser" : namaUser},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                if(dataResult.psn == "oke"){
                                    sessionStorage.setItem("userName", namaUser);
                                    setTimeout(() => {
                                        window.location.href = "<?=base_url('users/');?>"+proses+"";
                                    }, "1300");
                                } else {
                                    if(proses == "insgrey"){ peringatan('Anda tidak boleh memproses Inspect Grey'); }
                                    if(proses == "insfinish"){ peringatan('Anda tidak boleh memproses Inspect Finish'); }
                                    if(proses == "folgrey"){ peringatan('Anda tidak boleh memproses Folding Grey'); }
                                    if(proses == "folfinish"){ peringatan('Anda tidak boleh memproses Folding Finish'); }
                                    if(proses == "penjualan"){ peringatan('Anda tidak boleh memproses Packing Penjualan'); }
                                    if(proses == "kirimpst"){ peringatan('Anda tidak boleh memproses kiriman ke pusatex'); }
                                }
                            } else {
                                peringatan('Username tidak ditemukan');
                            }
                        }
                });
            }
        });
            function peringatan(txt) {
                Toastify({
                    text: ""+txt+"",
                    duration: 4000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#cc214e",
                }).showToast();
            }
            function suksestoast(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 3000,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
            function loading(txt){
                Toastify({
                    text: ""+txt+"",
                    duration: 1300,
                    close:true,
                    gravity:"top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            }
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register("serviceworker.js");
            }
    </script>
</body>
</html>