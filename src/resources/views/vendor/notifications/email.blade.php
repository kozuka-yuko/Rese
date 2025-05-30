@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')<br><br>
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
    case 'success':
    case 'error':
        $color = $level;
        break;
    default:
        $color = 'primary';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards')<br><br>
<p>Rese</p>
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
{{ $actionText }}ボタンがクリックできない場合は、下のURLをコピーしてブラウザのアドレス欄に貼り付け、直接アクセスしてください。
[{{ $displayableActionUrl }}]({{ $actionUrl }})
@endslot
@endisset
@endcomponent