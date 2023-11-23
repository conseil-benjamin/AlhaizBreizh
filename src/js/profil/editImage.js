var m, f, v;

m = null;
f = document.getElementById("fileUpload");
v = document.getElementById("photo");

if (f != null) {
	f.addEventListener("change", function(e){
		m = new FileReader();
		m.onload = function(e){
			v.src = e.target.result;
		};
		m.readAsDataURL(this.files[0]);
	});
}