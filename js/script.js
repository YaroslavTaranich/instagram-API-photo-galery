document.addEventListener("DOMContentLoaded", () => {

    async function getDataFromInsta() {
        let response = await fetch('../instagramAPI.php');
        if (response.ok) {
            let array = await response.json();
            return array;
        } else {
            console.log("Ошибка:" + response.status);
        }
    }

    function drowImgFromInsta(array) {
        const container = document.querySelector(".container");
        let imgN = 0,
            srcArray = [];
        for (n in array) {
            imgWrapper = document.createElement("div");
            imgWrapper.classList.add("img__wrapper");
            imgWrapper.innerHTML = `
        <img src="${array[n].img}" alt="" data-img-number="${imgN}"> 
        `;
            container.appendChild(imgWrapper);
            srcArray[imgN] = array[n].img;
            imgN++;
        }
        return srcArray;
    }

    function showModalBigImage(srcArray){
        const body = document.querySelector("body"),
        container = document.querySelector(".container"),
        bigImageWrapper = document.querySelector(".img__modal"),
        modal = document.querySelector('.modal__wrapper');
        let dataImg = 0;

        function drowBigImage(src, dataImg) {
            bigImageWrapper.innerHTML = `
            <img src="${src}" class="big__image" alt="" data-img-number="${dataImg}">
            `;
        }
        container.addEventListener('click', (e) => {
            // console.log(e.target);
            if (e.target && e.target.tagName === "IMG") {
                let src = e.target.getAttribute('src');
                dataImg = e.target.getAttribute("data-img-number");
                drowBigImage(src, dataImg);
                modal.style.display = "block";
                body.style.overflow = "hidden";
            }
        })
        modal.addEventListener("click", (e) => {
            console.log(e.target );
            if (e.target && e.target.classList.contains('modal__wrapper') || e.target.classList.contains('modal__close')){
                modal.style.display = "";
                body.style.overflow = "";
            }
            if (e.target && e.target.classList.contains('modal__next')){
                if(srcArray.length - 1 === +dataImg) {
                    dataImg = 0;
                } else {
                    dataImg++;
                }
                drowBigImage(srcArray[dataImg], dataImg);
            }
            if (e.target && e.target.classList.contains('modal__prev')){
                if(0 === +dataImg) {
                    dataImg = srcArray.length - 1;
                } else {
                    dataImg--;
                }
                drowBigImage(srcArray[dataImg], dataImg);
            }
        })
    };
    getDataFromInsta()
    .then(array => drowImgFromInsta(array))
    .then(srcArray => showModalBigImage(srcArray));
});