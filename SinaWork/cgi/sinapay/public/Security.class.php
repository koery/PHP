<?php
class SECURITY
{
	//3DED
	public function tripleDES($input, &$output, $key, $flag)
	{
		if($flag)
		{
			//���ܹ���
			/*$str = base64_decode($input);
			//���ܷ���
			$cipher_alg = MCRYPT_TRIPLEDES;
			//��ʼ�����������Ӱ�ȫ��
			$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
			//��ʼ����
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
			//���ܷ���
			/*$cipher_alg = MCRYPT_TRIPLEDES;
			//��ʼ�����������Ӱ�ȫ��
			$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
			//��ʼ����
			$encrypted_string = mcrypt_encrypt($cipher_alg, $key, $input, MCRYPT_MODE_ECB, $iv);
			$output = base64_encode($encrypted_string);//ת����16����*/
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
	//RSA ʹ��˽Կ����
	public function RSA_Decrypt($input, &$output, $ver, $conn)
	{
		//1�����ݰ汾�Ż�ȡ˽Կ
		$private_key = '';
		$rv = $this->RSA_GetKey($ver, $private_key, $conn);
		if($rv != 1)
		{
			return 0;
		}

		//2������
		$private_key = openssl_pkey_get_private($private_key);
		$rv = openssl_private_decrypt($input, $output, $private_key);
		if(!$rv)
		{
			return -1;
		}
		return 1;
	}
	//RSA ʹ��˽Կ����
	public function RSA_Encrypt($input, &$output, $ver, $conn)
	{
		//1�����ݰ汾�Ż�ȡ˽Կ
		$private_key = null;
		$rv = $this->RSA_GetKey($ver, $private_key, $conn);
		if($rv != 1)
		{
			return 0;
		}
		$private_key = openssl_pkey_get_private($private_key);

		//2������
		$maxlength = 117;
		$output = '';
		//while($input)
		if(1)
		{
			$str	= substr($input, 0, $maxlength);//ÿ�ν�ȡǰ117
			$input	= substr($input, $maxlength);	//����ȡ
			$rv= openssl_private_encrypt($str, $tmp, $private_key);
			if(!$rv)
			{
				return -1;
			}
			$output .= $tmp;
		}
		return 1;
	}
	//ʹ�ù�Կ����
	public function RSA_Decrypt_pub($input, &$output, $ver, $conn)
	{
		//1�����ݰ汾�Ż�ȡ˽Կ
		$private_key = '';
		$rv = $this->RSA_GetKey($ver, $private_key, $conn);
		if($rv != 1)
		{
			return 0;
		}
		$public_key = openssl_pkey_get_public($public_key);

		//2������
		$maxlength=128;
		$output='';
		while($input)
		{
			$str	= substr($input, 0, $maxlength);//ÿ�ν�ȡǰ117
			$input	= substr($input, $maxlength);	//����ȡ
			$rv= openssl_public_decrypt($str, $tmp, $public_key);
			if(!$rv)
			{
				return -1;
			}
			$output .= $tmp;
		}
		return 1;
	}
	//ͨ����Կ�汾�Ż�ȡRSA��Կ��˽Կ
	private function RSA_GetKey($keyVersion, &$private_key, $conn)
	{
		$sql= "SELECT * FROM platform_key WHERE keyVersion = '$keyVersion' ORDER BY `createTime` DESC LIMIT 0 , 1";
		$result = mysql_query($sql, $conn);
		if(($row = mysql_fetch_assoc($result)) == null)
		{
			return 0;
		}
		//����keyVersion�����ݿ�ȡ����
		$private_key = $row["privateKey"];
		return 1;
	}
	//����RSA��Կ��
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