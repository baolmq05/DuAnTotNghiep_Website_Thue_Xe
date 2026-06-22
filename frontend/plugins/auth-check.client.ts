import { watch } from "vue";
import { useRoute } from "#app";
import { useAuthModal } from "~/composables/useAuthModal";

export default defineNuxtPlugin(() => {
  const route = useRoute();
  const { openLogin } = useAuthModal();

  watch(
    () => route.query.showLogin,
    (showLogin) => {
      if (showLogin === "true") {
        openLogin();
      }
    },
    { immediate: true }
  );
});
