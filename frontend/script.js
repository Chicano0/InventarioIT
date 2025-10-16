// URL base de la API
const API_URL = '/api';

let currentEditId = null;

// Elementos del DOM
const itemForm = document.getElementById('itemForm');
const itemsList = document.getElementById('itemsList');
const loadingSpinner = document.getElementById('loadingSpinner');
const errorMessage = document.getElementById('errorMessage');
const btnText = document.getElementById('btnText');
const totalItemsSpan = document.getElementById('totalItems');

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    loadItems();
    itemForm.addEventListener('submit', handleSubmit);
});

// Cargar items desde la API
async function loadItems() {
    try {
        showLoading(true);
        hideError();
        
        const response = await fetch(`${API_URL}/get_items.php`);
        
        if (!response.ok) {
            throw new Error('Error al cargar los items');
        }
        
        const items = await response.json();
        displayItems(items);
        updateStats(items.length);
        
    } catch (error) {
        console.error('Error:', error);
        showError('No se pudieron cargar los items. Verifica que el backend esté funcionando.');
    } finally {
        showLoading(false);
    }
}

// Mostrar items en el DOM
function displayItems(items) {
    if (items.length === 0) {
        itemsList.innerHTML = '<div class="loading">No hay items en el inventario. ¡Agrega el primero!</div>';
        return;
    }
    
    itemsList.innerHTML = items.map(item => `
        <div class="item-card" data-id="${item.id}">
            <div class="item-header">
                <div>
                    <div class="item-name">${escapeHtml(item.nombre)}</div>
                    <span class="item-category">${escapeHtml(item.categoria)}</span>
                </div>
            </div>
            
            <div class="item-body">
                ${item.descripcion ? `<p class="item-description">${escapeHtml(item.descripcion)}</p>` : ''}
                
                <div class="item-details">
                    <div class="item-detail">
                        <span class="item-detail-label">Cantidad:</span>
                        <span class="item-detail-value">${item.cantidad}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Estado:</span>
                        <span class="item-status status-${getStatusClass(item.estado)}">${escapeHtml(item.estado)}</span>
                    </div>
                </div>
                
                ${item.ubicacion ? `
                    <div class="item-detail">
                        <span class="item-detail-label">📍 Ubicación:</span>
                        <span class="item-detail-value">${escapeHtml(item.ubicacion)}</span>
                    </div>
                ` : ''}
                
                <div class="item-date">
                    Registrado: ${formatDate(item.fecha_registro)}
                </div>
            </div>
            
            <div class="item-footer">
                <button class="btn-small btn-edit" onclick="editItem(${item.id})">
                    ✏️ Editar
                </button>
                <button class="btn-small btn-delete" onclick="deleteItem(${item.id})">
                    🗑️ Eliminar
                </button>
            </div>
        </div>
    `).join('');
}

// Manejar envío del formulario
async function handleSubmit(e) {
    e.preventDefault();
    
    const itemData = {
        nombre: document.getElementById('nombre').value.trim(),
        descripcion: document.getElementById('descripcion').value.trim(),
        categoria: document.getElementById('categoria').value,
        cantidad: parseInt(document.getElementById('cantidad').value) || 0,
        ubicacion: document.getElementById('ubicacion').value.trim(),
        estado: document.getElementById('estado').value
    };
    
    try {
        btnText.textContent = currentEditId ? 'Actualizando...' : 'Agregando...';
        hideError();
        
        if (currentEditId) {
            await updateItem(currentEditId, itemData);
        } else {
            await addItem(itemData);
        }
        
        itemForm.reset();
        currentEditId = null;
        btnText.textContent = 'Agregar Item';
        await loadItems();
        
    } catch (error) {
        console.error('Error:', error);
        showError('Error al guardar el item. Por favor, intenta de nuevo.');
        btnText.textContent = currentEditId ? 'Actualizar Item' : 'Agregar Item';
    }
}

// Agregar nuevo item
async function addItem(itemData) {
    const response = await fetch(`${API_URL}/add_item.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(itemData)
    });
    
    if (!response.ok) {
        throw new Error('Error al agregar item');
    }
    
    return await response.json();
}

// Actualizar item existente
async function updateItem(id, itemData) {
    const response = await fetch(`${API_URL}/update_item.php`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ...itemData, id })
    });
    
    if (!response.ok) {
        throw new Error('Error al actualizar item');
    }
    
    return await response.json();
}

// Editar item
function editItem(id) {
    const itemCard = document.querySelector(`.item-card[data-id="${id}"]`);
    if (!itemCard) return;
    
    currentEditId = id;
    
    const nombre = itemCard.querySelector('.item-name').textContent;
    const categoria = itemCard.querySelector('.item-category').textContent;
    const descripcion = itemCard.querySelector('.item-description')?.textContent || '';
    const cantidad = itemCard.querySelector('.item-detail-value').textContent;
    const ubicacionEl = itemCard.querySelector('.item-detail-label:nth-of-type(2)')?.nextElementSibling;
    const ubicacion = ubicacionEl ? ubicacionEl.textContent : '';
    const estadoEl = itemCard.querySelector('.item-status');
    const estado = estadoEl ? estadoEl.textContent : 'Activo';
    
    document.getElementById('nombre').value = nombre;
    document.getElementById('categoria').value = categoria;
    document.getElementById('descripcion').value = descripcion;
    document.getElementById('cantidad').value = parseInt(cantidad) || 0;
    document.getElementById('ubicacion').value = ubicacion;
    document.getElementById('estado').value = estado;
    
    btnText.textContent = 'Actualizar Item';
    document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' });
}

// Eliminar item
async function deleteItem(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este item?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_URL}/delete_item.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id })
        });
        
        if (!response.ok) {
            throw new Error('Error al eliminar item');
        }
        
        await loadItems();
        
    } catch (error) {
        console.error('Error:', error);
        showError('Error al eliminar el item. Por favor, intenta de nuevo.');
    }
}

// Funciones auxiliares
function showLoading(show) {
    loadingSpinner.style.display = show ? 'block' : 'none';
}

function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.add('show');
}

function hideError() {
    errorMessage.classList.remove('show');
}

function updateStats(total) {
    totalItemsSpan.textContent = total;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getStatusClass(estado) {
    const statusMap = {
        'Activo': 'activo',
        'En uso': 'uso',
        'Mantenimiento': 'mantenimiento',
        'Inactivo': 'inactivo'
    };
    return statusMap[estado] || 'activo';
}