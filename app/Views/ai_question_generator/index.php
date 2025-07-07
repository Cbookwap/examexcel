<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('css') ?>
<style>
    .ai-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .ai-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(var(--primary-color-rgb), 0.4);
    }
	  .section-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 1.5rem 1.5rem 1.5rem;
        border-radius: 15px 15px 0;
    }
    .section-header h5 {
        margin: 0;
        font-weight: 600;
    }
    .btn-outline-secondary {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .question-type-card {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .question-type-card:hover {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }
    .total-questions {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        font-weight: bold;
    }
    .material-symbols-rounded {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block !important;
        line-height: 1 !important;
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">AI Question Generator</h4>
                <p class="text-muted mb-0">Generate questions automatically using AI technology</p>
            </div>
        </div>
    </div>
</div>
<form id="ai-generation-form">
    <div class="row">
        <!-- Left Column - Basic Information -->
        <div class="col-md-6">
            <div class="card ai-card">
                <div class="section-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="material-symbols-rounded me-2">info</i>Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject" name="subject" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
                        <select class="form-select" id="class" name="class" required>
                            <option value="">Select Class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= esc($class['display_name'] ?? $class['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="topic" class="form-label">Topic <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="topic" name="topic"
                               placeholder="e.g., Photosynthesis, Algebra, World War II" required>
                        <div class="form-text">Main topic for question generation</div>
                    </div>

                    <div class="mb-3">
                        <label for="subtopics" class="form-label">Subtopics (Optional)</label>
                        <textarea class="form-control" id="subtopics" name="subtopics" rows="3"
                                  placeholder="Separate multiple subtopics with commas&#10;e.g., Light reactions, Calvin cycle, Chloroplast structure"></textarea>
                        <div class="form-text">Specific areas to focus on within the main topic</div>
                    </div>

                    <div class="mb-3">
                        <label for="reference_links" class="form-label">Reference Links (Optional)</label>
                        <textarea class="form-control" id="reference_links" name="reference_links" rows="2"
                                  placeholder="Paste any reference URLs or materials here"></textarea>
                        <div class="form-text">Any reference materials for AI to consider</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - AI Configuration & Question Types -->
        <div class="col-md-6">
            <div class="card ai-card">
                <div class="section-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="material-symbols-rounded me-2">settings</i>AI Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">AI Model Provider <span class="text-danger">*</span></label>
                        <select name="provider" class="form-select" id="aiModelProvider" required>
                            <option value="">Select AI Provider</option>
                            <option value="openai">OpenAI (GPT-3.5/GPT-4) - Paid</option>
                            <option value="gemini">Google Gemini - FREE ⭐</option>
                            <option value="claude">Anthropic Claude - Paid</option>
                            <option value="groq">Groq - FREE ⭐</option>
                            <option value="huggingface">Hugging Face - FREE</option>
                        </select>
                        <div id="providerInfo" class="mt-2" style="display: none;">
                            <!-- Provider information will be shown here -->
                        </div>
                    </div>

                    <div class="mb-3" id="aiModelSelect">
                        <label class="form-label">AI Model <span class="text-danger">*</span></label>
                        <select name="model" class="form-select" id="aiModel" required>
                            <option value="">Select Model</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="api_key" name="api_key"
                                   placeholder="Enter your API key" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggle-api-key">
                                <i class="material-symbols-rounded">visibility</i>
                            </button>
                        </div>
                        <div class="form-text">
                            <a href="#" id="api-help-link" target="_blank">How to get API key?</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-info" id="test-ai-connection">
                            <i class="material-symbols-rounded me-2">wifi_tethering</i>Test AI Connection
                        </button>
                        <div id="ai-test-result" class="mt-2" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Types Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card ai-card">
                <div class="section-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="material-symbols-rounded me-2">quiz</i>Question Types & Quantities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="question-type-card">
                                <label for="mcq_count" class="form-label fw-bold">Multiple Choice (MCQ)</label>
                                <input type="number" class="form-control question-count" id="mcq_count"
                                       name="mcq_count" min="0" max="20" value="5">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="question-type-card">
                                <label for="true_false_count" class="form-label fw-bold">True/False</label>
                                <input type="number" class="form-control question-count" id="true_false_count"
                                       name="true_false_count" min="0" max="20" value="3">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="question-type-card">
                                <label for="yes_no_count" class="form-label fw-bold">Yes/No</label>
                                <input type="number" class="form-control question-count" id="yes_no_count"
                                       name="yes_no_count" min="0" max="20" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="question-type-card">
                                <label for="short_answer_count" class="form-label fw-bold">Short Answer</label>
                                <input type="number" class="form-control question-count" id="short_answer_count"
                                       name="short_answer_count" min="0" max="20" value="2">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="question-type-card">
                                <label for="essay_count" class="form-label fw-bold">Essay</label>
                                <input type="number" class="form-control question-count" id="essay_count"
                                       name="essay_count" min="0" max="10" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="question-type-card">
                                <label for="fill_blank_count" class="form-label fw-bold">Fill in the Blank</label>
                                <input type="number" class="form-control question-count" id="fill_blank_count"
                                       name="fill_blank_count" min="0" max="20" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="total-questions">
                                <i class="material-symbols-rounded me-2">calculate</i>
                                <div>Total Questions</div>
                                <div class="fs-2" id="total-questions">10</div>
                                <small>Maximum 50 questions per generation</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg me-3" id="generate-btn">
                    <i class="material-symbols-rounded me-2">auto_awesome</i>Generate Questions
                </button>
                <button type="button" class="btn btn-outline-secondary btn-lg" id="reset-form">
                    <i class="material-symbols-rounded me-2">refresh</i>Reset Form
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Generated Questions Preview -->
<div class="row mt-4" id="questions-preview" style="display: none;">
    <div class="col-12">
        <div class="card ai-card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="material-symbols-rounded me-2">preview</i>Generated Questions Preview
                </h5>
                <div class="float-end">
                    <button type="button" class="btn btn-light btn-sm me-2" id="save-all-questions">
                        <i class="material-symbols-rounded me-1">save</i>Save All Questions
                    </button>
                    <button type="button" class="btn btn-outline-light btn-sm" id="edit-questions">
                        <i class="material-symbols-rounded me-1">edit</i>Edit Questions
                    </button>
                </div>
            </div>
            <div class="card-body" id="questions-container">
                <!-- Generated questions will be displayed here -->
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loading-modal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mb-3">Generating Questions...</h5>
                <p class="text-muted mb-4">This may take a few moments. Please wait.</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                         role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>


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
    console.log('AI Question Generator loaded');

    // Test if elements exist immediately
    console.log('aiModelProvider element:', document.getElementById('aiModelProvider'));
    console.log('aiModel element:', document.getElementById('aiModel'));

    // Initialize AI settings - exact copy from settings page
    initializeAISettings();

    // Initialize other functionality
    initializeQuestionGenerator();
});

function initializeAISettings() {
    const aiModelProvider = document.getElementById('aiModelProvider');
    const aiModelSelect = document.getElementById('aiModelSelect');
    const aiModel = document.getElementById('aiModel');
    const toggleApiKey = document.getElementById('toggle-api-key');
    const aiApiKey = document.getElementById('api_key');

    // AI Model configurations - EXACT copy from settings page
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
            { value: 'claude-3-haiku', label: 'Claude 3 Haiku (Fast)' },
            { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet (Balanced)' },
            { value: 'claude-3-opus', label: 'Claude 3 Opus (Best)' }
        ],
        groq: [
            { value: 'llama-3.1-8b-instant', label: 'Llama 3.1 8B' },
            { value: 'llama-3.3-70b-versatile', label: 'Llama 3.3 70B' },
            { value: 'llama-3.3-70b-specdec', label: 'Llama 3.3 Specdec 70B' }
        ],
        huggingface: [
            { value: 'microsoft/DialoGPT-medium', label: 'DialoGPT Medium (Free)' },
            { value: 'facebook/blenderbot-400M-distill', label: 'BlenderBot (Free)' }
        ]
    };

    console.log('Elements found:', {
        aiModelProvider: !!aiModelProvider,
        aiModelSelect: !!aiModelSelect,
        aiModel: !!aiModel,
        toggleApiKey: !!toggleApiKey,
        apiKey: !!aiApiKey
    });

    console.log('aiModels defined:', Object.keys(aiModels));

    if (!aiModelProvider || !aiModelSelect || !aiModel) {
        console.error('Required elements not found');
        return;
    }

    // Handle provider change - EXACT copy from settings page
    aiModelProvider.addEventListener('change', function() {
        const provider = this.value;
        const providerInfo = document.getElementById('providerInfo');

        console.log('=== PROVIDER CHANGE EVENT ===');
        console.log('Provider changed to:', provider);
        console.log('aiModels object:', aiModels);
        console.log('aiModels[provider]:', aiModels[provider]);
        console.log('aiModel element:', aiModel);

        if (provider && aiModels[provider]) {
            console.log('Provider found in aiModels, populating dropdown...');
            aiModel.innerHTML = '<option value="">Select Model</option>';

            aiModels[provider].forEach((model, index) => {
                console.log(`Adding model ${index}:`, model);
                const option = document.createElement('option');
                option.value = model.value;
                option.textContent = model.label;
                aiModel.appendChild(option);
            });

            console.log('Final aiModel innerHTML:', aiModel.innerHTML);
            console.log('Added', aiModels[provider].length, 'models to dropdown');

            // Show provider info
            if (providerInfoContent[provider]) {
                providerInfo.innerHTML = providerInfoContent[provider];
                providerInfo.style.display = 'block';
            }
        } else {
            console.log('Provider not found or empty, clearing dropdown');
            aiModel.innerHTML = '<option value="">Select Model</option>';
            if (providerInfo) {
                providerInfo.style.display = 'none';
            }
        }
        console.log('=== END PROVIDER CHANGE EVENT ===');
    });

    // Toggle API key visibility
    if (toggleApiKey && aiApiKey) {
        toggleApiKey.addEventListener('click', function() {
            const type = aiApiKey.type === 'password' ? 'text' : 'password';
            aiApiKey.type = type;
            const icon = this.querySelector('i');
            if (icon) {
                icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
            }
        });
    }

    // Test function
    window.testProvider = function(provider) {
        console.log('Testing provider:', provider);
        const providerSelect = document.getElementById('aiModelProvider');
        if (providerSelect) {
            providerSelect.value = provider;
            providerSelect.dispatchEvent(new Event('change'));
        }
    };
}

function initializeQuestionGenerator() {
    // Update total questions count
    updateTotalQuestions();

    // Event listeners
    $('.question-count').on('input', updateTotalQuestions);
    $('#ai-generation-form').on('submit', generateQuestions);
    $('#reset-form').on('click', resetForm);
}

function updateTotalQuestions() {
    let total = 0;
    $('.question-count').each(function() {
        const value = parseInt($(this).val()) || 0;
        total += value;
    });

    $('#total-questions').text(total);
}
</script>
<script src="<?= base_url('assets/js/ai-question-generator.js') ?>"></script>
<?= $this->endSection() ?>
