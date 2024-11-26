<?php

namespace App\Models;

use CodeIgniter\Model;

class ReimbursementRequestModel extends Model
{
    protected $table = 'reimbursement_requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'transaction_id',
        'employee',
        'reimbursement_name',
        'effective_date',
        'description',
        'status',
        'attachment',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;

    /**
     * Generate a unique transaction ID
     *
     * @return string
     */
    public function generateTransactionId()
    {
        $datePrefix = date('ym'); // Format YYMM (tahun 2 digit dan bulan 2 digit)
        $lastTransaction = $this->select('transaction_id')
                                ->like('transaction_id', "REQ-$datePrefix", 'after') // Cari berdasarkan prefix tahun dan bulan
                                ->orderBy('transaction_id', 'DESC')
                                ->first();

        if ($lastTransaction) {
            // Ambil angka terakhir dari ID sebelumnya
            $lastNumber = (int) substr($lastTransaction['transaction_id'], -3); // Gunakan 3 digit terakhir
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Tambahkan angka baru dengan panjang 3 digit
        } else {
            // Jika belum ada transaksi untuk bulan ini
            $newNumber = str_pad(1, 3, '0', STR_PAD_LEFT); // Mulai dari 001
        }

        return "REQ-$datePrefix$newNumber"; // Contoh hasil: REQ-24101
    }

}
