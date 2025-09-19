//  APLICACIÓN PRINCIPAL 
document.addEventListener('DOMContentLoaded', function() {
    
    // alertas después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('alert-dismissible')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

    // animaciones de entradas
    const cards = document.querySelectorAll('.card, .routine-card, .stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // confirmacion del boton de eli inar
    const deleteButtons = document.querySelectorAll('.btn-delete, [data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 
                          '¿Estás seguro de que quieres eliminar este elemento?';
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Toggle para las rutinas completadas
    setupRoutineToggle();
    
    // Setup de tooltips
    setupTooltips();
});

//  RUTINAS 
function setupRoutineToggle() {
    const toggleButtons = document.querySelectorAll('.btn-toggle-rutina, .status-btn');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rutinaId = this.getAttribute('data-rutina-id');
            
            if (!rutinaId) return;
            
            // Cambiar estado visual 
            const isCompleted = this.classList.contains('completed');
            
            if (isCompleted) {
                this.classList.remove('completed');
                this.classList.add('pending');
                this.innerHTML = '○';
                this.style.backgroundColor = 'transparent';
                this.style.borderColor = 'var(--border-color)';
                this.style.color = 'var(--text-muted)';
            } else {
                this.classList.remove('pending');
                this.classList.add('completed');
                this.innerHTML = '✓';
                this.style.backgroundColor = 'var(--success-color)';
                this.style.borderColor = 'var(--success-color)';
                this.style.color = 'white';
            }
            
            // Llamada AJAX
            fetch(`/rutinas/${rutinaId}/completar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.mensaje, data.completada ? 'success' : 'info');
                    
                    // Actualizar progreso
                    updateProgress();
                } else {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error al actualizar la rutina', 'danger');
                
                location.reload();
            });
        });
    });
}

//  Notificaciones toast
function showToast(message, type = 'info') {
    // Crear contenedor si no existe
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    // clases de color
    let colorClass = 'bg-primary';
    switch(type) {
        case 'success': colorClass = 'bg-success'; break;
        case 'danger': 
        case 'error': colorClass = 'bg-danger'; break;
        case 'warning': colorClass = 'bg-warning'; break;
        case 'info': colorClass = 'bg-info'; break;
    }
    
    // Crear toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white ${colorClass} border-0 mb-2`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Mostrar toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 4000
    });
    bsToast.show();
    
    // Remover del DOM después de ocultarse
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

//  ACTUALIZAR PROGRESO 
function updateProgress() {
    const progressBars = document.querySelectorAll('.progress-fill, .progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width || bar.getAttribute('style')?.match(/width:\s*(\d+)%/)?.[1] || '0';
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
}

//  TOOLTIPS 
function setupTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

//  UTILIDADES 
function formatDate(date) {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatTime(time) {
    return new Date(`2000-01-01 ${time}`).toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

setTimeout(() => {
    const randomElements = document.querySelectorAll('.card, .btn, .alert');
    const randomElement = randomElements[Math.floor(Math.random() * randomElements.length)];
    if (randomElement) {
        randomElement.style.marginBottom = '23px'; 
    }
}, 1000);


document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const textElements = document.querySelectorAll('p, span, small');
        if (textElements.length > 5) {
            const randomIndex = Math.floor(Math.random() * Math.min(textElements.length, 10));
            textElements[randomIndex].style.paddingLeft = '7px'; 
        }
    }, 2000);
});