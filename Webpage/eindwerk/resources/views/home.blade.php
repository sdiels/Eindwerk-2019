<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Nike Webpage</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/105/three.js"></script>
    <script src="https://threejsfundamentals.org/threejs/resources/threejs/r105/js/controls/OrbitControls.js"></script>
    <script src="{{ URL::asset('js/GLTFLoader.js') }}"></script>
    <script src="{{ URL::asset('js/threex.domevents.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script>

</head>
<style>
    @font-face {
        font-family: 'futura';
        src: url('{{ URL::asset("fonts/futura.ttf") }}');
    }
    
    #bg {
        position: absolute;
        left: 0;
        top: 0;
        height: 100vh;
        width: 100vw;
        opacity: 1;
        background-color: #e5e5e5;
    }
    
    .loaded {
        animation-name: loaded;
        animation-duration: 3s;
        visibility: hidden;
    }
    
    body {
        margin: 0;
        height: 100vh;
        font-family: 'futura', sans-serif;
        color: black;
    }
    
    a {
        color: black;
        text-decoration: none;
    }
    
    a:hover {
        color: black;
        text-decoration: none;
    }
    
    canvas {
        display: block;
    }
    
    #overlay,
    #overlay-text {
        position: absolute;
        z-index: 100;
        width: 400px;
    }
    
    #overlay-text {
        top: 35vh;
    }
    
    #logo {
        width: 300px;
        display: none;
    }
    
    #logo.anim {
        display: block;
        overflow: hidden;
        animation-name: logo;
        animation-duration: 2s;
    }
    
    #logo img {
        width: 300px;
    }
    
    #release,
    #overlay-text h1,
    .mail-form {
        font-weight: bold;
        margin-left: 24%;
    }
    
    .mail-form {
        padding-top: 5px;
    }
    
    #overlay-text h2 {
        font-size: 25px;
        height: 30px;
        width: 300px;
        margin: 0;
    }
    
    #release {
        display: none;
    }
    
    #release.anim {
        display: block;
        overflow: hidden;
        animation-name: release;
        animation-duration: 2s;
    }
    
    #overlay-text h1 {
        display: none;
        font-size: 90px;
        line-height: 80px;
        width: 350px;
    }
    
    #overlay-text h1.anim {
        display: block;
        overflow: hidden;
        animation-name: notify;
        animation-duration: 2s;
    }
    
    #shadow {
        position: absolute;
        bottom: 5%;
        left: 35%;
        width: 30%;
        opacity: 0.3;
    }
    
    h3 {
        position: absolute;
        right: 0;
        top: 0;
    }
    
    #email {
        width: 110%;
        background-color: rgba(255, 255, 255, 0);
        border: none;
        padding: 2px;
        border-bottom: 1px solid;
    }
    /* Change the white to any color ;) */
    
    #email:-webkit-autofill,
    #email:-webkit-autofill:hover,
    #email:-webkit-autofill:focus,
    #email:-webkit-autofill:active,
    #email::-internal-autofill-selected {
        -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0) inset !important;
    }
    
    #email:focus {
        outline: none;
    }
    
    #submit {
        border: 2px black solid;
        color: white;
        background-color: black;
    }
    
    @keyframes logo {
        0% {
            width: 0%;
        }
        25% {
            width: 0%;
        }
        50% {
            width: 300px;
        }
    }
    
    @keyframes release {
        0% {
            width: 0px;
        }
        50% {
            width: 0px;
        }
        75% {
            width: 300px;
        }
    }
    
    @keyframes notify {
        0% {
            width: 0px;
        }
        75% {
            width: 0px;
        }
        100% {
            width: 350px;
        }
    }
    
    @keyframes loaded {
        0% {
            opacity: 1;
            visibility: visible;
        }
        100% {
            opacity: 0;
            visibility: visible;
        }
    }
    
    @media only screen and (max-width: 768px) {
        #shadow {
            bottom: 40%;
            left: 15%;
            width: 70%;
        }
    }
    
    @media only screen and (max-width: 1000px) {
        #shadow {
            bottom: 25%;
            left: 15%;
            width: 70%;
        }
        #overlay,
        #overlay-text {
            width: 50vw;
            text-align: center;
            margin: 5vh 25vw;
        }
        #overlay-text {
            width: 70vw;
            text-align: center;
            margin: 0 15vw;
            top: 80vh;
        }
        #release,
        #overlay-text h1,
        #overlay-text h2,
        .mail-form {
            margin-left: 0;
            margin: auto;
        }
        #logo {
            width: 50vw;
        }
        #logo img {
            width: 50vw;
        }
        @keyframes logo {
            0% {
                width: 0px;
            }
            25% {
                width: 0px;
            }
            50% {
                width: 50vw;
            }
        }
        #mail-form {
            padding-top: 2vh;
        }
        .emailcol,
        .submitcol {
            line-height: 50px;
            font-size: 30px;
            width: 100%;
        }
    }

</style>

<body>
    <img id="shadow" src="{{ URL::asset('img/shadow.png') }}" alt="shadow">
    <div id="bg">
    </div>
    <div id="overlay">
        <div id="logo">
            <img src="{{ URL::asset('img/logo.png') }}" alt="logo">
        </div>
    </div>
    <div id="overlay-text">
        <div id="release">
            <h2>RELEASING SOON</h2>
        </div>
        <a id="notify" data-toggle="collapse" href="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm">
            <h1>NOTIFY ME</h1>
        </a>
        <div class="collapse" id="collapseForm">
            <form class="mail-form" action="{{ route('storeMail') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-8 emailcol">
                        <input type="email" placeholder="Your Email" name="email" id="email" required>
                    </div>
                    <div class="col-sm-4 submitcol">
                        <input id="submit" type="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <h3 id="info"></h3>

    <script>
        //SET BASIC VARIABLES FOR VIEWPORT
        var scene = new THREE.Scene();
        var clock = new THREE.Clock();

        var camera = new THREE.PerspectiveCamera(20, window.innerWidth / window.innerHeight, 0.1, 1000)
        camera.position.z = 6;

        var renderer = new THREE.WebGLRenderer({
            antialias: true
        });
        renderer.setClearColor("#e5e5e5");
        renderer.toneMappingExposure = 1.7;
        renderer.setSize(window.innerWidth, window.innerHeight);

        document.body.appendChild(renderer.domElement);

        //VIEWPORT RESIZING
        window.addEventListener('resize', () => {
            renderer.setSize(window.innerWidth, window.innerHeight);
            camera.aspect = window.innerWidth / window.innerHeight;

            camera.updateProjectionMatrix();
        })

        var geometry = new THREE.BoxGeometry(1, 1, 1);
        var material = new THREE.MeshLambertMaterial({
            color: 0xF7F7F7
        });

        //VARIABLES
        var root, pivot;
        var hovered = false;

        const domEvents = new THREEx.DomEvents(camera, renderer.domElement);
        var mixer;
        var animations;

        //LOAD GLTF MODEL
        const gltfLoader = new THREE.GLTFLoader();
        gltfLoader.load('{{ URL::asset("models/model_anim2.glb") }}', (gltf) => {
            root = gltf.scene;
            if (window.innerWidth <= window.innerHeight) {
                root.scale.set(0.5, 0.5, 0.5);
                root.position.set(0, -0.2, 0.25);

                root.traverse(function(child) {

                    domEvents.addEventListener(child, 'click', function(event) {
                        if (hovered) {
                            hovered = false;
                        } else {
                            hovered = true;
                        }
                    }, false);

                });

                domEvents.addEventListener(root.getObjectByName('upper'), 'touchstart', function(event) {
                    if (hovered) {
                        hovered = false;
                    } else {
                        hovered = true;
                    }
                }, false);
            } else {
                root.position.set(0, -0.4, 0.5);

                domEvents.addEventListener(root.getObjectByName('upper'), 'mouseover', function(event) {
                    hovered = true;
                }, false);
                domEvents.addEventListener(root.getObjectByName('upper'), 'mouseout', function(event) {
                    hovered = false;
                }, false);
            }
            pivot = new THREE.Object3D();
            pivot.add(root);
            scene.add(pivot);

            mixer = new THREE.AnimationMixer(pivot);
            animations = gltf.animations;

            //SET HOVER ACTIONS

            domEvents.addEventListener(root.getObjectByName('top'), 'mouseover', function(event) {
                document.getElementById("info").innerHTML = "SUEDE TOP";
            }, false);
            domEvents.addEventListener(root.getObjectByName('top'), 'mouseout', function(event) {
                document.getElementById("info").innerHTML = "";
            }, false);

            /*            root.traverse( function ( child ) {
                                    
                            domEvents.addEventListener(child, 'mouseover', function(event){
            	               hovered = true;
                            }, false);
                            domEvents.addEventListener(child, 'mouseout', function(event){
            	               hovered = false;
                            }, false);
                                    
                                } );*/

            document.querySelector("#bg").classList.add("loaded");
            document.querySelector("#logo").classList.add("anim");
            document.querySelector("#release").classList.add("anim");
            document.querySelector("h1").classList.add("anim");
        });

        //LIGHTING
        var light = new THREE.PointLight(0xFFFFFF, 1, 300)
        light.position.set(0, 0, 0);
        scene.add(light);

        var light = new THREE.PointLight(0xFFFFFF, 2, 500)
        light.position.set(0, 1, 15);
        scene.add(light);

        //RENDER FUNCTION
        var render = function(deltaSeconds) {
            var delta = clock.getDelta();

            requestAnimationFrame(render);

            if (mixer && animations) {
                mixer.update(delta);

                if (hovered == true) {

                    pivot.rotation.y += Math.PI * delta / 12;

                    animations.forEach(function(clip) {

                        if (mixer.clipAction(clip).time >= 2.3) {
                            mixer.clipAction(clip).timeScale = 0;
                        } else {
                            mixer.clipAction(clip).play();
                            mixer.clipAction(clip).timeScale = 4;
                        }

                    });
                } else {
                    animations.forEach(function(clip) {
                        if (mixer.clipAction(clip).time <= 0.2) {
                            mixer.clipAction(clip).timeScale = 0;
                        } else {
                            mixer.clipAction(clip).play();
                            mixer.clipAction(clip).timeScale = -4;
                        }
                    });
                }
            }
            if (pivot && hovered == false) {
                pivot.rotation.y += Math.PI * delta / 6;
            }

            if (document.querySelector("#notify").classList.contains("collapsed") == false) {
                document.querySelector("#email").focus();
            }

            renderer.render(scene, camera);
        }

        render();

    </script>
</body>

</html>
