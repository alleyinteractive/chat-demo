<template>
  <a href="#" class="flex-shrink-0 group block">
    <div class="flex items-center">
      <div class="inline-block relative">
        <img class="inline-block h-9 w-9 rounded-full" :src="avatar_url" alt="">
        <span
          :class="{ 'bg-green-400': online === true, 'bg-gray-300': online !== true }"
          class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white"
        ></span>
      </div>
      <div class="ml-3">
        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
          {{ name }}
        </p>
      </div>
    </div>
  </a>

</template>

<script>
export default {
  props: [
    'name',
    'avatar_url',
    'online',
  ],
}
</script>
