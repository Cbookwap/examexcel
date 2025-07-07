// AI Model configurations
const aiModels = {
    openai: [
        { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo (Recommended)' },
        { value: 'gpt-4', label: 'GPT-4 (Premium)' },
        { value: 'gpt-4-turbo', label: 'GPT-4 Turbo (Latest)' }
    ],
    gemini: [
        { value: 'gemini-1.5-flash', label: 'Gemini 1.5 Flash (Free, Fast)' },
        { value: 'gemini-1.5-pro', label: 'Gemini 1.5 Pro (Free, Better)' },
        { value: 'gemini-pro', label: 'Gemini Pro (Legacy)' }
    ],
    claude: [
        { value: 'claude-3-haiku-20240307', label: 'Claude 3 Haiku (Fast)' },
        { value: 'claude-3-sonnet-20240229', label: 'Claude 3 Sonnet (Balanced)' },
        { value: 'claude-3-opus-20240229', label: 'Claude 3 Opus (Best)' }
    ],
    groq: [
        { value: 'llama3-8b-8192', label: 'Llama 3 8B (Free, Fast)' },
        { value: 'llama3-70b-8192', label: 'Llama 3 70B (Free, Better)' },
        { value: 'mixtral-8x7b-32768', label: 'Mixtral 8x7B (Free, Good)' }
    ],
    huggingface: [
        { value: 'microsoft/DialoGPT-large', label: 'DialoGPT Large (Free)' },
        { value: 'microsoft/DialoGPT-medium', label: 'DialoGPT Medium (Free)' },
        { value: 'facebook/blenderbot-400M-distill', label: 'BlenderBot (Free)' }
    ]
};

// Provider information content
const providerInfoContent = {
    'openai': `
        <div class="alert alert-info">
            <h6><i class="material-symbols-rounded me-2">info</i>OpenAI API Setup</h6>
            <p><strong>Cost:</strong> Paid service (requires billing setup)</p>
            <p><strong>Get API Key:</strong> <a href="https://platform.openai.com/api-keys" target="_blank">https://platform.openai.com/api-keys</a></p>
        </div>
    `,
    'gemini': `
        <div class="alert alert-success">
            <h6><i class="material-symbols-rounded me-2">info</i>Google Gemini API Setup</h6>
            <p><strong>Cost:</strong> FREE with generous limits ⭐</p>
            <p><strong>Get API Key:</strong> <a href="https://makersuite.google.com/app/apikey" target="_blank">https://makersuite.google.com/app/apikey</a></p>
        </div>
    `,
    'claude': `
        <div class="alert alert-info">
            <h6><i class="material-symbols-rounded me-2">info</i>Anthropic Claude API Setup</h6>
            <p><strong>Cost:</strong> Paid service</p>
            <p><strong>Get API Key:</strong> <a href="https://console.anthropic.com/" target="_blank">https://console.anthropic.com/</a></p>
        </div>
    `,
    'groq': `
        <div class="alert alert-success">
            <h6><i class="material-symbols-rounded me-2">info</i>Groq API Setup</h6>
            <p><strong>Cost:</strong> FREE with high speed ⭐</p>
            <p><strong>Get API Key:</strong> <a href="https://console.groq.com/keys" target="_blank">https://console.groq.com/keys</a></p>
        </div>
    `,
    'huggingface': `
        <div class="alert alert-success">
            <h6><i class="material-symbols-rounded me-2">info</i>Hugging Face API Setup</h6>
            <p><strong>Cost:</strong> FREE</p>
            <p><strong>Get API Key:</strong> <a href="https://huggingface.co/settings/tokens" target="_blank">https://huggingface.co/settings/tokens</a></p>
        </div>
    `
};

$(document).ready(function() {
    console.log('AI Question Generator JavaScript loaded');
    console.log('aiModels:', aiModels);

    // Initialize the AI Question Generator
    initializeAIGenerator();
    initializeProviderSelection();

    // Test if elements exist
    setTimeout(function() {
        console.log('Elements check:');
        console.log('Provider select:', document.getElementById('provider'));
        console.log('Model select div:', document.getElementById('modelSelect'));
        console.log('Model dropdown:', document.getElementById('model'));
        console.log('Provider info:', document.getElementById('providerInfo'));

        // Test manual trigger
        const providerSelect = document.getElementById('provider');
        if (providerSelect) {
            console.log('Adding test click handler to provider select');
            providerSelect.addEventListener('click', function() {
                console.log('Provider select clicked, current value:', this.value);
            });
        }

        // Add global test function
        window.testProviderChange = function(provider) {
            console.log('Manual test with provider:', provider || 'groq');
            const providerSelect = document.getElementById('provider');
            if (providerSelect) {
                providerSelect.value = provider || 'groq';
                providerSelect.dispatchEvent(new Event('change'));
            }
        };

        console.log('You can test manually by running: testProviderChange("groq") in the console');
    }, 1000);
});

function initializeAIGenerator() {
    // Update total questions count
    updateTotalQuestions();

    // Event listeners
    $('.question-count').on('input', updateTotalQuestions);
    $('#ai-generation-form').on('submit', generateQuestions);
    $('#reset-form').on('click', resetForm);
    $('#save-all-questions').on('click', saveAllQuestions);

    // Provider change is handled in initializeProviderSelection()

    // API key toggle
    $('#toggle-api-key').on('click', function() {
        const apiKeyInput = $('#api_key');
        const icon = $(this).find('i');

        if (apiKeyInput.attr('type') === 'password') {
            apiKeyInput.attr('type', 'text');
            icon.text('visibility_off');
        } else {
            apiKeyInput.attr('type', 'password');
            icon.text('visibility');
        }
    });

    // Test AI connection
    $('#test-ai-connection').on('click', function() {
        testAIConnection();
    });
}

function initializeProviderSelection() {
    console.log('initializeProviderSelection called');

    const aiModelProvider = document.getElementById('provider');
    const aiModel = document.getElementById('model');
    const providerInfo = document.getElementById('providerInfo');

    if (!aiModelProvider || !aiModel || !providerInfo) {
        console.error('Required elements not found');
        return;
    }

    // Handle provider change - exact copy from settings page
    aiModelProvider.addEventListener('change', function() {
        const provider = this.value;
        console.log('Provider changed to:', provider);

        if (provider && aiModels[provider]) {
            aiModel.innerHTML = '<option value="">Select Model</option>';
            aiModels[provider].forEach(model => {
                const option = document.createElement('option');
                option.value = model.value;
                option.textContent = model.label;
                aiModel.appendChild(option);
            });

            // Show provider info
            if (providerInfoContent[provider]) {
                providerInfo.innerHTML = providerInfoContent[provider];
                providerInfo.style.display = 'block';
            }

            console.log('Models populated:', aiModels[provider].length);
        } else {
            aiModel.innerHTML = '<option value="">Select Model</option>';
            providerInfo.style.display = 'none';
            console.log('Provider cleared');
        }
    });
}



function updateTotalQuestions() {
    let total = 0;
    $('.question-count').each(function() {
        const value = parseInt($(this).val()) || 0;
        total += value;
    });

    $('#total-questions').text(total);

    // Validate maximum
    if (total > 50) {
        $('#total-questions').parent().removeClass('alert-info').addClass('alert-warning');
        $('#total-questions').parent().find('small').text('Warning: Maximum 50 questions recommended');
    } else if (total === 0) {
        $('#total-questions').parent().removeClass('alert-info alert-warning').addClass('alert-danger');
        $('#total-questions').parent().find('small').text('Please specify at least one question type');
    } else {
        $('#total-questions').parent().removeClass('alert-warning alert-danger').addClass('alert-info');
        $('#total-questions').parent().find('small').text('Maximum 50 questions per generation');
    }
}

function updateApiHelpLink() {
    const provider = document.getElementById('provider').value;
    const helpLinks = {
        'groq': 'https://console.groq.com/keys',
        'gemini': 'https://makersuite.google.com/app/apikey',
        'openai': 'https://platform.openai.com/api-keys',
        'claude': 'https://console.anthropic.com/',
        'huggingface': 'https://huggingface.co/settings/tokens'
    };

    const link = document.getElementById('api-help-link');
    if (link && helpLinks[provider]) {
        link.href = helpLinks[provider];
        link.textContent = 'How to get ' + provider.toUpperCase() + ' API key?';
    } else if (link) {
        link.href = '#';
        link.textContent = 'How to get API key?';
    }
}

function testAIConnection() {
    const provider = $('#provider').val();
    const model = $('#model').val();
    const apiKey = $('#api_key').val();
    const $resultDiv = $('#ai-test-result');
    const $button = $('#test-ai-connection');

    if (!provider || !model || !apiKey) {
        $resultDiv.html('<div class="alert alert-warning alert-sm">Please fill in all AI configuration fields first.</div>');
        $resultDiv.show();
        return;
    }

    $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Testing...');

    $.ajax({
        url: (window.CBT && window.CBT.baseUrl ? window.CBT.baseUrl : '') + 'admin/test-ai-connection',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: JSON.stringify({
            provider: provider,
            model: model,
            api_key: apiKey
        }),
        success: function(response) {
            if (response.success) {
                $resultDiv.html('<div class="alert alert-success alert-sm"><i class="material-symbols-rounded me-2">check_circle</i>' + response.message + '</div>');
            } else {
                $resultDiv.html('<div class="alert alert-danger alert-sm"><i class="material-symbols-rounded me-2">error</i>' + response.message + '</div>');
            }
            $resultDiv.show();
        },
        error: function(xhr, status, error) {
            let errorMessage = 'Connection test failed';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            $resultDiv.html('<div class="alert alert-danger alert-sm"><i class="material-symbols-rounded me-2">error</i>' + errorMessage + '</div>');
            $resultDiv.show();
        },
        complete: function() {
            $button.prop('disabled', false).html('<i class="material-symbols-rounded me-2">wifi_tethering</i>Test AI Connection');
        }
    });
}

function updateApiHelpLink() {
    const provider = $('#provider').val();
    const helpLinks = {
        'groq': 'https://console.groq.com/keys',
        'gemini': 'https://makersuite.google.com/app/apikey',
        'openai': 'https://platform.openai.com/api-keys',
        'huggingface': 'https://huggingface.co/settings/tokens'
    };
    
    const helpTexts = {
        'groq': 'Get free Groq API key (Recommended)',
        'gemini': 'Get free Gemini API key',
        'openai': 'Get OpenAI API key',
        'huggingface': 'Get HuggingFace API key'
    };
    
    $('#api-help-link').attr('href', helpLinks[provider] || '#');
    $('#api-help-link').text(helpTexts[provider] || 'How to get API key?');
}

function toggleApiKeyVisibility() {
    const apiKeyInput = $('#api_key');
    const toggleBtn = $('#toggle-api-key i');
    
    if (apiKeyInput.attr('type') === 'password') {
        apiKeyInput.attr('type', 'text');
        toggleBtn.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        apiKeyInput.attr('type', 'password');
        toggleBtn.removeClass('fa-eye-slash').addClass('fa-eye');
    }
}

function generateQuestions(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Show loading modal using Bootstrap 5 syntax
    let loadingModal;
    try {
        loadingModal = new bootstrap.Modal(document.getElementById('loading-modal'));
        loadingModal.show();
    } catch (err) {
        // Fallback for older Bootstrap versions
        $('#loading-modal').modal('show');
    }
    
    // Disable generate button
    $('#generate-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
    
    // Prepare form data
    const formData = new FormData($('#ai-generation-form')[0]);
    
    // Make AJAX request
    $.ajax({
        url: (window.CBT && window.CBT.baseUrl ? window.CBT.baseUrl : '') + 'admin/ai-generator/generate',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        timeout: 120000, // 2 minutes timeout
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            try {
                if (loadingModal) {
                    loadingModal.hide();
                } else {
                    $('#loading-modal').modal('hide');
                }
            } catch (err) {
                $('#loading-modal').modal('hide');
            }
            $('#generate-btn').prop('disabled', false).html('<i class="fas fa-magic"></i> Generate Questions');
            
            if (response.success) {
                displayGeneratedQuestions(response);
                showAlert('success', `Successfully generated ${response.questions.length} questions!`);
            } else {
                showAlert('error', response.message || 'Failed to generate questions');
            }
        },
        error: function(xhr, status, error) {
            try {
                if (loadingModal) {
                    loadingModal.hide();
                } else {
                    $('#loading-modal').modal('hide');
                }
            } catch (err) {
                $('#loading-modal').modal('hide');
            }
            $('#generate-btn').prop('disabled', false).html('<i class="fas fa-magic"></i> Generate Questions');
            
            let errorMessage = 'Failed to generate questions';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (status === 'timeout') {
                errorMessage = 'Request timed out. Please try again with fewer questions.';
            } else if (xhr.status === 0) {
                errorMessage = 'Network error. Please check your connection.';
            }
            
            showAlert('error', errorMessage);
        }
    });
}

function validateForm() {
    // Check required fields
    const subject = $('#subject').val();
    const classId = $('#class').val();
    const topic = $('#topic').val().trim();
    const provider = $('#provider').val();
    const model = $('#model').val();
    const apiKey = $('#api_key').val().trim();

    if (!subject) {
        showAlert('error', 'Please select a subject');
        $('#subject').focus();
        return false;
    }

    if (!classId) {
        showAlert('error', 'Please select a class');
        $('#class').focus();
        return false;
    }

    if (!topic) {
        showAlert('error', 'Please enter a topic');
        $('#topic').focus();
        return false;
    }

    if (!provider) {
        showAlert('error', 'Please select an AI provider');
        $('#provider').focus();
        return false;
    }

    if (!model) {
        showAlert('error', 'Please select an AI model');
        $('#model').focus();
        return false;
    }

    if (!apiKey) {
        showAlert('error', 'Please enter your API key');
        $('#api_key').focus();
        return false;
    }
    
    // Check if at least one question type is selected
    let hasQuestions = false;
    $('.question-count').each(function() {
        if (parseInt($(this).val()) > 0) {
            hasQuestions = true;
            return false;
        }
    });
    
    if (!hasQuestions) {
        showAlert('error', 'Please specify at least one question type with quantity > 0');
        return false;
    }
    
    // Check total questions limit
    const total = parseInt($('#total-questions').text());
    if (total > 50) {
        showAlert('warning', 'Maximum 50 questions recommended. Continue anyway?');
        return confirm('You have selected ' + total + ' questions. This may take longer to generate. Continue?');
    }
    
    return true;
}

function displayGeneratedQuestions(response) {
    const container = $('#questions-container');
    container.empty();
    
    // Store questions data for saving
    window.generatedQuestions = response.questions;
    window.questionMetadata = {
        subject_id: response.subject_id,
        class_id: response.class_id,
        subject_name: response.subject_name,
        class_name: response.class_name
    };
    
    // Create questions HTML
    let html = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <strong>Generation Complete!</strong> 
            Generated ${response.questions.length} questions for 
            <strong>${response.subject_name}</strong> - <strong>${response.class_name}</strong>
        </div>
    `;
    
    response.questions.forEach((question, index) => {
        html += createQuestionHTML(question, index);
    });
    
    container.html(html);
    $('#questions-preview').show();
    
    // Scroll to questions
    $('html, body').animate({
        scrollTop: $('#questions-preview').offset().top - 100
    }, 500);
}

function createQuestionHTML(question, index) {
    const questionNumber = index + 1;
    const questionTypeLabel = getQuestionTypeLabel(question.type);
    
    let html = `
        <div class="card mb-3 question-card" data-index="${index}">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <span class="badge badge-primary mr-2">${questionNumber}</span>
                    <span class="badge badge-info mr-2">${questionTypeLabel}</span>
                    Question ${questionNumber}
                </h6>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question" data-index="${index}">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label><strong>Question:</strong></label>
                    <textarea class="form-control question-text" rows="2">${question.question}</textarea>
                </div>
    `;
    
    // Add options for MCQ
    if (question.type === 'mcq' && question.options) {
        html += '<div class="form-group"><label><strong>Options:</strong></label>';
        question.options.forEach((option, optIndex) => {
            const letter = String.fromCharCode(65 + optIndex); // A, B, C, D
            html += `
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text">${letter})</span>
                    </div>
                    <input type="text" class="form-control option-input" value="${option.replace(/^[A-D]\)\s*/, '')}" data-option="${letter.toLowerCase()}">
                </div>
            `;
        });
        html += '</div>';
    }
    
    // Add correct answer
    html += `
                <div class="form-group">
                    <label><strong>Correct Answer:</strong></label>
                    <input type="text" class="form-control correct-answer" value="${question.correct_answer}">
                </div>
    `;
    
    // Add explanation if available
    if (question.explanation) {
        html += `
                <div class="form-group">
                    <label><strong>Explanation:</strong></label>
                    <textarea class="form-control explanation" rows="2">${question.explanation}</textarea>
                </div>
        `;
    }
    
    html += `
            </div>
        </div>
    `;
    
    return html;
}

function getQuestionTypeLabel(type) {
    const labels = {
        'mcq': 'Multiple Choice',
        'true_false': 'True/False',
        'yes_no': 'Yes/No',
        'short_answer': 'Short Answer',
        'essay': 'Essay',
        'fill_blank': 'Fill in the Blank'
    };
    return labels[type] || type;
}

function saveAllQuestions() {
    if (!window.generatedQuestions || !window.questionMetadata) {
        showAlert('error', 'No questions to save');
        return;
    }
    
    // Update questions with any edits made by user
    updateQuestionsFromForm();
    
    // Show loading
    $('#save-all-questions').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    $.ajax({
        url: (window.CBT && window.CBT.baseUrl ? window.CBT.baseUrl : '') + 'admin/ai-generator/save-questions',
        type: 'POST',
        data: {
            questions: JSON.stringify(window.generatedQuestions),
            subject_id: window.questionMetadata.subject_id,
            class_id: window.questionMetadata.class_id
        },
        success: function(response) {
            $('#save-all-questions').prop('disabled', false).html('<i class="fas fa-save"></i> Save All Questions');

            if (response.success) {
                showAlert('success', response.message);
                // Optionally redirect to questions page
                setTimeout(() => {
                    window.location.href = (window.CBT && window.CBT.baseUrl ? window.CBT.baseUrl : '') + 'questions';
                }, 2000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function() {
            $('#save-all-questions').prop('disabled', false).html('<i class="fas fa-save"></i> Save All Questions');
            showAlert('error', 'Failed to save questions');
        }
    });
}

function updateQuestionsFromForm() {
    $('.question-card').each(function() {
        const index = $(this).data('index');
        const question = window.generatedQuestions[index];
        
        if (question) {
            question.question = $(this).find('.question-text').val();
            question.correct_answer = $(this).find('.correct-answer').val();
            
            if ($(this).find('.explanation').length) {
                question.explanation = $(this).find('.explanation').val();
            }
            
            // Update options for MCQ
            if (question.type === 'mcq') {
                question.options = [];
                $(this).find('.option-input').each(function() {
                    const letter = $(this).data('option').toUpperCase();
                    question.options.push(`${letter}) ${$(this).val()}`);
                });
            }
        }
    });
}

function resetForm() {
    if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
        $('#ai-generation-form')[0].reset();
        $('#questions-preview').hide();
        $('.question-count').trigger('input'); // Update total
        showAlert('info', 'Form has been reset');
    }
}

function showAlert(type, message) {
    const alertClass = type === 'error' ? 'alert-danger' : `alert-${type}`;
    const iconClass = type === 'error' ? 'fa-exclamation-circle' : 
                     type === 'success' ? 'fa-check-circle' : 
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${iconClass} mr-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').not('.alert-info').remove();
    
    // Add new alert at top of page
    $('body').prepend(alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        $('.alert').not('.alert-info').fadeOut();
    }, 5000);
}

// Remove question functionality
$(document).on('click', '.remove-question', function() {
    const index = $(this).data('index');
    if (confirm('Are you sure you want to remove this question?')) {
        $(this).closest('.question-card').remove();
        // Remove from array
        if (window.generatedQuestions) {
            window.generatedQuestions.splice(index, 1);
        }
        showAlert('info', 'Question removed');
    }
});

// Base URL for AJAX requests - Use global CBT configuration for proper routing
const baseUrl = (window.CBT && window.CBT.baseUrl ? window.CBT.baseUrl : '') + 'admin/';
