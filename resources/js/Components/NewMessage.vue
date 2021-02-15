<template>
  <form class="m-4 flex-none" @submit.prevent="saveMessage">
    <label for="body" class="sr-only">Send a message</label>
    <div class="mt-1 flex rounded-md shadow-sm">
      <div class="flex items-stretch flex-grow focus-within:z-10">
        <input
          type="text"
          id="body"
          class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
          placeholder="Send a message"
          v-model="body"
          @input="recordTyping($event.target.value)"
          autocomplete="off"
        />
      </div>
      <button
        class="-ml-px px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
      >
        Send
      </button>
    </div>
  </form>
</template>

<script>
export default {
  data: () => ({
    body: '',
  }),

  methods: {
    saveMessage() {
      this.$emit('doneTyping');
      axios.post('/api/messages', {
        body: this.body,
      }).then(({ data }) => {
        this.$emit('newMessage', data);
        this.body = '';
      }).catch((error) => console.error(error));
    },

    recordTyping(value) {
      if (value !== '') {
        this.$emit('typing');
      } else {
        this.$emit('doneTyping');
      }
    },
  },
};
</script>

<style></style>
