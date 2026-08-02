<template>
  <div class="h-full flex flex-col overflow-hidden">
    <CommonLoadingOverlay :loading="isLoading" text="Đang tải dữ liệu..." />
    <!-- MAIN LAYOUT -->
    <div class="flex flex-1 overflow-hidden md:border md:border-gray-200/80 md:bg-white md:shadow-xs">
      <!-- SIDEBAR -->
      <aside :class="[
        'w-full md:w-72 bg-white border-r border-gray-200 flex-col flex-shrink-0',
        showChatOnMobile ? 'hidden md:flex' : 'flex'
      ]">

        <div class="flex-1 overflow-y-auto sidebar-scroll">

          <!-- Chatbot Drivio Conversation -->
          <div class="px-3 pt-3 pb-1">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold mb-1.5 px-1">Trợ lý AI</p>
            <button @click="selectConv('bot')"
              :class="['conv-item w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all', activeConvId === 'bot' ? 'active' : '']">
              <div
                class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-primary to-brand-dark flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H4a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A2.5 2.5 0 0 0 5 15.5A2.5 2.5 0 0 0 7.5 18a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 7.5 13m9 0a2.5 2.5 0 0 0-2.5 2.5a2.5 2.5 0 0 0 2.5 2.5a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 16.5 13M4 21v-1a7 7 0 0 1 7-7h2a7 7 0 0 1 7 7v1H4z" />
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-semibold text-gray-800">Chatbot Drivio</p>
                </div>
                <p class="text-xs text-gray-500 truncate">
                  {{ lastBotMessage }}
                </p>
              </div>
            </button>
          </div>

          <!-- ======================================================= -->
          <!-- ======================================================= -->
          <!-- Trò chuyện chuyến xe -->
          <div class="px-3 pt-3 pb-3">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 px-1">Chuyến xe</p>
            <div class="grid grid-cols-2 gap-1 bg-gray-100 p-1 rounded-xl border border-gray-200/80 mb-3">
              <button
                @click="chatFilterTab = 'active'"
                :class="['w-full py-1.5 text-center text-xs font-bold rounded-lg transition-all cursor-pointer', chatFilterTab === 'active' ? 'bg-white text-brand-primary shadow-xs' : 'text-gray-500 hover:text-gray-800']"
              >
                Đang chạy
              </button>
              <button
                @click="chatFilterTab = 'archived'"
                :class="['w-full py-1.5 text-center text-xs font-bold rounded-lg transition-all cursor-pointer', chatFilterTab === 'archived' ? 'bg-white text-brand-primary shadow-xs' : 'text-gray-500 hover:text-gray-800']"
              >
                Đã xong
              </button>
            </div>

            <div class="space-y-0.5">
              <div v-if="filteredConversations.length === 0" class="py-6 text-center text-xs text-gray-400">
                {{ chatFilterTab === 'active' ? 'Không có cuộc trò chuyện nào đang hoạt động' : 'Không có cuộc trò chuyện đã hoàn thành' }}
              </div>
              <button v-for="conversation in filteredConversations" :key="conversation.id" @click="selectConv(conversation.id)"
                :class="['conv-item w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all', activeConvId === conversation.id ? 'active' : '']">
                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                  <img v-if="conversation.other_user?.avatar" :src="conversation.other_user.avatar"
                    class="w-10 h-10 rounded-full object-cover" />

                  <div v-else
                    class="w-10 h-10 rounded-full bg-brand-secondary text-brand-primary flex items-center justify-center text-sm font-bold">
                    {{ conversation.other_user?.name?.charAt(0) || 'U' }}
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between gap-1">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ conversation.other_user?.name }}</p>
                    <span class="text-[10px] text-gray-400 flex-shrink-0">
                      {{ conversation.last_message?.time || 'Vừa xong' }}
                    </span>
                  </div>
                  <div class="flex items-center justify-between gap-1">
                    <p class="text-xs text-gray-500 truncate">{{ conversation.last_message?.text }}</p>
                    <span v-if="conversation.unread_count"
                      class="flex-shrink-0 w-4 h-4 rounded-full bg-brand-primary text-white text-[10px] flex items-center justify-center font-medium">{{
                        conversation.unread_count }}</span>
                  </div>
                  <!-- Car info badge -->
                  <p v-if="conversation.car" class="text-[10px] text-brand-accent font-medium truncate mt-0.5">{{
                    conversation.car.name }}</p>
                </div>
              </button>
            </div>
          </div>
        </div>
        <!-- ======================================================= -->

      </aside>

      <!-- CHAT AREA -->
      <main :class="[
        'flex-1 flex flex-col overflow-hidden bg-gray-50',
        showChatOnMobile ? 'flex' : 'hidden md:flex'
      ]">

        <!-- Chat header -->
        <div class="bg-white border-b border-gray-200 px-3 sm:px-4 md:px-6 py-2.5 sm:py-3 flex items-center gap-2.5 sm:gap-3 flex-shrink-0 w-full">
          <!-- Back button (Mobile only) -->
          <button @click="exitChat"
            class="md:hidden p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors -ml-1 mr-1"
            title="Quay lại danh sách">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m15 19l-7-7l7-7" />
            </svg>
          </button>

          <!-- Bot header -->
          <template v-if="activeConvId === 'bot'">
            <div
              class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-primary to-brand-dark flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H4a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A2.5 2.5 0 0 0 5 15.5A2.5 2.5 0 0 0 7.5 18a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 7.5 13m9 0a2.5 2.5 0 0 0-2.5 2.5a2.5 2.5 0 0 0 2.5 2.5a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 16.5 13M4 21v-1a7 7 0 0 1 7-7h2a7 7 0 0 1 7 7v1H4z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-800">Chatbot Drivio</p>
            </div>
          </template>

          <!-- Host header -->
          <template v-else-if="activeHost">
            <div class="relative flex-shrink-0">
              <img v-if="activeHost.other_user?.avatar" :src="activeHost.other_user.avatar"
                class="w-9 h-9 rounded-full object-cover" />
              <div v-else
                class="w-9 h-9 rounded-full bg-brand-secondary text-brand-primary flex items-center justify-center text-sm font-bold">
                {{ activeHost.other_user?.name?.charAt(0) }}
              </div>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-800">{{ activeHost.other_user?.name }}</p>
              <p v-if="activeHost.car" class="text-xs text-gray-500">{{ activeHost.car.name }}</p>
            </div>
            <!-- Car info pill -->
            <div v-if="activeHost.car"
              class="ml-3 hidden sm:flex items-center gap-1.5 bg-brand-secondary px-3 py-1 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-brand-accent" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" />
              </svg>
              <span class="text-xs text-brand-accent font-medium">{{ activeHost.car.name }}</span>
            </div>
          </template>

        </div>

        <!-- Messages -->
        <div ref="chatContainer" class="flex-1 overflow-y-auto chat-scroll px-4 md:px-6 py-6 space-y-5">
          <template v-for="(msg, index) in messages" :key="index">

            <!-- User / me (right side) -->
            <div v-if="msg.role === 'user'" class="flex gap-3 justify-end msg-anim">
              <div class="max-w-lg">
                <div :class="[
                  msg.type === 'image'
                    ? 'rounded-2xl overflow-hidden shadow-xs'
                    : 'bg-brand-primary text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow-xs'
                ]">
                  <div v-if="msg.type === 'image'" class="relative group max-w-[250px]">
                    <img 
                      :src="msg.text" 
                      alt="Hình ảnh" 
                      :class="[
                        'max-w-[250px] max-h-[300px] w-auto h-auto object-cover rounded-lg shadow-xs cursor-pointer transition-all duration-300',
                        msg.isUploading ? 'blur-[2px] brightness-75 scale-95' : 'hover:opacity-90'
                      ]" 
                      @click="!msg.isUploading && openImage(msg.text)" 
                    />
                    <div v-if="msg.isUploading" class="absolute inset-0 flex items-center justify-center bg-black/35 rounded-lg">
                      <div class="flex flex-col items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                        <span class="text-[10px] text-white font-medium drop-shadow-md">Đang gửi...</span>
                      </div>
                    </div>
                  </div>
                  <p v-else class="text-sm leading-relaxed whitespace-pre-line">{{ msg.text }}</p>
                </div>
                <p class="text-xs text-gray-400 mt-1 text-right mr-1">{{ msg.time }}</p>
              </div>
              <div
                class="w-8 h-8 rounded-full bg-brand-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-primary" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
              </div>
            </div>

            <!-- Bot / host (left side) -->
            <div v-else class="flex gap-3 msg-anim">
              <!-- Bot avatar -->
              <div v-if="activeConvId === 'bot'"
                class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-primary to-brand-dark flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H4a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A2.5 2.5 0 0 0 5 15.5A2.5 2.5 0 0 0 7.5 18a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 7.5 13m9 0a2.5 2.5 0 0 0-2.5 2.5a2.5 2.5 0 0 0 2.5 2.5a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 16.5 13M4 21v-1a7 7 0 0 1 7-7h2a7 7 0 0 1 7 7v1H4z" />
                </svg>
              </div>
              <!-- Host avatar -->
              <div v-else-if="activeHost" class="w-8 h-8 flex-shrink-0 mt-0.5">
                <img v-if="activeHost.other_user?.avatar" :src="activeHost.other_user.avatar"
                  class="w-8 h-8 rounded-full object-cover" />
                <div v-else
                  class="w-8 h-8 rounded-full flex items-center justify-center text-brand-primary bg-brand-secondary text-xs font-bold">
                  {{ activeHost.other_user?.name?.charAt(0) }}
                </div>
              </div>

              <div class="max-w-lg">
                <div :class="[
                  msg.type === 'image'
                    ? 'rounded-2xl overflow-hidden shadow-xs'
                    : 'bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-xs'
                ]">
                  <div v-if="msg.type === 'image'" class="relative group max-w-[250px]">
                    <img 
                      :src="msg.text" 
                      alt="Hình ảnh" 
                      :class="[
                        'max-w-[250px] max-h-[300px] w-auto h-auto object-cover rounded-lg shadow-xs cursor-pointer transition-all duration-300',
                        msg.isUploading ? 'blur-[2px] brightness-75 scale-95' : 'hover:opacity-90'
                      ]" 
                      @click="!msg.isUploading && openImage(msg.text)" 
                    />
                    <div v-if="msg.isUploading" class="absolute inset-0 flex items-center justify-center bg-black/35 rounded-lg">
                      <div class="flex flex-col items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                        <span class="text-[10px] text-white font-medium drop-shadow-md">Đang tải...</span>
                      </div>
                    </div>
                  </div>
                  <div v-else>
                    <p v-if="activeConvId === 'bot'" class="text-sm text-gray-800 leading-relaxed" v-html="msg.text"></p>
                    <p v-else class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ msg.text }}</p>
                  </div>
                </div>
                <p class="text-xs text-gray-400 mt-1 ml-1">
                  {{ activeConvId === 'bot' ? 'Chatbot Drivio' : activeHost?.other_user?.name }} • {{ msg.time }}
                </p>
              </div>
            </div>

          </template>

          <!-- Typing indicator -->
          <div v-if="isTyping" class="flex gap-3 msg-anim items-start">
            <!-- Bot Avatar -->
            <div v-if="activeConvId === 'bot'"
              class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-primary to-brand-dark flex items-center justify-center flex-shrink-0 animate-pulse mt-0.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H4a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A2.5 2.5 0 0 0 5 15.5A2.5 2.5 0 0 0 7.5 18a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 7.5 13m9 0a2.5 2.5 0 0 0-2.5 2.5a2.5 2.5 0 0 0 2.5 2.5a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 16.5 13M4 21v-1a7 7 0 0 1 7-7h2a7 7 0 0 1 7 7v1H4z" />
              </svg>
            </div>
            <!-- Host Avatar -->
            <div v-else-if="activeHost" class="w-8 h-8 flex-shrink-0 mt-0.5">
              <img v-if="activeHost.other_user?.avatar" :src="activeHost.other_user.avatar"
                class="w-8 h-8 rounded-full object-cover animate-pulse" />
              <div v-else
                class="w-8 h-8 rounded-full flex items-center justify-center text-brand-primary bg-brand-secondary text-xs font-bold animate-pulse">
                {{ activeHost.other_user?.name?.charAt(0) }}
              </div>
            </div>

            <!-- Thinking Bubble -->
            <div class="max-w-lg">
              <div
                class="bg-white border border-gray-200/80 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm flex items-center gap-2">
                <span class="text-sm text-gray-500 select-none">
                  {{ activeConvId === 'bot' ? 'Thinking' : `${activeHost?.other_user?.name} đang nhập` }}
                </span>

                <!-- Bouncing dots -->
                <div class="flex items-center gap-1 mt-0.5">
                  <span class="dot w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                  <span class="dot w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                  <span class="dot w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick suggestions (chỉ hiện với bot) -->
        <div v-if="showSuggestions && activeConvId === 'bot'" class="px-4 md:px-6 pb-3 flex gap-2 flex-wrap">
          <button v-for="s in suggestions" :key="s.text" @click="sendSuggestion(s.text)"
            class="text-xs text-brand-primary bg-brand-secondary border border-brand-primary/20 hover:bg-brand-light px-3 py-1.5 rounded-full transition-colors">
            {{ s.label }}
          </button>
        </div>

        <!-- Input area -->
        <div class="bg-gray-50 px-3 sm:px-4 md:px-6 pb-3 sm:pb-4 md:pb-6 pt-2 flex-shrink-0 w-full">
          <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-1.5 sm:gap-2 bg-white border border-gray-200 focus-within:border-brand-primary focus-within:ring-2 focus-within:ring-brand-primary/10 rounded-2xl sm:rounded-3xl p-1.5 sm:p-2 shadow-xs transition-all">
              <!-- Input chọn file ẩn -->
              <input type="file" ref="imageInputRef" accept="image/*" class="hidden" @change="uploadImage" />
              <!-- Nút hình ảnh bên trái -->
              <button @click="triggerImageSelect" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-gray-400 hover:text-brand-primary hover:bg-gray-100 transition-colors shrink-0" title="Gửi hình ảnh">
                <Icon name="lucide:image" class="w-5 h-5" />
              </button>

              <!-- Textarea ở giữa -->
              <textarea ref="inputRef" v-model="inputText" rows="1"
                :placeholder="activeConvId === 'bot' ? 'Nhập tin nhắn cho Chatbot...' : `Nhắn tin cho ${activeHost?.other_user?.name ?? 'chủ xe'}...`"
                class="flex-1 resize-none text-sm text-gray-800 placeholder-gray-400 bg-transparent border-0 focus:outline-none focus:ring-0 py-1.5 px-1 leading-relaxed"
                style="max-height: 120px" @keydown="handleKey" @input="autoResize" />

              <!-- Nút gửi bên phải -->
              <button @click="sendMessage" :disabled="isTyping || !inputText.trim()"
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-brand-primary hover:bg-brand-dark text-white flex items-center justify-center transition-all disabled:opacity-30 disabled:hover:bg-brand-primary shadow-xs shrink-0">
                <Icon name="lucide:arrow-up" class="w-5 h-5 stroke-[2.5]" />
              </button>
            </div>
          </div>
          <p v-if="activeConvId === 'bot'" class="text-center text-xs text-gray-400 mt-2">Drivio AI có thể mắc lỗi. Vui
            lòng
            kiểm tra thông tin quan trọng.</p>
        </div>

      </main>
    </div>
    <!-- Image Lightbox Modal -->
    <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
      <div v-if="showLightbox" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-md" @click.self="showLightbox = false">
        <button @click="showLightbox = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors duration-200 focus:outline-none" title="Đóng">
          <Icon name="lucide:x" class="w-8 h-8" />
        </button>
        <img :src="lightboxImageUrl" alt="Xem ảnh đầy đủ" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl select-none" />
      </div>
    </transition>
  </div>
</template>

<script setup>
definePageMeta({
  layout: "chat",
});

// ---------------- IMPORTS & CONFIG ----------------
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from 'vue'
import { ChatBotService } from '~/services/chatbot.service'
import { ChatService } from '~/services/chat.service'

const { $echo } = useNuxtApp()
const chatBotService = new ChatBotService()
const chatService = new ChatService()

// ---------------- STATE / REFS ----------------
const activeConvId = ref('bot')
const chat_id = ref(null)
const userInfo = useCookie('USER_INFO')

// Share state unread count và activeChatId sang Header component
const unreadChatsCount = useState('globalUnreadChatsCount', () => 0)
const activeChatId = useState('activeChatId', () => null)
const inputText = ref('')
const isTyping = ref(false)
const isLoading = ref(false)
const showSuggestions = ref(true)
const chatContainer = ref(null)
const inputRef = ref(null)
const showChatOnMobile = ref(false)
const showLightbox = ref(false)
const lightboxImageUrl = ref('')

const conversations = ref([])
const messages = ref([])
const lastBotMessage = ref('Xin chào tôi là Chatbot AI')

const botConversationId = ref('')
const suggestions = [
  { label: 'Thuê xe TP.HCM', text: 'Tôi muốn thuê xe ở TP. Hồ Chí Minh' },
  { label: 'Chi phí thuê xe', text: 'Chi phí thuê xe như thế nào?' },
  { label: 'Trở thành chủ xe', text: 'Làm sao để trở thành chủ xe Drivio?' },
  { label: 'Bảo hiểm', text: 'Chính sách bảo hiểm của Drivio' },
]
const activeChannels = ref([])

const chatFilterTab = ref('active')

// ---------------- COMPUTED ----------------
const activeHost = computed(() =>
  conversations.value.find(h => h.id === activeConvId.value) ?? null
)

const filteredConversations = computed(() => {
  if (chatFilterTab.value === 'active') {
    return conversations.value.filter(c => c.status === 1 || c.status === '1')
  }
  return conversations.value.filter(c => c.status === 0 || c.status === '0')
})

// ---------------- REALTIME & SOCKETS ----------------
function subscribeToAllConversations() {
  if (!$echo || !conversations.value.length) return

  activeChannels.value.forEach(channelName => {
    $echo.leave(channelName)
  })
  activeChannels.value = []

  conversations.value.forEach(conv => {
    const channelName = `chat.${conv.id}`
    activeChannels.value.push(channelName)
    console.log(`Subscribing to channel: ${channelName}`)

    $echo.private(channelName)
      .listen('.message.sent', (e) => {
        console.log('Realtime message received:', e.message)
        const timeFormatted = new Date(e.message.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })

        if (activeConvId.value === conv.id) {
          // Chỉ hiển thị tin nhắn từ người khác (người gửi đã tự push tin nhắn của mình locally)
          if (e.message.sender_id !== userInfo.value?.id) {
            messages.value.push({
              role: 'other',
              text: e.message.text,
              type: e.message.type || 'text',
              time: timeFormatted
            })
            scrollToBottom()
          }

          chatService.markAsRead(conv.id).catch(err => console.error(err))
        } else {
          if (e.message.sender_id !== userInfo.value?.id) {
            conv.unread_count = (conv.unread_count || 0) + 1
            unreadChatsCount.value++
          }
        }

        if (!conv.last_message) {
          conv.last_message = {}
        }
        conv.last_message.text = e.message.text
        conv.last_message.time = timeFormatted
      })
  })
}

watch(conversations, (newVal) => {
  if (newVal && newVal.length > 0 && activeChannels.value.length === 0) {
    subscribeToAllConversations()
  }
}, { deep: false })

// ---------------- API & SERVICE ACTIONS ----------------
const getConversations = async () => {
  isLoading.value = true
  try {
    const res = await chatService.getConversations()
    if (res && res.conversations) {
      conversations.value = res.conversations
      subscribeToAllConversations()
    }
  } catch (error) {
    console.error('Error loading conversations:', error)
  } finally {
    isLoading.value = false
  }
}

async function fetchBotMessages() {
  isLoading.value = true
  try {
    const data = await chatBotService.getMessages()
    if (data && data.res) {
      botConversationId.value = data.res.id
      if (data.res.messages && data.res.messages.length > 0) {
        messages.value = data.res.messages.map(m => ({
          role: m.role === 'user' ? 'user' : 'bot',
          text: m.content,
          time: new Date(m.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
        }))
        showSuggestions.value = false
      } else {
        messages.value = []
        showSuggestions.value = true
      }

      if (messages.value.length > 0) {
        lastBotMessage.value = messages.value[messages.value.length - 1].text
      } else {
        lastBotMessage.value = 'Xin chào tôi là Chatbot AI'
      }
      await scrollToBottom()
    }
  } catch (error) {
    console.error('Error loading bot messages:', error)
  } finally {
    isLoading.value = false
  }
}

async function fetchHostMessages(id) {
  isLoading.value = true
  try {
    const res = await chatService.getMessagesByConversationId(id)

    if (res && res.messages) {
      messages.value = res.messages.map(m => ({
        role: m.sender_id === userInfo.value?.id ? 'user' : 'other',
        text: m.text,
        type: m.type || 'text',
        time: m.created_at ? new Date(m.created_at).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }) : getTime()
      }))
    } else {
      messages.value = []
    }

    await scrollToBottom()
  } catch (error) {
    console.error('Error loading host messages:', error)
    messages.value = []
  } finally {
    isLoading.value = false
  }
}

// ---------------- CHAT ACTIONS ----------------
async function sendMessage() {
  const text = inputText.value.trim()
  if (!text || isTyping.value) return

  showSuggestions.value = false
  const time = getTime()

  if (activeConvId.value === 'bot') {
    messages.value.push({ role: 'user', text, time })
    inputText.value = ''
    if (inputRef.value) inputRef.value.style.height = 'auto'
    await scrollToBottom()

    isTyping.value = true
    await scrollToBottom()

    try {
      const reply = await chatBotService.sendMessage(botConversationId.value || undefined, text)
      isTyping.value = false
      const replyText = typeof reply === 'string' ? reply : (reply.text || reply)

      messages.value.push({
        role: 'bot',
        text: replyText,
        time: getTime()
      })
      lastBotMessage.value = replyText

      if (!botConversationId.value) {
        await fetchBotMessages()
      }
    } catch (e) {
      isTyping.value = false
      messages.value.push({
        role: 'bot',
        text: 'Có lỗi xảy ra khi kết nối tới Drivio AI. Vui lòng thử lại.',
        time: getTime()
      })
      lastBotMessage.value = 'Có lỗi xảy ra khi kết nối tới Drivio AI. Vui lòng thử lại.'
    }
    await scrollToBottom()
    return
  }

  if (activeHost.value && chat_id.value) {
    if (activeHost.value.last_message) {
      activeHost.value.last_message.text = text
      activeHost.value.last_message.time = time
    }

    // Tự động push tin nhắn vừa gửi vào local để hiển thị ngay lập tức
    messages.value.push({
      role: 'user',
      text: text,
      type: 'text',
      time: time
    })

    inputText.value = ''
    if (inputRef.value) inputRef.value.style.height = 'auto'
    await scrollToBottom()

    try {
      await chatService.storeMessage({
        conversation_id: chat_id.value,
        text: text,
        type: 'text'
      })
    } catch (error) {
      console.error('Error sending message:', error)
    }
    return
  }
}

function sendSuggestion(text) {
  inputText.value = text
  sendMessage()
}

async function selectConv(id) {
  activeConvId.value = id
  chat_id.value = id === 'bot' ? null : id
  showChatOnMobile.value = true
  activeChatId.value = id === 'bot' ? null : id

  if (id === 'bot') {
    await fetchBotMessages()
  } else {
    const host = conversations.value.find(h => h.id === id)
    if (host) {
      if (host.unread_count > 0) {
        unreadChatsCount.value = Math.max(0, unreadChatsCount.value - host.unread_count)
        host.unread_count = 0
      }
      console.log('Fetching host messages:', await fetchHostMessages(id))

      try {
        await chatService.markAsRead(id)
      } catch (error) {
        console.error('Error marking conversation as read:', error)
      }
    }
  }
  await scrollToBottom()
}

function exitChat() {
  showChatOnMobile.value = false
  activeConvId.value = null
  chat_id.value = null
  activeChatId.value = null
}

// ---------------- UI HELPERS ----------------
function getTime() {
  return new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
}

async function scrollToBottom() {
  await nextTick()
  if (chatContainer.value) chatContainer.value.scrollTop = chatContainer.value.scrollHeight
}

function handleKey(e) {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault()
    sendMessage()
  }
}

function autoResize(e) {
  const el = e.target
  el.style.height = 'auto'
  el.style.height = Math.min(el.scrollHeight, 120) + 'px'
}

const imageInputRef = ref(null)

function triggerImageSelect() {
  if (imageInputRef.value) {
    imageInputRef.value.click()
  }
}

async function uploadImage(event) {
  const file = event.target.files[0]
  if (!file) return

  // Kiểm tra kích thước file (tối đa 10MB)
  if (file.size > 10 * 1024 * 1024) {
    alert("Dung lượng hình ảnh tải lên tối đa là 10MB.")
    return
  }

  const time = getTime()
  const localBlobUrl = URL.createObjectURL(file)
  const tempId = 'temp-' + Date.now()

  try {
    // Optimistic UI - Hiển thị ảnh mờ kèm hiệu ứng tải lên ngay lập tức
    messages.value.push({
      id: tempId,
      role: 'user',
      text: localBlobUrl,
      type: 'image',
      isUploading: true,
      time: time
    })
    await scrollToBottom()

    // Tạo FormData gửi lên Backend
    const formData = new FormData()
    formData.append('conversation_id', chat_id.value)
    formData.append('type', 'image')
    formData.append('image', file)

    const res = await chatService.storeMessage(formData)

    if (res && res.success) {
      // Tìm tin nhắn tạm và cập nhật URL ảnh Cloudinary thực tế cùng tắt loading
      const tempMsg = messages.value.find(m => m.id === tempId)
      if (tempMsg) {
        tempMsg.text = res.message.text
        tempMsg.isUploading = false
      }

      // Cập nhật tin nhắn cuối trên sidebar
      if (activeHost.value && activeHost.value.last_message) {
        activeHost.value.last_message.text = '[Hình ảnh]'
        activeHost.value.last_message.time = getTime()
      }
    } else {
      // Xóa tin nhắn tạm thời nếu lỗi
      messages.value = messages.value.filter(m => m.id !== tempId)
      alert("Tải ảnh lên thất bại.")
    }
  } catch (error) {
    console.error("Lỗi gửi ảnh:", error)
    // Xóa tin nhắn tạm thời nếu lỗi
    messages.value = messages.value.filter(m => m.id !== tempId)
    alert("Không thể gửi ảnh.")
  } finally {
    if (imageInputRef.value) {
      imageInputRef.value.value = ''
    }
    // Giải phóng bộ nhớ Blob URL sau khi upload xong
    setTimeout(() => {
      URL.revokeObjectURL(localBlobUrl)
    }, 10000)
  }
}

function openImage(url) {
  lightboxImageUrl.value = url
  showLightbox.value = true
}

const route = useRoute()

// ---------------- LIFECYCLE ----------------
onMounted(async () => {
  await fetchBotMessages()
  await getConversations()

  const targetTripId = route.query.trip_id
  const targetPartnerId = route.query.partner_id || route.query.user_id

  if (targetTripId) {
    const targetConv = conversations.value.find(c => c.trip_id == targetTripId)
    if (targetConv) {
      selectConv(targetConv.id)
    } else if (targetPartnerId) {
      const targetConvByPartner = conversations.value.find(c => c.other_user?.id == targetPartnerId)
      if (targetConvByPartner) {
        selectConv(targetConvByPartner.id)
      }
    }
  } else if (targetPartnerId) {
    const targetConv = conversations.value.find(c => c.other_user?.id == targetPartnerId)
    if (targetConv) {
      selectConv(targetConv.id)
    }
  }
})

onUnmounted(() => {
  activeChatId.value = null
  activeChannels.value.forEach(channelName => {
    if ($echo) {
      $echo.leave(channelName)
    }
  })
})
</script>

<style scoped>
.chat-scroll::-webkit-scrollbar {
  width: 4px;
}

.chat-scroll::-webkit-scrollbar-track {
  background: transparent;
}

.chat-scroll::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 999px;
}

.sidebar-scroll::-webkit-scrollbar {
  width: 3px;
}

.sidebar-scroll::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar-scroll::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 999px;
}

@keyframes dot-bounce {

  0%,
  80%,
  100% {
    transform: translateY(0);
    opacity: 0.4;
  }

  40% {
    transform: translateY(-6px);
    opacity: 1;
  }
}

.dot {
  animation: dot-bounce 1.2s infinite ease-in-out;
}

.dot:nth-child(2) {
  animation-delay: 0.2s;
}

.dot:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes fade-up {
  from {
    opacity: 0;
    transform: translateY(8px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.msg-anim {
  animation: fade-up 0.25s ease-out;
}

.conv-item {
  border-left: 3px solid transparent;
}

.conv-item:hover {
  background: rgba(40, 104, 116, 0.05);
}

.conv-item.active {
  background: rgba(40, 104, 116, 0.08);
  border-left: 3px solid #0d9488;
}
</style>