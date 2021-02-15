<template>
  <app-layout>
    <div class="flex h-full">
      <div class="flex flex-col flex-auto">
        <div
          ref="messages"
          class="bg-white overflow-y-auto shadow rounded-lg m-4 mb-0 flex-auto h-96"
        >
          <div class="p-4">
            <div>
              <ul class="divide-y divide-gray-200">
                <li
                  class="py-4"
                  v-for="message in allMessages"
                  :key="message.id"
                >
                  <message :message="message"></message>
                </li>
              </ul>
              <message-loader v-if="allMessages.length === 0"></message-loader>
            </div>
          </div>
        </div>
        <users-typing :users="usersTyping"></users-typing>
        <new-message @newMessage="addMessage" @doneTyping="stopWhisper" @typing="whisperTyping"></new-message>
      </div>

      <div class="flex flex-none flex-col w-1/6">
        <div
          class="bg-white shadow overflow-hidden rounded-md m-4 ml-0 overflow-y-auto flex-auto h-96"
        >
          <ul class="divide-y divide-gray-200">
            <li class="px-4 py-4" v-for="user in allUsers" :key="user.id">
              <user
                :name="user.name"
                :avatar_url="user.avatar_url"
                :online="user.online"
              ></user>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

import Message from "@/Components/Message.vue";
import NewMessage from "../Components/NewMessage.vue";
import MessageLoader from "../Components/MessageLoader.vue";
import User from "../Components/User.vue";
import UsersTyping from '../Components/UsersTyping.vue';

export default {
  components: {
    AppLayout,
    Message,
    NewMessage,
    MessageLoader,
    User,
    UsersTyping,
  },

  props: ['messages', 'users', 'user'],

  data: () => ({
    pusher: null,
    newMessages: [],
    activeUsers: [],
    typing: {},
  }),

  computed: {
    allMessages() {
      return [...this.messages, ...this.newMessages];
    },

    allUsers() {
      return _.sortBy(
        _.uniqBy([...this.activeUsers, ...this.users], "id"),
        "name"
      );
    },

    usersTyping() {
      return Object.keys(this.typing);
    },
  },

  methods: {
    addMessage(message, scroll = true) {
      this.newMessages = [...this.newMessages, message];
      if (scroll) {
        setTimeout(() => this.scrollToBottom(), 50);
      }
    },

    scrollToBottom() {
      this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
    },

    whisperTyping: _.throttle(
      function() {
        this.pusher.whisper('typing', { name: this.user.name });
      },
      1500,
      { trailing: false }
    ),

    stopWhisper() {
      this.pusher.whisper('doneTyping', { name: this.user.name });
    }
  },

  mounted() {
    this.scrollToBottom();

    this.pusher = Echo.join("chat.1")
      .here((users) => {
        this.activeUsers = users.map(u => ({ ...u, online: true }));
      })
      .joining((user) => {
        this.activeUsers = [...this.activeUsers, { ...user, online: true }];
      })
      .leaving((user) => {
        this.activeUsers = this.activeUsers.filter((u) => u.id !== user.id);
      })
      .listen("NewMessage", ({ message }) => {
        const { scrollTop, offsetHeight, scrollHeight } = this.$refs.messages;
        this.addMessage(message, scrollTop + offsetHeight === scrollHeight);
      })
      .listenForWhisper('typing', ({ name }) => {
        if (typeof this.typing[name] !== 'undefined') {
          clearTimeout(this.typing[name]);
        }

        this.typing = {
          ...this.typing,
          ...{
            [name]: setTimeout(() => {
              const { [name]: deleted, ...names } = this.typing;
              this.typing = names;
            }, 3000),
          },
        };
      })
      .listenForWhisper('doneTyping', ({ name }) => {
        if (typeof this.typing[name] !== 'undefined') {
          clearTimeout(this.typing[name]);
        }
        const { [name]: deleted, ...names } = this.typing;
        this.typing = names;
      });
  }
};
</script>

<style></style>
