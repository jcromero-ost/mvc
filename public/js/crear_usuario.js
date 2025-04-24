document.addEventListener('DOMContentLoaded', function () {
    const password = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const help = document.getElementById('passwordHelp');
  
    if (password && confirm && help) {
      confirm.addEventListener('input', () => {
        help.classList.toggle('d-none', confirm.value === password.value);
      });
    }
  
    let cropper;
    const dropArea = document.getElementById('drop-area');
    const dropText = document.getElementById('drop-text');
    const fileInput = document.getElementById('foto');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('preview-container');
    const hiddenField = document.getElementById('foto_recortada');
    const clearBtn = document.getElementById('btn-clear');
  
    function handleFile(file) {
      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.classList.remove('d-none');
      previewContainer.classList.remove('d-none');
      dropText.classList.add('d-none');
  
      if (cropper) cropper.destroy();
  
      cropper = new Cropper(preview, {
        aspectRatio: 1,
        viewMode: 1,
        cropend: function () {
          const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
          hiddenField.value = canvas.toDataURL('image/jpeg');
        }
      });
    }
  
    if (dropArea && fileInput && preview && previewContainer && hiddenField && dropText) {
      dropArea.addEventListener('click', (e) => {
        if (e.target === dropArea || e.target.id === 'drop-text') {
          fileInput.click();
        }
      });
  
      fileInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) handleFile(file);
      });
  
      dropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dropArea.classList.add('bg-light');
      });
  
      dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('bg-light');
      });
  
      dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.classList.remove('bg-light');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
      });
  
      clearBtn.addEventListener('click', () => {
        fileInput.value = "";
        previewContainer.classList.add('d-none');
        preview.src = "";
        dropText.classList.remove('d-none');
        hiddenField.value = "";
        if (cropper) {
          cropper.destroy();
          cropper = null;
        }
      });
    }
  
    // Mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirm = document.getElementById('toggleConfirm');
  
    if (togglePassword && password) {
      togglePassword.addEventListener('click', () => {
        const icon = document.getElementById('iconPassword');
        const isVisible = password.type === 'text';
        password.type = isVisible ? 'password' : 'text';
        icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
      });
    }
  
    if (toggleConfirm && confirm) {
      toggleConfirm.addEventListener('click', () => {
        const icon = document.getElementById('iconConfirm');
        const isVisible = confirm.type === 'text';
        confirm.type = isVisible ? 'password' : 'text';
        icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
      });
    }
  });
  