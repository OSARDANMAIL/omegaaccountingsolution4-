document.querySelectorAll('.upload-box').forEach(box => {
    box.addEventListener('dragover', event => {
        event.preventDefault();
        box.classList.add('hover');
    });

    box.addEventListener('dragleave', () => {
        box.classList.remove('hover');
    });

    box.addEventListener('drop', event => {
        event.preventDefault();
        box.classList.remove('hover');

        const input = box.querySelector('input[type="file"]');
        const fileList = box.querySelector('#' + box.id.replace('upload-box', 'file-list'));
        const files = event.dataTransfer.files;

        const dt = new DataTransfer();
        for (let i = 0; i < input.files.length; i++) {
            dt.items.add(input.files[i]);
        }
        for (let i = 0; i < files.length; i++) {
            dt.items.add(files[i]);
            addFileToList(files[i], fileList);
        }
        input.files = dt.files;
    });
});

document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', event => {
        const fileList = input.nextElementSibling.nextElementSibling;
        const files = event.target.files;

        const dt = new DataTransfer();
        for (let i = 0; i < input.files.length; i++) {
            dt.items.add(input.files[i]);
        }
        for (let i = 0; i < files.length; i++) {
            addFileToList(files[i], fileList);
            dt.items.add(files[i]);
        }
        input.files = dt.files;
    });
});

function addFileToList(file, container) {
    const fileItem = document.createElement('div');
    fileItem.className = 'file-item';
    fileItem.innerHTML = `
        <div>${file.name} ~ ${Math.round(file.size / 1024)} KB</div>
        <button type="button" onclick="removeFileFromList(this, '${file.name}')">delete</button>
    `;
    container.appendChild(fileItem);
}

function removeFileFromList(button, fileName) {
    const fileItem = button.parentElement;
    const container = fileItem.parentElement;
    const input = container.previousElementSibling.previousElementSibling;

    const dt = new DataTransfer();
    for (let i = 0; i < input.files.length; i++) {
        if (input.files[i].name !== fileName) {
            dt.items.add(input.files[i]);
        }
    }
    input.files = dt.files;
    container.removeChild(fileItem);
}

function submitFiles() {
    const formData = new FormData();
    const sections = [
        'file-2020', 'file-2021', 'file-2022', 'file-2023', 
        'file-2020-pl', 'file-2021-pl', 'file-2022-pl', 'file-2023-pl', 
        'file-balance'
    ];
    sections.forEach(section => {
        const input = document.getElementById(section);
        for (let i = 0; i < input.files.length; i++) {
            formData.append(section + '[]', input.files[i]);
        }
    });

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Files uploaded successfully!');
        } else {
            alert('Error uploading files: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error uploading your files.');
    });
}
