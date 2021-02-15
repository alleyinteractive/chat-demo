<template>
    <div class="flex space-x-3">
        <img
            class="h-6 w-6 rounded-full"
            :src="message.user.avatar_url"
            alt=""
        />
        <div class="flex-1 space-y-1">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">{{ message.user.name }}</h3>
                <p class="text-sm text-gray-500">{{ timeAgo }} ago</p>
            </div>
            <p class="text-sm text-gray-500">
                {{ message.body }}
            </p>
        </div>
    </div>
</template>

<script>
import { formatDistance } from 'date-fns';

export default {
  props: [
    'message',
  ],

  data: () => ({
    timeAgo: null,
  }),

  computed: {
    messageDate() {
      return Date.parse(this.message.updated_at);
    }
  },

  methods: {
    updateTimeAgo() {
      this.timeAgo = formatDistance(this.messageDate, new Date());
    }
  },

  mounted() {
    this.updateTimeAgo();
    setInterval(this.updateTimeAgo, 10000);
  },
};
</script>

<style></style>
