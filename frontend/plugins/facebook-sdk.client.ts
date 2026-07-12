// Plugin này dùng để login facebook

declare global {
    interface Window {
        fbAsyncInit: () => void;
        FB: any;
    }
}

export default defineNuxtPlugin(() => {
    // Commented out Facebook SDK initialization
    /*
    window.fbAsyncInit = function () {
        window.FB.init({
            appId: '1318387693233381',
            cookie: true,
            xfbml: true,
            version: 'v24.0'
        });

        window.FB.AppEvents.logPageView();
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) { return; }
        js = d.createElement(s) as HTMLScriptElement;
        js.id = id;
        js.src = "https://connect.facebook.net/vi_VN/sdk.js";

        if (fjs && fjs.parentNode) {
            fjs.parentNode.insertBefore(js, fjs);
        } else {
            d.head?.appendChild(js);
        }
    }(document, 'script', 'facebook-jssdk'));
    */
})
