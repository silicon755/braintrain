<!-- Simple MDE - Markdown Editor -->
<div @include('admin::panel.inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <textarea
    	id="simplemde-{{ $field['name'] }}"
        name="{{ $field['name'] }}"
        @include('admin::panel.inc.field_attributes', ['default_class' => 'form-control ckeditor'])
    	>{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($xPanel->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    @endpush

@endif

@push('crud_fields_scripts')
<script>
    var simplemde = new SimpleMDE({ element: $("#simplemde-{{ $field['name'] }}")[0] });
</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
