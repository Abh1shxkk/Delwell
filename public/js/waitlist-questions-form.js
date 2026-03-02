document.addEventListener('DOMContentLoaded', function() {
    const questionTextarea = document.getElementById('question_text');
    const charCount = document.getElementById('char-count');
    const questionTypeSelect = document.getElementById('question_type');
    const optionsContainer = document.getElementById('optionsContainer');
    const maxSelectionsContainer = document.getElementById('maxSelectionsContainer');
    const optionsList = document.getElementById('optionsList');
    const addOptionBtn = document.getElementById('addOption');

    // Character counter
    if (questionTextarea && charCount) {
        function updateCharCount() {
            const text = questionTextarea.value;
            charCount.textContent = text.length;
        }

        questionTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial update
    }

    // Question type change handler
    if (questionTypeSelect) {
        questionTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            
            // Show/hide options container
            if (selectedType === 'radio' || selectedType === 'checkbox') {
                optionsContainer.style.display = 'block';
            } else {
                optionsContainer.style.display = 'none';
            }
            
            // Show/hide max selections for checkbox
            if (selectedType === 'checkbox') {
                maxSelectionsContainer.style.display = 'block';
            } else {
                maxSelectionsContainer.style.display = 'none';
            }
        });
        
        // Trigger on page load
        questionTypeSelect.dispatchEvent(new Event('change'));
    }

    // Add option button
    if (addOptionBtn) {
        addOptionBtn.addEventListener('click', function() {
            const optionCount = optionsList.querySelectorAll('.option-item').length;
            const newOption = document.createElement('div');
            newOption.className = 'input-group mb-2 option-item';
            newOption.innerHTML = `
                <input type="text" class="form-control" name="options[]" placeholder="Option ${optionCount + 1}">
                <button type="button" class="btn btn-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            `;
            optionsList.appendChild(newOption);
            updateRemoveButtons();
        });
    }

    // Remove option button (event delegation)
    if (optionsList) {
        optionsList.addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                const optionItem = e.target.closest('.option-item');
                optionItem.remove();
                updateRemoveButtons();
                updateOptionPlaceholders();
            }
        });
    }

    // Update remove buttons state
    function updateRemoveButtons() {
        const options = optionsList.querySelectorAll('.option-item');
        options.forEach((option, index) => {
            const removeBtn = option.querySelector('.remove-option');
            if (removeBtn) {
                removeBtn.disabled = (options.length === 1);
            }
        });
    }

    // Update option placeholders
    function updateOptionPlaceholders() {
        const options = optionsList.querySelectorAll('.option-item input');
        options.forEach((input, index) => {
            input.placeholder = `Option ${index + 1}`;
        });
    }

    // Auto-generate field name from question text
    const fieldNameInput = document.getElementById('field_name');
    if (questionTextarea && fieldNameInput && !fieldNameInput.value) {
        let isManuallyEdited = false;
        
        fieldNameInput.addEventListener('input', function() {
            isManuallyEdited = true;
        });
        
        questionTextarea.addEventListener('input', function() {
            if (!isManuallyEdited) {
                const text = this.value.trim();
                const fieldName = text
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .substring(0, 50);
                fieldNameInput.value = fieldName;
            }
        });
    }

    // Initialize remove buttons state on page load
    updateRemoveButtons();
});
