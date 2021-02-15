<template>
  <p class="mt-2 mx-4 -mb-6 flex-none text-sm text-gray-400 h-6">{{ typingMessage }}</p>
</template>

<script>
export default {
  props: ['users'],

  computed: {
    typingMessage() {
      switch (this.users.length) {
        case 0:
          return '';

        case 1:
          return `${this.users[0]} is typing`;

        case 2:
          return `${this.users.join(' and ')} are typing`;

        case 3:
          return `${this.users[0]}, ${this.users[1]}, and ${this.users[2]} are typing`;

        default:
          return 'Several users are typing';
      }
    },
  }
}
</script>

<style>

</style>
