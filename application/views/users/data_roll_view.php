<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    
	<link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Data Roll RJS">
    <meta name="apple-mobile-web-app-title" content="Data Roll RJS">
    <meta name="theme-color" content="#246cd1">
    <meta name="msapplication-navbutton-color" content="#246cd1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="https://weaving.rdgjt.com/data-roll">
    <link rel="apple-touch-icon" sizes="180x180" href="https://dataroll.rdgjt.com/logo.png"/>
		<link rel="icon" type="image/png" sizes="32x32" href="https://dataroll.rdgjt.com/logo.png"/>
		<link rel="icon" type="image/png" sizes="16x16" href="https://dataroll.rdgjt.com/logo.png"/>

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    
    <title>DATA ROLL</title>
    <style>
        .form {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding: 0 15px;
        }
        .form input {
            width: 90%;
            background: #fff;
            outline: none;
            border: none;
            border-radius: 4px;
            padding: 0 10px;
            font-size: 14px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }
        .button {
            position: relative;
            border: none;
            background-color: white;
            color: #212121;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            gap: 5px;
            border-radius: 5px;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            overflow: hidden;
            }

            .button span {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            }

            .button::before {
            content: "";
            position: absolute;
            background-color: palevioletred;
            width: 100%;
            height: 100%;
            left: 0%;
            bottom: 0%;
            transform: translate(-100%, 100%);
            border-radius: inherit;
            }

            .button svg {
            fill: palevioletred;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
            }

            .button:hover::before {
            animation: shakeBack 0.6s forwards;
            }

            .button:hover svg {
            fill: white;
            scale: 1.3;
            }

            .button:active {
            box-shadow: none;
            }

            @keyframes shakeBack {
            0% {
                transform: translate(-100%, 100%);
            }

            50% {
                transform: translate(20%, -20%);
            }

            100% {
                transform: translate(0%, 0%);
            }
            }
            .cs {
                font-size: 18px;
                margin-top: 25px;
                margin-bottom: -15px;
                margin-left: 15px;
            }
            .hasil {
                width: 100%;
                min-height: calc(100vh - 150px);
                display: flex;
                justify-content: space-between;
                margin-top: 30px;
            }
            .hasil img {
                width: 70%;
                margin: auto;
            }
            .has {
                width: 100%;
                display: flex;
                position: relative;
            }
            .has span.left {
                width: 2px;
                height: 100%;
                background: #212121;
                position: absolute;
                left: 50%;
            }
            /* The actual timeline (the vertical ruler) */
.main-timeline {
  position: relative;
}

/* The actual timeline (the vertical ruler) */
.main-timeline::after {
  content: "";
  position: absolute;
  width: 6px;
  background-color: #939597;
  top: 0;
  bottom: 0;
  left: 50%;
  margin-left: -3px;
}

/* Container around content */
.timeline {
  position: relative;
  background-color: inherit;
  width: 50%;
}

/* The circles on the timeline */
.timeline::after {
  content: "";
  position: absolute;
  width: 25px;
  height: 25px;
  right: -13px;
  background-color: #939597;
  border: 5px solid #f5df4d;
  top: 15px;
  border-radius: 50%;
  z-index: 1;
}

/* Place the container to the left */
.left {
  padding: 0px 40px 20px 0px;
  left: 0;
}

/* Place the container to the right */
.right {
  padding: 0px 0px 20px 40px;
  left: 50%;
}

/* Add arrows to the left container (pointing right) */
.left::before {
  content: " ";
  position: absolute;
  top: 18px;
  z-index: 1;
  right: 30px;
  border: medium solid white;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent white;
}

/* Add arrows to the right container (pointing left) */
.right::before {
  content: " ";
  position: absolute;
  top: 18px;
  z-index: 1;
  left: 30px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
}

/* Fix the circle for containers on the right side */
.right::after {
  left: -12px;
}

/* Media queries - Responsive timeline on screens less than 600px wide */
.kops {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: -25px;
    font-size: 20px;
    font-weight: bold;
}
.kops2 {
    width: 100%;
    display: flex;
    justify-content: center;
    font-size: 14px;
    margin-top: 20px;
    margin-bottom: -25px;
}
.card23 {
  width: 100%;
  padding: 1rem;
  text-align: center;
  border-radius: .8rem;
  background-color: white;
}

.card__skeleton {
  background-image: linear-gradient(
		90deg,
		#ccc 0px,
		rgb(229 229 229 / 90%) 40px,
		#ccc 80px
	);
  background-size: 300%;
  background-position: 100% 0;
  border-radius: inherit;
  animation: shimmer 1.5s infinite;
}

.card__title {
  height: 15px;
  margin-bottom: 15px;
}

.card__description {
  height: 100px;
}

@keyframes shimmer {
  to {
    background-position: -100% 0;
  }
}
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body>
    <div style="width:100%;display:flex;justify-content:space-between;padding-right:15px;">
        <h1 class="cs">DATA ROLL</h1>
        <h1 class="cs" onclick="returnCari()">Cari Kode</h1>
    </div>
    
    <div class="form">
        <input type="text" id="kodesp" placeholder="Masukan Kode Roll">
        <button class="button" onclick="carikode()">
            <span>
              <svg viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M9.145 18.29c-5.042 0-9.145-4.102-9.145-9.145s4.103-9.145 9.145-9.145 9.145 4.103 9.145 9.145-4.102 9.145-9.145 9.145zm0-15.167c-3.321 0-6.022 2.702-6.022 6.022s2.702 6.022 6.022 6.022 6.023-2.702 6.023-6.022-2.702-6.022-6.023-6.022zm9.263 12.443c-.817 1.176-1.852 2.188-3.046 2.981l5.452 5.453 3.014-3.013-5.42-5.421z"></path></svg>
            </span>
        </button>
    </div>
    
    <div class="hasil" id="hasil">
        <img src="tes.svg" alt="Hasil Pencarian Kode Roll">
        <!-- <div class="card23">
            <div class="card__skeleton card__title"></div>
            <div class="card__skeleton card__description"></div>
        </div> -->
        <!-- <section style="background-color: #d7e1f5; width: 100%;">
            <span class="kops">RX9018</span>
            <div class="container py-5">
              <div class="main-timeline">
                <div class="timeline left">
                  <div class="card">
                    <div class="card-body" style="padding: 15px;">
                      <h3 style="font-size: 12px;">Inspect Grey</h3>
                      <p class="mb-0" style="font-size: 11px;">
                        Konstruksi : <strong>SM03A</strong><br>
                        Ukuran : <strong>420 Meter</strong><br>
                        No. Mesin : <strong>A22</strong><br>
                        No Beam : <strong>RJS 20</strong><br>
                        OKA : <strong>1198</strong><br>
                        Tanggal : <strong>21-Sep-2023</strong><br>
                        Operator : <strong>Farda</strong><br>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="timeline right">
                  <div class="card">
                    <div class="card-body">
                      <h3 style="font-size: 12px;">Folding Grey</h3>
                      <p class="mb-0" style="width:100%;font-size: 11px;">
                        Ukuran : <strong>420 Meter</strong><br>
                        Tanggal : <strong>21-Sep-2023</strong><br>
                        Operator : <strong>Dakom</strong><br>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="timeline left">
                    <div class="card">
                      <div class="card-body" style="padding: 15px;">
                        <h3 style="font-size: 12px;">Penjualan</h3>
                        <p class="mb-0" style="font-size: 11px;">
                          SJ : <strong>1923/X/1/2023</strong><br>
                          Tanggal : <strong>21-Sep-2023</strong><br>
                          Customer : <strong>KM Hiva</strong><br>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="timeline right">
                    <div class="card">
                      <div class="card-body">
                        <h3 style="font-size: 12px;">Folding Grey</h3>
                        <p class="mb-0" style="width:100%;font-size: 11px;">
                          Ukuran : <strong>420 Meter</strong><br>
                          Tanggal : <strong>21-Sep-2023</strong><br>
                          Operator : <strong>Dakom</strong><br>
                        </p>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </section> -->
    </div>
    <!-- MDB -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    function carikode(){
        var kode = $('#kodesp').val();
        if(kode == ""){
            peringatan('Anda belum mengisi kode roll');
        } else {
            $('#hasil').html('<div class="card23"><div class="card__skeleton card__title"></div><div class="card__skeleton card__description"></div></div>');
            $.ajax({
                url:"proses.php",
                type: "POST",
                data: {"kode" : kode},
                cache: false,
                success: function(dataResult){
                    $('#hasil').html(dataResult);
                }
            });
        }
    }
    function peringatan(txt) {
        Toastify({
            text: ""+txt+"",
            duration: 4000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#cc214e",
        }).showToast();
    }
    function returnCari(){
        $('#hasil').html('<div class="card23"><div class="card__skeleton card__title"></div><div class="card__skeleton card__description"></div></div>');
        setTimeout(function() { 
            document.location.href= 'https://dataroll.rdgjt.com/carikode.html';
        }, 2000);
    }
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register("serviceworker.js");
    }
</script>
</body>
</html>