<?php

namespace App\Support;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\BankTransfer;
use App\Models\BankTransferAttachment;
use App\Models\Business;
use App\Models\CurrencyPurchase;
use App\Models\GeneralVoucher;
use App\Models\GeneralVoucherAttachment;
use App\Models\MoneyExchange;
use App\Models\MoneyExchangeAttachment;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\PartyOpeningBalance;
use App\Models\PartyTransfer;
use App\Models\PartyTransferAttachment;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class ActivityBusinessScope
{
    /**
     * @return list<class-string<Model>>
     */
    public static function directBusinessModels(): array
    {
        return [
            Asset::class,
            AssetCategory::class,
            Bank::class,
            BankLedger::class,
            BankTransfer::class,
            CurrencyPurchase::class,
            GeneralVoucher::class,
            MoneyExchange::class,
            Party::class,
            PartyLedger::class,
            PartyTransfer::class,
            Purchase::class,
            Sale::class,
        ];
    }

    public static function resolveBusinessId(?Model $subject): ?int
    {
        if (! $subject) {
            $sessionBusinessId = session('active_business');

            return $sessionBusinessId ? (int) $sessionBusinessId : null;
        }

        if ($subject instanceof Business) {
            return (int) $subject->getKey();
        }

        if (isset($subject->business_id)) {
            return (int) $subject->business_id;
        }

        if ($subject instanceof PartyTransferAttachment) {
            return $subject->partyTransfer?->business_id
                ? (int) $subject->partyTransfer->business_id
                : null;
        }

        if ($subject instanceof GeneralVoucherAttachment) {
            return $subject->generalVoucher?->business_id
                ? (int) $subject->generalVoucher->business_id
                : null;
        }

        if ($subject instanceof MoneyExchangeAttachment) {
            return $subject->moneyExchange?->business_id
                ? (int) $subject->moneyExchange->business_id
                : null;
        }

        if ($subject instanceof BankTransferAttachment) {
            return $subject->bankTransfer?->business_id
                ? (int) $subject->bankTransfer->business_id
                : null;
        }

        if ($subject instanceof PartyOpeningBalance) {
            return $subject->party?->business_id
                ? (int) $subject->party->business_id
                : null;
        }

        $sessionBusinessId = session('active_business');

        return $sessionBusinessId ? (int) $sessionBusinessId : null;
    }

    public static function assignBusinessId(Activity $activity): void
    {
        if (! Schema::hasColumn('activity_log', 'business_id')) {
            return;
        }

        if ($activity->business_id) {
            return;
        }

        $subject = $activity->subject;

        if (! $subject && $activity->subject_type && $activity->subject_id) {
            $subjectClass = $activity->subject_type;

            if (class_exists($subjectClass)) {
                $subject = $subjectClass::query()->find($activity->subject_id);
            }
        }

        $businessId = self::resolveBusinessId($subject);

        if ($businessId) {
            $activity->business_id = $businessId;
        }
    }

    public static function scopeQuery(Builder $query, int $businessId): Builder
    {
        if (! Schema::hasColumn('activity_log', 'business_id')) {
            return $query->where(function (Builder $q) use ($businessId) {
                self::applyLegacySubjectScope($q, $businessId);
            });
        }

        return $query->where(function (Builder $q) use ($businessId) {
            $q->where('business_id', $businessId)
                ->orWhere(function (Builder $legacy) use ($businessId) {
                    $legacy->whereNull('business_id');
                    self::applyLegacySubjectScope($legacy, $businessId);
                });
        });
    }

    public static function applyLegacySubjectScope(Builder $query, int $businessId): void
    {
        $query->where(function (Builder $q) use ($businessId) {
            $q->whereHasMorph(
                'subject',
                self::directBusinessModels(),
                fn (Builder $sub) => $sub->where('business_id', $businessId)
            )
                ->orWhere(function (Builder $q2) use ($businessId) {
                    $q2->where('subject_type', Business::class)
                        ->where('subject_id', $businessId);
                })
                ->orWhereHasMorph(
                    'subject',
                    [PartyTransferAttachment::class],
                    fn (Builder $sub) => $sub->whereHas(
                        'partyTransfer',
                        fn (Builder $pt) => $pt->where('business_id', $businessId)
                    )
                )
                ->orWhereHasMorph(
                    'subject',
                    [GeneralVoucherAttachment::class],
                    fn (Builder $sub) => $sub->whereHas(
                        'generalVoucher',
                        fn (Builder $gv) => $gv->where('business_id', $businessId)
                    )
                )
                ->orWhereHasMorph(
                    'subject',
                    [MoneyExchangeAttachment::class],
                    fn (Builder $sub) => $sub->whereHas(
                        'moneyExchange',
                        fn (Builder $me) => $me->where('business_id', $businessId)
                    )
                )
                ->orWhereHasMorph(
                    'subject',
                    [BankTransferAttachment::class],
                    fn (Builder $sub) => $sub->whereHas(
                        'bankTransfer',
                        fn (Builder $bt) => $bt->where('business_id', $businessId)
                    )
                )
                ->orWhereHasMorph(
                    'subject',
                    [PartyOpeningBalance::class],
                    fn (Builder $sub) => $sub->whereHas(
                        'party',
                        fn (Builder $party) => $party->where('business_id', $businessId)
                    )
                );
        });
    }
}
