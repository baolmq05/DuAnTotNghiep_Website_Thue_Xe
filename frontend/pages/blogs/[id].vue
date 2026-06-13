<template>
    <section class="bg-gray-50 min-h-screen">
      <div class="relative h-[400px] md:h-[500px] overflow-hidden">
        <img
          :src="blogPost.image"
          :alt="blogPost.title"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-black/20"></div>
      </div>
  
      <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 mt-4 relative z-10">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 text-center leading-tight max-w-4xl mx-auto">
          {{ blogPost.title }}
        </h1>
        <p class="text-gray-600 text-base md:text-lg text-center font-medium">
          {{ blogPost.author }} • {{ blogPost.date }}
        </p>
      </div>
  
      <div class="max-w-7xl mx-auto px-4 lg:px-8 pb-16 pt-8">
        <div class="grid lg:grid-cols-12 gap-8 items-start">
          
          <div class="lg:col-span-8 xl:col-span-9 order-2 lg:order-1">
            <article class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-10 lg:p-12">
              <template v-for="(block, index) in blogPost.blocks" :key="index">
                <h2 
                  v-if="block.type === 'h2'" 
                  :id="block.id" 
                  class="text-2xl md:text-3xl font-bold text-gray-900 mt-10 mb-5 scroll-mt-24"
                >
                  {{ block.content }}
                </h2>
  
                <h3 
                  v-else-if="block.type === 'h3'" 
                  :id="block.id" 
                  class="text-xl md:text-2xl font-semibold text-gray-800 mt-8 mb-4 scroll-mt-24"
                >
                  {{ block.content }}
                </h3>
  
                <figure v-else-if="block.type === 'image'" class="my-8">
                  <img 
                    :src="block.src" 
                    :alt="block.alt" 
                    class="w-full h-auto rounded-2xl object-cover shadow-sm max-h-[500px]"
                  />
                  <figcaption class="text-center text-sm text-gray-500 mt-3 italic">
                    {{ block.alt }}
                  </figcaption>
                </figure>
  
                <p 
                  v-else-if="block.type === 'paragraph'" 
                  class="text-gray-700 leading-relaxed text-lg mb-5 text-justify"
                >
                  {{ block.content }}
                </p>
              </template>
            </article>
          </div>
  
          <aside class="lg:col-span-4 xl:col-span-3 order-1 lg:order-2 sticky top-24 space-y-6">
            
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
              <h3 class="text-lg font-bold text-gray-900 mb-4 uppercase tracking-wide border-b pb-3">
                Mục lục
              </h3>
              <nav>
                <ul class="space-y-3">
                  <li v-for="item in blogPost.toc" :key="item.id">
                    <a 
                      :href="`#${item.id}`" 
                      class="block text-gray-600 hover:text-blue-600 transition-colors duration-200"
                      :class="{ 'font-semibold text-gray-800': item.level === 2, 'ml-4 text-sm': item.level === 3 }"
                    >
                      {{ item.title }}
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
  
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hidden lg:block">
              <h3 class="text-lg font-bold text-gray-900 mb-4 uppercase tracking-wide border-b pb-3">
                Bài viết liên quan
              </h3>
              <ul class="space-y-5">
                <li v-for="post in relatedPosts" :key="post.id">
                  <NuxtLink
                    :to="`/blogs/${post.id}`"
                    class="flex items-center gap-4 group"
                  >
                    <img
                      :src="post.image"
                      :alt="post.title"
                      class="w-20 h-20 object-cover rounded-xl shadow-sm group-hover:opacity-80 transition"
                    />
                    <div>
                      <h4 class="font-semibold text-gray-800 leading-snug group-hover:text-blue-600 transition line-clamp-2">
                        {{ post.title }}
                      </h4>
                      <p class="text-xs text-gray-500 mt-2">{{ post.date }}</p>
                    </div>
                  </NuxtLink>
                </li>
              </ul>
            </div>
          </aside>
  
        </div>
      </div>
    </section>
  </template>
  
  <script lang="ts" setup>
  import { ref } from 'vue'
  import { useRoute } from 'vue-router'
  
  const route = useRoute()
  const blogId = route.params.id
  
  // Dữ liệu bài viết được cấu trúc hoá theo từng khối (blocks)
  const blogPost = ref({
    id: blogId,
    title: 'Gợi ý những địa điểm cặp đôi hẹn hò cuối tuần bằng xe tự lái 4 chỗ',
    author: 'Võ Hoàng Phi',
    date: '13/06/2026',
    image: `/images/about/policy.webp`, 
    
    // Dữ liệu tạo mục lục tự động
    toc: [
      { id: 'phan-1', title: 'I. Gợi ý địa điểm lý tưởng', level: 2 },
      { id: 'diem-1', title: '1. Dạo phố, quán cà phê hot', level: 3 },
      { id: 'diem-2', title: '2. Vi vu ngoại thành', level: 3 },
      { id: 'diem-3', title: '3. Picnic bãi cỏ ven sông', level: 3 },
      { id: 'diem-4', title: '4. Ngắm hoàng hôn', level: 3 },
      { id: 'phan-2', title: 'II. Tips chọn xe tự lái 4 chỗ', level: 2 },
      { id: 'xe-1', title: '1. Xe phổ thông', level: 3 },
      { id: 'xe-2', title: '2. Xe sang trọng', level: 3 },
      { id: 'xe-3', title: '3. Chọn theo phong cách', level: 3 },
    ],
  
    // Cấu trúc nội dung đan xen văn bản và hình ảnh
    blocks: [
      { type: 'paragraph', content: 'Một buổi hẹn hò cuối tuần sẽ thú vị hơn khi cả hai cùng lên xe và bắt đầu một hành trình nhỏ. Không cần đi quá xa hay chuẩn bị lịch trình cầu kỳ, chỉ cần một chiếc xe tự lái 4 chỗ và vài địa điểm phù hợp là đã đủ để tạo nên một buổi hẹn nhẹ nhàng, riêng tư và đáng nhớ.' },
      { type: 'paragraph', content: 'So với những buổi gặp gỡ quen thuộc tại quán cà phê, xu hướng thuê xe tự lái để hẹn hò cuối tuần đang ngày càng được nhiều couple lựa chọn. Việc chủ động phương tiện giúp cả hai dễ dàng di chuyển giữa nhiều địa điểm khác nhau, tận hưởng cảm giác tự do khám phá và dành nhiều thời gian bên nhau hơn.' },
      
      { type: 'h2', id: 'phan-1', content: 'I. Một vài gợi ý địa điểm lý tưởng bằng xe tự lái dành cho các cặp đôi' },
      
      { type: 'h3', id: 'diem-1', content: '1. Dạo phố, checkin khám phá quán cà phê hot' },
      { type: 'image', src: '/images/about/policy.webp', alt: 'Cặp đôi check-in quán cà phê đẹp bằng xe tự lái' },
      { type: 'paragraph', content: 'Một trong những kiểu hẹn hò của couple hiện nay đơn giản nhưng vẫn luôn mang lại cảm giác thú vị là cùng nhau lái xe dạo quanh thành phố và ghé vào những quán cà phê có vibe đẹp. Những quán rooftop, quán có view sông hoặc các tiệm cà phê nhỏ nằm trong con hẻm yên tĩnh thường mang lại không gian riêng tư cho các cặp đôi.' },
      { type: 'paragraph', content: 'Một buổi chiều chậm rãi, vài điểm dừng nhỏ, một tách cà phê và những câu chuyện riêng cũng đủ để tạo nên một buổi hẹn hò nhẹ nhàng.' },
  
      { type: 'h3', id: 'diem-2', content: '2. Vi vu ngoại thành đổi gió trong ngày' },
      { type: 'image', src: '/images/about/policy.webp', alt: 'Trải nghiệm vi vu ngoại thành cuối tuần' },
      { type: 'paragraph', content: 'Nếu muốn thay đổi không khí, một chuyến du lịch tự lái ngắn ngày ra ngoại thành cũng là lựa chọn rất phù hợp cho các cặp đôi. Chỉ cần khoảng 1-3 tiếng, bạn đã có thể đến những khu camping ngoại ô hoặc vi vu dạo biển xanh.' },
  
      { type: 'h3', id: 'diem-3', content: '3. Picnic ở công viên hoặc bãi cỏ ven sông' },
      { type: 'image', src: '/images/about/policy.webp', alt: 'Setup picnic nhẹ nhàng bên không gian xanh' },
      { type: 'paragraph', content: 'Một buổi picnic nhỏ với vài món ăn nhẹ, trái cây hoặc bánh ngọt cũng là ý tưởng hẹn hò khá thú vị. Với xe tự lái 4 chỗ, các couple có thể chuẩn bị sẵn đồ ăn nhẹ như bánh ngọt, nước uống thảm picnic. Dễ dàng di chuyển đến những địa điểm picnic gần thành phố để tận hưởng không gian thoáng đãng, riêng tư.' },
      { type: 'paragraph', content: 'Không cần quá cầu kỳ, chỉ cần một góc nhỏ yên tĩnh, chút đồ ăn và một bản nhạc nhẹ cũng đủ để tạo nên những khoảnh khắc đáng nhớ trong buổi hẹn hò cuối tuần.' },
  
      { type: 'h3', id: 'diem-4', content: '4. Lái xe ngắm hoàng hôn hoặc dạo phố đêm' },
      { type: 'paragraph', content: 'Một trải nghiệm lãng mạn mà nhiều cặp đôi yêu thích là cùng nhau lái xe vào buổi chiều muộn để ngắm hoàng hôn hoặc thành phố khi lên đèn. Những cung đường ven sông, cầu lớn hay các tòa nhà có view ngắm cảnh thường mang lại khung cảnh rất đẹp vào thời điểm này.' },
      { type: 'paragraph', content: 'Chỉ cần mở một playlist chill, trò chuyện trên hành trình và dừng lại ở một điểm có view đẹp, buổi hẹn hò bằng xe tự lái đã trở nên đặc biệt hơn rất nhiều.' },
  
      { type: 'h2', id: 'phan-2', content: 'II. Tips chọn xe tự lái 4 chỗ cho Couple hẹn hò cuối tuần' },
      { type: 'paragraph', content: 'Một buổi hẹn hò cuối tuần bằng xe tự lái 4 chỗ không chỉ là việc chọn điểm đến mà còn là lựa chọn chiếc xe phù hợp với không khí của buổi hẹn. Tùy vào phong cách và kế hoạch di chuyển, các couple có thể cân nhắc giữa xe phổ thông và xe sang để có trải nghiệm trọn vẹn hơn.' },
  
      { type: 'h3', id: 'xe-1', content: '1. Xe 4 chỗ phổ thông - Phù hợp cho những buổi hẹn nhẹ nhàng' },
      { type: 'image', src: '/images/about/policy.webp', alt: 'Các dòng xe phổ thông tiện lợi' },
      { type: 'paragraph', content: 'Nếu kế hoạch cuối tuần chỉ đơn giản là dạo phố, ghé quán cà phê hoặc chạy xe vòng quanh thành phố, các dòng xe phổ thông 4 chỗ sẽ là lựa chọn khá hợp lý. Những mẫu xe này thường dễ lái, linh hoạt khi di chuyển trong khu vực đông xe và thuận tiện khi tìm chỗ đỗ tại các quán ăn, trung tâm thương mại hay những con phố nhỏ. Bạn có thể tham khảo một số dòng xe phổ biến trên DRIVIO đến từ các thương hiệu như Toyota, Hyundai, Kia, Mazda hay Honda.' },
      { type: 'paragraph', content: 'Bên cạnh sự tiện lợi khi di chuyển, xe phổ thông cũng mang lại cảm giác thoải mái và gần gũi cho buổi hẹn. Với những cặp đôi yêu thích sự đơn giản, chỉ cần một chiếc xe gọn gàng, nội thất sạch sẽ và vận hành ổn định là đã đủ để cùng nhau bắt đầu hành trình cuối tuần nhẹ nhàng và thư giãn.' },
  
      { type: 'h3', id: 'xe-2', content: '2. Xe 4 chỗ sang trọng - Tạo điểm nhấn cho buổi hẹn đặc biệt' },
      { type: 'paragraph', content: 'Trong những dịp đặc biệt như kỷ niệm ngày quen nhau hoặc một buổi hẹn được chuẩn bị kỹ lưỡng, xe sang 4 chỗ có thể giúp trải nghiệm trở nên ấn tượng hơn. Thiết kế sang trọng, nội thất cao cấp và không gian yên tĩnh trong xe mang lại cảm giác chỉn chu và khác biệt.' },
      { type: 'paragraph', content: 'Lái xe dạo phố buổi tối, ghé một nhà hàng đẹp hoặc ngắm cảnh thành phố khi lên đèn trong một chiếc xe sang cũng là cách nhiều couple tạo thêm cảm xúc cho buổi hẹn cuối tuần. Nếu muốn thử trải nghiệm này, bạn có thể tham khảo một số dòng xe như Mercedes, BMW hay Audi đang có sẵn trên ứng dụng DRIVIO.' },
  
      { type: 'h3', id: 'xe-3', content: '3. Chọn xe theo phong cách của buổi hẹn' },
      { type: 'paragraph', content: 'Điều quan trọng là chiếc xe tự lái 4 chỗ nên phù hợp với kế hoạch của buổi hẹn. Nếu muốn một chuyến đi nhẹ nhàng và linh hoạt, xe phổ thông là lựa chọn hợp lý. Nếu muốn trải nghiệm mới mẻ và tạo cảm giác đặc biệt hơn, xe sang sẽ giúp hành trình trở nên đáng nhớ.' },
      { type: 'paragraph', content: 'Dù là xe sang hay xe phổ thông, khi lựa chọn được chiếc xe phù hợp, buổi hẹn hò cuối tuần sẽ trở nên trọn vẹn hơn - nơi cả hai có thể tận hưởng từng khoảnh khắc lãng mạn trên hành trình cùng nhau.' },
  
      { type: 'h2', id: 'tong-ket', content: 'Tổng kết' },
      { type: 'paragraph', content: 'Một buổi hẹn hò cuối tuần bằng xe tự lái 4 chỗ không chỉ là chuyến đi mà còn là khoảng thời gian để cả hai cùng tận hưởng hành trình theo cách riêng. Từ dạo phố, khám phá quán cà phê đến vi vu ngoại thành hay picnic nhẹ nhàng, mỗi điểm đến đều có thể trở thành một kỷ niệm đáng nhớ.' },
      { type: 'paragraph', content: 'Chỉ cần chọn một chiếc xe phù hợp trên DRIVIO và vài địa điểm thú vị, chuyến đi cuối tuần cũng đủ để mang lại những khoảnh khắc lãng mạn và thư giãn bên người thương.' }
    ]
  })
  
  // Dữ liệu mẫu bài viết liên quan
  const relatedPosts = ref([
    {
      id: 1,
      title: 'Top 5 cung đường ven biển đẹp nhất miền Nam cho chuyến Roadtrip',
      date: '10/06/2026',
      image: `/images/about/policy.webp`,
    },
    {
      id: 2,
      title: 'Bí kíp thuê xe tự lái cho người mới bắt đầu trên DRIVIO',
      date: '11/06/2026',
      image: `/images/about/policy.webp`,
    },
    {
      id: 3,
      title: 'Kinh nghiệm chuẩn bị đồ đạc cho chuyến cắm trại ngoại thành',
      date: '12/06/2026',
      image: `/images/about/policy.webp`,
    },
  ])
  </script>
  
  <style scoped>
  html {
    scroll-behavior: smooth; /* Hỗ trợ scroll mượt khi click vào mục lục */
  }
  
  /* Thêm thuộc tính scroll-margin-top để khi click mục lục, heading không bị dính sát mép trên */
  .scroll-mt-24 {
    scroll-margin-top: 6rem;
  }
  </style>