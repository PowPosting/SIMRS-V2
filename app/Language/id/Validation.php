<?php

/**
 * Validation language strings (Indonesian).
 *
 * @package      CodeIgniter
 * @author       CodeIgniter Dev Team
 * @copyright    Copyright (c) 2014-2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license      https://opensource.org/licenses/MIT	MIT License
 * @link         https://codeigniter.com
 * @since        Version 3.0.0
 * @filesource
 *
 * @codeCoverageIgnore
 */
return [
	// Core Messages
	'noRuleSets'            => 'Tidak ada aturan yang ditentukan dalam konfigurasi Validasi.',
	'ruleNotFound'          => '{0} bukan aturan yang valid.',
	'groupNotFound'         => '{0} bukan grup aturan validasi.',
	'invalidTemplate'       => '{0} bukan template Validasi yang valid.',
	'missingRequired'       => 'Field {0} wajib diisi.',
	'notInQuery'            => '{0} harus ada dalam tabel yang ditentukan.',

	// Rule Messages
	'alpha'                 => 'Field {0} hanya boleh berisi karakter alfabet.',
	'alpha_dash'            => 'Field {0} hanya boleh berisi karakter alfanumerik, garis bawah, dan tanda hubung.',
	'alpha_numeric'         => 'Field {0} hanya boleh berisi karakter alfanumerik.',
	'alpha_numeric_punct'   => 'Field {0} hanya boleh berisi karakter alfanumerik, spasi, dan karakter ~ ! # $ % & * - _ + = | : .',
	'alpha_numeric_space'   => 'Field {0} hanya boleh berisi karakter alfanumerik dan spasi.',
	'alpha_space'           => 'Field {0} hanya boleh berisi karakter alfabet dan spasi.',
	'decimal'               => 'Field {0} harus berisi angka desimal.',
	'differs'               => 'Field {0} harus berbeda dari field {1}.',
	'equals'                => 'Field {0} harus sama dengan: {1}.',
	'exact_length'          => 'Field {0} harus tepat {1} karakter.',
	'greater_than'          => 'Field {0} harus berisi angka lebih besar dari {1}.',
	'greater_than_equal_to' => 'Field {0} harus berisi angka lebih besar atau sama dengan {1}.',
	'hex'                   => 'Field {0} hanya boleh berisi karakter heksadesimal.',
	'in_list'               => 'Field {0} harus salah satu dari: {1}.',
	'integer'               => 'Field {0} harus berisi bilangan bulat.',
	'is_natural'            => 'Field {0} hanya boleh berisi angka.',
	'is_natural_no_zero'    => 'Field {0} hanya boleh berisi angka dan harus lebih besar dari nol.',
	'is_not_unique'         => 'Field {0} harus berisi nilai yang sudah ada sebelumnya di database.',
	'is_unique'             => 'Field {0} harus berisi nilai yang unik.',
	'less_than'             => 'Field {0} harus berisi angka kurang dari {1}.',
	'less_than_equal_to'    => 'Field {0} harus berisi angka kurang dari atau sama dengan {1}.',
	'matches'               => 'Field {0} tidak cocok dengan field {1}.',
	'max_length'            => 'Field {0} tidak boleh melebihi {1} karakter.',
	'min_length'            => 'Field {0} harus minimal {1} karakter.',
	'not_equals'            => 'Field {0} tidak boleh: {1}.',
	'not_in_list'           => 'Field {0} tidak boleh salah satu dari: {1}.',
	'numeric'               => 'Field {0} harus berisi angka saja.',
	'regex_match'           => 'Field {0} tidak dalam format yang benar.',
	'required'              => 'Field {0} wajib diisi.',
	'required_with'         => 'Field {0} wajib diisi ketika {1} ada.',
	'required_without'      => 'Field {0} wajib diisi ketika {1} tidak ada.',
	'string'                => 'Field {0} harus berupa string yang valid.',
	'timezone'              => 'Field {0} harus berupa zona waktu yang valid.',
	'valid_base64'          => 'Field {0} harus berupa string base64 yang valid.',
	'valid_email'           => 'Field {0} harus berisi alamat email yang valid.',
	'valid_emails'          => 'Field {0} harus berisi semua alamat email yang valid.',
	'valid_ip'              => 'Field {0} harus berisi IP yang valid.',
	'valid_url'             => 'Field {0} harus berisi URL yang valid.',
	'valid_url_strict'      => 'Field {0} harus berisi URL yang valid.',
	'valid_date'            => 'Field {0} harus berisi tanggal yang valid.',
	'valid_json'            => 'Field {0} harus berisi json yang valid.',

	// Credit Cards
	'valid_cc_num'          => '{0} tampaknya bukan nomor kartu kredit yang valid.',

	// Files
	'uploaded'              => '{0} bukan file upload yang valid.',
	'max_size'              => '{0} terlalu besar ukuran file.',
	'is_image'              => '{0} bukan file gambar yang valid.',
	'mime_in'               => '{0} tidak memiliki tipe mime yang valid.',
	'ext_in'                => '{0} tidak memiliki ekstensi file yang valid.',
	'max_dims'              => '{0} bukan gambar, atau terlalu lebar atau tinggi.',
];
