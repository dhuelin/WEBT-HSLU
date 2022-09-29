// data
let imageList = [
    {
        name: "Tree",
        src: "img/tree.jpg",
        date: "2021-01-01",
        description: "This is a tree."
    },
    {
        name: "Dog",
        src: "img/dog.jpg",
        date: "2021-01-02",
        description: "This is a dog."
    }
];

let myData = {
    activeSite: "Gallery",
    images: imageList
}

// vue
new Vue({
    el: "#content",
    data: myData,
    created() {
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        myData.activeSite = params.get("site") || "Gallery";
    },
    mounted() {
        if (myData.activeSite === "Canvas") {
            this.drawCanvas();
        }

        let newImages = this.getCookieAsJson("uploadedImages");
        this.addNewImages(imageList, newImages);
    },
    methods: {
        siteChange: function (newPage) {
            myData.activeSite = newPage;
        },
        isActive: function (name) {
            return myData.activeSite === name;
        },
        drawCanvas: function () {
            let canvas = document.getElementById("myCanvas");
            let ctx = canvas.getContext('2d');

            ctx.moveTo(0, 0);
            ctx.lineTo(100, 100);
            ctx.lineTo(150, 100);
            ctx.lineTo(100, 150);
            ctx.lineTo(150, 150);
            ctx.lineTo(200, 150);
            ctx.lineTo(150, 200);
            ctx.lineTo(200, 200);
            ctx.lineTo(250, 100);
            ctx.lineTo(100, 250);
            ctx.lineTo(300, 150);
            ctx.lineTo(150, 300);
            ctx.quadraticCurveTo(150, 300, 200, 350);
            ctx.strokeStyle = "black";
            ctx.lineWidth = 15;
            ctx.stroke();
        },
        // get php cookie as json
        getCookieAsJson: function (cname) {
            if(document.cookie === "") {
                return null;
            }
            let uploadedImages = document.cookie
            .split('; ')
            .find(row => row.startsWith(cname))
            .split('=')[1];
            let decodedJson = this.decodeJson(uploadedImages);
            return JSON.parse(decodedJson);
          },
          // resolve json encoded by PHP
        decodeJson: function (json) {
            let decodedURI = decodeURI(json);
            console.log(decodedURI);
            decodedURI = decodedURI.replaceAll("%3A", ":");
            decodedURI = decodedURI.replaceAll("%2C", ",");
            decodedURI = decodedURI.replaceAll("\%2F", "/");
            decodedURI = decodedURI.replaceAll("%3B", ";");
            decodedURI = decodedURI.replaceAll("%3D", "=");
            return decodedURI.replaceAll("\\", "");
        },
        // add uploaded images to imagesList
        addNewImages: function (currentImages, newImages) {
            if(newImages != null) {
                for(const image of newImages) {
                    if(!currentImages.includes(image)) {
                        currentImages.push(image);
                    }
                }
            }
        }
          
    }
});

// js functions
function openNavigation() {
    document.getElementById("navigation").style.display = "block";
}

function closeNavigation() {
    document.getElementById("navigation").style.display = "none";
}

function validateUpload() {
    let dateValue = document.getElementById("imageDate").value;
    if(dateValue != null) {
        let currentDate = new Date();
        let inputDate = new Date(dateValue);
        if(inputDate > currentDate) {
            return false;
        }
        return true;
    }
}