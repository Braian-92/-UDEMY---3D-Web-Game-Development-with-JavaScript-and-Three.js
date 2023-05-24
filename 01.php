<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style>
		body{
			margin: 0;
		}
	</style>
</head>
<body>

</body>
<script type="text/javascript" src="three.min.js"></script>
<script type="text/javascript">
	const scene = new THREE.Scene();
	scene.background = new THREE.Color(0xFD7304);
	const renderer = new THREE.WebGLRenderer();
	renderer.setSize(window.innerWidth, window.innerHeight);
	document.body.appendChild(renderer.domElement);

	const camera = new THREE.PerspectiveCamera(
		100,
		window.innerWidth / window.innerHeight,
		0.1,//! near 
		200 //! far
	);
	camera.position.z = 5;
	scene.add(camera);

	const floorGeometry = new THREE.PlaneGeometry(
		1000,
		1000,
		10,
		10
	);
	const floorMaterial = new THREE.MeshBasicMaterial({
		color : 0x9FAD28,
		side : THREE.DoubleSide
	});
	const floorMesh = new THREE.Mesh(
		floorGeometry,
		floorMaterial
	);
	floorMesh.rotation.x = Math.PI / 2;
	floorMesh.position.y = -5;
	scene.add(floorMesh);

	// var characterGeometry =  new THREE.SphereGeometry(2, 32, 3);
	var characterGeometry =  new THREE.SphereBufferGeometry(2, 32, 32);
	var characterMaterial = new THREE.MeshBasicMaterial({
		color : 0x72E327
	});
	var characterMesh = new THREE.Mesh(characterGeometry, characterMaterial);
	scene.add(characterMesh);

	const animate = function (){
		requestAnimationFrame(animate);
		renderer.render(scene, camera);
	}

	animate();

</script>
</html>