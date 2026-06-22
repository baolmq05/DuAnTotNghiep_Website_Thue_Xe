<template>
  <div class="h-full flex flex-col overflow-hidden">
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
                  <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                </div>
                <p class="text-xs text-gray-500 truncate">
                  {{ lastBotMessage }}
                </p>
              </div>
            </button>
          </div>

          <!-- Chủ xe -->
          <div class="px-3 pt-3 pb-3">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold mb-1.5 px-1">Chủ xe</p>
            <div class="space-y-0.5">
              <button v-for="host in hostConversations" :key="host.id" @click="selectConv(host.id)"
                :class="['conv-item w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all', activeConvId === host.id ? 'active' : '']">
                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold"
                    :style="{ backgroundColor: host.color }">
                    {{ host.name.charAt(0) }}
                  </div>
                  <span v-if="host.online"
                    class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-green-500 border-2 border-white"></span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between gap-1">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ host.name }}</p>
                    <span class="text-[10px] text-gray-400 flex-shrink-0">{{ host.time }}</span>
                  </div>
                  <div class="flex items-center justify-between gap-1">
                    <p class="text-xs text-gray-500 truncate">{{ host.lastMessage }}</p>
                    <span v-if="host.unread"
                      class="flex-shrink-0 w-4 h-4 rounded-full bg-brand-primary text-white text-[10px] flex items-center justify-center font-medium">{{
                        host.unread }}</span>
                  </div>
                  <!-- Car info badge -->
                  <p class="text-[10px] text-brand-accent font-medium truncate mt-0.5">{{ host.car }}</p>
                </div>
              </button>
            </div>
          </div>
        </div>


      </aside>

      <!-- CHAT AREA -->
      <main :class="[
        'flex-1 flex flex-col overflow-hidden bg-gray-50',
        showChatOnMobile ? 'flex' : 'hidden md:flex'
      ]">

        <!-- Chat header -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 flex items-center gap-3 flex-shrink-0">
          <!-- Back button (Mobile only) -->
          <button @click="showChatOnMobile = false"
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
              <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                <span class="text-xs text-gray-500">Đang hoạt động</span>
              </div>
            </div>
          </template>

          <!-- Host header -->
          <template v-else-if="activeHost">
            <div class="relative flex-shrink-0">
              <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold"
                :style="{ backgroundColor: activeHost.color }">
                {{ activeHost.name.charAt(0) }}
              </div>
              <span v-if="activeHost.online"
                class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-green-500 border-2 border-white"></span>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-800">{{ activeHost.name }}</p>
              <div class="flex items-center gap-1.5">
                <span v-if="activeHost.online" class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                <span class="text-xs text-gray-500">{{ activeHost.online ? 'Đang online' : 'Offline' }} • {{
                  activeHost.car }}</span>
              </div>
            </div>
            <!-- Car info pill -->
            <div class="ml-3 hidden sm:flex items-center gap-1.5 bg-brand-secondary px-3 py-1 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-brand-accent" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" />
              </svg>
              <span class="text-xs text-brand-accent font-medium">{{ activeHost.car }}</span>
            </div>
          </template>


        </div>

        <!-- Messages -->
        <div ref="chatContainer" class="flex-1 overflow-y-auto chat-scroll px-4 md:px-6 py-6 space-y-5">
          <template v-for="(msg, index) in messages" :key="index">

            <!-- User / me (right side) -->
            <div v-if="msg.role === 'user'" class="flex gap-3 justify-end msg-anim">
              <div class="max-w-lg">
                <div class="bg-brand-primary text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow-sm">
                  <p class="text-sm leading-relaxed whitespace-pre-line">{{ msg.text }}</p>
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
              <div v-else-if="activeHost"
                class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5"
                :style="{ backgroundColor: activeHost.color }">
                {{ activeHost.name.charAt(0) }}
              </div>

              <div class="max-w-lg">
                <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                  <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ msg.text }}</p>
                </div>
                <p class="text-xs text-gray-400 mt-1 ml-1">
                  {{ activeConvId === 'bot' ? 'Chatbot Drivio' : activeHost?.name }} • {{ msg.time }}
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
            <div v-else-if="activeHost"
              class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5"
              :style="{ backgroundColor: activeHost.color }">
              {{ activeHost.name.charAt(0) }}
            </div>

            <!-- Thinking Bubble -->
            <div class="max-w-lg">
              <div class="bg-white border border-gray-200/80 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm flex items-center gap-2">
                <span class="text-sm text-gray-500 select-none">
                  {{ activeConvId === 'bot' ? 'Thinking' : `${activeHost?.name} đang nhập` }}
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
        <div class="bg-gray-50 px-4 md:px-6 pb-6 pt-2 flex-shrink-0">
          <div class="flex items-end gap-3 max-w-4xl mx-auto">
            <div class="flex-1 relative">
              <textarea ref="inputRef" v-model="inputText" rows="1"
                :placeholder="activeConvId === 'bot' ? 'Nhập tin nhắn cho Chatbot...' : `Nhắn tin cho ${activeHost?.name ?? 'chủ xe'}...`"
                class="w-full resize-none text-sm text-gray-800 placeholder-gray-400 bg-white border border-gray-200 focus:border-brand-primary focus:outline-none rounded-2xl px-4 py-3 pr-12 transition-all leading-relaxed shadow-sm"
                style="max-height: 120px" @keydown="handleKey" @input="autoResize" />
              <button class="absolute right-3 bottom-3 text-gray-400 hover:text-brand-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5a2.5 2.5 0 0 1 5 0v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5a2.5 2.5 0 0 0 5 0V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z" />
                </svg>
              </button>
            </div>
            <button @click="sendMessage" :disabled="isTyping || !inputText.trim()"
              class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-primary hover:bg-brand-dark text-white flex items-center justify-center transition-colors disabled:opacity-40 disabled:cursor-not-allowed shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="currentColor" d="M2.01 21L23 12L2.01 3L2 10l15 2l-15 2z" />
              </svg>
            </button>
          </div>
          <p v-if="activeConvId === 'bot'" class="text-center text-xs text-gray-400 mt-2">Drivio AI có thể mắc lỗi. Vui
            lòng
            kiểm tra thông tin quan trọng.</p>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: "chat",
});

import { ref, computed, nextTick, onMounted } from 'vue'
import { chatBotService } from '~/services/chatbot.service'

// ---------------- STATE / REFS ----------------
const activeConvId = ref('bot')
const inputText = ref('')
const isTyping = ref(false)
const showSuggestions = ref(true)
const chatContainer = ref(null)
const inputRef = ref(null)
const showChatOnMobile = ref(false)

// Unified Messages State
const messages = ref([])
const lastBotMessage = ref('Xin chào tôi là Chatbot AI')

// Bot State
const botConversationId = ref('')
const suggestions = [
  { label: 'Thuê xe TP.HCM', text: 'Tôi muốn thuê xe ở TP. Hồ Chí Minh' },
  { label: 'Chi phí thuê xe', text: 'Chi phí thuê xe như thế nào?' },
  { label: 'Trở thành chủ xe', text: 'Làm sao để trở thành chủ xe Drivio?' },
  { label: 'Bảo hiểm', text: 'Chính sách bảo hiểm của Drivio' },
]

// Host Mock State
const hostConversations = ref([
  {
    id: 'host-1',
    name: 'Trần Minh Tuấn',
    car: 'Toyota Fortuner 2022',
    color: '#A77E52',
    online: true,
    time: '10:32',
    lastMessage: 'Bạn có thể nhận xe lúc 8h sáng nhé!',
    unread: 2,
    messages: [
      { role: 'other', text: 'Xin chào! Tôi thấy bạn đã đặt xe Fortuner của tôi cho chuyến Đà Lạt cuối tuần này 🎉', time: '10:20' },
      { role: 'user', text: 'Vâng anh ơi! Cho em hỏi xe đổ xăng loại gì ạ?', time: '10:25' },
      { role: 'other', text: 'Xe dùng xăng RON 95 nha bạn. Tôi sẽ đổ đầy bình trước khi giao xe.', time: '10:28' },
      { role: 'other', text: 'Bạn có thể nhận xe lúc 8h sáng nhé!', time: '10:32' },
    ],
  },
  {
    id: 'host-2',
    name: 'Lê Thị Hương',
    car: 'Kia Morning 2021',
    color: '#286874',
    online: false,
    time: 'Hôm qua',
    lastMessage: 'Cảm ơn bạn đã thuê xe nhà tôi!',
    unread: 0,
    messages: [
      { role: 'other', text: 'Chào bạn! Xe Kia Morning của tôi đã sẵn sàng cho chuyến đi của bạn.', time: 'Hôm qua' },
      { role: 'user', text: 'Cảm ơn chị! Chị cho em hỏi xe có camera hành trình không ạ?', time: 'Hôm qua' },
      { role: 'other', text: 'Có bạn nhé, camera hành trình gắn sẵn rồi. Chúc bạn chuyến đi vui vẻ!', time: 'Hôm qua' },
      { role: 'other', text: 'Cảm ơn bạn đã thuê xe nhà tôi!', time: 'Hôm qua' },
    ],
  },
])
const hostReplies = [
  'Được bạn ơi, tôi sẽ xem lại và phản hồi sớm nhé!',
  'Cảm ơn bạn đã hỏi! Để tôi kiểm tra lại thông tin xe cho bạn.',
  'Không vấn đề gì! Bạn cứ liên hệ tôi nếu cần hỗ trợ thêm.',
  'Vâng, xe sẽ được vệ sinh sạch sẽ trước khi giao cho bạn.',
  'Địa điểm giao xe có thể thỏa thuận thêm bạn nhé!',
]

// ---------------------------------- COMPUTED --------------------------------------
const activeHost = computed(() =>
  hostConversations.value.find(h => h.id === activeConvId.value) ?? null
)

// ---------------------------------- API / SERVICE ACTIONS --------------------------------------
async function fetchBotMessages() {
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

      // Update sidebar preview
      if (messages.value.length > 0) {
        lastBotMessage.value = messages.value[messages.value.length - 1].text
      } else {
        lastBotMessage.value = 'Xin chào tôi là Chatbot AI'
      }

      await scrollToBottom()
    }
  } catch (error) {
    console.error('Lỗi khi tải tin nhắn bot:', error)
  }
}

// ---------------------------------- LIFECYCLE --------------------------------------
onMounted(() => {
  fetchBotMessages()
})

// ---------------------------------- CHAT ACTIONS --------------------------------------
async function sendMessage() {
  const text = inputText.value.trim()
  if (!text || isTyping.value) return

  showSuggestions.value = false
  const time = getTime()

  // 1. Chatbot Drivio Logic
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

  // 2. Owner/Host Chat Logic
  if (activeHost.value) {
    messages.value.push({ role: 'user', text, time })
    activeHost.value.lastMessage = text
    activeHost.value.time = time
  }

  inputText.value = ''
  if (inputRef.value) inputRef.value.style.height = 'auto'
  await scrollToBottom()

  isTyping.value = true
  await scrollToBottom()

  setTimeout(async () => {
    isTyping.value = false
    const replyTime = getTime()

    if (activeHost.value) {
      const reply = hostReplies[Math.floor(Math.random() * hostReplies.length)]
      messages.value.push({ role: 'other', text: reply, time: replyTime })
      activeHost.value.lastMessage = reply
      activeHost.value.time = replyTime
    }

    await scrollToBottom()
  }, 1000 + Math.random() * 800)
}

function sendSuggestion(text) {
  inputText.value = text
  sendMessage()
}

async function selectConv(id) {
  activeConvId.value = id
  if (id === 'bot') {
    scrollToBottom();
    await fetchBotMessages()
  } else {
    const host = hostConversations.value.find(h => h.id === id)
    if (host) {
      host.unread = 0
      messages.value = host.messages
    }
  }
  scrollToBottom()
  showChatOnMobile.value = true
}


// ---------------------------------- UI HELPERS --------------------------------------
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
