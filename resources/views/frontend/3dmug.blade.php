<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Mug Customizer</title>
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: "Inter", sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f0f4f8; /* Light background */
            position: relative;
        }
        #webgl-output {
            display: block;
            background-color: #ffffff; /* White background for canvas */
            border-radius: 1rem; /* Rounded corners for the canvas */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Soft shadow */
            width: 100vw; /* Occupy full viewport width */
            height: calc(100vh - 8rem); /* Adjust height for controls */
            max-width: 900px; /* Max width for larger screens */
            max-height: 600px; /* Max height */
            margin-bottom: 2rem;
        }
        #controls {
            position: absolute;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }
        #loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.5rem;
            z-index: 999;
        }
    </style>
</head>
<body>
    <div id="loading-overlay" class="rounded-lg">Loading 3D Scene...</div>

    <div id="controls">
        <label for="photo-upload" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out cursor-pointer text-sm">
            Upload Your Photo
        </label>
        <input type="file" id="photo-upload" accept="image/*" class="hidden">
        <p class="text-gray-600 text-xs text-center mt-1">Drag to rotate, scroll to zoom the mug.</p>
    </div>

    <div id="webgl-output"></div>

    <!-- Three.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <!-- OrbitControls for camera interaction -->
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

    <script type="module">
        // Import necessary components from Three.js if using modules (though CDNs often make it global)
        // Here, we assume they are global for simplicity with CDN links.

        let scene, camera, renderer, mugBodyMesh, mugHandleMesh, controls;
        const textureLoader = new THREE.TextureLoader();
        const loadingOverlay = document.getElementById('loading-overlay');
        const webglOutput = document.getElementById('webgl-output');

        /**
         * Initializes the Three.js scene, camera, renderer, and objects.
         */
        function init() {
            // Scene
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0xf0f4f8); // Light background for the scene

            // Camera
            camera = new THREE.PerspectiveCamera(75, webglOutput.clientWidth / webglOutput.clientHeight, 0.1, 1000);
            camera.position.set(0, 1.5, 4); // Position camera slightly above and in front of the mug

            // Renderer
            renderer = new THREE.WebGLRenderer({ antialias: true }); // Enable antialiasing for smoother edges
            renderer.setSize(webglOutput.clientWidth, webglOutput.clientHeight);
            renderer.setPixelRatio(window.devicePixelRatio); // Handle high-DPI screens
            webglOutput.appendChild(renderer.domElement);

            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.7); // Soft ambient light
            scene.add(ambientLight);

            const directionalLight1 = new THREE.DirectionalLight(0xffffff, 0.6);
            directionalLight1.position.set(5, 10, 7); // Light from top-right-front
            scene.add(directionalLight1);

            const directionalLight2 = new THREE.DirectionalLight(0xffffff, 0.4);
            directionalLight2.position.set(-5, -10, -7); // Light from bottom-left-back
            scene.add(directionalLight2);

            // Create the Mug Body (Cylinder)
            // Parameters: radiusTop, radiusBottom, height, radialSegments, heightSegments
            const mugBodyGeometry = new THREE.CylinderGeometry(1.0, 1.0, 2.0, 64, 1);
            // Initial material for the mug body sides (white/grey)
            const initialSideMaterial = new THREE.MeshStandardMaterial({ color: 0xdddddd, metalness: 0.1, roughness: 0.6 });
            const capMaterial = new THREE.MeshStandardMaterial({ color: 0xeeeeee, metalness: 0.1, roughness: 0.6 });

            // CylinderGeometry has three material groups: 0 (sides), 1 (top cap), 2 (bottom cap)
            const mugBodyMaterials = [
                initialSideMaterial, // Material for the sides (where the photo will go)
                capMaterial,         // Material for the top cap
                capMaterial          // Material for the bottom cap
            ];
            mugBodyMesh = new THREE.Mesh(mugBodyGeometry, mugBodyMaterials);
            mugBodyMesh.position.y = 1; // Center the mug vertically
            scene.add(mugBodyMesh);

            // Create the Mug Handle (Torus)
            // Parameters: radius, tube, radialSegments, tubularSegments
            const mugHandleGeometry = new THREE.TorusGeometry(0.7, 0.15, 30, 200);
            const mugHandleMaterial = new THREE.MeshStandardMaterial({ color: 0xbbbbbb, metalness: 0.1, roughness: 0.6 });
            mugHandleMesh = new THREE.Mesh(mugHandleGeometry, mugHandleMaterial);

            // Position and rotate the handle relative to the mug body
            mugHandleMesh.position.set(1.2, 1, 0); // Position next to the mug body
            mugHandleMesh.rotation.y = Math.PI / 2; // Rotate to face outward
            scene.add(mugHandleMesh);

            // OrbitControls for interactive camera movement
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true; // Smooth camera movement
            controls.dampingFactor = 0.05;
            controls.screenSpacePanning = false; // Prevents panning beyond limits
            controls.minDistance = 2; // Closest zoom
            controls.maxDistance = 10; // Furthest zoom
            controls.maxPolarAngle = Math.PI / 2; // Prevent camera from going below the ground

            // Event Listeners
            window.addEventListener('resize', onWindowResize);
            document.getElementById('photo-upload').addEventListener('change', handlePhotoUpload);

            // Hide loading overlay once everything is initialized
            loadingOverlay.style.display = 'none';
        }

        /**
         * Animation loop for rendering the scene.
         */
        function animate() {
            requestAnimationFrame(animate); // Request the next frame
            controls.update(); // Update OrbitControls
            renderer.render(scene, camera); // Render the scene with the camera
        }

        /**
         * Handles window resizing to keep the scene responsive.
         */
        function onWindowResize() {
            camera.aspect = webglOutput.clientWidth / webglOutput.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(webglOutput.clientWidth, webglOutput.clientHeight);
        }

        /**
         * Handles the photo upload event, reads the file, and applies it as a texture.
         * @param {Event} event - The file input change event.
         */
        function handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Load the image as a Three.js texture
                    textureLoader.load(e.target.result, (texture) => {
                        // The CylinderGeometry's material at index 0 is for the sides.
                        // We replace its map property with the new texture.
                        if (mugBodyMesh && mugBodyMesh.material && mugBodyMesh.material[0]) {
                            mugBodyMesh.material[0].map = texture;
                            mugBodyMesh.material[0].needsUpdate = true; // Tell Three.js the material needs re-rendering
                            console.log('Texture applied successfully!');
                        }
                    }, undefined, (error) => {
                        console.error('An error occurred loading the texture:', error);
                    });
                };
                reader.onerror = (error) => {
                    console.error('Error reading file:', error);
                };
                reader.readAsDataURL(file); // Read the file as a Data URL
            } else {
                console.log('No file selected.');
            }
        }

        // Initialize and start the animation loop when the window loads
        window.onload = function () {
            init();
            animate();
        };
    </script>
</body>
</html>
