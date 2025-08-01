# Pharmacy Medicine Request Workflow

## Overview
This document explains the workflow for medicine requests from doctors to pharmacy and how patient queue status is updated.

## Patient Status Flow

```
Menunggu Pemeriksaan → Menunggu Dokter → Dalam Pemeriksaan → Menunggu Farmasi → Menunggu Kasir → Selesai
```

## Medicine Request Process

### 1. Doctor Prescribes Medicine (SOAP Form)
- Doctor completes SOAP examination
- Prescribes medicine during examination
- Medicine requests are saved to `resep` table with status 'pending'
- Patient status changes to 'Menunggu Farmasi'

### 2. Pharmacy Receives Request
- Pharmacy staff sees pending medicine requests in `/farmasi/permintaan-obat`
- Each request shows patient details, medicine, dosage, and instructions

### 3. Pharmacy Processes Request
- Staff clicks "Proses" to change status to 'processing'
- Medicine is prepared according to prescription

### 4. Pharmacy Completes Request ✨ **NEW FEATURE** ✨
- Staff clicks "Selesai" to complete the medicine request
- System checks if patient has other pending medicine requests for today
- **If all medicine requests are completed**: Patient queue status changes to 'Menunggu Kasir'
- **If other requests are still pending**: Patient remains in 'Menunggu Farmasi' status

### 5. Patient Proceeds to Cashier
- Patient with 'Menunggu Kasir' status can proceed to payment
- After payment, status changes to 'Selesai'

## Technical Implementation

### Database Tables Involved
- `resep`: Medicine requests with status tracking
- `antrian`: Patient queue with current status
- `antrian_poli`: Detailed queue per polyclinic
- `pasien`: Patient information (linked via no_rekam_medis)

### Key Code Changes
- Modified `Farmasi::selesaiPermintaan()` method
- Added intelligent status checking for multiple medicine requests
- Automatic patient queue status update when all medicines are ready

### Status Logic
```php
// Check for remaining pending medicine requests
$pendingResep = $resepModel->getResepWithDetails([
    'p.no_rekam_medis' => $no_rm,
    'r.status !=' => 'completed',
    'DATE(r.tanggal_resep)' => date('Y-m-d')
]);

// Update patient queue only when all medicines are ready
if (empty($pendingResep)) {
    // Update status to 'Menunggu Kasir'
}
```

## Benefits
1. **Automatic workflow**: No manual status updates needed
2. **Smart handling**: Considers multiple medicine requests per patient
3. **Real-time tracking**: Patient status updates immediately
4. **Audit trail**: Logs all status changes for monitoring
5. **Error handling**: Transaction-based updates with rollback capability

## Monitoring
- Check application logs for workflow tracking
- Monitor patient status in `/admisi/pasien-terdaftar-hari-ini`
- Pharmacy dashboard shows request statistics
