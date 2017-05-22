<?php
class SECURITY
{
	//3DED
	public function tripleDES($input, &$output, $key, $flag)
	{
		if($flag)
		{
			//解密过程
			/*$str = base64_decode($input);
			//解密方法
			$cipher_alg = MCRYPT_TRIPLEDES;
			//初始化向量来增加安全性
			$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
			//开始解密
			$decrypted_string = mcrypt_decrypt($cipher_alg, $key, $str, MCRYPT_MODE_ECB, $iv);
			$output = trim($decrypted_string);*/
			if($input == null)
			{
				return 0;
			}
			$td = mcrypt_module_open('tripledes', '', 'ecb', '');
			$td_size = mcrypt_enc_get_iv_size($td);
			$iv = mcrypt_create_iv($td_size, MCRYPT_RAND);
			//$key = substr($key, 0,$td_size);
			mcrypt_generic_init($td, $key, $iv);
			$output = trim($this->pkcs5_unpad(mdecrypt_generic($td, $input)));
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			return 1;
		}
		else
		{
			//加密方法
			/*$cipher_alg = MCRYPT_TRIPLEDES;
			//初始化向量来增加安全性
			$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
			//开始加密
			$encrypted_string = mcrypt_encrypt($cipher_alg, $key, $input, MCRYPT_MODE_ECB, $iv);
			$output = base64_encode($encrypted_string);//转化成16进制*/
			if($input == null)
			{
				return 0;
			}
			$td = mcrypt_module_open('tripledes', '', 'ecb', '');
			$td_size = mcrypt_enc_get_iv_size($td);
			$iv = mcrypt_create_iv($td_size,MCRYPT_RAND);
			//$key = substr($key, 0, $td_size);
			mcrypt_generic_init($td, $key, $iv);
			$str = $this->pkcs5_pad($input, 8);
			$output = mcrypt_generic($td, $str);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			return 1;
		}
	}
	
	private function pkcs5_pad($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	private function pkcs5_unpad($text)
	{
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text))
		{
			return false;
		}
		if( strspn($text, chr($pad), strlen($text) - $pad) != $pad)
		{
			return false;
		}
		return substr($text, 0, -1 * $pad);
	}
############################################################################
	//RSA 使用私钥解密
	public function RSA_Decrypt($input, &$output, $ver, $conn)
	{
		//1、根据版本号获取私钥
		$private_key = '';
		$rv = $this->RSA_GetKey($ver, $private_key, $conn);
		if($rv != 1)
		{
			return 0;
		}

		//2、解密
		$private_key = openssl_pkey_get_private($private_key);
		$rv = openssl_private_decrypt($input, $output, $private_key);
		if(!$rv)
		{
			return -1;
		}
		return 1;
	}
	//RSA 使用私钥加密
	public function RSA_Encrypt($input, &$output, $ver, $conn)
	{
		//1、根据版本号获取私钥
		$private_key = null;
		$rv = $this->RSA_GetKey($ver, $private_key, $conn);
		if($rv != 1)
		{
			return 0;
		}
		$private_key = openssl_pkey_get_private($private_key);

		//2、加密
		$maxlength = 117;
		$output = '';
		//while($input)
		if(1)
		{
			$str	= substr($input, 0, $maxlength);//每次截取前117
			$input	= substr($input, $maxlength);	//向后截取
			$rv= openssl_private_encrypt($str, $tmp, $private_key);
			if(!$rv)
			{
				return -1;
			}
			$output .= $tmp;
		}
		return 1;
	}
	//使用公钥解密
	public function RSA_Decrypt_pub($input, &$output, $ver, $conn)
	{
		//1、根据版本号获取私钥
		$private_key = '';
		$rv = $this->RSA_GetKey($ver, $private_key, $conn);
		if($rv != 1)
		{
			return 0;
		}
		$public_key = openssl_pkey_get_public($public_key);

		//2、解密
		$maxlength=128;
		$output='';
		while($input)
		{
			$str	= substr($input, 0, $maxlength);//每次截取前117
			$input	= substr($input, $maxlength);	//向后截取
			$rv= openssl_public_decrypt($str, $tmp, $public_key);
			if(!$rv)
			{
				return -1;
			}
			$output .= $tmp;
		}
		return 1;
	}
	//通过密钥版本号获取RSA公钥和私钥
	private function RSA_GetKey($keyVersion, &$private_key, $conn)
	{
		$sql= "SELECT * FROM platform_key WHERE keyVersion = '$keyVersion' ORDER BY `createTime` DESC LIMIT 0 , 1";
		$result = mysql_query($sql, $conn);
		if(($row = mysql_fetch_assoc($result)) == null)
		{
			return 0;
		}
		//根据keyVersion从数据库取出。
		$private_key = $row["privateKey"];
		return 1;
	}
	//创建RSA密钥对
	public function RSA_KeyPair()
	{
		$res = openssl_pkey_new( array(	'private_key_bits' => 1024,
										'private_key_type' =>OPENSSL_KEYTYPE_RSA));
		openssl_pkey_export($res, $private_key);
		$pub_key = openssl_pkey_get_details($res);
		$pub_key['key'] = $pub_key['key'];

		$n = $pub_key['rsa']['n'] ;
		$e = $pub_key['rsa']['e'] ;

		$ret['n'] = bin2hex($n);
		$ret['e'] = bin2hex($e);
	
		$ret['private_key'] =  $private_key;
		$ret['public_key'] = $pub_key['key'];
		return $ret;
	}
}