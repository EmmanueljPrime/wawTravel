document.addEventListener('turbo:load', () => {
    const container = document.querySelector('#checkpoints');
    const prototype = container.dataset.prototype;
    let index = container.querySelectorAll('.checkpoint-item').length;

    // Ajout d'un checkpoint
    document.querySelector('#add-checkpoint').addEventListener('click', () => {
        const newCheckpoint = prototype.replace(/__name__/g, index);
        const checkpointElement = document.createElement('div');
        checkpointElement.innerHTML = newCheckpoint;
        checkpointElement.classList.add('checkpoint-item', 'mb-3', 'border-light', 'shadow-sm');
        checkpointElement.querySelector('.remove-checkpoint').addEventListener('click', () => {
            checkpointElement.remove();
        });
        container.appendChild(checkpointElement);
        index++;
    });

    // Suppression d'un checkpoint existant
    container.querySelectorAll('.remove-checkpoint').forEach(button => {
        button.addEventListener('click', (e) => {
            e.target.closest('.checkpoint-item').remove();
        });
    });
});