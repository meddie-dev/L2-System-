@props(['active' => false, 'isLast' => false])

<li class="tw-inline-flex tw-items-center  {{ $active ? 'tw-text-gray-500 dark:tw-text-gray-400' : 'tw-text-gray-700 hover:tw-text-blue-600 dark:tw-text-gray-400 dark:hover:tw-text-white' }}"
  @if($active) aria-current="page" @endif>
  @if(!$active)
  <a href="{{ $attributes->get('href') }}" class="tw-flex tw-h-10 tw-items-center tw-gap-1.5 tw-bg-gray-100 tw-px-2 tw-transition hover:tw-text-gray-900"">
            {{ $slot }}
        </a>
    @else
        <span class=" tw-inline-flex tw-items-center tw-px-2 ">
            {{ $slot }}
        </span>
    @endif

    @if(!$isLast)
        <svg class=" rtl:tw-rotate-180 tw-w-3 tw-h-3 tw-text-gray-400 tw-mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
    </svg>
    @endif
</li>