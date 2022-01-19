function showGradeMenu() {
	let x = document.getElementById("gradeMenu");
	x.style.display = "block";
}

function hideGradeMenu() {
	let x = document.getElementById("gradeMenu");
	x.style.display = "none";
}

function showChosenGrade() {
	let student = document.getElementById("student");
	if (student.checked) {
		showGradeMenu();
	}
}

function showAgreement() {
	let x = document.getElementById("agreementDiv");
	x.style.display = "block";
	document.getElementById("agreement").checked = false;
}

window.onload = function() {
	showChosenGrade();
};


// initialize hidden elements of lightbox
window.onload = function (){
	document.getElementById("positionBigImage").style.display = "none";
	document.getElementById("lightbox").style.display = "none";
};

//onchange hash
function hash() {
	console.log(document.getElementById("passwordField").value);
	document.getElementById("password").value = md5(document.getElementById("passwordField").value);
	console.log(document.getElementById("password").value);
}

//Onchange of upload, get temp url and create an image
function previewImg () {
	showAgreement();
	const [imgFile] = document.getElementById("image").files;
	console.log(imgFile);
	if (imgFile) {
		document.getElementById("preview").src = createObjectURL(imgFile[0]);//<?php precreate(imgFile, 500, 500);?>;
	}
}

// change the visibility of ID
function changeVisibility(divID) {
  var element = document.getElementById(divID);
   console.log(element.style.display);
  if (element) {
	if (element.style.display == "none")
        element.style.display = "block";
	else 
		element.style.display = "none";
  }
} // changeVisibility

// global variables
let jsondata; // array of profiles currently being displayed on the screen
let currentUid; // uid of profile currently displayed in lightbox

// display lightbox with big image in it
function displayLightBox(alt, imageFile) {
  let boundaryImageDiv = document.getElementById("boundaryBigImage");
  let textDiv = document.getElementById("text");   
  let image = new Image();
  let bigImage = document.getElementById("bigImage");
  let download = document.getElementById("download");
  
  // get uid from image
  var requestedUid = imageFile.split(".")[0];

  // save uid into global variable
  currentUid = requestedUid;
  
  // get json data for uid
  if (imageFile != "") {
	  fetch ("./getData.php?uid=" + requestedUid)
	    .then(response => response.json())
		.then(data => updatePostContents(data))
		.catch(err => console.log("error occured" + err));
  }
  
  // update big image to access
  image.src = "postimages/" + imageFile;
  image.alt = alt;	
  
  // update download link
  download.href = image.src;
  
  // force big image to preload so we can have access 
  // to it's width so it will be centered in the page
  image.onload = function () { 
       var width = image.width; 
	   boundaryImageDiv.style.width = width + "px";  
  };
 
  bigImage.src = image.src;  // put big image in page
  textDiv.innerHTML = "<h4>" + alt + "</h4>";
  
  
  // show light box with big image
  changeVisibility('lightbox');
  changeVisibility('positionBigImage'); 
}

// display user's name, grade, description, ect. under big image in lightbox
function updatePostContents(data) {
	console.log(data);
	
	
	
	var taglinks = "";
	
	for (tag in data.tags) {
		taglinks += "<a href='javascript:searchProfiles(\"" + data.tags[tag] + "\"); changeVisibility(\"lightbox\"); changeVisibility(\"positionBigImage\");'> #" + data.tags[tag] + "</a>&nbsp;&nbsp;&nbsp;&nbsp;"; 
	}
	
	document.getElementById("text").innerHTML = "Posted by: " + data.author + "<br><br>" + data.desc + "<br><br>" + taglinks;
}



// sorts list of profiles by uid
function sortByUID() {
	return function(a, b) {
		if (a["uid"] > b["uid"]) {
			return 1;
		} else {
			return -1;
		}
	}
}


// load all posts or users's posts only
function loadImages(access, isPost){
	console.log(isPost);
	console.log(access);
	if (isPost) {
		thumbFolder = "thumbnails/";
		
	} else {
		thumbFolder = "pfpthumbs/";
	}
    fetch("./readjson.php?access=" + access).
    then(function(resp){ 
      return resp.json();
    })
    .then(function(data){
      console.log(data); 
	  let followingArray = [];
	  
	  // everything beyond this point can be turned into a method probably
      let i;  // counter     
	  let j; // other counter
      let main = document.getElementById("main");
      // remove all existing children of main
      while (main.firstChild) {
        main.removeChild(main.firstChild);
      }
     
	  // sort contents of data by uid
	  if (data != null) {
		data.sort(sortByUID());
	 
	  	
		
		//get following list
		if (!isPost) {
			for (j in data) { // fix me :(
			console.log(data[j].current);
				if (data[j].current) {
					followingArray = data[j].following;
					console.log(followingArray); // come back
					data.splice(j, 1);
					
					break;
				}				
			}
		}
		
		console.log(followingArray); // come back
		
		// save data into global array
	  	jsondata = data;

      	// for every image, create a new image object and add to main
      	for (i in data){
        let img = new Image();
		let card = document.createElement('div');
		card.className = "card";

		console.log(data[i].uid + "." + data[i].imagetype);
        img.src = thumbFolder + data[i].uid + "." + data[i].imagetype;
        img.alt = data[i].desc;
		img.className = "thumb";
        main.appendChild(card).appendChild(img);
		
		if (isPost) {
			card.setAttribute("onclick", "displayLightBox('alt', '" + data[i].uid + "." + data[i].imagetype + "')");	
		
			let likeform = document.createElement('form');
			likeform.method = "post";
			//likeform.setAttribute("onsubmit", "loadImages('allpfs', false)"); // doesnt workkk
			let like = document.createElement('input');
			like.type = "image";
			like.src = "images/like.png";
			like.alt = "like button";
			like.className = "like";
			let postToLike = document.createElement('input');
			postToLike.type = "hidden";
			postToLike.name = "postToLike";
			postToLike.value = data[i].uid;
			card.appendChild(likeform).appendChild(like);
			likeform.appendChild(postToLike);
		}
		if (!isPost) {
			
			let followform = document.createElement('form');
			followform.method = "post";
			followform.setAttribute("onsubmit", "loadImages('allpfs', false)");
			let follow = document.createElement('input');
			follow.type = "image";
			follow.className = "follow";
			let userToFollow = document.createElement('input');
			userToFollow.type = "hidden";
			userToFollow.value = data[i].uid;
			card.appendChild(followform).appendChild(follow);
			followform.appendChild(userToFollow);
			console.log(followingArray); // come back
				if (followingArray.includes(data[i].uid)) {
					follow.src = "images/unfollow.png";
					follow.alt = "unfollow button";
					userToFollow.name = "userToUnfollow";
				
				}
			 else {
				follow.src = "images/follow.png";
				follow.alt = "follow button";
				userToFollow.name = "userToFollow";
				
			}
			
			
			let username = document.createElement('a');
			let usernameText = document.createTextNode(data[i].username);
			username.href = "javascript:loadImages(" + data[i].uid + ", true);";
			username.appendChild(usernameText);
			
			card.appendChild(username);

		}

      }
	  }
	  
    });
} // loadImages

// return the provided session variable FIX ME
function getSessionVariable(variable) {
	fetch("./getsessionvariables.php?var=" + variable).
    then(function(resp){
        return resp.json;
    }).
	then(function(data) {
		console.log(data);
		console.log("sdhfjkdskf");
		return data;	});
}

// display the next image (of images currently displayed) in the lightbox
function goToNextImage(direction) {
	
	let current; // index of current image being displayed (changes)
	let i; // counter
	
	for (i in jsondata) {
		if (jsondata[i].uid == currentUid) {
			current = i;
			break;
		}
	}
	
	// logic based on direction - 1 means right, 0 means left
	if (direction == 1) {
		current++;
		if (current < jsondata.length) {
			displayLightBox('','');
			displayLightBox('alt', jsondata[current].uid + "." + jsondata[current].imagetype);
			currentUid = jsondata[current].uid;
		} 
	} else {
		current--;
		if (current > -1) {
			displayLightBox('','');
			displayLightBox('alt', jsondata[current].uid + "." + jsondata[current].imagetype);
			currentUid = jsondata[current].uid;
		}
	}
}

window.onload = function() {
	loadImages("all", true);
}

const searchbar = document.getElementById("searchbar");

if (searchbar != null) {
	// when search is submitted, execute code to search profiles
	searchbar.addEventListener('submit', event => {
		event.preventDefault();
		searchProfiles(document.getElementById("search").value);
	})
} // if


// searches profiles based on terms in a string
function searchProfiles(term) {
	
	// convert terms string into a string that can be passed into a url
	// note: should add more validation
	const termsArray = term.split(" ");
	let termsUrl = "";
	for (let i = 0; i < termsArray.length; i++) {
		termsUrl += termsArray[i];
		if ((i + 1) != termsArray.length) {	
			termsUrl += "%";
		}
	}

	fetch("./searchprofiles.php?term=" + termsUrl).
    then(function(resp){ 
      return resp.json();
    })
    .then(function(data){
      console.log(data); 
	  
	  // everything beyond this point can be turned into a method probably
      let i;  // counter     
      let main = document.getElementById("main");
	  let message = document.getElementById("message");
      
      // remove all existing children of main
	  if (main.firstChild != null) {
		  while (main.firstChild) {
			main.removeChild(main.firstChild);
		  }
	  }
	 
	  // sort contents of data by uid
	  data.sort(sortByUID());
	 
	  // save data into global array
	  jsondata = data;
	  
	  // if profiles are returned from the search, display them 
	  // otherwise, display message saying that no results were returned
	  if (data.length != 0) {
		for (i in data){
			let img = new Image();
			let card = document.createElement('div');
			card.className = "card";
			card.setAttribute("onclick", "displayLightBox('alt', '" + data[i].uid + "." + data[i].imagetype + "')");	
			console.log(data[i].uid + "." + data[i].imagetype);
			img.src = "thumbnails/" + data[i].uid + "." + data[i].imagetype;
			img.alt = data[i].desc;
			img.className = "thumb";
			main.appendChild(card).appendChild(img);
		}
	  } else {
		  message.innerHTML = "Sorry, doesn't ring a bell. (Your search returned no results.)"
	  }
      // for every image, create a new image object and add to main
      
    });


}


