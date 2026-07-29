# 🔍 Tài Liệu SEO — Website Thuê Xe DRIVIO

> **Framework:** Nuxt 3 (Vue 3 + SSR)  
> **Phương pháp SEO:** `useSeoMeta()` & `useHead()` (Nuxt built-in composables)  
> **Cập nhật lần cuối:** 29/07/2026

---

## 📑 Mục Lục

- [1. Tổng Quan Chiến Lược SEO](#1-tổng-quan-chiến-lược-seo)
- [2. Cấu Hình Global SEO (nuxt.config.ts)](#2-cấu-hình-global-seo-nuxtconfigts)
- [3. SEO Cho Từng Trang](#3-seo-cho-từng-trang)
  - [3.1. Trang Công Khai (Public Pages)](#31-trang-công-khai-public-pages)
  - [3.2. Trang Người Dùng (User Pages)](#32-trang-người-dùng-user-pages)
  - [3.3. Trang Quản Lý Xe (Car Owner Pages)](#33-trang-quản-lý-xe-car-owner-pages)
  - [3.4. Trang Chính Sách (Policy Pages)](#34-trang-chính-sách-policy-pages)
- [4. Trạng Thái Triển Khai](#4-trạng-thái-triển-khai)
- [5. Hướng Dẫn Code Mẫu](#5-hướng-dẫn-code-mẫu)

---

## 1. Tổng Quan Chiến Lược SEO

### Bộ Từ Khóa Chính (Primary Keywords)

| Nhóm | Từ khóa |
|------|---------|
| **Core** | thuê xe tự lái, thuê xe ô tô, cho thuê xe, drivio |
| **Loại xe** | thuê xe 4 chỗ, thuê xe 7 chỗ, thuê xe điện, thuê xe bán tải, thuê xe hạng sang |
| **Địa điểm** | thuê xe Cần Thơ, thuê xe tự lái Cần Thơ, thuê xe giá rẻ |
| **Hành động** | đặt xe online, thuê xe nhanh, thuê xe có tài xế, giao xe tận nơi |
| **Chủ xe** | cho thuê xe kiếm tiền, đăng ký cho thuê xe, chủ xe drivio |
| **Trust** | thuê xe uy tín, thuê xe bảo hiểm, thuê xe an toàn |

### Quy Tắc Đặt Title

```
[Nội dung trang] | DRIVIO — [Tagline ngắn]
```

- Giữ title dưới **60 ký tự** (tối ưu hiển thị Google)
- Luôn chứa **brand name "DRIVIO"**
- Từ khóa chính đặt **đầu title**

### Quy Tắc Meta Description

- Dài **150–160 ký tự** (tối ưu snippet Google)
- Chứa **từ khóa chính** + **CTA** (lời kêu gọi hành động)
- Viết dưới dạng **câu hoàn chỉnh, hấp dẫn**

---

## 2. Cấu Hình Global SEO (nuxt.config.ts)

Thêm SEO mặc định toàn cục trong `nuxt.config.ts`:

```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  app: {
    head: {
      htmlAttrs: { lang: 'vi' },
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'DRIVIO — Thuê Xe Tự Lái Uy Tín Hàng Đầu',
      meta: [
        { name: 'description', content: 'DRIVIO - Nền tảng thuê xe tự lái uy tín hàng đầu Việt Nam. Đa dạng dòng xe, giá tốt, bảo hiểm đầy đủ, giao xe tận nơi.' },
        { name: 'keywords', content: 'thuê xe tự lái, thuê xe ô tô, cho thuê xe, drivio, thuê xe Cần Thơ, thuê xe giá rẻ' },
        { name: 'author', content: 'DRIVIO' },
        { name: 'robots', content: 'index, follow' },
        // Open Graph
        { property: 'og:type', content: 'website' },
        { property: 'og:site_name', content: 'DRIVIO' },
        { property: 'og:locale', content: 'vi_VN' },
        // Twitter Card
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:site', content: '@drivio_vn' },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/logo.png' },
        { rel: 'canonical', href: 'https://drivio.vn' }
      ]
    }
  }
})
```

---

## 3. SEO Cho Từng Trang

### 3.1. Trang Công Khai (Public Pages)

---

#### 🏠 Trang Chủ — `pages/index.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/` |
| **Title** | `DRIVIO — Thuê Xe Tự Lái Uy Tín, Giá Tốt Hàng Đầu Việt Nam` |
| **Meta Description** | `Thuê xe tự lái 4 chỗ, 7 chỗ, xe điện, xe hạng sang giá tốt tại DRIVIO. Đặt xe online nhanh chóng, bảo hiểm đầy đủ, giao xe tận nơi. Hỗ trợ 24/7.` |
| **Keywords** | `thuê xe tự lái, thuê xe ô tô, đặt xe online, cho thuê xe, drivio, thuê xe giá rẻ, thuê xe Cần Thơ` |
| **OG Title** | `DRIVIO — Nền Tảng Thuê Xe Tự Lái Hàng Đầu` |
| **OG Description** | `Đặt xe tự lái đa dạng dòng xe, giá tốt, bảo hiểm toàn diện tại DRIVIO.` |
| **OG Image** | `/images/og/home-banner.jpg` |

```typescript
useSeoMeta({
  title: 'DRIVIO — Thuê Xe Tự Lái Uy Tín, Giá Tốt Hàng Đầu Việt Nam',
  description: 'Thuê xe tự lái 4 chỗ, 7 chỗ, xe điện, xe hạng sang giá tốt tại DRIVIO. Đặt xe online nhanh chóng, bảo hiểm đầy đủ, giao xe tận nơi. Hỗ trợ 24/7.',
  keywords: 'thuê xe tự lái, thuê xe ô tô, đặt xe online, cho thuê xe, drivio, thuê xe giá rẻ, thuê xe Cần Thơ',
  ogTitle: 'DRIVIO — Nền Tảng Thuê Xe Tự Lái Hàng Đầu',
  ogDescription: 'Đặt xe tự lái đa dạng dòng xe, giá tốt, bảo hiểm toàn diện tại DRIVIO.',
  ogImage: '/images/og/home-banner.jpg',
  ogType: 'website',
  twitterCard: 'summary_large_image',
})
```

---

#### 📋 Danh Sách Xe — `pages/vehicle-list.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/vehicle-list` |
| **Title** | `Danh Sách Xe Cho Thuê Tự Lái | DRIVIO` |
| **Meta Description** | `Tìm và đặt thuê xe tự lái phù hợp tại DRIVIO. Lọc theo hãng xe, số chỗ, khoảng giá. Đa dạng xe 4-7 chỗ, xe điện, xe hạng sang với giá ưu đãi.` |
| **Keywords** | `danh sách xe cho thuê, tìm xe tự lái, thuê xe 4 chỗ, thuê xe 7 chỗ, lọc xe cho thuê, so sánh giá thuê xe` |
| **OG Title** | `Khám Phá Xe Cho Thuê Tự Lái | DRIVIO` |
| **OG Description** | `Hàng trăm xe tự lái đa dạng dòng xe chờ bạn khám phá tại DRIVIO.` |

```typescript
useSeoMeta({
  title: 'Danh Sách Xe Cho Thuê Tự Lái | DRIVIO',
  description: 'Tìm và đặt thuê xe tự lái phù hợp tại DRIVIO. Lọc theo hãng xe, số chỗ, khoảng giá. Đa dạng xe 4-7 chỗ, xe điện, xe hạng sang với giá ưu đãi.',
  keywords: 'danh sách xe cho thuê, tìm xe tự lái, thuê xe 4 chỗ, thuê xe 7 chỗ, lọc xe cho thuê, so sánh giá thuê xe',
  ogTitle: 'Khám Phá Xe Cho Thuê Tự Lái | DRIVIO',
  ogDescription: 'Hàng trăm xe tự lái đa dạng dòng xe chờ bạn khám phá tại DRIVIO.',
  ogImage: '/images/og/vehicle-list.jpg',
  twitterCard: 'summary_large_image',
})
```

---

#### 🚗 Chi Tiết Xe — `pages/vehicles/[id].vue`

> **Lưu ý:** Trang dynamic — SEO meta được cập nhật sau khi fetch dữ liệu xe từ API.

| Thuộc tính | Giá trị (Template) |
|------------|---------|
| **Route** | `/vehicles/:id` |
| **Title** | `Thuê {Tên Xe} — {Giá}/ngày | DRIVIO` |
| **Meta Description** | `Thuê {Tên Xe} {Số chỗ} chỗ, {Hộp số}, {Nhiên liệu} tại DRIVIO. Giá chỉ từ {Giá}đ/ngày. Xe {Năm SX}, đầy đủ bảo hiểm. Đặt xe ngay!` |
| **Keywords** | `thuê {tên xe}, thuê xe {hãng xe}, cho thuê {tên xe}, thuê xe tự lái {tên xe}` |
| **OG Image** | Ảnh thumbnail của xe |

```typescript
// Gọi sau khi fetch dữ liệu xe thành công
useSeoMeta({
  title: `Thuê ${car.value.name} — ${formatPrice(car.value.unit_price)}/ngày | DRIVIO`,
  description: `Thuê ${car.value.name} ${car.value.seat_count} chỗ, ${car.value.transmission}, ${car.value.fuel_type} tại DRIVIO. Giá chỉ từ ${formatPrice(car.value.unit_price)}đ/ngày. Đầy đủ bảo hiểm, đặt xe ngay!`,
  keywords: `thuê ${car.value.name}, thuê xe ${car.value.car_brand?.brand_name}, cho thuê ${car.value.name}, thuê xe tự lái ${car.value.name}`,
  ogTitle: `Thuê ${car.value.name} | DRIVIO`,
  ogDescription: `Xe ${car.value.seat_count} chỗ, ${car.value.transmission}. Giá từ ${formatPrice(car.value.unit_price)}đ/ngày tại DRIVIO.`,
  ogImage: thumbnailUrl,
  ogType: 'product',
  twitterCard: 'summary_large_image',
})
```

---

#### ℹ️ Giới Thiệu — `pages/about.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/about` |
| **Title** | `Giới Thiệu DRIVIO — Nền Tảng Thuê Xe Tự Lái Uy Tín` |
| **Meta Description** | `Tìm hiểu về DRIVIO — nền tảng kết nối chia sẻ xe tự lái hàng đầu Việt Nam. 10K+ khách hàng, 500+ xe đa dạng, bảo hiểm đầy đủ, hỗ trợ 24/7.` |
| **Keywords** | `giới thiệu drivio, về chúng tôi drivio, nền tảng thuê xe, chia sẻ xe tự lái, thuê xe uy tín` |
| **OG Title** | `Giới Thiệu Về DRIVIO` |
| **OG Description** | `Nền tảng chia sẻ xe ô tô tự lái uy tín hàng đầu Việt Nam.` |
| **OG Image** | `/images/about/aboutHero.jpg` |

```typescript
useSeoMeta({
  title: 'Giới Thiệu DRIVIO — Nền Tảng Thuê Xe Tự Lái Uy Tín',
  description: 'Tìm hiểu về DRIVIO — nền tảng kết nối chia sẻ xe tự lái hàng đầu Việt Nam. 10K+ khách hàng, 500+ xe đa dạng, bảo hiểm đầy đủ, hỗ trợ 24/7.',
  keywords: 'giới thiệu drivio, về chúng tôi drivio, nền tảng thuê xe, chia sẻ xe tự lái, thuê xe uy tín',
  ogTitle: 'Giới Thiệu Về DRIVIO',
  ogDescription: 'Nền tảng chia sẻ xe ô tô tự lái uy tín hàng đầu Việt Nam.',
  ogImage: '/images/about/aboutHero.jpg',
  twitterCard: 'summary_large_image',
})
```

---

#### 📞 Liên Hệ — `pages/contact.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/contact` |
| **Title** | `Liên Hệ & Hỗ Trợ Khách Hàng | DRIVIO` |
| **Meta Description** | `Liên hệ DRIVIO — Hotline 1900 8888 hỗ trợ 24/7. Giải đáp thắc mắc thuê xe, tiếp nhận khiếu nại, tư vấn đặt xe nhanh chóng và chuyên nghiệp.` |
| **Keywords** | `liên hệ drivio, hỗ trợ thuê xe, hotline drivio, tư vấn thuê xe, khiếu nại drivio` |
| **OG Title** | `Liên Hệ DRIVIO — Hỗ Trợ 24/7` |
| **OG Description** | `Hotline 1900 8888 — hỗ trợ đặt xe, giải đáp thắc mắc, tiếp nhận phản hồi.` |

```typescript
useSeoMeta({
  title: 'Liên Hệ & Hỗ Trợ Khách Hàng | DRIVIO',
  description: 'Liên hệ DRIVIO — Hotline 1900 8888 hỗ trợ 24/7. Giải đáp thắc mắc thuê xe, tiếp nhận khiếu nại, tư vấn đặt xe nhanh chóng và chuyên nghiệp.',
  keywords: 'liên hệ drivio, hỗ trợ thuê xe, hotline drivio, tư vấn thuê xe, khiếu nại drivio',
  ogTitle: 'Liên Hệ DRIVIO — Hỗ Trợ 24/7',
  ogDescription: 'Hotline 1900 8888 — hỗ trợ đặt xe, giải đáp thắc mắc, tiếp nhận phản hồi.',
  ogImage: '/images/og/contact.jpg',
  twitterCard: 'summary_large_image',
})
```

---

#### 📖 Hướng Dẫn Đặt Xe — `pages/bookinghowto.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/bookinghowto` |
| **Title** | `Hướng Dẫn Đặt Xe Tự Lái Online | DRIVIO` |
| **Meta Description** | `Hướng dẫn 4 bước đặt thuê xe tự lái tại DRIVIO: chọn xe, đặt lịch, thanh toán, nhận xe. Quy trình minh bạch, đơn giản, nhanh chóng.` |
| **Keywords** | `hướng dẫn thuê xe, cách đặt xe tự lái, quy trình thuê xe online, thuê xe drivio, đặt xe nhanh` |
| **OG Title** | `Hướng Dẫn Đặt Xe | DRIVIO` |
| **OG Description** | `4 bước đơn giản để thuê xe tự lái tại DRIVIO.` |

```typescript
useSeoMeta({
  title: 'Hướng Dẫn Đặt Xe Tự Lái Online | DRIVIO',
  description: 'Hướng dẫn 4 bước đặt thuê xe tự lái tại DRIVIO: chọn xe, đặt lịch, thanh toán, nhận xe. Quy trình minh bạch, đơn giản, nhanh chóng.',
  keywords: 'hướng dẫn thuê xe, cách đặt xe tự lái, quy trình thuê xe online, thuê xe drivio, đặt xe nhanh',
  ogTitle: 'Hướng Dẫn Đặt Xe | DRIVIO',
  ogDescription: '4 bước đơn giản để thuê xe tự lái tại DRIVIO.',
  ogImage: '/images/og/booking-guide.jpg',
  twitterCard: 'summary_large_image',
})
```

---

#### 📝 Blog — `pages/blogs/index.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/blogs` |
| **Title** | `Blog Thuê Xe Tự Lái | Kinh Nghiệm & Mẹo Hay — DRIVIO` |
| **Meta Description** | `Khám phá blog DRIVIO — kinh nghiệm thuê xe tự lái, mẹo chọn xe phù hợp, đánh giá dòng xe phổ biến, tin tức khuyến mãi và hướng dẫn đặt xe online.` |
| **Keywords** | `blog thuê xe, kinh nghiệm thuê xe tự lái, mẹo chọn xe, đánh giá xe, tin tức thuê xe, khuyến mãi thuê xe` |
| **OG Title** | `Blog Thuê Xe Tự Lái | DRIVIO` |
| **OG Description** | `Mẹo chọn xe, kinh nghiệm đi đường dài, đánh giá xe và khuyến mãi thuê xe — tất cả tại Blog DRIVIO.` |

```typescript
useSeoMeta({
  title: 'Blog Thuê Xe Tự Lái | Kinh Nghiệm & Mẹo Hay — DRIVIO',
  description: 'Khám phá blog DRIVIO — kinh nghiệm thuê xe tự lái, mẹo chọn xe phù hợp, đánh giá dòng xe phổ biến, tin tức khuyến mãi và hướng dẫn đặt xe online.',
  keywords: 'blog thuê xe, kinh nghiệm thuê xe tự lái, mẹo chọn xe, đánh giá xe, tin tức thuê xe, khuyến mãi thuê xe',
  ogTitle: 'Blog Thuê Xe Tự Lái | DRIVIO',
  ogDescription: 'Mẹo chọn xe, kinh nghiệm đi đường dài, đánh giá xe và khuyến mãi thuê xe — tất cả tại Blog DRIVIO.',
  ogImage: '/images/og/blog.jpg',
  twitterCard: 'summary_large_image',
})
```

---

#### 📄 Chi Tiết Blog — `pages/blogs/[id].vue`

> **Lưu ý:** Trang dynamic — SEO meta cập nhật sau khi fetch bài viết từ API.

| Thuộc tính | Giá trị (Template) |
|------------|---------|
| **Route** | `/blogs/:id` |
| **Title** | `{Tiêu đề bài viết} | Blog DRIVIO` |
| **Meta Description** | `{Excerpt của bài viết}` (fallback: `Đọc chi tiết bài viết trên Blog DRIVIO.`) |
| **Keywords** | `{slug bài viết}, blog thuê xe, kinh nghiệm thuê xe, drivio blog` |
| **OG Image** | Thumbnail bài viết |

```typescript
// Gọi sau khi fetch bài viết thành công
useSeoMeta({
  title: `${post.title} | Blog DRIVIO`,
  description: post.excerpt || 'Đọc chi tiết bài viết trên Blog DRIVIO.',
  keywords: `${post.slug}, blog thuê xe, kinh nghiệm thuê xe, drivio blog`,
  ogTitle: `${post.title} | Blog DRIVIO`,
  ogDescription: post.excerpt || 'Đọc chi tiết bài viết trên Blog DRIVIO.',
  ogImage: getThumbnailUrl(post.thumbnail),
  ogType: 'article',
  twitterCard: 'summary_large_image',
})

useHead({
  link: [
    {
      rel: 'canonical',
      href: `https://drivio.vn/blogs/${post.slug}`
    }
  ]
})
```

---

### 3.2. Trang Người Dùng (User Pages)

> **Lưu ý SEO:** Các trang này yêu cầu đăng nhập, Google thường không index. Nên thêm `robots: 'noindex, nofollow'` để tránh lỗi crawl.

---

#### 👤 Thông Tin Tài Khoản — `pages/profile/index.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/profile` |
| **Title** | `Thông Tin Tài Khoản | DRIVIO` |
| **Meta Description** | `Quản lý thông tin cá nhân, cập nhật hồ sơ, xác minh bằng lái xe và quản lý tài khoản DRIVIO của bạn.` |
| **Keywords** | `tài khoản drivio, thông tin cá nhân, hồ sơ người dùng, cập nhật tài khoản` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Thông Tin Tài Khoản | DRIVIO',
  description: 'Quản lý thông tin cá nhân, cập nhật hồ sơ, xác minh bằng lái xe và quản lý tài khoản DRIVIO của bạn.',
  keywords: 'tài khoản drivio, thông tin cá nhân, hồ sơ người dùng',
  robots: 'noindex, nofollow',
})
```

---

#### 👤 Hồ Sơ Công Khai — `pages/profile/[id].vue`

> **Lưu ý:** Trang dynamic — có thể index vì là hồ sơ công khai.

| Thuộc tính | Giá trị (Template) |
|------------|---------|
| **Route** | `/profile/:id` |
| **Title** | `{Tên người dùng} — Hồ Sơ Chủ Xe | DRIVIO` |
| **Meta Description** | `Xem hồ sơ và đánh giá của {Tên người dùng} trên DRIVIO. {Số xe} xe cho thuê, {Số chuyến} chuyến thành công.` |
| **Keywords** | `hồ sơ chủ xe, đánh giá chủ xe, chủ xe drivio, {tên người dùng}` |

```typescript
useSeoMeta({
  title: `${user.name} — Hồ Sơ Chủ Xe | DRIVIO`,
  description: `Xem hồ sơ và đánh giá của ${user.name} trên DRIVIO.`,
  keywords: `hồ sơ chủ xe, đánh giá chủ xe, chủ xe drivio`,
  ogTitle: `${user.name} | DRIVIO`,
  ogImage: user.avatar || '/images/og/default-user.jpg',
})
```

---

#### 🔑 Đổi Mật Khẩu — `pages/profile/change-password.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/profile/change-password` |
| **Title** | `Đổi Mật Khẩu | DRIVIO` |
| **Meta Description** | `Thay đổi mật khẩu tài khoản DRIVIO để bảo vệ tài khoản của bạn.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Đổi Mật Khẩu | DRIVIO',
  description: 'Thay đổi mật khẩu tài khoản DRIVIO để bảo vệ tài khoản của bạn.',
  robots: 'noindex, nofollow',
})
```

---

#### 📍 Quản Lý Địa Chỉ — `pages/profile/address.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/profile/address` |
| **Title** | `Quản Lý Địa Chỉ | DRIVIO` |
| **Meta Description** | `Thêm, sửa, xóa địa chỉ nhận xe và giao xe trên tài khoản DRIVIO của bạn.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Quản Lý Địa Chỉ | DRIVIO',
  description: 'Thêm, sửa, xóa địa chỉ nhận xe và giao xe trên tài khoản DRIVIO của bạn.',
  robots: 'noindex, nofollow',
})
```

---

#### ❤️ Xe Yêu Thích — `pages/profile/favorite.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/profile/favorite` |
| **Title** | `Xe Yêu Thích Của Tôi | DRIVIO` |
| **Meta Description** | `Xem danh sách xe yêu thích đã lưu, so sánh giá và đặt thuê xe nhanh chóng trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Xe Yêu Thích Của Tôi | DRIVIO',
  description: 'Xem danh sách xe yêu thích đã lưu, so sánh giá và đặt thuê xe nhanh chóng trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 🗺️ Chuyến Đi Của Tôi — `pages/profile/my-trips.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/profile/my-trips` |
| **Title** | `Chuyến Đi Của Tôi | DRIVIO` |
| **Meta Description** | `Quản lý các chuyến đi đã đặt, theo dõi trạng thái thuê xe và lịch sử chuyến đi trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Chuyến Đi Của Tôi | DRIVIO',
  description: 'Quản lý các chuyến đi đã đặt, theo dõi trạng thái thuê xe và lịch sử chuyến đi trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 🧾 Chi Tiết Chuyến Đi — `pages/trips/[id].vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/trips/:id` |
| **Title** | `Chi Tiết Chuyến Đi | DRIVIO` |
| **Meta Description** | `Xem chi tiết chuyến đi, thông tin xe thuê, lịch trình và trạng thái chuyến đi trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Chi Tiết Chuyến Đi | DRIVIO',
  description: 'Xem chi tiết chuyến đi, thông tin xe thuê, lịch trình và trạng thái chuyến đi trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 🔔 Thông Báo — `pages/notifications.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/notifications` |
| **Title** | `Thông Báo | DRIVIO` |
| **Meta Description** | `Xem thông báo mới nhất về chuyến đi, khuyến mãi và cập nhật từ DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Thông Báo | DRIVIO',
  description: 'Xem thông báo mới nhất về chuyến đi, khuyến mãi và cập nhật từ DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 💳 Thanh Toán — `pages/payment.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/payment` |
| **Title** | `Thanh Toán Thuê Xe | DRIVIO` |
| **Meta Description** | `Thanh toán đặt cọc thuê xe an toàn qua MoMo, ví DRIVIO. Giao dịch bảo mật, xác nhận tức thì.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Thanh Toán Thuê Xe | DRIVIO',
  description: 'Thanh toán đặt cọc thuê xe an toàn qua MoMo, ví DRIVIO. Giao dịch bảo mật, xác nhận tức thì.',
  robots: 'noindex, nofollow',
})
```

---

#### ✅ Kết Quả Thanh Toán — `pages/payment-result.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/payment-result` |
| **Title** | `Kết Quả Thanh Toán | DRIVIO` |
| **Meta Description** | `Xem kết quả giao dịch thanh toán thuê xe trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Kết Quả Thanh Toán | DRIVIO',
  description: 'Xem kết quả giao dịch thanh toán thuê xe trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 💰 Ví Của Tôi — `pages/mywallet.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/mywallet` |
| **Title** | `Ví DRIVIO — Quản Lý Số Dư & Giao Dịch` |
| **Meta Description** | `Quản lý ví điện tử DRIVIO — xem số dư, lịch sử giao dịch, nạp tiền và rút tiền nhanh chóng.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Ví DRIVIO — Quản Lý Số Dư & Giao Dịch',
  description: 'Quản lý ví điện tử DRIVIO — xem số dư, lịch sử giao dịch, nạp tiền và rút tiền nhanh chóng.',
  robots: 'noindex, nofollow',
})
```

---

#### 📊 Sao Kê Giao Dịch — `pages/mymonthlyreport.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/mymonthlyreport` |
| **Title** | `Sao Kê Chi Tiết Giao Dịch | DRIVIO` |
| **Meta Description** | `Xem sao kê chi tiết giao dịch thuê xe, doanh thu cho thuê và thống kê hàng tháng trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Sao Kê Chi Tiết Giao Dịch | DRIVIO',
  description: 'Xem sao kê chi tiết giao dịch thuê xe, doanh thu cho thuê và thống kê hàng tháng trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 💬 Chat — `pages/chats/index.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/chats` |
| **Title** | `Tin Nhắn | DRIVIO` |
| **Meta Description** | `Nhắn tin trực tiếp với chủ xe hoặc khách thuê trên DRIVIO. Trao đổi chi tiết chuyến đi nhanh chóng.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Tin Nhắn | DRIVIO',
  description: 'Nhắn tin trực tiếp với chủ xe hoặc khách thuê trên DRIVIO. Trao đổi chi tiết chuyến đi nhanh chóng.',
  robots: 'noindex, nofollow',
})
```

---

### 3.3. Trang Quản Lý Xe (Car Owner Pages)

---

#### 🚘 Đăng Ký Cho Thuê Xe — `pages/car-register/index.vue`

> **Lưu ý:** Trang này nên **được index** vì thu hút chủ xe tiềm năng.

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/car-register` |
| **Title** | `Đăng Ký Cho Thuê Xe — Kiếm Thu Nhập Thụ Động | DRIVIO` |
| **Meta Description** | `Đăng ký cho thuê xe trên DRIVIO — kiếm thu nhập thụ động từ xe nhàn rỗi. Bảo hiểm toàn diện, hỗ trợ quản lý xe, thanh toán nhanh chóng.` |
| **Keywords** | `đăng ký cho thuê xe, cho thuê xe kiếm tiền, chủ xe drivio, thu nhập thụ động xe ô tô, đăng xe cho thuê` |
| **OG Title** | `Trở Thành Chủ Xe Trên DRIVIO` |
| **OG Description** | `Cho thuê xe nhàn rỗi, kiếm thu nhập thụ động với DRIVIO.` |

```typescript
useSeoMeta({
  title: 'Đăng Ký Cho Thuê Xe — Kiếm Thu Nhập Thụ Động | DRIVIO',
  description: 'Đăng ký cho thuê xe trên DRIVIO — kiếm thu nhập thụ động từ xe nhàn rỗi. Bảo hiểm toàn diện, hỗ trợ quản lý xe, thanh toán nhanh chóng.',
  keywords: 'đăng ký cho thuê xe, cho thuê xe kiếm tiền, chủ xe drivio, thu nhập thụ động xe ô tô, đăng xe cho thuê',
  ogTitle: 'Trở Thành Chủ Xe Trên DRIVIO',
  ogDescription: 'Cho thuê xe nhàn rỗi, kiếm thu nhập thụ động với DRIVIO.',
  ogImage: '/images/og/car-register.jpg',
  twitterCard: 'summary_large_image',
})
```

---

#### 🏢 Quản Lý Xe Của Tôi — `pages/my-cars/index.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars` |
| **Title** | `Xe Của Tôi — Quản Lý Xe Cho Thuê | DRIVIO` |
| **Meta Description** | `Quản lý danh sách xe cho thuê, theo dõi trạng thái, cập nhật giá và thông tin xe trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Xe Của Tôi — Quản Lý Xe Cho Thuê | DRIVIO',
  description: 'Quản lý danh sách xe cho thuê, theo dõi trạng thái, cập nhật giá và thông tin xe trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 📋 Đơn Đặt Xe — `pages/my-cars/bookings.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/bookings` |
| **Title** | `Quản Lý Đơn Thuê Xe | DRIVIO` |
| **Meta Description** | `Quản lý các đơn đặt thuê xe, duyệt yêu cầu, theo dõi chuyến đi và xem lịch sử cho thuê trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Quản Lý Đơn Thuê Xe | DRIVIO',
  description: 'Quản lý các đơn đặt thuê xe, duyệt yêu cầu, theo dõi chuyến đi và xem lịch sử cho thuê trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 📅 Lịch Cho Thuê — `pages/my-cars/calendar.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/calendar` |
| **Title** | `Lịch Cho Thuê Xe | DRIVIO` |
| **Meta Description** | `Xem lịch thuê xe, quản lý ngày cho thuê và kiểm tra khả dụng xe trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Lịch Cho Thuê Xe | DRIVIO',
  description: 'Xem lịch thuê xe, quản lý ngày cho thuê và kiểm tra khả dụng xe trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 📊 Dashboard Chủ Xe — `pages/my-cars/dashboard.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/dashboard` |
| **Title** | `Bảng Điều Khiển Chủ Xe | DRIVIO` |
| **Meta Description** | `Tổng quan doanh thu, số chuyến, đánh giá và thống kê cho thuê xe trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Bảng Điều Khiển Chủ Xe | DRIVIO',
  description: 'Tổng quan doanh thu, số chuyến, đánh giá và thống kê cho thuê xe trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### ✏️ Chỉnh Sửa Xe — `pages/my-cars/edit/[id].vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/edit/:id` |
| **Title** | `Chỉnh Sửa Thông Tin Xe | DRIVIO` |
| **Meta Description** | `Cập nhật thông tin, giá thuê, hình ảnh và tính năng xe cho thuê trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Chỉnh Sửa Thông Tin Xe | DRIVIO',
  description: 'Cập nhật thông tin, giá thuê, hình ảnh và tính năng xe cho thuê trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 📜 Hợp Đồng — `pages/my-cars/contract.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/contract` |
| **Title** | `Hợp Đồng Cho Thuê Xe | DRIVIO` |
| **Meta Description** | `Xem mẫu hợp đồng cho thuê xe tự lái giữa chủ xe và khách thuê trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Hợp Đồng Cho Thuê Xe | DRIVIO',
  description: 'Xem mẫu hợp đồng cho thuê xe tự lái giữa chủ xe và khách thuê trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 🔒 Quyền Riêng Tư Xe — `pages/my-cars/privacy.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/privacy` |
| **Title** | `Cài Đặt Quyền Riêng Tư Xe | DRIVIO` |
| **Meta Description** | `Quản lý cài đặt quyền riêng tư, bảo mật thông tin xe cho thuê trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Cài Đặt Quyền Riêng Tư Xe | DRIVIO',
  description: 'Quản lý cài đặt quyền riêng tư, bảo mật thông tin xe cho thuê trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 📘 Hướng Dẫn Cho Thuê — `pages/my-cars/rentalguide.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/rentalguide` |
| **Title** | `Hướng Dẫn Cho Thuê Xe | DRIVIO` |
| **Meta Description** | `Hướng dẫn chi tiết quy trình cho thuê xe trên DRIVIO dành cho chủ xe mới.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Hướng Dẫn Cho Thuê Xe | DRIVIO',
  description: 'Hướng dẫn chi tiết quy trình cho thuê xe trên DRIVIO dành cho chủ xe mới.',
  robots: 'noindex, nofollow',
})
```

---

#### 🚔 Vi Phạm Giao Thông — `pages/my-cars/traffic-fines.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/traffic-fines` |
| **Title** | `Quản Lý Vi Phạm Giao Thông | DRIVIO` |
| **Meta Description** | `Xem và quản lý các vi phạm giao thông liên quan đến xe cho thuê trên DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Quản Lý Vi Phạm Giao Thông | DRIVIO',
  description: 'Xem và quản lý các vi phạm giao thông liên quan đến xe cho thuê trên DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

#### 📡 GPS Xe — `pages/my-cars/gps.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/my-cars/gps` |
| **Title** | `Theo Dõi GPS Xe | DRIVIO` |
| **Meta Description** | `Theo dõi vị trí xe cho thuê theo thời gian thực trên bản đồ DRIVIO.` |
| **Robots** | `noindex, nofollow` |

```typescript
useSeoMeta({
  title: 'Theo Dõi GPS Xe | DRIVIO',
  description: 'Theo dõi vị trí xe cho thuê theo thời gian thực trên bản đồ DRIVIO.',
  robots: 'noindex, nofollow',
})
```

---

### 3.4. Trang Chính Sách (Policy Pages)

> **Lưu ý:** Các trang chính sách NÊN được index vì chứa nội dung pháp lý quan trọng.

---

#### 📜 Chính Sách & Quy Định — `pages/policy/index.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/policy` |
| **Title** | `Chính Sách & Quy Định Thuê Xe | DRIVIO` |
| **Meta Description** | `Xem đầy đủ chính sách thuê xe, quy định hủy chuyến, điều khoản bảo hiểm và quyền lợi khách hàng tại DRIVIO. Minh bạch, rõ ràng.` |
| **Keywords** | `chính sách thuê xe, quy định thuê xe tự lái, điều khoản drivio, chính sách hủy chuyến, bảo hiểm thuê xe` |
| **OG Title** | `Chính Sách & Quy Định | DRIVIO` |
| **OG Description** | `Thông tin chi tiết về các điều khoản, quyền lợi và nghĩa vụ của khách thuê và chủ xe trên DRIVIO.` |

```typescript
useSeoMeta({
  title: 'Chính Sách & Quy Định Thuê Xe | DRIVIO',
  description: 'Xem đầy đủ chính sách thuê xe, quy định hủy chuyến, điều khoản bảo hiểm và quyền lợi khách hàng tại DRIVIO. Minh bạch, rõ ràng.',
  keywords: 'chính sách thuê xe, quy định thuê xe tự lái, điều khoản drivio, chính sách hủy chuyến, bảo hiểm thuê xe',
  ogTitle: 'Chính Sách & Quy Định | DRIVIO',
  ogDescription: 'Thông tin chi tiết về các điều khoản, quyền lợi và nghĩa vụ của khách thuê và chủ xe trên DRIVIO.',
  ogImage: '/images/about/policy.webp',
  twitterCard: 'summary_large_image',
})
```

---

#### 📋 Điều Khoản Sử Dụng — `pages/policy/term.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/policy/term` |
| **Title** | `Điều Khoản Sử Dụng Dịch Vụ | DRIVIO` |
| **Meta Description** | `Đọc điều khoản sử dụng dịch vụ thuê xe tự lái DRIVIO. Quy định về quyền và nghĩa vụ của người thuê, chủ xe, và nền tảng.` |
| **Keywords** | `điều khoản sử dụng, điều khoản dịch vụ, quy định thuê xe, điều khoản drivio` |

```typescript
useSeoMeta({
  title: 'Điều Khoản Sử Dụng Dịch Vụ | DRIVIO',
  description: 'Đọc điều khoản sử dụng dịch vụ thuê xe tự lái DRIVIO. Quy định về quyền và nghĩa vụ của người thuê, chủ xe, và nền tảng.',
  keywords: 'điều khoản sử dụng, điều khoản dịch vụ, quy định thuê xe, điều khoản drivio',
  ogTitle: 'Điều Khoản Sử Dụng | DRIVIO',
  ogDescription: 'Quy định về quyền và nghĩa vụ khi sử dụng dịch vụ thuê xe DRIVIO.',
  ogImage: '/images/about/policy.webp',
})
```

---

#### 🔐 Chính Sách Bảo Mật Thông Tin — `pages/policy/personalinfo.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/policy/personalinfo` |
| **Title** | `Chính Sách Bảo Mật Thông Tin Cá Nhân | DRIVIO` |
| **Meta Description** | `Cam kết bảo mật thông tin cá nhân của DRIVIO. Tìm hiểu cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu khách hàng.` |
| **Keywords** | `chính sách bảo mật, bảo mật thông tin cá nhân, privacy policy drivio, bảo vệ dữ liệu` |

```typescript
useSeoMeta({
  title: 'Chính Sách Bảo Mật Thông Tin Cá Nhân | DRIVIO',
  description: 'Cam kết bảo mật thông tin cá nhân của DRIVIO. Tìm hiểu cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu khách hàng.',
  keywords: 'chính sách bảo mật, bảo mật thông tin cá nhân, privacy policy drivio, bảo vệ dữ liệu',
  ogTitle: 'Chính Sách Bảo Mật | DRIVIO',
  ogDescription: 'Cam kết bảo mật và bảo vệ thông tin cá nhân khách hàng tại DRIVIO.',
  ogImage: '/images/about/policy.webp',
})
```

---

#### ⚖️ Giải Quyết Tranh Chấp — `pages/policy/resolveconflic.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/policy/resolveconflic` |
| **Title** | `Chính Sách Giải Quyết Tranh Chấp | DRIVIO` |
| **Meta Description** | `Quy trình giải quyết tranh chấp giữa khách thuê và chủ xe trên DRIVIO. Minh bạch, công bằng, xử lý nhanh chóng.` |
| **Keywords** | `giải quyết tranh chấp thuê xe, khiếu nại thuê xe, quy trình xử lý tranh chấp, khiếu nại drivio` |

```typescript
useSeoMeta({
  title: 'Chính Sách Giải Quyết Tranh Chấp | DRIVIO',
  description: 'Quy trình giải quyết tranh chấp giữa khách thuê và chủ xe trên DRIVIO. Minh bạch, công bằng, xử lý nhanh chóng.',
  keywords: 'giải quyết tranh chấp thuê xe, khiếu nại thuê xe, quy trình xử lý tranh chấp, khiếu nại drivio',
  ogTitle: 'Giải Quyết Tranh Chấp | DRIVIO',
  ogDescription: 'Quy trình xử lý tranh chấp minh bạch và công bằng trên DRIVIO.',
  ogImage: '/images/about/policy.webp',
})
```

---

#### 🛡️ Quyền Riêng Tư Ứng Viên — `pages/policy/candidateprivacy.vue`

| Thuộc tính | Giá trị |
|------------|---------|
| **Route** | `/policy/candidateprivacy` |
| **Title** | `Chính Sách Quyền Riêng Tư Ứng Viên | DRIVIO` |
| **Meta Description** | `Chính sách bảo mật thông tin dành cho ứng viên tuyển dụng tại DRIVIO. Cam kết bảo vệ dữ liệu cá nhân ứng viên.` |
| **Keywords** | `quyền riêng tư ứng viên, chính sách tuyển dụng drivio, bảo mật thông tin ứng viên` |

```typescript
useSeoMeta({
  title: 'Chính Sách Quyền Riêng Tư Ứng Viên | DRIVIO',
  description: 'Chính sách bảo mật thông tin dành cho ứng viên tuyển dụng tại DRIVIO. Cam kết bảo vệ dữ liệu cá nhân ứng viên.',
  keywords: 'quyền riêng tư ứng viên, chính sách tuyển dụng drivio, bảo mật thông tin ứng viên',
  ogTitle: 'Quyền Riêng Tư Ứng Viên | DRIVIO',
  ogDescription: 'Cam kết bảo vệ thông tin cá nhân ứng viên tuyển dụng tại DRIVIO.',
  ogImage: '/images/about/policy.webp',
})
```

---

## 4. Trạng Thái Triển Khai

### Tổng kết: 33 trang — Phân loại theo trạng thái SEO hiện tại

| Trạng thái | Số trang | Chi tiết |
|------------|----------|----------|
| ✅ Đã có `useSeoMeta` đầy đủ | 8 | `about`, `blogs/index`, `blogs/[id]`, `policy/index`, `policy/term`, `policy/resolveconflic`, `policy/personalinfo`, `policy/candidateprivacy` |
| ⚠️ Có `useHead` nhưng thiếu keywords/OG | 7 | `index`, `contact`, `bookinghowto`, `notifications`, `profile/index`, `profile/change-password`, `my-cars/edit/[id]` |
| ❌ Chưa có SEO | 18 | Tất cả các trang còn lại |

### Bảng Chi Tiết Từng Trang

| # | Trang | Route | SEO Hiện Tại | Cần Cải Thiện |
|---|-------|-------|--------------|---------------|
| 1 | `index.vue` | `/` | ⚠️ `useHead` — thiếu keywords, OG | Chuyển sang `useSeoMeta`, thêm keywords |
| 2 | `about.vue` | `/about` | ✅ `useSeoMeta` đầy đủ | Thêm keywords |
| 3 | `contact.vue` | `/contact` | ⚠️ `useHead` — thiếu keywords, OG | Chuyển sang `useSeoMeta`, thêm keywords |
| 4 | `bookinghowto.vue` | `/bookinghowto` | ⚠️ `useHead` — thiếu keywords, OG | Chuyển sang `useSeoMeta`, thêm keywords |
| 5 | `vehicle-list.vue` | `/vehicle-list` | ❌ Không có | Thêm `useSeoMeta` |
| 6 | `vehicles/[id].vue` | `/vehicles/:id` | ❌ Không có | Thêm `useSeoMeta` dynamic |
| 7 | `blogs/index.vue` | `/blogs` | ✅ `useSeoMeta` đầy đủ | Thêm keywords |
| 8 | `blogs/[id].vue` | `/blogs/:id` | ✅ `useSeoMeta` dynamic | Thêm keywords |
| 9 | `car-register/index.vue` | `/car-register` | ❌ Không có | Thêm `useSeoMeta` (quan trọng!) |
| 10 | `profile/index.vue` | `/profile` | ⚠️ `useHead` — thiếu keywords | Chuyển sang `useSeoMeta`, thêm noindex |
| 11 | `profile/[id].vue` | `/profile/:id` | ❌ Không có | Thêm `useSeoMeta` dynamic |
| 12 | `profile/change-password.vue` | `/profile/change-password` | ⚠️ `useHead` — chỉ có title | Chuyển sang `useSeoMeta`, thêm noindex |
| 13 | `profile/address.vue` | `/profile/address` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 14 | `profile/favorite.vue` | `/profile/favorite` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 15 | `profile/my-trips.vue` | `/profile/my-trips` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 16 | `trips/[id].vue` | `/trips/:id` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 17 | `notifications.vue` | `/notifications` | ⚠️ `useHead` — chỉ có title | Chuyển sang `useSeoMeta`, thêm noindex |
| 18 | `payment.vue` | `/payment` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 19 | `payment-result.vue` | `/payment-result` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 20 | `mywallet.vue` | `/mywallet` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 21 | `mymonthlyreport.vue` | `/mymonthlyreport` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 22 | `chats/index.vue` | `/chats` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 23 | `my-cars/index.vue` | `/my-cars` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 24 | `my-cars/bookings.vue` | `/my-cars/bookings` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 25 | `my-cars/calendar.vue` | `/my-cars/calendar` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 26 | `my-cars/dashboard.vue` | `/my-cars/dashboard` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 27 | `my-cars/edit/[id].vue` | `/my-cars/edit/:id` | ⚠️ `useHead` — chỉ có title | Chuyển sang `useSeoMeta` + noindex |
| 28 | `my-cars/contract.vue` | `/my-cars/contract` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 29 | `my-cars/privacy.vue` | `/my-cars/privacy` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 30 | `my-cars/rentalguide.vue` | `/my-cars/rentalguide` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 31 | `my-cars/traffic-fines.vue` | `/my-cars/traffic-fines` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 32 | `my-cars/gps.vue` | `/my-cars/gps` | ❌ Không có | Thêm `useSeoMeta` + noindex |
| 33 | `policy/*` (5 trang) | `/policy/*` | ✅ `useSeoMeta` đầy đủ | Thêm keywords, phân biệt title |

---

## 5. Hướng Dẫn Code Mẫu

### ✅ Cách sử dụng `useSeoMeta()` — Recommended

```typescript
// Trong <script setup> của mỗi page
useSeoMeta({
  // === SEO Cơ Bản ===
  title: 'Tiêu đề trang | DRIVIO',
  description: 'Mô tả meta 150-160 ký tự...',
  keywords: 'từ khóa 1, từ khóa 2, từ khóa 3',

  // === Open Graph (Facebook, Zalo) ===
  ogTitle: 'Tiêu đề OG',
  ogDescription: 'Mô tả OG ngắn gọn',
  ogImage: '/images/og/page-image.jpg',
  ogType: 'website', // hoặc 'article', 'product'
  ogUrl: 'https://drivio.vn/route',

  // === Twitter Card ===
  twitterCard: 'summary_large_image',
  twitterTitle: 'Tiêu đề Twitter',
  twitterDescription: 'Mô tả Twitter',
  twitterImage: '/images/og/page-image.jpg',

  // === Robots (cho trang private) ===
  robots: 'noindex, nofollow', // Chỉ dùng cho trang yêu cầu auth
})
```

### 🔄 SEO Dynamic cho trang chi tiết

```typescript
// Ví dụ: pages/vehicles/[id].vue
const fetchCarDetail = async () => {
  const res = await carService.getCarDetail(id)
  if (res.success && res.data) {
    car.value = res.data

    // Cập nhật SEO sau khi có dữ liệu
    useSeoMeta({
      title: `Thuê ${res.data.name} | DRIVIO`,
      description: `Thuê ${res.data.name} ${res.data.seat_count} chỗ...`,
      ogImage: res.data.thumbnail_url,
    })
  }
}
```

### 📌 Thêm Canonical URL

```typescript
useHead({
  link: [
    { rel: 'canonical', href: `https://drivio.vn${route.path}` }
  ]
})
```

### 📌 Thêm JSON-LD Structured Data (Nâng cao)

```typescript
// Ví dụ cho trang chi tiết xe
useHead({
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Product',
        name: car.value.name,
        description: car.value.description,
        image: thumbnailUrl,
        offers: {
          '@type': 'Offer',
          price: car.value.unit_price,
          priceCurrency: 'VND',
          availability: 'https://schema.org/InStock'
        }
      })
    }
  ]
})
```
