export enum TripStatus {
  Pending = 0,
  WaitingPayment = 1,
  Confirmed = 2,
  Ongoing = 3,
  Complete = 4,
  UserCancel = 5,
  OwnerCancel = 6,
  WaitingExtension = 7,
}

export const TripStatusLabel: Record<TripStatus, string> = {
  [TripStatus.Pending]: 'Chờ duyệt',
  [TripStatus.WaitingPayment]: 'Chờ thanh toán',
  [TripStatus.Confirmed]: 'Đã xác nhận',
  [TripStatus.Ongoing]: 'Đang diễn ra',
  [TripStatus.Complete]: 'Đã hoàn thành',
  [TripStatus.UserCancel]: 'Người dùng hủy',
  [TripStatus.OwnerCancel]: 'Chủ xe hủy',
  [TripStatus.WaitingExtension]: 'Chờ gia hạn',
};

export const TripStatusBadgeClass: Record<TripStatus, string> = {
  [TripStatus.Pending]: 'bg-amber-50 text-amber-600 border border-amber-200',
  [TripStatus.WaitingPayment]: 'bg-sky-50 text-sky-600 border border-sky-200',
  [TripStatus.Confirmed]: 'bg-slate-100 text-slate-600 border border-slate-200',
  [TripStatus.Ongoing]: 'bg-amber-50 text-amber-600 border border-amber-200',
  [TripStatus.Complete]: 'bg-emerald-50 text-emerald-600 border border-emerald-100',
  [TripStatus.UserCancel]: 'bg-rose-50 text-rose-500 border border-rose-100',
  [TripStatus.OwnerCancel]: 'bg-rose-50 text-rose-500 border border-rose-100',
  [TripStatus.WaitingExtension]: 'bg-indigo-50 text-indigo-600 border border-indigo-200',
};

export const TripStatusOptions = [
  { value: TripStatus.Pending, label: TripStatusLabel[TripStatus.Pending] },
  { value: TripStatus.WaitingPayment, label: TripStatusLabel[TripStatus.WaitingPayment] },
  { value: TripStatus.Confirmed, label: TripStatusLabel[TripStatus.Confirmed] },
  { value: TripStatus.Ongoing, label: TripStatusLabel[TripStatus.Ongoing] },
  { value: TripStatus.Complete, label: TripStatusLabel[TripStatus.Complete] },
  { value: TripStatus.UserCancel, label: TripStatusLabel[TripStatus.UserCancel] },
  { value: TripStatus.OwnerCancel, label: TripStatusLabel[TripStatus.OwnerCancel] },
  { value: TripStatus.WaitingExtension, label: TripStatusLabel[TripStatus.WaitingExtension] },
] as const;
