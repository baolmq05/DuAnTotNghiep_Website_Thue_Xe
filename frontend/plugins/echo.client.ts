import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default defineNuxtPlugin((nuxtApp) => {
    // Chỉ chạy ở môi trường client
    if (import.meta.env.SSR) return;

    (window as any).Pusher = Pusher;

    const echo = new Echo({
        broadcaster: 'reverb',
        key: 'bp54cuveeawxyvoq2yk9', // Thay bằng REVERB_APP_KEY của bạn trong .env
        wsHost: 'localhost',
        wsPort: 8080,
        wssPort: 8080,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        // Sử dụng custom authorizer để lấy token mới nhất mỗi khi kết nối kênh Private
        authorizer: (channel: any, options: any) => {
            return {
                authorize: (socketId: string, callback: Function) => {
                    const token = useCookie<string | null>("USER_TOKEN").value;
                    
                    $fetch('http://localhost:8000/api/broadcasting/auth', {
                        method: 'POST',
                        headers: {
                            Authorization: `Bearer ${token}`,
                            Accept: 'application/json',
                        },
                        body: {
                            socket_id: socketId,
                            channel_name: channel.name
                        }
                    })
                    .then(res => {
                        callback(false, res);
                    })
                    .catch(err => {
                        console.error('[Echo Auth Error]', err);
                        callback(true, err);
                    });
                }
            };
        }
    });

    // Cung cấp $echo toàn cục để sử dụng ở bất cứ Page/Component nào
    return {
        provide: {
            echo: echo
        }
    };
});
