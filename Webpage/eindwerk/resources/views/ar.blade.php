<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta charset="UTF-8">
    <title>Nike AR Experience</title>

    <!-- three.js library -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/three.js/105/three.js'></script>
    <script src="{{ URL::asset('js/GLTFLoader.js') }}"></script>
    <!-- ar.js -->
    <script src="{{ URL::asset('artoolkit/three.js/build/ar.js') }}"></script>
    <script>
        THREEx.ArToolkitContext.baseURL = '{{ URL::asset("artoolkit/") }}'

    </script>
</head>

<style>
    @font-face {
        font-family: 'futura';
        src: url('{{ URL::asset("fonts/futura.ttf") }}');
    }
    
    a {
        color: black;
        text-decoration: none;
    }
    
    a:hover {
        color: black;
        text-decoration: none;
    }
    
    #overlay {
        width: 32vw;
        margin: 2vh 34vw;
        max-height: 20vh;
        position: absolute;
        z-index: 100;
        opacity: 0.5;
    }
    
    #overlay-text {
        bottom: 0vh;
        width: 100vw;
        height: 20vh;
        text-align: center;
        position: absolute;
        z-index: 100;
        background-size: cover;
        font-family: 'futura';
        font-size: 5vw;
        opacity: 0;
    }
    
    #overlay-text.anim {
        opacity: 1;
        animation-name: btn;
        animation-duration: 1s;
    }
    
    h1 {
        width: 40vw;
        margin: auto;
        background-color: black;
        color: white;
        border-radius: 50px;
        font-size: 20px;
        padding: 10px;
    }
    
    #logo,
    #logo img {
        width: 32vw;
    }
    
    #instruction {
        position: absolute;
        width: 80vw;
        left: 10vw;
        top: 43vh;
        text-align: center;
        color: white;
    }
    
    #instruction.gone {
        visibility: hidden;
    }
    
    @keyframes text {
        0% {
            width: 0vw;
        }
        100% {
            width: 50vw;
        }
    }
    
    @keyframes btn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

</style>

<body style='margin : 0px; overflow: hidden; font-family: Monospace;'>
    <div id="overlay">
        <div id="logo">
            <img src='{{ URL::asset("img/logo-white.png") }}' alt="logo">
        </div>
    </div>
    <h2 id="instruction">Point your camera at the Nike logo.</h2>
    <div id="overlay-text">
        <a id="link" href="{{route('home')}}">
            <h1>I want 'em</h1>
        </a>
    </div>
    <div style='position: absolute; top: 10px; width:100%; text-align: center; z-index: 1;'>

        <script>
            // init renderer
            var renderer = new THREE.WebGLRenderer({
                antialias: true,
                alpha: true
            });
            renderer.setClearColor(new THREE.Color('lightgrey'), 0)
            renderer.setSize(640, 480);
            renderer.domElement.style.position = 'absolute'
            renderer.domElement.style.top = '0px'
            renderer.domElement.style.left = '0px'
            document.body.appendChild(renderer.domElement);
            // array of functions for the rendering loop
            var onRenderFcts = [];
            // init scene and camera
            var scene = new THREE.Scene();

            // Create a camera
            var camera = new THREE.Camera();
            scene.add(camera);

            // AR
            var arToolkitSource = new THREEx.ArToolkitSource({
                sourceType: 'webcam',
            })
            arToolkitSource.init(function onReady() {
                onResize()
            })

            // handle resize
            window.addEventListener('resize', function() {
                onResize()
            })

            function onResize() {
                arToolkitSource.onResize()
                arToolkitSource.copySizeTo(renderer.domElement)
                if (arToolkitContext.arController !== null) {
                    arToolkitSource.copySizeTo(arToolkitContext.arController.canvas)
                }
            }

            // create atToolkitContext
            var arToolkitContext = new THREEx.ArToolkitContext({
                    cameraParametersUrl: THREEx.ArToolkitContext.baseURL + '/data/data/camera_para.dat',
                    detectionMode: 'mono',
                })
                // initialize it
            arToolkitContext.init(function onCompleted() {
                    // copy projection matrix to camera
                    camera.projectionMatrix.copy(arToolkitContext.getProjectionMatrix());
                })
                // update artoolkit on every frame
            onRenderFcts.push(function() {
                if (arToolkitSource.ready === false) return
                arToolkitContext.update(arToolkitSource.domElement)

                // update scene.visible if the marker is seen
                scene.visible = camera.visible
                if (camera.visible) {
                    document.getElementById("overlay-text").classList.add("anim");
                    document.getElementById("link").classList.add("anim");
                    document.getElementById("instruction").classList.add("gone");
                }
            })

            // init controls for camera
            var markerControls = new THREEx.ArMarkerControls(arToolkitContext, camera, {
                    type: 'pattern',
                    patternUrl: '{{ URL::asset("img/nike.patt") }}',

                    changeMatrixMode: 'cameraTransformMatrix'
                })
                // as we do changeMatrixMode: 'cameraTransformMatrix', start with invisible scene
            scene.visible = false

            //LIGHTING
            var light = new THREE.PointLight(0xFFFFFF, 1, 300)
            light.position.set(0, 0, 0);
            scene.add(light);

            var light = new THREE.PointLight(0xFFFFFF, 2, 500)
            light.position.set(0, 15, 0);
            scene.add(light);

            // Load GLTF model
            var pivot;
            const gltfLoader = new THREE.GLTFLoader();
            gltfLoader.load('{{ URL::asset("models/ar_model.glb")}}', (gltf) => {
                var root = gltf.scene;
                root.position.set(0, -0.4, 0.5);
                pivot = new THREE.Object3D();
                pivot.rotation.x = -1.5;
                pivot.position.set(0, 1, -0.1);
                pivot.add(root);
                scene.add(pivot);
            });

            onRenderFcts.push(function(delta) {
                if(pivot) {
                    pivot.rotation.y += Math.PI * delta / 5;
                }
            })

            // render the scene
            onRenderFcts.push(function() {
                    renderer.render(scene, camera);
                })
                // run the rendering loop
            var lastTimeMsec = null
            requestAnimationFrame(function animate(nowMsec) {
                // keep looping
                requestAnimationFrame(animate);
                // measure time
                lastTimeMsec = lastTimeMsec || nowMsec - 1000 / 60
                var deltaMsec = Math.min(200, nowMsec - lastTimeMsec)
                lastTimeMsec = nowMsec
                    // call each update function
                onRenderFcts.forEach(function(onRenderFct) {
                    onRenderFct(deltaMsec / 1000, nowMsec / 1000)
                })
            })

        </script>
    </div>
</body>
