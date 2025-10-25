/* Hacer la imagen clickeable para ver en un modal */
document.addEventListener('DOMContentLoaded', function() {
    const image = document.getElementById('image-clickable');
    if (image) {
        image.style.cursor = 'pointer';
        image.addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '1000';
            modal.addEventListener('click', function() {
                document.body.removeChild(modal);
            }); 
            const modalImage = document.createElement('img');
            modalImage.src = image.src;
            modalImage.style.maxWidth = '90%';
            modalImage.style.maxHeight = '90%';
            modalImage.style.boxShadow = '0 0 10px #fff';
            modal.appendChild(modalImage);
            document.body.appendChild(modal);
        }
        );
    }
});
