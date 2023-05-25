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
		#healthLabel{
			position : fixed;
			z-index: 2;
			font-size: 5em;
			color: white;
		}
	</style>
</head>
<body>
	<div id="healthLabel">Healt: 100</div>
</body>
<!-- <script type="text/javascript" src="three.min.js"></script> -->
<script type="text/javascript" src="three.min_viejo.js"></script>
<script type="text/javascript" src="ascii_teclas.js"></script>
<script type="text/javascript">
  const movedistance = .1;
  var keyboardEvent;
  var pressed = false;
  var collidableObjects = [];


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

	var characterGeometry =  new THREE.SphereGeometry(2, 32, 32);
	// var characterGeometry =  new THREE.SphereBufferGeometry(2, 32, 32);
	var characterMaterial = new THREE.MeshBasicMaterial({
		color : 0xC11D1D
	});

	var characterMesh = new THREE.Mesh(characterGeometry, characterMaterial);
	characterMesh.health = 100;
	scene.add(characterMesh);
	characterMesh.add(camera);

	const loader = new THREE.TextureLoader();

	loader.load("venus.jpg", function(texture){
		characterMesh.material.needsUpdate = true;
		characterMesh.material.map = texture;
	});

	const obstacleGeometry = new THREE.BoxGeometry(6,4,2);
	const obstacleMaterial = new THREE.MeshBasicMaterial({
		color : 0x750CE1
	});
	const obstacleMesh = new THREE.Mesh(
		obstacleGeometry,
		obstacleMaterial
	);
	scene.add(obstacleMesh);
	collidableObjects.push(obstacleMesh);

	const animate = function (){
		requestAnimationFrame(animate);
		// camera.lookAt(obstacleMesh.position.clone());
		renderer.render(scene, camera);
    update();
	}
	animate();


  function update(){
    if(pressed){
      if(keyboardEvent.keyCode === KEY_LEFT){
        characterMesh.rotation.y += movedistance;
      }else if(keyboardEvent.keyCode === KEY_RIGHT){
        characterMesh.rotation.y -= movedistance;
      }else if(keyboardEvent.keyCode === KEY_UP){
        characterMesh.translateZ(-movedistance);
      }else if(keyboardEvent.keyCode === KEY_DOWN){
        characterMesh.translateZ(movedistance);
      }else if(keyboardEvent.keyCode === KEY_SPACE){
        characterMesh.position.y += movedistance;
      }else if(keyboardEvent.keyCode === KEY_SHIFT){
        characterMesh.position.y -= movedistance;
      }
    }

    var originPoint = characterMesh.position.clone();

    for (var vertexIndex = 0; vertexIndex < characterMesh.geometry.vertices.length; vertexIndex++){		
      var localVertex = characterMesh.geometry.vertices[vertexIndex].clone();
      var globalVertex = localVertex.applyMatrix4( characterMesh.matrix );
      var directionVector = globalVertex.sub( characterMesh.position );

      var raycast = new THREE.Raycaster( originPoint, directionVector.clone().normalize() );
      var collisions = raycast.intersectObjects( collidableObjects );
      if (collisions.length > 0 && collisions[0].distance < directionVector.length()) {
      	characterMesh.health--;
        console.log("Collision Detected");
        document.getElementById('healthLabel').firstChild.nodeValue = "Health: " + characterMesh.health;
        break;
      } 
    }	
  }


	window.addEventListener('keydown', onKeyDown, false);
  window.addEventListener('keyup'  , onKeyUp  , false);

	function onKeyDown(event){
    keyboardEvent = event;
    pressed = true;
  }
  function onKeyUp(event){
    pressed = false;
    keyboardEvent = event;
  }

  window.addEventListener("resize", onWindowResize, false);

  function onWindowResize(){
  	camera.aspect = window.innerWidth / window.innerHeight;
  	camera.updateProjectionMatrix();
  	renderer.setSize(window.innerWidth, window.innerHeight);
  }

</script>
</html>