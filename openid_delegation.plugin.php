<?php

class OpenID_Delegation extends Plugin {

	private $config = array();

	function set_priorities()
	{
		return array(
			'theme_header' => 11,
		);
	}

	public function action_init()
	{
		$class_name= strtolower( get_class( $this ) );
		$this->config['provider'] = Options::get( $class_name . '__provider' );
		$this->config['identity'] = Options::get( $class_name . '__identity' );
		$this->config['is2'] = Options::get( $class_name . '__is2' );
	}

	public function configure()
	{
		$class_name = "openid_delegation";
		$ui = new FormUI( $class_name );

		$provider = $ui->append( 'text', 'provider', $class_name . '__provider', _t( 'Address of your identity server (required)' ) );
		$provider->add_validator( 'validate_required' );
		$provider->add_validator( 'validate_url' );

		$identity = $ui->append( 'text', 'identity', $class_name . '__identity', _t( 'Your OpenID identifier with that identity provider (required)' ) );
		$identity->add_validator( 'validate_required' );
		$identity->add_validator( 'validate_url' );

		$is2 = $ui->append( 'checkbox','is2', $class_name . '__is2', _t( 'Add links for OpenID 2.0 (must be supported by your provider)' ) );

		$ui->append( 'submit', 'save', 'save' );
		return $ui;
	}

	public function theme_header( $theme )
	{
		if( isset($theme) && $theme->request->display_home ) {
			return $this->add_links();
		}
	}

	private function add_links()
	{
		$out = '';

		$provider = $this->config['provider'];
		$identity = $this->config['identity'];
		$is2 = $this->config['is2'];

		if ( isset( $provider ) and isset( $identity ) ) {
			$out = "\t<link rel=\"openid.server\" href=\"" . $provider . "\">\n";
			$out .= "\t<link rel=\"openid.delegate\" href=\"" . $identity . "\">\n";

			if ($is2) {
				$out .= "\t<link rel=\"openid2.provider\" href=\"" . $provider . "\">\n";
				$out .= "\t<link rel=\"openid2.local_id\" href=\"" . $identity . "\">\n";
			}
			return $out;
		}
	}

}
?>
