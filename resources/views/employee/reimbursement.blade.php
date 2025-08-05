<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit New Reimbursement</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Submit New Reimbursement</h1>
                    <p class="text-sm text-gray-600">Fill in the details for your expense reimbursement</p>
                </div>
                <a href="{{ route('employee.dashboard') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form Card -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Reimbursement Details</h3>
                <p class="text-sm text-gray-600 mt-1">Please provide accurate information for faster processing</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 m-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Please correct the following errors:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form action="/employee/reimbursement" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Amount Field -->
                        <div>
                            <label for="total" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm font-medium">Rp</span>
                                </div>
                                <input type="text" id="total" name="total" required value="{{ old('total') }}"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-medium"
                                    placeholder="0" oninput="formatCurrency(this)">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Minimum: Rp 1,000 - Maximum: Rp 10,000,000</p>
                            @error('total')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agenda Field -->
                        <div>
                            <label for="agenda" class="block text-sm font-medium text-gray-700 mb-2">
                                Agenda <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="agenda" name="agenda" required maxlength="255"
                                value="{{ old('agenda') }}"
                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., Business lunch, Transportation, Office supplies, Training materials">
                            <p class="mt-1 text-xs text-gray-500">Brief description of the expense category</p>
                            @error('agenda')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-gray-400">(Optional)</span>
                            </label>
                            <textarea id="description" name="description" rows="4" maxlength="1000"
                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                placeholder="Additional details about the expense (optional)...">{{ old('description') }}</textarea>
                            <div class="flex justify-between mt-1">
                                <p class="text-xs text-gray-500">Provide additional context if needed</p>
                                <span id="charCount" class="text-xs text-gray-400">0/1000</span>
                            </div>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Invoice Upload Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Invoice/Receipt <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-200"
                                id="dropzone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="invoice"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="invoice" name="invoice" type="file" class="sr-only"
                                                accept=".jpg,.jpeg,.png,.pdf" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, PDF up to 5MB
                                    </p>
                                </div>
                            </div>
                            <div id="filePreview" class="mt-3 hidden">
                                <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="ml-3 flex-1">
                                        <p id="fileName" class="text-sm font-medium text-blue-900"></p>
                                        <p id="fileSize" class="text-xs text-blue-600"></p>
                                    </div>
                                    <button type="button" onclick="removeFile()"
                                        class="ml-3 text-blue-400 hover:text-blue-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('invoice')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Searchable Approver Dropdown -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Approver <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" id="approverDropdown">
                                <!-- Hidden input for form submission -->
                                <input type="hidden" id="approver_id" name="approver_id"
                                    value="{{ old('approver_id') }}" required>

                                <!-- Dropdown Button -->
                                <button type="button" id="approverButton"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-3 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    onclick="toggleDropdown()">
                                    <span id="selectedApprover" class="block truncate text-gray-500">Choose an
                                        approver...</span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                            id="dropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>

                                <!-- Dropdown Panel -->
                                <div id="approverPanel"
                                    class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-lg border border-gray-200 overflow-hidden">
                                    <!-- Search Input -->
                                    <div class="p-3 border-b border-gray-200">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="text" id="approverSearch"
                                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                placeholder="Search approver..." oninput="filterApprovers()">
                                        </div>
                                    </div>

                                    <!-- Approver Options -->
                                    <div class="max-h-48 overflow-y-auto">
                                        @foreach ($approvers as $approver)
                                        <div class="approver-option cursor-pointer hover:bg-blue-50 transition-colors duration-150"
                                            data-id={{ $approver->id }}
                                            data-name={{ $approver->name }}
                                            data-email={{ $approver->email }}
                                            onclick="selectApprover(this)">
                                            <div class="flex items-center p-3">
                                                <div
                                                    class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                                    <span id="icon-{{ $approver->id }}"
                                                        class="text-sm font-medium text-blue-600">{{ collect(explode('
                                                        ', $approver->name))->map(fn($n) =>
                                                        strtoupper($n[0]))->implode('') }}</span>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="text-sm font-medium text-gray-900">{{ $approver->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $approver->email }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <!-- No Results -->
                                        <div id="noResults" class="hidden p-4 text-center text-gray-500 text-sm">
                                            No approvers found matching your search.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('approver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center pt-8 mt-8 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Note:</span> Your request will be sent to the selected approver for
                        review.
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('employee.dashboard') }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 font-medium shadow-sm">
                            Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </main>

    <!-- JavaScript -->
    <script>
        // Currency formatting
        function formatCurrency(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = parseInt(value).toLocaleString('id-ID');
            }
        }

        // Character counter for description
        document.getElementById('description').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('charCount').textContent = charCount + '/1000';

            if (charCount > 900) {
                document.getElementById('charCount').classList.add('text-red-500');
            } else {
                document.getElementById('charCount').classList.remove('text-red-500');
            }
        });

        // File upload handling
        document.getElementById('invoice').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                showFilePreview(file);
            }
        });

        function showFilePreview(file) {
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);

            document.getElementById('fileName').textContent = fileName;
            document.getElementById('fileSize').textContent = fileSize + ' MB';
            document.getElementById('filePreview').classList.remove('hidden');
            document.getElementById('dropzone').classList.add('border-green-300', 'bg-green-50');
        }

        function removeFile() {
            document.getElementById('invoice').value = '';
            document.getElementById('filePreview').classList.add('hidden');
            document.getElementById('dropzone').classList.remove('border-green-300', 'bg-green-50');
        }

        // Drag and drop functionality
        const dropzone = document.getElementById('dropzone');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzone.classList.add('border-blue-400', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropzone.classList.remove('border-blue-400', 'bg-blue-50');
        }

        dropzone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                document.getElementById('invoice').files = files;
                showFilePreview(files[0]);
            }
        }

        // Searchable Dropdown Functions
        function toggleDropdown() {
            const panel = document.getElementById('approverPanel');
            const icon = document.getElementById('dropdownIcon');
            const search = document.getElementById('approverSearch');

            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                search.focus();
            } else {
                panel.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                search.value = '';
                filterApprovers();
            }
        }

        function makeAlias(name) {
            return name.split(' ').map(n => n[0]).join('');
        }

        function selectApprover(element) {
            const id = element.getAttribute('data-id');
            const name = element.getAttribute('data-name');
            const email = element.getAttribute('data-email');

            // Update hidden input
            document.getElementById('approver_id').value = id;

            // Update button text
            document.getElementById('selectedApprover').innerHTML = `
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full mr-3">
                        <span class="text-xs font-medium text-blue-600">${name.split(' ').map(n => n[0]).join('')}</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${email}</div>
                    </div>
                </div>
            `;
            document.getElementById('selectedApprover').classList.remove('text-gray-500');
            document.getElementById('selectedApprover').classList.add('text-gray-900');

            // Close dropdown
            toggleDropdown();
        }

        function filterApprovers() {
            const searchTerm = document.getElementById('approverSearch').value.toLowerCase();
            const options = document.querySelectorAll('.approver-option');
            const noResults = document.getElementById('noResults');
            let hasVisibleOptions = false;

            options.forEach(option => {
                const name = option.getAttribute('data-name').toLowerCase();
                const email = option.getAttribute('data-email').toLowerCase();

                const matches = name.includes(searchTerm) ||
                               email.includes(searchTerm);

                if (matches) {
                    option.style.display = 'block';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });

            if (hasVisibleOptions) {
                noResults.classList.add('hidden');
            } else {
                noResults.classList.remove('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('approverDropdown');
            const panel = document.getElementById('approverPanel');

            if (!dropdown.contains(event.target)) {
                panel.classList.add('hidden');
                document.getElementById('dropdownIcon').style.transform = 'rotate(0deg)';
                document.getElementById('approverSearch').value = '';
                filterApprovers();
            }
        });

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const total = document.getElementById('total').value.replace(/[^\d]/g, '');
            const approverId = document.getElementById('approver_id').value;

            if (parseInt(total) < 1000) {
                e.preventDefault();
                alert('Minimum amount is Rp 1,000');
                return false;
            }

            if (!approverId) {
                e.preventDefault();
                alert('Please select an approver');
                return false;
            }

            // Update the total field with clean number for submission
            document.getElementById('total').value = total;
        });

        // Initialize dropdown with old value if exists
        document.addEventListener('DOMContentLoaded', function() {
            const oldApproverId = '{{ old("approver_id") }}';
            if (oldApproverId) {
                const option = document.querySelector(`[data-id="${oldApproverId}"]`);
                if (option) {
                    selectApprover(option);
                }
            }
        });
    </script>
</body>

</html>
