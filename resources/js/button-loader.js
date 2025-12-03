// resources/js/button-loader.js

class ButtonLoader {
    constructor() {
        this.spinnerSVG = `
            <svg class="btn-loader-spinner" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
    }
    
    init() {
        this.processExistingButtons();
        this.setupMutationObserver();
        console.log('✅ ButtonLoader initialisé avec succès!');
    }
    
    processExistingButtons() {
        // Sélectionner tous les boutons
        const buttons = document.querySelectorAll('button');
        
        buttons.forEach(button => {
            // Vérifier si le bouton a un attribut wire:click
            // Nous devons vérifier manuellement car querySelector avec backslash ne fonctionne pas bien
            const attributes = button.attributes;
            let hasWireClick = false;
            
            for (let i = 0; i < attributes.length; i++) {
                if (attributes[i].name.startsWith('wire:click')) {
                    hasWireClick = true;
                    break;
                }
            }
            
            if (hasWireClick && !button.classList.contains('btn-enhanced')) {
                this.enhanceButton(button);
            }
        });
    }
    
    enhanceButton(button) {
        // Ajouter la classe pour le CSS
        button.classList.add('btn-enhanced');
        
        // Le JavaScript de Livewire gérera wire:loading.attr et wire:loading.class
        // Nous allons juste nous assurer qu'ils sont présents
        if (!button.hasAttribute('wire:loading.attr')) {
            button.setAttribute('wire:loading.attr', 'disabled');
        }
        if (!button.hasAttribute('wire:loading.class')) {
            button.setAttribute('wire:loading.class', 'btn-livewire-loading');
        }
        
        // Récupérer l'attribut wire:click
        let wireClick = '';
        for (let i = 0; i < button.attributes.length; i++) {
            if (button.attributes[i].name.startsWith('wire:click')) {
                wireClick = button.attributes[i].value;
                break;
            }
        }
        
        if (wireClick) {
            // Vérifier si le bouton a déjà un contenu de loading
            const hasLoadingContent = button.innerHTML.includes('wire:loading');
            
            if (!hasLoadingContent) {
                const originalHTML = button.innerHTML;
                const loadingText = this.getLoadingText(button, wireClick);
                
                button.innerHTML = `
                    <span wire:loading.remove wire:target="${wireClick}" class="btn-original-content">
                        ${originalHTML}
                    </span>
                    <span wire:loading wire:target="${wireClick}" class="btn-loading-content">
                        ${this.spinnerSVG}
                        <span class="btn-loading-text">${loadingText}</span>
                    </span>
                `;
            }
        }
    }
    
    getLoadingText(button, wireClick) {
        // Priorité 1: Attribut data-loading-text
        const customText = button.getAttribute('data-loading-text');
        if (customText) return customText;
        
        // Priorité 2: Basé sur le texte du bouton
        const buttonText = button.textContent.toLowerCase();
        
        if (buttonText.includes('enregistrer') || buttonText.includes('save')) return 'Enregistrement...';
        if (buttonText.includes('supprimer') || buttonText.includes('delete')) return 'Suppression...';
        if (buttonText.includes('exporter') || buttonText.includes('export')) return 'Export en cours...';
        if (buttonText.includes('importer') || buttonText.includes('import')) return 'Import en cours...';
        if (buttonText.includes('générer') || buttonText.includes('generate')) return 'Génération...';
        if (buttonText.includes('télécharger') || buttonText.includes('download')) return 'Téléchargement...';
        
        // Par défaut
        return 'Traitement...';
    }
    
    setupMutationObserver() {
        // Observer l'ajout de nouveaux éléments dans le DOM
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        // Si c'est un bouton
                        if (node.tagName === 'BUTTON') {
                            this.checkAndEnhanceButton(node);
                        }
                        
                        // Chercher des boutons dans l'élément ajouté
                        if (node.querySelectorAll) {
                            const buttons = node.querySelectorAll('button');
                            buttons.forEach(button => {
                                this.checkAndEnhanceButton(button);
                            });
                        }
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    checkAndEnhanceButton(button) {
        // Vérifier si le bouton a wire:click
        let hasWireClick = false;
        for (let i = 0; i < button.attributes.length; i++) {
            if (button.attributes[i].name.startsWith('wire:click')) {
                hasWireClick = true;
                break;
            }
        }
        
        if (hasWireClick && !button.classList.contains('btn-enhanced')) {
            this.enhanceButton(button);
        }
    }
}

// Initialisation
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initButtonLoader);
} else {
    initButtonLoader();
}

function initButtonLoader() {
    const loader = new ButtonLoader();
    loader.init();
    
    // Support pour Livewire
    if (window.Livewire) {
        Livewire.hook('morph.added', ({ el }) => {
            setTimeout(() => {
                loader.init();
            }, 10);
        });
        
        Livewire.hook('navigate.finish', () => {
            setTimeout(() => {
                loader.init();
            }, 100);
        });
    }
}