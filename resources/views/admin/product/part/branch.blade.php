@php($level++)
@foreach ($items->where('parent_id', $parent) as $item)
    <option value="{{ $item->id }}" @if ($item->id == $category_id) selected @endif>
        @if ($level) {!! str_repeat('&nbsp;&nbsp;&nbsp;', $level) !!}  @endif {{ $item->name }}
    </option>
@endforeach