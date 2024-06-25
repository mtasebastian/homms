const video = document.getElementById('qr-video');
const canvasElement = document.getElementById('qr-canvas');
const canvas = canvasElement.getContext('2d');
const qrResult = document.getElementById('qr-result');

let scanning = false;

const qrScan = () => {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            video.srcObject = stream;
            video.setAttribute('playsinline', true);
            video.play();
            requestAnimationFrame(tick);
        })
        .catch(function(err) {
            console.error('Error accessing camera:', err);
        });
};

const tick = () => {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvasElement.hidden = false;
        canvasElement.height = 260;
        canvasElement.width = 260;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: 'dontInvert',
        });
        if(code){
            openRequest(code.data);
            scanning = true;
        } else {
            if (!scanning) {
                qrResult.textContent = 'No QR code detected.';
            }
        }
    }
    if (!scanning) {
        requestAnimationFrame(tick);
    }
};