document.querySelectorAll('.drop-zone').forEach(dropZone => {
  const inputElement = dropZone.querySelector('.drop-zone-input');

  dropZone.addEventListener('click', e => {
    inputElement.click();
  });

  inputElement.addEventListener('change', event => {
    updateThumbnail(dropZone, inputElement.files[0]);
  });

  dropZone.addEventListener('dragover', dragOver);
  dropZone.addEventListener('dragenter', dragEnter);
  dropZone.addEventListener('dragleave', dragLeave);
  dropZone.addEventListener('drop', drop);
});

function dragOver(event) {
  event.preventDefault();
  this.classList.add('outline');
}

function dragEnter(event) {
  event.preventDefault();
  this.classList.add('outline');
}

function dragLeave(event) {
  event.preventDefault();
  this.classList.remove('outline');
}

function drop(event) {
  event.preventDefault();

  if (event.dataTransfer.files.length) {
    const dropZone = event.currentTarget;
    updateThumbnail(dropZone, event.dataTransfer.files[0]);
  }

  this.classList.remove('outline');
}

/**
 * Updates the thumbnail on a drop zone element.
 *
 * @param {HTMLElement} dropZoneElement
 * @param {File} file
 */
function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector('.drop-zone-thumb');

  // First time - remove the prompt
  const initialPromptText = dropZoneElement.querySelector('.prompt-text');
  if (initialPromptText) {
    initialPromptText.remove();
  }

  // get the file extension
  let fileExtension = file.name.split('.').pop();
  fileExtension =
    fileExtension.charAt(0).toUpperCase() + fileExtension.slice(1);

  // First time - there is no thumbnail element, so lets create it
  if (!thumbnailElement) {
    thumbnailElement = document.createElement('div');
    thumbnailElement.classList.add('drop-zone-thumb');

    const overlayElement = document.createElement('div');
    overlayElement.classList.add('overlay');

    const overlayFileNameElement = document.createElement('p');
    overlayFileNameElement.className = 'thumbnail-file-name';
    overlayFileNameElement.textContent = file.name;

    const overlayPromptTextElement = document.createElement('p');
    overlayPromptTextElement.textContent = 'Drag and drop or click to replace';
    overlayPromptTextElement.className = 'overlay-prompt-text';

    const removeFileButton = document.createElement('button');

    removeFileButton.addEventListener('click', e => {
      thumbnailElement.remove();
      dropZoneElement.appendChild(initialPromptText);
      e.stopPropagation();
    });

    const removeIcon = document.createElement('i');
    removeIcon.className = 'fas fa-trash-alt';
    removeFileButton.append(removeIcon);
    removeFileButton.append(document.createTextNode('REMOVE'));

    overlayElement.appendChild(overlayFileNameElement);
    overlayElement.appendChild(overlayPromptTextElement);
    overlayElement.appendChild(removeFileButton);

    thumbnailElement.appendChild(overlayElement);

    dropZoneElement.appendChild(thumbnailElement);
  } else {
    const overlayPromptTextElement = dropZoneElement.querySelector('.thumbnail-file-name');
    overlayPromptTextElement.textContent = file.name;
  }

  // Show thumbnail for image files
  if (file.type.startsWith('image/')) {
    if (dropZoneElement.querySelector('.thumbnail-doc-type')) {
      dropZoneElement.querySelector('.thumbnail-doc-type').remove();
    }

    const reader = new FileReader();

    reader.readAsDataURL(file);
    reader.onload = () => {
      thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
    };
  } else {
    thumbnailElement.style.backgroundImage = null;

    // the file extension icon is not present
    if (!dropZoneElement.querySelector('.thumbnail-doc-type')) {
      const thumbnailDocType = document.createElement('div');

      const icon = document.createElement('i');
      icon.className = 'fas fa-file';

      const docExtensionText = document.createTextNode(fileExtension);
      const docExtension = document.createElement('span');
      docExtension.append(docExtensionText);

      docExtension.className = 'doc-extension';

      thumbnailDocType.appendChild(icon);
      thumbnailDocType.appendChild(docExtension);
      thumbnailDocType.classList.add('thumbnail-doc-type');

      thumbnailElement.appendChild(thumbnailDocType);
    } else {
      // the file extension icon is present we update the text
      dropZoneElement.querySelector('.doc-extension').textContent = fileExtension;
    }
  }
}
