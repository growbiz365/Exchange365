<?php

namespace App\Support;

use App\Models\Asset;
use App\Models\BankTransfer;
use App\Models\GeneralVoucher;
use App\Models\MoneyExchange;
use App\Models\Party;
use App\Models\PartyTransfer;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;

class VoucherLink
{
    /**
     * Resolve the show-page URL for a ledger voucher reference.
     */
    public static function showUrl(?string $voucherType, int|string|null $voucherId): ?string
    {
        if (!$voucherType || $voucherId === null || $voucherId === '') {
            return null;
        }

        return match ($voucherType) {
            Sale::VOUCHER_TYPE => route('sales.show', $voucherId),
            Purchase::VOUCHER_TYPE => route('purchases.show', $voucherId),
            GeneralVoucher::VOUCHER_TYPE => route('general-vouchers.show', $voucherId),
            'Bank Transfer' => route('bank-transfers.show', $voucherId),
            'Money Exchange' => route('money-exchanges.show', $voucherId),
            Asset::VOUCHER_TYPE => route('assets.show', $voucherId),
            'Party Transfer' => route('party-transfers.show', $voucherId),
            default => null,
        };
    }

    /**
     * Resolve voucher_id for legacy party_ledger rows that pre-date voucher_id storage.
     */
    public static function resolvePartyLedgerVoucherId(
        ?string $voucherType,
        int $partyId,
        int $currencyId,
        $dateAdded,
        float $credit,
        float $debit,
        ?string $details = null
    ): ?int {
        if (!$voucherType || $voucherType === 'Opening Balance') {
            return null;
        }

        $date = Carbon::parse($dateAdded)->toDateString();
        $amount = $credit > 0 ? $credit : $debit;

        return match ($voucherType) {
            GeneralVoucher::VOUCHER_TYPE => self::resolveGeneralVoucherByParty($partyId, $currencyId, $date, $amount),
            Sale::VOUCHER_TYPE => Sale::query()
                ->where('party_id', $partyId)
                ->where('party_currency_id', $currencyId)
                ->whereDate('date_added', $date)
                ->where('party_amount', $debit > 0 ? $debit : $amount)
                ->value('sales_id'),
            Purchase::VOUCHER_TYPE => Purchase::query()
                ->where('party_id', $partyId)
                ->where('party_currency_id', $currencyId)
                ->whereDate('date_added', $date)
                ->where('debit_amount', $debit > 0 ? $debit : $amount)
                ->value('purchase_id'),
            'Party Transfer' => self::resolvePartyTransferId($partyId, $currencyId, $date, $credit, $debit),
            Asset::VOUCHER_TYPE => self::resolveAssetPartyId($partyId, $date, $credit, $debit),
            default => null,
        };
    }

    private static function resolveGeneralVoucherByParty(int $partyId, int $currencyId, string $date, float $amount): ?int
    {
        return GeneralVoucher::query()
            ->where('party_id', $partyId)
            ->whereDate('date_added', $date)
            ->where('amount', $amount)
            ->whereHas('bank', fn ($query) => $query->where('currency_id', $currencyId))
            ->value('general_voucher_id');
    }

    private static function resolvePartyTransferId(int $partyId, int $currencyId, string $date, float $credit, float $debit): ?int
    {
        if ($credit > 0) {
            return PartyTransfer::query()
                ->where('credit_party', $partyId)
                ->where('credit_currency_id', $currencyId)
                ->whereDate('date_added', $date)
                ->where('credit_amount', $credit)
                ->value('party_transfer_id');
        }

        if ($debit > 0) {
            return PartyTransfer::query()
                ->where('debit_party', $partyId)
                ->where('debit_currency_id', $currencyId)
                ->whereDate('date_added', $date)
                ->where('debit_amount', $debit)
                ->value('party_transfer_id');
        }

        return null;
    }

    private static function resolveAssetPartyId(int $partyId, string $date, float $credit, float $debit): ?int
    {
        if ($credit > 0) {
            return Asset::query()
                ->where('purchase_party_id', $partyId)
                ->whereDate('date_added', $date)
                ->where('cost_amount', $credit)
                ->value('asset_id');
        }

        if ($debit > 0) {
            return Asset::query()
                ->where('sale_party_id', $partyId)
                ->whereDate('sale_date', $date)
                ->where('sale_amount', $debit)
                ->value('asset_id');
        }

        return null;
    }

    /**
     * Resolve voucher_id for legacy bank_ledger rows that pre-date voucher_id storage.
     */
    public static function resolveBankLedgerVoucherId(
        ?string $voucherType,
        int $bankId,
        $dateAdded,
        float $deposit,
        float $withdrawal,
        ?string $details = null
    ): ?int {
        if (!$voucherType || $voucherType === 'Opening Balance') {
            return null;
        }

        $date = Carbon::parse($dateAdded)->toDateString();
        $amount = $deposit > 0 ? $deposit : $withdrawal;

        return match ($voucherType) {
            GeneralVoucher::VOUCHER_TYPE => self::resolveGeneralVoucherId($bankId, $date, $amount, $details),
            Sale::VOUCHER_TYPE => Sale::query()
                ->where('bank_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('currency_amount', $withdrawal > 0 ? $withdrawal : $amount)
                ->value('sales_id'),
            Purchase::VOUCHER_TYPE => Purchase::query()
                ->where('bank_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('credit_amount', $deposit > 0 ? $deposit : $amount)
                ->value('purchase_id'),
            'Bank Transfer' => self::resolveBankTransferId($bankId, $date, $deposit, $withdrawal),
            'Money Exchange' => self::resolveMoneyExchangeId($bankId, $date, $deposit, $withdrawal),
            Asset::VOUCHER_TYPE => self::resolveAssetId($bankId, $date, $deposit, $withdrawal),
            default => null,
        };
    }

    private static function resolveGeneralVoucherId(int $bankId, string $date, float $amount, ?string $details): ?int
    {
        $query = GeneralVoucher::query()
            ->where('bank_id', $bankId)
            ->whereDate('date_added', $date)
            ->where('amount', $amount);

        if ($details && preg_match('/Party:([^,]+)/', $details, $matches)) {
            $partyName = trim($matches[1]);
            $partyId = Party::query()->where('party_name', $partyName)->value('party_id');
            if ($partyId) {
                $query->where('party_id', $partyId);
            }
        }

        return $query->value('general_voucher_id');
    }

    private static function resolveBankTransferId(int $bankId, string $date, float $deposit, float $withdrawal): ?int
    {
        if ($withdrawal > 0) {
            return BankTransfer::query()
                ->where('from_account_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('amount', $withdrawal)
                ->value('bank_transfer_id');
        }

        if ($deposit > 0) {
            return BankTransfer::query()
                ->where('to_account_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('amount', $deposit)
                ->value('bank_transfer_id');
        }

        return null;
    }

    private static function resolveMoneyExchangeId(int $bankId, string $date, float $deposit, float $withdrawal): ?int
    {
        if ($withdrawal > 0) {
            return MoneyExchange::query()
                ->where('from_account_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('debit_amount', $withdrawal)
                ->value('money_exchange_id');
        }

        if ($deposit > 0) {
            return MoneyExchange::query()
                ->where('to_account_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('credit_amount', $deposit)
                ->value('money_exchange_id');
        }

        return null;
    }

    private static function resolveAssetId(int $bankId, string $date, float $deposit, float $withdrawal): ?int
    {
        if ($withdrawal > 0) {
            return Asset::query()
                ->where('purchase_bank_id', $bankId)
                ->whereDate('date_added', $date)
                ->where('cost_amount', $withdrawal)
                ->value('asset_id');
        }

        if ($deposit > 0) {
            return Asset::query()
                ->where('sale_bank_id', $bankId)
                ->whereDate('sale_date', $date)
                ->where('sale_amount', $deposit)
                ->value('asset_id');
        }

        return null;
    }
}
