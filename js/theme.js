// Funkcia pre prepínanie tmavého režimu
document.addEventListener('DOMContentLoaded', function() {
    console.log('Theme.js loaded');
    
    // Načítam aktuálne nastavenie z localStorage
    const currentTheme = localStorage.getItem('theme');
    console.log('Current theme from localStorage:', currentTheme);
    
    // Nastavím príslušný stav a vzhľad stránky
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        console.log('Dark mode activated on load');
    }
    
    // Pridám click handler pre všetko v theme-switch-wrapper
    document.addEventListener('click', function(e) {
        // Kontrolujem, či sme klikli na niečo súvisiace s prepínačom témy
        if (e.target.closest('.theme-switch-wrapper') || 
            e.target.classList.contains('mode-text') || 
            e.target.classList.contains('mode-icon') ||
            e.target.id === 'toggle-button-ui') {
            
            console.log('Theme switch element clicked:', e.target);
            switchTheme();
            e.preventDefault();
        }
    });
    
    // Inicializácia drag and drop pre úlohy, ak existujú
    if (typeof initDragAndDrop === 'function') {
        initDragAndDrop();
    }
    
    // Inicializácia navigačných tlačidiel pre úlohy, ak existujú
    if (typeof initTaskNavigation === 'function') {
        initTaskNavigation();
    }
});

// Funkcia na okamžitú aktualizáciu vizuálu slidera
function updateSliderVisuals(e) {
    const slider = e.target;
    const value = slider.value;
    
    if (parseInt(value) === 1) {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.body.classList.remove('dark-mode');
        document.documentElement.setAttribute('data-theme', 'light');
    }
}

// Funkcia pre inicializáciu drag and drop
function initDragAndDrop() {
    const draggables = document.querySelectorAll('.draggable');
    const containers = document.querySelectorAll('.task-container');
    
    // Pridáme event listenery na všetky draggable elementy
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging');
        });
        
        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
            
            // Získame ID projektu z URL
            const urlParams = new URLSearchParams(window.location.search);
            const projectId = urlParams.get('idProject');
            
            // Získame ID úlohy a nový status
            const taskId = draggable.getAttribute('data-task-id');
            const newStatus = draggable.parentElement.getAttribute('data-status');
            
            // Aktualizujeme status úlohy cez AJAX
            updateTaskStatus(taskId, newStatus, projectId);
        });
    });
    
    // Pridáme event listenery na kontajnery
    containers.forEach(container => {
        container.addEventListener('dragover', e => {
            e.preventDefault();
            container.classList.add('drag-over');
            const draggable = document.querySelector('.dragging');
            container.appendChild(draggable);
        });
        
        container.addEventListener('dragleave', () => {
            container.classList.remove('drag-over');
        });
        
        container.addEventListener('drop', () => {
            container.classList.remove('drag-over');
        });
    });
}

// Funkcia pre aktualizáciu statusu úlohy cez AJAX
function updateTaskStatus(taskId, newStatus, projectId) {
    // Vytvoríme form data
    const formData = new FormData();
    formData.append('id_task', taskId);
    formData.append('task_status', newStatus);
    formData.append('id_project', projectId);
    formData.append('update_status', true);
    
    // Pošleme AJAX request
    fetch('task_update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Zobrazíme potvrdenie
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Úloha aktualizovaná',
                showConfirmButton: false,
                timer: 800
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Funkcia pre inicializáciu navigačných tlačidiel pre úlohy
function initTaskNavigation() {
    const tasks = document.querySelectorAll('.task-item');
    const statuses = ["TO DO", "IN PROGRESS", "COMPLETE"];
    
    // Pridáme navigačné tlačidlá do každej úlohy
    tasks.forEach(task => {
        // Vytvoríme navigačné tlačidlá
        const navButtons = document.createElement('div');
        navButtons.className = 'task-nav-buttons';
        
        // Tlačidlo pre posun doľava
        const leftButton = document.createElement('button');
        leftButton.className = 'task-nav-button';
        leftButton.innerHTML = '<i class="fas fa-arrow-left"></i>';
        
        // Tlačidlo pre posun doprava
        const rightButton = document.createElement('button');
        rightButton.className = 'task-nav-button';
        rightButton.innerHTML = '<i class="fas fa-arrow-right"></i>';
        
        // Pridáme event listenery
        const currentStatus = parseInt(task.getAttribute('data-status'));
        
        // Deaktivujeme tlačidlo, ak sme na kraji
        if (currentStatus === 1) {
            leftButton.disabled = true;
        }
        
        if (currentStatus === 3) {
            rightButton.disabled = true;
        }
        
        // Pridáme funkcionalitu pre posun doľava
        leftButton.addEventListener('click', () => {
            if (currentStatus > 1) {
                // Získame údaje
                const taskId = task.getAttribute('data-task-id');
                const projectId = new URLSearchParams(window.location.search).get('idProject');
                
                // Vytvoríme formulár a pridáme doň údaje
                const form = document.createElement('form');
                form.method = 'post';
                form.style.display = 'none';
                
                const taskIdInput = document.createElement('input');
                taskIdInput.name = 'id_task_left';
                taskIdInput.value = taskId;
                
                const projectIdInput = document.createElement('input');
                projectIdInput.name = 'id_project_left';
                projectIdInput.value = projectId;
                
                const taskStatusInput = document.createElement('input');
                taskStatusInput.name = 'task_status';
                taskStatusInput.value = currentStatus;
                
                form.appendChild(taskIdInput);
                form.appendChild(projectIdInput);
                form.appendChild(taskStatusInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Pridáme funkcionalitu pre posun doprava
        rightButton.addEventListener('click', () => {
            if (currentStatus < 3) {
                // Získame údaje
                const taskId = task.getAttribute('data-task-id');
                const projectId = new URLSearchParams(window.location.search).get('idProject');
                
                // Vytvoríme formulár a pridáme doň údaje
                const form = document.createElement('form');
                form.method = 'post';
                form.style.display = 'none';
                
                const taskIdInput = document.createElement('input');
                taskIdInput.name = 'id_task_right';
                taskIdInput.value = taskId;
                
                const projectIdInput = document.createElement('input');
                projectIdInput.name = 'id_project_right';
                projectIdInput.value = projectId;
                
                const taskStatusInput = document.createElement('input');
                taskStatusInput.name = 'task_status';
                taskStatusInput.value = currentStatus;
                
                form.appendChild(taskIdInput);
                form.appendChild(projectIdInput);
                form.appendChild(taskStatusInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Pridáme tlačidlá do navigačného panelu
        navButtons.appendChild(leftButton);
        navButtons.appendChild(rightButton);
        
        // Pridáme navigačný panel do úlohy
        task.appendChild(navButtons);
    });
}

// Jednoduchá funkcia pre prepínanie témy
function switchTheme() {
    console.log('switchTheme called');
    
    if (document.body.classList.contains('dark-mode')) {
        // Prepnutie z tmavého na svetlý režim
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        console.log('Switched to light mode');
        document.documentElement.style.backgroundColor = '';
        console.log('Background color reset');
    } else {
        // Prepnutie zo svetlého na tmavý režim
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        console.log('Switched to dark mode');
        document.documentElement.style.backgroundColor = '#1f2937';
        console.log('Background color set to dark');
    }
} 