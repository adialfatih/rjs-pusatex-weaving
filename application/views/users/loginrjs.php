<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    
	<link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="RJS Inspect">
    <meta name="apple-mobile-web-app-title" content="RJS Inspect">
    <meta name="theme-color" content="#246cd1">
    <meta name="msapplication-navbutton-color" content="#246cd1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="https://inspect.rdgjt.com/usersrjs">
    <link rel="apple-touch-icon" sizes="180x180" href="https://sm.rdgjt.com/logo/logo.png"/>
	<link rel="icon" type="image/png" sizes="32x32" href="https://sm.rdgjt.com/logo/logo.png"/>
	<link rel="icon" type="image/png" sizes="16x16" href="https://sm.rdgjt.com/logo/logo.png"/>
		
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sesi Operator</title>
    <link rel="stylesheet" href="<?=base_url('new_db/');?>style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .absBottom{
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body style="background-color: #ffffff;">
    <div class="full-device">
        <div class="rows center" id="judul">
            <h2>PT. Rindang Jati Weaving</h2>
        </div>
        <div class="kotakan">
            <div class="input-kotak" id="ig">
                INSPECT GREY
            </div>
            <div class="input-kotak" id="pkg">
                PKGLIST
            </div>
        </div>
        <div class="kotakan">
            <div class="input-kotak" id="if">
                INSPECT FINISH
            </div>
            <div class="input-kotak" id="fol">
                FOLDING
            </div>
        </div>
        <div class="kotakan">
            <div class="input-kotak" id="jual">
                PENJUALAN
            </div>
            <div class="input-kotak" id="dtbs">
                DATA BS
            </div>
        </div>
        
        <div class="rows center" style="border:none;">
            <div class="ipt-user">
                <label for="iduser">Masukan Nama Anda</label>
                <input type="text" name="iduser" class="iduser" id="iduser" placeholder="........" autofocus>
            </div>
        </div>
        <div class="rows center" style="margin-top:-20px;border:none;">
            <div class="ipt-user">
                <label for="pinuser">Masukan PIN Anda</label>
                <input type="password" name="pinuser" class="iduser" id="pinuser" placeholder="........" inputmode="numeric">
            </div>
        </div>
        <input type="hidden" name="proses" id="proses" value="0">
        <div class="rows center">
            <button class="btn-login" id="btn-submit">Submit</button>
        </div>
        <span class="absBottom">&copy; 2025 PT. Rindang Jati Weaving</span>
    </div>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var button1 = document.getElementById("ig");
        var button2 = document.getElementById("pkg");
        var button3 = document.getElementById("if");
        var button4 = document.getElementById("fol");
        var button5 = document.getElementById("jual");
        var button6 = document.getElementById("dtbs");
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
            document.getElementById("proses").value = 'kirimpst';
        });
        button3.addEventListener("click", function() {
            button3.classList.add("active");
            button1.classList.remove("active");
            button2.classList.remove("active");
            button4.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'insfinish';
        });
        button4.addEventListener("click", function() {
            button4.classList.add("active");
            button1.classList.remove("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button5.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'folding';
        });
        button5.addEventListener("click", function() {
            button5.classList.add("active");
            button1.classList.remove("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button4.classList.remove("active");
            button6.classList.remove("active");
            document.getElementById("proses").value = 'penjualan';
        });
        button6.addEventListener("click", function() {
            button6.classList.add("active");
            button1.classList.remove("active");
            button2.classList.remove("active");
            button3.classList.remove("active");
            button4.classList.remove("active");
            button5.classList.remove("active");
            document.getElementById("proses").value = 'databs';
        });
        
        submit.addEventListener("click", function() {
            //peringatan('oke bos jadi');
            var proses = document.getElementById("proses").value;
            var namaUser = document.getElementById("iduser").value;
            var pinUser = document.getElementById("pinuser").value;
            if(proses==0){
                peringatan('Anda belum memilih proses produksi.!')
            }
            if(namaUser==""){
                peringatan('Masukan nama anda ...!!')
            }
            if(pinUser==""){
                peringatan('Masukan PIN anda ...!!')
            }
            if(proses!=0 && namaUser!=""){
                loading('Loading...');
                $.ajax({
                    url:"<?=base_url();?>usersrjs/cekopt",
                    type: "POST",
                    data: {"proses" : proses, "namaUser" : namaUser, "pinUser" : pinUser},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                sessionStorage.setItem("userName", namaUser);
                                setTimeout(() => {
                                    window.location.href = "<?=base_url('usersrjs/');?>"+proses+"";
                                }, "1000");
                            } else {
                                peringatan('Username & PIN tidak cocok');
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