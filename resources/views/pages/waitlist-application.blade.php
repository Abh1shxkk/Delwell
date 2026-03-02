<x-layout title="DelWell - Membership Application">
    <style>
        .responsive-container {
            min-height: calc(100vh - 200px);
            padding: 3rem 1rem;
        }
        .responsive-form-container {
            max-width: 42rem;
            width: 100%;
        }
        .responsive-title {
            font-size: 2.25rem;
        }
        
        @media (min-width: 640px) {
            .responsive-container {
                padding: 3rem 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .responsive-container {
                padding: 3rem 2rem;
            }
        }
        
        .checkbox-limit {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    <div class="responsive-container flex items-center justify-center">
        <div class="responsive-form-container space-y-8">
            <div class="text-center">
                <h2 class="mt-6 responsive-title font-extrabold text-[#3A3A3A] font-['Cormorant_Garamond']">
                    Membership Application
                </h2>
                <p class="mt-2 text-gray-600">
                    To ensure our community is built on shared intention, please answer the questions below.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/80">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Please correct the following errors:
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-8" action="{{ route('waitlist.store') }}" method="POST">
                    @csrf
                    
                    @foreach($questions as $index => $question)
                        @if($question->question_type === 'textarea')
                            <!-- Textarea Question -->
                            <div>
                                <label for="{{ $question->field_name }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    @if(!$question->is_required)
                                        <span class="text-gray-500">(Optional)</span>
                                    @endif
                                </label>
                                <textarea
                                    id="{{ $question->field_name }}"
                                    name="{{ $question->field_name }}"
                                    rows="3"
                                    {{ $question->is_required ? 'required' : '' }}
                                    class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3B18A] focus:border-[#A3B18A] sm:text-sm"
                                    placeholder="{{ $question->placeholder }}"
                                >{{ old($question->field_name) }}</textarea>
                                @if($question->help_text)
                                    <p class="mt-1 text-sm text-gray-500">{{ $question->help_text }}</p>
                                @endif
                            </div>

                        @elseif($question->question_type === 'text')
                            <!-- Text Input Question -->
                            <div>
                                <label for="{{ $question->field_name }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    @if(!$question->is_required)
                                        <span class="text-gray-500">(Optional)</span>
                                    @endif
                                </label>
                                <input
                                    id="{{ $question->field_name }}"
                                    name="{{ $question->field_name }}"
                                    type="text"
                                    value="{{ old($question->field_name) }}"
                                    {{ $question->is_required ? 'required' : '' }}
                                    class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3B18A] focus:border-[#A3B18A] sm:text-sm"
                                    placeholder="{{ $question->placeholder }}"
                                />
                                @if($question->help_text)
                                    <p class="mt-1 text-sm text-gray-500">{{ $question->help_text }}</p>
                                @endif
                            </div>

                        @elseif($question->question_type === 'email')
                            <!-- Email Input Question -->
                            <div>
                                <label for="{{ $question->field_name }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    @if(!$question->is_required)
                                        <span class="text-gray-500">(Optional)</span>
                                    @endif
                                </label>
                                <input
                                    id="{{ $question->field_name }}"
                                    name="{{ $question->field_name }}"
                                    type="email"
                                    autocomplete="email"
                                    value="{{ old($question->field_name) }}"
                                    {{ $question->is_required ? 'required' : '' }}
                                    class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3B18A] focus:border-[#A3B18A] sm:text-sm"
                                    placeholder="{{ $question->placeholder }}"
                                />
                                @if($question->help_text)
                                    <p class="mt-1 text-sm text-gray-500">{{ $question->help_text }}</p>
                                @endif
                            </div>

                        @elseif($question->question_type === 'radio')
                            <!-- Radio Button Question -->
                            <fieldset>
                                <legend class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    @if(!$question->is_required)
                                        <span class="text-gray-500">(Optional)</span>
                                    @endif
                                </legend>
                                @if($question->help_text)
                                    <p class="mb-2 text-sm text-gray-500">{{ $question->help_text }}</p>
                                @endif
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    @foreach($question->options as $optIndex => $option)
                                        <div class="flex items-center">
                                            <input
                                                id="{{ $question->field_name }}-{{ $optIndex }}"
                                                name="{{ $question->field_name }}"
                                                type="radio"
                                                value="{{ $option }}"
                                                {{ old($question->field_name) == $option ? 'checked' : '' }}
                                                {{ $question->is_required ? 'required' : '' }}
                                                class="focus:ring-[#A3B18A] h-4 w-4 text-[#A3B18A] border-gray-300"
                                            />
                                            <label for="{{ $question->field_name }}-{{ $optIndex }}" class="block text-sm text-gray-700" style="margin-left: 0.75rem;">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>

                        @elseif($question->question_type === 'checkbox')
                            <!-- Checkbox Question -->
                            <fieldset>
                                <legend class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    @if($question->max_selections)
                                        <span class="text-gray-500">(Select up to {{ $question->max_selections }})</span>
                                    @endif
                                    @if(!$question->is_required)
                                        <span class="text-gray-500">(Optional)</span>
                                    @endif
                                </legend>
                                @if($question->help_text)
                                    <p class="mb-2 text-sm text-gray-500">{{ $question->help_text }}</p>
                                @endif
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    @php
                                        $selectedValues = old($question->field_name, []);
                                    @endphp
                                    @foreach($question->options as $optIndex => $option)
                                        <div class="flex items-center">
                                            <input
                                                id="{{ $question->field_name }}-{{ $optIndex }}"
                                                name="{{ $question->field_name }}[]"
                                                type="checkbox"
                                                value="{{ $option }}"
                                                {{ in_array($option, $selectedValues) ? 'checked' : '' }}
                                                class="focus:ring-[#A3B18A] h-4 w-4 text-[#A3B18A] border-gray-300 rounded checkbox-{{ $question->field_name }}"
                                                data-max="{{ $question->max_selections }}"
                                                data-group="{{ $question->field_name }}"
                                                onchange="limitCheckboxes(this)"
                                            />
                                            <label for="{{ $question->field_name }}-{{ $optIndex }}" class="block text-sm text-gray-700" style="margin-left: 0.75rem;">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif
                    @endforeach

                    <div>
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-full text-white bg-[#FFC09F] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFC09F]/80 transition-colors"
                        >
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function limitCheckboxes(checkbox) {
            const group = checkbox.dataset.group;
            const maxSelections = parseInt(checkbox.dataset.max);
            
            if (!maxSelections) return;
            
            const checkboxes = document.querySelectorAll(`.checkbox-${group}`);
            const checkedBoxes = document.querySelectorAll(`.checkbox-${group}:checked`);
            
            if (checkedBoxes.length >= maxSelections) {
                checkboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.disabled = true;
                        cb.parentElement.classList.add('checkbox-limit');
                    }
                });
            } else {
                checkboxes.forEach(cb => {
                    cb.disabled = false;
                    cb.parentElement.classList.remove('checkbox-limit');
                });
            }
        }

        // Initialize checkbox limiting on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Find all checkbox groups with max selections
            const checkboxGroups = {};
            document.querySelectorAll('input[type="checkbox"][data-max]').forEach(cb => {
                const group = cb.dataset.group;
                if (!checkboxGroups[group]) {
                    checkboxGroups[group] = true;
                    limitCheckboxes(cb);
                }
            });
        });
    </script>
</x-layout>
