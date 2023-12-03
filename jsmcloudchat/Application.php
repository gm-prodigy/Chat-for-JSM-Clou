<?php
/**
 * @brief		Chat for JSM Cloud Application Class
 * @author		<a href=''>Gm Prodigy</a>
 * @copyright	(c) 2023 Gm Prodigy
 * @package		Invision Community
 * @subpackage	Chat for JSM Cloud
 * @since		01 Dec 2023
 * @version		
 */
 
namespace IPS\jsmcloudchat;

/**
 * Chat for JSM Cloud Application Class
 */
class _Application extends \IPS\Application
{
    public static function GetJWT($member_id) {

        try
        {
            //load the member
            $member = \IPS\Member::load($member_id);

            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT',
            ];
    
            $payload = [
                'email' => $member->email,
                'displayName' => $member->name,
                'iat' => time(), // current Unix timestamp
            ];
    
            $sharedSecret = \IPS\Settings::i()->jsmcloudchat_api_key;
    
            // Use the generate function to create the token
            $token = self::generate($header, $payload, $sharedSecret);
    
            //save the token to session
            \IPS\Request::i()->setCookie('jsmcloudchat_jwt', $token, 0, '/', null, false, true);

            return $token;

        }
        catch ( \RuntimeException $e )
        {
            if ( method_exists( get_parent_class(), __FUNCTION__ ) )
            {
                return \call_user_func_array( 'parent::' . __FUNCTION__, \func_get_args() );
            }
            else
            {
                throw $e;
            }
        }


    }

    private static function generate(array $header, array $payload, $sharedSecret): string
    {
        $headers = self::base64UrlEncode(json_encode($header)); // encode headers
        $payload["exp"] = time() + \IPS\Settings::i()->jsmcloudchat_exp_minutes * 60; // add expiration to payload
        $payload = self::base64UrlEncode(json_encode($payload)); // encode payload
        $signature = hash_hmac('SHA256', "$headers.$payload", $sharedSecret, true); // create SHA256 signature
        $signature = self::base64UrlEncode($signature); // encode signature

        return "$headers.$payload.$signature";
    }
    
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

