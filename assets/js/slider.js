const sliders = document.querySelectorAll('.vizyonSlier');

sliders.forEach((slider) => {
    const rightButton = slider.querySelector('.arrows.right');
    const leftButton = slider.querySelector('.arrows.left');
    const vizyonLeft = slider.querySelector('.mainvizyonImg');
    let vizyonRight = Array.from(slider.querySelectorAll('.vizyonBox'));

    rightButton.addEventListener('click', () => {
        slideRight(vizyonLeft, vizyonRight, slider);
    });

    leftButton.addEventListener('click', () => {
        slideLeft(vizyonLeft, vizyonRight, slider);
    });

    function slideRight(vizyonLeft, vizyonRight, slider) {

        const currentMainImg = vizyonLeft.querySelector('img').src;
        const currentMainTitle = vizyonLeft.querySelector('.namevizyon').textContent;
        const currentMainHref = vizyonLeft.getAttribute('href');
        const currentMainP = vizyonLeft.querySelector('p') ? vizyonLeft.querySelector('p').textContent : ''; // p varsa al
    
        const firstvizyonRight = vizyonRight[0];
        const newMainImg = firstvizyonRight.querySelector('img').src;
        const newMainTitle = firstvizyonRight.querySelector('h3').textContent;
        const newMainHref = firstvizyonRight.getAttribute('href');
        const newMainP = firstvizyonRight.querySelector('p') ? firstvizyonRight.querySelector('p').textContent : ''; // p varsa al
    
        vizyonLeft.querySelector('img').src = newMainImg;
        vizyonLeft.querySelector('.namevizyon').textContent = newMainTitle;
        vizyonLeft.setAttribute('href', newMainHref);
    
        if (vizyonLeft.querySelector('p')) {
            vizyonLeft.querySelector('p').textContent = newMainP;
        } else if (newMainP) {
            const newP = document.createElement('p');
            newP.textContent = newMainP;
            vizyonLeft.appendChild(newP);
        }
    
        firstvizyonRight.querySelector('img').src = currentMainImg;
        firstvizyonRight.querySelector('h3').textContent = currentMainTitle;
        firstvizyonRight.setAttribute('href', currentMainHref);
    
        if (firstvizyonRight.querySelector('p')) {
            firstvizyonRight.querySelector('p').textContent = currentMainP;
        } else if (currentMainP) {
            const newP = document.createElement('p');
            newP.textContent = currentMainP;
            firstvizyonRight.appendChild(newP);
        }
    
        vizyonRight.push(vizyonRight.shift());
    
        const parentvizyonRight = firstvizyonRight.parentElement;
        parentvizyonRight.appendChild(firstvizyonRight);
    }

    function slideLeft(vizyonLeft, vizyonRight, slider) {

        const currentMainImg = vizyonLeft.querySelector('img').src;
        const currentMainTitle = vizyonLeft.querySelector('.namevizyon').textContent;
        const currentMainHref = vizyonLeft.getAttribute('href');
        const currentMainP = vizyonLeft.querySelector('p') ? vizyonLeft.querySelector('p').textContent : '';
    
        const lastvizyonRight = vizyonRight[vizyonRight.length - 1];
        const newMainImg = lastvizyonRight.querySelector('img').src;
        const newMainTitle = lastvizyonRight.querySelector('h3').textContent;
        const newMainHref = lastvizyonRight.getAttribute('href');
        const newMainP = lastvizyonRight.querySelector('p') ? lastvizyonRight.querySelector('p').textContent : '';
    
        vizyonLeft.querySelector('img').src = newMainImg;
        vizyonLeft.querySelector('.namevizyon').textContent = newMainTitle;
        vizyonLeft.setAttribute('href', newMainHref);
    
        if (vizyonLeft.querySelector('p')) {
            vizyonLeft.querySelector('p').textContent = newMainP;
        } else if (newMainP) {
            const newP = document.createElement('p');
            newP.textContent = newMainP;
            vizyonLeft.appendChild(newP);
        }
    
        lastvizyonRight.querySelector('img').src = currentMainImg;
        lastvizyonRight.querySelector('h3').textContent = currentMainTitle;
        lastvizyonRight.setAttribute('href', currentMainHref);
    
        if (lastvizyonRight.querySelector('p')) {
            lastvizyonRight.querySelector('p').textContent = currentMainP;
        } else if (currentMainP) {
            const newP = document.createElement('p'); 
            newP.textContent = currentMainP;
            lastvizyonRight.appendChild(newP);
        }
    
        vizyonRight.unshift(vizyonRight.pop());
    
        const parentvizyonRight = lastvizyonRight.parentElement;
        parentvizyonRight.prepend(lastvizyonRight);
    }
});