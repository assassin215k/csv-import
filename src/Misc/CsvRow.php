<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 17.12.21
 * Time: 08:44
 */

namespace App\Misc;

class CsvRow {
	
	public static array $headers = [
		"code"  => "Product Code",
		"name"  => "Product Name",
		"desc"  => "Product Description",
		"stock" => "Stock",
		"cost"  => "Cost in GBP",
		"disc"  => "Discontinued",
	];
}
