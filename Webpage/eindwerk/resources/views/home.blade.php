<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Nike 3D Experience</title>
    
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('css/home.css')}}">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/105/three.js"></script>
    <script src="https://threejsfundamentals.org/threejs/resources/threejs/r105/js/controls/OrbitControls.js"></script>
    <script src="{{ URL::asset('js/GLTFLoader.js') }}"></script>
    <script src="{{ URL::asset('js/threex.domevents.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script>

</head>

<body>
    <img id="shadow" src="{{ URL::asset('img/shadow.png') }}" alt="shadow">
    <div id="bg">
        <div id="loader">
            <img src="{{ URL::asset('img/loader.gif')}}" alt="loading">
        </div>
    </div>
    <div id="hint">
        <p>MOVE YOUR MOUSE OVER THE SNEAKER</p>
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
        @if ($errors->any())
        <div class="msg">
                @foreach ($errors->all() as $error)
                <p class="error">{{ $error }}</p>
                @endforeach
                @if(session()->has('message'))
                <p class="success">
                    {{ session()->get('message') }}
                </p>
                @endif
        </div>
        @endif
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
        <div id="ar-mobile">
            <a href="https://play.google.com/store/apps/details?id=com.nike.omega&hl=nl">
                <p>Try them on in AR.</p>
            </a>
        </div>
    </div>
    <div id="ar">
        <a id="ar-link" href="https://play.google.com/store/apps/details?id=com.nike.omega&hl=nl">
            <p>Try them on in AR.</p>
        </a>
    </div>
    <div id="info">
        <h3 id="infoTxt"></h3>
    </div>

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
        var element = document.getElementById("info");
        var info = document.getElementById("infoTxt");
        var hint = document.querySelector("#hint p");

        const domEvents = new THREEx.DomEvents(camera, renderer.domElement);
        var mixer;
        var animations;

        //MAKE CIRCLE FOR HOVER
        var geometry = new THREE.CircleGeometry(0.8, 10);
        var material = new THREE.MeshBasicMaterial({
            color: 0x0000ff,
            transparent: true,
            opacity: 0.0
        });
        var circle = new THREE.Mesh(geometry, material);
        circle.position.set(0, 0, -1.5);
        scene.add(circle);

        //LOAD GLTF MODEL
        const gltfLoader = new THREE.GLTFLoader();
        gltfLoader.load('{{ URL::asset("models/model_anim2.glb") }}', (gltf) => {
            root = gltf.scene;

            //SET HOVER EVENTS
            if (window.innerWidth <= window.innerHeight) {
                root.scale.set(0.5, 0.5, 0.5);
                root.position.set(0, -0.05, 0.25);

                hint.innerHTML = "TAP TO EXPAND";

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
                        hint.innerHTML = "";
                    }
                }, false);
            } else {
                root.position.set(0, -0.4, 0.5);

                domEvents.addEventListener(circle, 'mouseover', function(event) {
                    hovered = true;
                    hint.innerHTML = 'CLICK TO GET MORE INFO';
                }, false);
                domEvents.addEventListener(circle, 'mouseout', function(event) {
                    hovered = false;
                    info.innerHTML = "";
                    hint.innerHTML = 'MOVE YOUR MOUSE OVER THE SNEAKER';
                }, false);

                //DESKTOP CLICK EVENTS
                domEvents.addEventListener(root.getObjectByName('upper'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "MESH UPPER";
                }, false);
                domEvents.addEventListener(root.getObjectByName('back'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE HEEL";
                }, false);
                domEvents.addEventListener(root.getObjectByName('back-top'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "REFLECTIVE TOP";
                }, false);
                domEvents.addEventListener(root.getObjectByName('back2'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE REINFORCEMENTS";
                }, false);
                domEvents.addEventListener(root.getObjectByName('midsole'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SECOND SOLE";
                }, false);
                domEvents.addEventListener(root.getObjectByName('sole'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SOLE";
                }, false);
                domEvents.addEventListener(root.getObjectByName('swooshleft1'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "LEATHER SWOOSH";
                }, false);
                domEvents.addEventListener(root.getObjectByName('swooshleft2'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "REFLECTIVE SWOOSH";
                }, false);
                domEvents.addEventListener(root.getObjectByName('swooshright1'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "LEATHER SWOOSH";
                }, false);
                domEvents.addEventListener(root.getObjectByName('swooshright2'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "REFLECTIVE SWOOSH";
                }, false);
                domEvents.addEventListener(root.getObjectByName('toe1'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE TOE";
                }, false);
                domEvents.addEventListener(root.getObjectByName('toe2'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE REINFORCEMENTS";
                }, false);
                domEvents.addEventListener(root.getObjectByName('tongue'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "RETRO FOAM TONGUE";
                }, false);
                domEvents.addEventListener(root.getObjectByName('top'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE UPPER";
                }, false);
                domEvents.addEventListener(root.getObjectByName('top2'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE REINFORCEMENTS";
                }, false);
                domEvents.addEventListener(root.getObjectByName('top3'), 'click', function(event) {
                    element.classList.remove("anim");
                    element.offsetWidth;
                    element.classList.add("anim");
                    info.innerHTML = "SUEDE REINFORCEMENTS";
                }, false);
            }

            pivot = new THREE.Object3D();
            pivot.add(root);
            scene.add(pivot);

            mixer = new THREE.AnimationMixer(pivot);
            animations = gltf.animations;

            document.querySelector("#bg").classList.add("loaded");
            document.querySelector("#loader").classList.add("done");
            document.querySelector("#logo").classList.add("anim");
            document.querySelector("#release").classList.add("anim");
            document.querySelector("h1").classList.add("anim");
            document.querySelector(".msg").classList.add("anim");
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

                    circle.scale.set(2.5, 1.5, 1.0);
                } else {
                    animations.forEach(function(clip) {
                        if (mixer.clipAction(clip).time <= 0.2) {
                            mixer.clipAction(clip).timeScale = 0;
                        } else {
                            mixer.clipAction(clip).play();
                            mixer.clipAction(clip).timeScale = -4;
                        }
                    });

                    circle.scale.set(1.5, 0.8, 1);
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
