import { ref, computed } from "vue";
import { notificationService, type NotificationItem } from "~/services/notification.service";
import { useAuth } from "~/composables/useAuth";

export const useNotifications = () => {
  const { user, isLoggedIn } = useAuth();
  const notifications = useState<NotificationItem[]>("notifications", () => []);
  const loading = useState<boolean>("notifications_loading", () => false);

  const unreadCount = computed(() => {
    return notifications.value.filter(item => item.is_read === "0").length;
  });

  const fetchNotifications = async () => {
    if (!isLoggedIn.value || !user.value) {
      notifications.value = [];
      return;
    }
    loading.value = true;
    try {
      const res = await notificationService.getNotifications(user.value.id);
      if (res?.success) {
        notifications.value = res.data || [];
      }
    } catch (err) {
      console.error("[useNotifications] Failed to fetch notifications:", err);
    } finally {
      loading.value = false;
    }
  };

  const readNotification = async (notification: NotificationItem) => {
    if (notification.is_read === "1") return;

    // Optimistic UI update
    notification.is_read = "1";

    try {
      if (notification.id) {
        await notificationService.updateNotification(notification.id, { is_read: "1" });
      }
    } catch (err) {
      console.error("[useNotifications] Failed to update notification status:", err);
      // Rollback on failure
      notification.is_read = "0";
    }
  };

  const markAllRead = async () => {
    const unread = notifications.value.filter(n => n.is_read === "0");
    if (unread.length === 0) return;

    // Optimistic UI update
    notifications.value.forEach(n => {
      n.is_read = "1";
    });

    try {
      await notificationService.markAllRead();
    } catch (err) {
      console.error("[useNotifications] Failed to mark all notifications as read:", err);
      // Fallback: refetch to sync from server
      await fetchNotifications();
    }
  };

  const formatTimeAgo = (dateStr?: string) => {
    if (!dateStr) return "";
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) return dateStr;
    const now = new Date();
    const diffMs = now.getTime() - date.getTime();
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHr = Math.floor(diffMin / 60);
    const diffDays = Math.floor(diffHr / 24);

    if (diffSec < 60) return "Vừa xong";
    if (diffMin < 60) return `${diffMin} phút trước`;
    if (diffHr < 24) return `${diffHr} giờ trước`;
    if (diffDays === 1) return "Hôm qua";
    if (diffDays < 7) return `${diffDays} ngày trước`;

    return date.toLocaleDateString("vi-VN", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric"
    });
  };

  return {
    notifications,
    loading,
    unreadCount,
    fetchNotifications,
    readNotification,
    markAllRead,
    formatTimeAgo
  };
};
