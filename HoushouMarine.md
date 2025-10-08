# File apa aja yang berguna (inti)
## Halaman Utama (Home)
-> Home.php (Controller)

-> home.blade.php (View)

## Halaman Dokumen Catalog
-> DocumentList.php (Controller)

-> document-list.blade.php (View)

## Halaman Buat Dokumen Baru
-> CreateDocument.php (Controller)

-> create-document.blade.php (View)

## Halaman Detail Dokumen
-> DocumentDetails.php (Controller)

-> document-details.blade.php (View)

## Database
-> 2025_08_13_071228_create_documents_table.php (di  ..\database\migrations)

## Watermarking
-> PdfWaterMarkService.php (di ..\app\Http\Controlers)

-> DocumentPreviewController.php (di ..\app\Services)

-> Ada routenya juga di web.php

# Fitur-fitur yang belum / mungkin belum sesuai
1. Upload dokumen di CreateDocument.php belum 'required
2. Fitur role tertentu bisa ngelihat dokumen tertentu juga. Di database sama di form buat dokumen (CreateDocument.php sama create-document.blade.php) belum ada opsinya, tapi udah ada atribut 'Confidentiality'. Atribut itu tuh ada 'Public', semua karyawan bisa liat, 'Internal Use', cuman satu atau lebih divisi bisa liat dokumen itu, 'Confidential', cuman Admin sama tim Bispro yang bisa liat dokumennya.
3. Belum bisa edit sama delete dokumen yang udah di upload (gatau ini mau diimplementasiin atau gak)
4. Kayaknya kalau udah lewat effective date dokumen, dokumennya gak ke update jadi "Inactive" (belum ada fungsi yang ngurus ini)
5. Notifikasi ke email tim Bispro pas dokumen udah ~1 minggu lagi mau expire dan pas expirednya (mungkin tambahin juga h-1 expired)
6. Pas teken tombol "Preview" file masih ke tab baru, belum yang kayak pop-up gitu.

# Notes Tambahan
Kalau misal code di view-nya agak gajelas mas, bisa pergi ke versi project ini sebelum commit "BIG UI CHANGES". Terus kalau suka cuman sebagian dari UI yang baru tinggal di-copy kan hehe.


# Notes Tambahan Lagi

Makasi ya mas udah sabar ngajarin aku di intern ini :DDDD, titip salam juga ke Mas Risman sama Mas Fauzan kalau ketemu hehehe

Oiya sama tolong bilangin ke Mas Andre kalau bisa nama aplikasinya MARINE aja, ada singkatan yang bagus kok nyambung sama aplikasi ini,

M -> Mandatory

A -> Agreements &

R -> Rules for

I -> Integrity,

N -> Norms, and

E -> Ethics


Ini aku kasih wallpaper dari membership marine.

![Houshou_Marine_Membership](新衣装_メン限壁紙_4K.jpg "HoushouMarine")