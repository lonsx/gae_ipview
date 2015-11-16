<?php

require "includes/class.template.php";
$template = new verySimpleTemplate();

switch( @$_POST['action'] )
{
	case 'ip':
	if( $_POST['domain'] ) { ip( $_POST['domain'] ); } else { def(); } break;


	case 'id':
	if( $_POST['idcard'] ) { id( $_POST['idcard'] ); } else { def(); } break;

	default: def(); break;
}

function ip( $c )
{
	global $template;
	require 'includes/class.ip2location.php';

	$o = new Ip2Location;
	$p = "^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$";

	if ( preg_match( "/[a-zA-Z\-_]+/si", $c ) )
	{
		$i = gethostbyname( $c );
		if ( eregi( $p, $i ) )
		{
			$o->qqwry( $i );
			$l = str_replace( 'CZ88.NET', '', ($o->Country . $o->Local) );
			$arrayVar = array();
			$template->deal( 'template/header.tpl' );
			$h = $template->template;
			$template->deal( 'template/idxBody.tpl' );
			$b = $template->template;
			$arrayVar = array ( 
								'DOMAIN'	=> $c,
								'DOMAIN_IP' => $i,
								'LOCALTION' => $l
							  );
			$template->deal( 'template/domainLocationRender.tpl', $arrayVar );
			$r = $template->template;
			$template->deal( 'template/footer.tpl' );
			$f = $template->template;
			echo $h . $b . $r . $f;
		}
		else
		{
			error( '对不起，您输入的域名信息有误，请重新输入。' );
		}
	}
	else
	{
		if ( eregi( $p, $c ) )
		{
			$o->qqwry( $c );
			$l = str_replace( 'CZ88.NET', '', ($o->Country . $o->Local) );
			$arrayVar = array();
			$template->deal( 'template/header.tpl' );
			$h = $template->template;
			$template->deal( 'template/idxBody.tpl' );
			$b = $template->template;
			if( @$_GET['ipwhois'] )
			{
				$arrayVar = array ( 
									'IP'  => @$_GET['ipwhois'],
									'LOCALTION' => $l
								);
				$template->deal( 'template/ipLocationWhoisRender.tpl', $arrayVar );
				$r = $template->template;
				$template->deal( 'template/footer.tpl' );
				$f = $template->template;
				echo $h . $b . $r . $f;
			}
			else
			{
				$arrayVar = array ( 
									'IP'  => $c,
									'LOCALTION' => $l
								);
				$template->deal( 'template/ipLocationRender.tpl', $arrayVar );
				$r = $template->template;
				$template->deal( 'template/footer.tpl' );
				$f = $template->template;
				echo $h . $b . $r . $f;
			}
		}
		else
		{
			error( '对不起，您输入的IP信息有误，请重新输入。' );
		}
	}


}

function def()
{
	global $template;

	require 'includes/class.ip2location.php';
	$o = new Ip2Location();

	if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
	{
		$c = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
	{
		$c = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif ( !empty( $_SERVER['REMOTE_ADDR'] ) )
	{
		$c = $_SERVER['REMOTE_ADDR'];
	}
	else
	{
		$c = '';
	}

	$c = trim( $c );

	if( $c )
	{
		$o->qqwry( $c );
		$l = str_replace( 'CZ88.NET', '', ($o->Country . $o->Local) );
	}
	else
	{
		$c = '未知';
		$l = '未知';
	}
	if( !$l ) $l = '未知';

	$arrayVar = array();
	$template->deal( 'template/header.tpl' );
	$h = $template->template;
	$template->deal( 'template/idxBody.tpl' );
	$b = $template->template;
	if( @$_GET['ipwhois'] )
	{
		$arrayVar = array ( 
							'LOCAL_IP'  => @$_GET['ipwhois'],
							'LOCALTION' => $l
						);
		$template->deal( 'template/ipLocalWhoisRender.tpl', $arrayVar );
		$r = $template->template;
		$template->deal( 'template/footer.tpl' );
		$f = $template->template;
		echo $h . $b . $r . $f;
	}
	else
	{
		$arrayVar = array ( 
							'LOCAL_IP'  => $c,
							'LOCALTION' => $l
						);
		$template->deal( 'template/ipLocalRender.tpl', $arrayVar );
		$r = $template->template;
		$template->deal( 'template/footer.tpl' );
		$f = $template->template;
		echo $h . $b . $r . $f;
	}
}

function error( $e )
{
	global $template;
	$arrayVar = array();
	$template->deal( 'template/header.tpl' );
	$h = $template->template;
	$template->deal( 'template/idxBody.tpl' );
	$b = $template->template;
	$arrayVar = array ( 
						'ERROR'  => $e
					  );
	$template->deal( 'template/errorRender.tpl', $arrayVar );
	$r = $template->template;
	$template->deal( 'template/footer.tpl' );
	$f = $template->template;
	echo $h . $b . $r . $f;
}
?>