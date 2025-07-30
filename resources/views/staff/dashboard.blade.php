<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Desnet</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1e40af;
            color: white;
            position: fixed;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .status-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .green-dot { background-color: #10b981; }
        .orange-dot { background-color: #f59e0b; }
    </style>
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="p-4">
                <h2 class="text-xl font-bold">Desnet</h2>
                <div class="mt-10">
                    <h3 class="text-lg">Beranda Komisi</h3>
                    <ul class="mt-4">
                        <li class="mb-2"><a href="#" class="text-white">Personnel</a></li>
                    </ul>
                </div>
                <button class="mt-10 w-full bg-red-500 text-white py-2 rounded">Logout</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h1 class="text-2xl font-bold mb-4">Management Arsip Dokumen dan Komisi</h1>

            <!-- Work Order Table -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Work Order</h2>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">No</th>
                            <th class="p-2">Judul Proyek</th>
                            <th class="p-2">Status Dokumen</th>
                            <th class="p-2">Status Komisi</th>
                            <th class="p-2">Nilai Proyek</th>
                            <th class="p-2">Personel</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2">1</td>
                            <td class="p-2">Trip Karirum Jawa</td>
                            <td class="p-2"><span class="green-dot status-dot"></span> Sudah Diakukan</td>
                            <td class="p-2"><span class="green-dot status-dot"></span> Disetujui</td>
                            <td class="p-2">10.000.000</td>
                            <td class="p-2">ahmad, fikri, tiara...</td>
                            <td class="p-2">
                                <button class="bg-green-500 text-white py-1 px-2 rounded mr-2">Detail</button>
                                <button class="bg-blue-500 text-white py-1 px-2 rounded">Tambah</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">2</td>
                            <td class="p-2">Background.jpg</td>
                            <td class="p-2"><span class="orange-dot status-dot"></span> Belum Diakukan</td>
                            <td class="p-2"><span class="orange-dot status-dot"></span> Belum Disetujui</td>
                            <td class="p-2">50.000.000</td>
                            <td class="p-2">ahmad, fikri, tiara...</td>
                            <td class="p-2">
                                <button class="bg-green-500 text-white py-1 px-2 rounded mr-2">Detail</button>
                                <button class="bg-blue-500 text-white py-1 px-2 rounded">Tambah</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">3</td>
                            <td class="p-2">Proposal.docx</td>
                            <td class="p-2"><span class="orange-dot status-dot"></span> Belum Diakukan</td>
                            <td class="p-2"><span class="orange-dot status-dot"></span> Belum Disetujui</td>
                            <td class="p-2">41.500.000</td>
                            <td class="p-2">ahmad, fikri, tiara...</td>
                            <td class="p-2">
                                <button class="bg-green-500 text-white py-1 px-2 rounded mr-2">Detail</button>
                                <button class="bg-blue-500 text-white py-1 px-2 rounded">Tambah</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">4</td>
                            <td class="p-2">Illustration.ai</td>
                            <td class="p-2"><span class="green-dot status-dot"></span> Sudah Diakukan</td>
                            <td class="p-2"><span class="green-dot status-dot"></span> Disetujui</td>
                            <td class="p-2">15.000.000</td>
                            <td class="p-2">ahmad, fikri, tiara...</td>
                            <td class="p-2">
                                <button class="bg-green-500 text-white py-1 px-2 rounded mr-2">Detail</button>
                                <button class="bg-blue-500 text-white py-1 px-2 rounded">Tambah</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Dokumen and Komisi Cards -->
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div class="card">
                    <h3 class="text-lg font-semibold">Dokumen</h3>
                    <div class="mt-2">
                        <p>Total Dokumen</p>
                        <p class="text-3xl font-bold text-blue-500">47</p>
                    </div>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold">Dokumen Revisi</h3>
                    <div class="mt-2">
                        <p>Dokumen Revisi</p>
                        <p class="text-3xl font-bold text-yellow-500">15</p>
                    </div>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold">Dokumen Selesai</h3>
                    <div class="mt-2">
                        <p>Dokumen Selesai</p>
                        <p class="text-3xl font-bold text-green-500">32</p>
                    </div>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold">Komisi</h3>
                    <div class="mt-2">
                        <p>Komisi Bulan Ini</p>
                        <p class="text-3xl font-bold">Rp. 76.000.000,00</p>
                    </div>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold">Komisi</h3>
                    <div class="mt-2">
                        <p>Komisi Tahun Ini</p>
                        <p class="text-3xl font-bold">Rp. 1.546.000.000,00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>