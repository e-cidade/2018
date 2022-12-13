<?php
/**
 * Transcreve um número até a casa dos milhões 
 * @param  integer $iNumero  número a ser transcrito
 * @param  integer $array_row é a linha atual do relatório
 * @return string  número transcrito            
 */
function db_numero_to_palavra($iNumero, $array_row) {
  
  $nValor = ereg_replace(",", "\.", $iNumero);

  $zeros = '000.000.000,00';
  $nValor = number_format($nValor,2);
  $nValor = substr($zeros,0,strlen($zeros)-strlen($nValor)) . $nValor;
  

  $sMilhao  = transcreve_numero(substr($nValor,0,3));
  $sMilhao .= ( (substr($nValor,0,3) > 1) ? 'milhões' : '' );
  $sMilhar  = transcreve_numero(substr($nValor,4,3));

  if (trim($sMilhar) == "um") {
    $sMilhar = 'mil';
  } else {
    $sMilhar .= ( (substr($nValor,4,3) > 0) ? 'mil' : '' );
  }
  $sUnidades  = transcreve_numero(substr($nValor,8,3));

  if (trim($sMilhar) == "mil" && (strlen(trim($sMilhar))<>0 && strlen(trim($sUnidades))<>0) ) {
    $sMilhar .=" e ";
  } elseif (strlen(trim($sMilhar)) <> 0 && strlen(trim($sUnidades)) <> 0) {

    /**
     * verifica se a unidade se trata de uma centena ou uma dezena
     */
    if (strlen( (int) substr($nValor,8,3)) <= 2) {
      $sMilhar .=" e ";  
    } else {
      $sMilhar .=", ";
    }
  } else {
    $sMilhar .="";
  }

  $cRETURN = $sMilhao . ((strlen(trim($sMilhao))<>0 && strlen(trim($sMilhar))<>0) ? ', ' : '') .
             $sMilhar . 
             $sUnidades ;

            
  return trim($cRETURN);
}

function transcreve_numero($nValor) {

  $aUnidade  = array('','um ','dois ','três ','quatro ','cinco ','seis ','sete ','oito ','nove ');
  $aDezenas  = array('',' ','vinte','trinta','quarenta', 'cinquenta', 'sessenta', 'setenta','oitenta','noventa');
  $aCentenas = array('','cento','duzentos','trezentos','quatrocentos','quinhentos','seiscentos','setecentos','oitocentos','novecentos');
  $aExcessao = array('dez ', 'onze ', 'doze ', 'treze ', 'quatorze ', 'quinze ', 'desesseis ', 'desessete ', 'dezoito ', 'desenove ');

  $nPosicao1 = substr($nValor,0,1);
  $nPosicao2 = substr($nValor,1,1);
  $nPosicao3 = substr($nValor,2,1);

  $sCentena  = $aCentenas[($nPosicao1)];
  $sDezena   = $aDezenas[($nPosicao2)];
  $sUnidade  = $aUnidade[($nPosicao3)];

  if (substr($nValor,0,3) == '100')
  { $sCentena = 'cem '; }

  if (substr($nValor,1,1) == '1')
  {  $sDezena = $aExcessao[$nPosicao3];
     $sUnidade = '';
  }

  $aResultado = array();
  if (!empty($sCentena)) {
    $aResultado[] = $sCentena;
  }
  if (!empty($sDezena)) {
    $aResultado[] = $sDezena;
  }
  if (!empty($sUnidade)) {
    $aResultado[] = $sUnidade;
  }
  
  $sResultado = implode(" e ", $aResultado);

  // $sResultado = $sCentena . $sDezena . $sUnidade;
  $sResultado = substr($sResultado,0, strlen($sResultado));
  return $sResultado;
}

