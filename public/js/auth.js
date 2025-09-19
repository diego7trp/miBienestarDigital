// ===== AUTENTICACIÓN =====
document.addEventListener('DOMContentLoaded', function() {
    
    // Animación de entrada
    const authCard = document.querySelector('.auth-card');
    if (authCard) {
        authCard.style.opacity = '0';
        authCard.style.transform = 'translateY(30px)';
        setTimeout(() => {
            authCard.style.transition = 'all 0.6s ease';
            authCard.style.opacity = '1';
            authCard.style.transform = 'translateY(0)';
        }, 100);
    }
    
    // Validación en tiempo real
    setupRealTimeValidation();
    
    // Cálculo de IMC automático
    setupIMCCalculator();
    
    // Contador de caracteres
    setupCharacterCounters();
    
    // Auto-activar notificaciones cuando se selecciona horario
    setupNotificationToggle();
});

// ===== VALIDACIÓN EN TIEMPO REAL =====
function setupRealTimeValidation() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const name = field.name;
    
    // Limpiar errores previos
    clearFieldError(field);
    
    // Validaciones específicas
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Este campo es obligatorio');
        return false;
    }
    
    if (type === 'email' && value && !isValidEmail(value)) {
        showFieldError(field, 'Ingresa un email válido');
        return false;
    }
    
    if (name === 'password' && value && value.length < 8) {
        showFieldError(field, 'La contraseña debe tener al menos 8 caracteres');
        return false;
    }
    
    if (name === 'password_confirmation') {
        const password = document.querySelector('input[name="password"]');
        if (password && value !== password.value) {
            showFieldError(field, 'Las contraseñas no coinciden');
            return false;
        }
    }
    
    if (name === 'edad' && value) {
        const edad = parseInt(value);
        if (edad < 13 || edad > 120) {
            showFieldError(field, 'Edad debe estar entre 13 y 120 años');
            return false;
        }
    }
    
    return true;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    // Buscar o crear elemento de error
    let errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        field.parentNode.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ===== CALCULADORA DE IMC =====
function setupIMCCalculator() {
    const pesoInput = document.getElementById('peso');
    const alturaInput = document.getElementById('altura');
    
    if (pesoInput && alturaInput) {
        const imcContainer = document.getElementById('imc-result') || createIMCContainer(alturaInput);
        
        function calcularIMC() {
            const peso = parseFloat(pesoInput.value);
            const altura = parseFloat(alturaInput.value) / 100; // convertir cm a m
            
            if (peso && altura && peso > 0 && altura > 0) {
                const imc = peso / (altura * altura);
                let categoria = '';
                let colorClass = '';
                
                if (imc < 18.5) {
                    categoria = 'Bajo peso';
                    colorClass = 'text-info';
                } else if (imc < 25) {
                    categoria = 'Peso normal';
                    colorClass = 'text-success';
                } else if (imc < 30) {
                    categoria = 'Sobrepeso';
                    colorClass = 'text-warning';
                } else {
                    categoria = 'Obesidad';
                    colorClass = 'text-danger';
                }
                
                imcContainer.innerHTML = `
                    <small class="d-block mt-2">
                        <strong>IMC:</strong> ${imc.toFixed(1)} - 
                        <span class="${colorClass}">${categoria}</span>
                    </small>
                `;
            } else {
                imcContainer.innerHTML = '';
            }
        }
        
        pesoInput.addEventListener('input', calcularIMC);
        alturaInput.addEventListener('input', calcularIMC);
        
        // Calcular al cargar si ya hay valores
        calcularIMC();
    }
}

function createIMCContainer(alturaInput) {
    const container = document.createElement('div');
    container.id = 'imc-result';
    alturaInput.parentNode.appendChild(container);
    return container;
}

// ===== CONTADOR DE CARACTERES =====
function setupCharacterCounters() {
    const textareas = document.querySelectorAll('textarea[maxlength]');
    const inputs = document.querySelectorAll('input[maxlength]');
    
    [...textareas, ...inputs].forEach(element => {
        const maxLength = parseInt(element.getAttribute('maxlength'));
        const counter = createCharacterCounter(element, maxLength);
        
        element.addEventListener('input', () => {
            updateCharacterCounter(element, counter, maxLength);
        });
        
        // Actualizar contador inicial
        updateCharacterCounter(element, counter, maxLength);
    });
}

function createCharacterCounter(element, maxLength) {
    const counter = document.createElement('div');
    counter.className = 'form-text text-end character-counter';
    counter.style.fontSize = '0.8rem';
    element.parentNode.appendChild(counter);
    return counter;
}

function updateCharacterCounter(element, counter, maxLength) {
    const currentLength = element.value.length;
    counter.textContent = `${currentLength}/${maxLength}`;
    
    // Cambiar color según uso
    if (currentLength > maxLength * 0.9) {
        counter.className = 'form-text text-end character-counter text-danger';
    } else if (currentLength > maxLength * 0.8) {
        counter.className = 'form-text text-end character-counter text-warning';
    } else {
        counter.className = 'form-text text-end character-counter text-muted';
    }
}

// ===== AUTO-ACTIVAR NOTIFICACIONES =====
function setupNotificationToggle() {
    const horarioInput = document.getElementById('Horario');
    const notificacionesCheck = document.getElementById('notificaciones');
    
    if (horarioInput && notificacionesCheck) {
        horarioInput.addEventListener('change', function() {
            if (this.value && !notificacionesCheck.checked) {
                notificacionesCheck.checked = true;
                
                // Mostrar feedback visual
                const label = notificacionesCheck.parentNode;
                label.style.backgroundColor = 'rgba(23, 137, 62, 0.1)';
                label.style.borderRadius = 'var(--border-radius)';
                label.style.padding = '8px';
                label.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    label.style.backgroundColor = 'transparent';
                    label.style.padding = '0';
                }, 2000);
            }
        });
    }
}

// ===== MEJORAS DE UX =====
// Focus automático en primer campo
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.querySelector('input:not([type="hidden"]):not([type="checkbox"])');
    if (firstInput) {
        setTimeout(() => firstInput.focus(), 500);
    }
});

// Animación en botones
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
    });
});

// Errores visuales intencionados
setTimeout(() => {
    const labels = document.querySelectorAll('label');
    if (labels.length > 2) {
        labels[2].style.marginBottom = '11px'; // Error intencional
    }
}, 1500);