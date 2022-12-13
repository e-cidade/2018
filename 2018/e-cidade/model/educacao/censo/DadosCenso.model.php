<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/**
 * Classe base da geracao dos arquivos do censo
 *
 */
class DadosCenso {

  /**
   * Remove Caracteres nao Permitidos
   *
   * $string = string a ser retirados os caracteres
   * $tipo = tipo de validação: 1, 2, 3 e 4
   *
   * 1 - Somente Letras e espaço
   * 2 - Somente Números, Letras, espaço, ª, º  e traço
   * 3 - Somente Números, Letras, espaço,  ponto, virgula, barra e traço
   * 4 - Somente Números, Letras, arroba, ponto, sublinha e traço (email)
   * 6 - Somente aceita: (ABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789 ª º -).
   * 7 - Somente aceita: (ABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789 ªº - / . ,).
   * 8 - Somente aceita: (ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789).
   *
   * @param string  $string
   * @param integer $tipo
   * @param boolean $retira_acentos
   * @return string
   */
  public function removeCaracteres($string, $tipo, $retira_acentos = true) {

    $string = str_replace(chr(92),"",$string);// contrabarra -> \
    $string = str_replace(";","",$string);
    $string = str_replace(":","",$string);
    $string = str_replace("?","",$string);
    $string = str_replace("'","",$string);
    $string = str_replace(chr(34),"",$string);// aspas dupla -> "
    $string = str_replace("!","",$string);
    $string = str_replace("#","",$string);
    $string = str_replace("$","",$string);
    $string = str_replace("%","",$string);
    $string = str_replace("&","",$string);
    $string = str_replace("*","",$string);
    $string = str_replace("(","",$string);
    $string = str_replace(")","",$string);
    $string = str_replace("+","",$string);
    $string = str_replace("=","",$string);
    $string = str_replace("{","",$string);
    $string = str_replace("}","",$string);
    $string = str_replace("[","",$string);
    $string = str_replace("]","",$string);
    $string = str_replace("<","",$string);
    $string = str_replace(">","",$string);
    $string = str_replace("|","",$string);
    $string = str_replace("§","",$string);
    $string = str_replace("¹","",$string);
    $string = str_replace("²","",$string);
    $string = str_replace("³","",$string);
    $string = str_replace("£","",$string);
    $string = str_replace("¢","",$string);
    $string = str_replace("¬","",$string);
    $string = str_replace("~","",$string);
    $string = str_replace("^","",$string);
    $string = str_replace("´","",$string);
    $string = str_replace("`","",$string);
    $string = str_replace("¨","",$string);

    if ($tipo == 1) {

      $string = str_replace("/","",$string);
      $string = str_replace("@","",$string);
      $string = str_replace(".","",$string);
      $string = str_replace(",","",$string);
      $string = str_replace("-","",$string);
      $string = str_replace("_","",$string);
      $string = str_replace("0","",$string);
      $string = str_replace("1","",$string);
      $string = str_replace("2","",$string);
      $string = str_replace("3","",$string);
      $string = str_replace("4","",$string);
      $string = str_replace("5","",$string);
      $string = str_replace("6","",$string);
      $string = str_replace("7","",$string);
      $string = str_replace("8","",$string);
      $string = str_replace("9","",$string);
      $string = str_replace("ª","",$string);
      $string = str_replace("º","",$string);
      $string = str_replace("°","",$string);
    }

    if ($tipo == 2) {

      $string = str_replace("/","",$string);
      $string = str_replace("@","",$string);
      $string = str_replace(".","",$string);
      $string = str_replace(",","",$string);
      $string = str_replace("_","",$string);
      $string = str_replace("°","º",$string);
    }

    if ($tipo == 3) {

      $string = str_replace("@","",$string);
      $string = str_replace("_","",$string);
      $string = str_replace("ª","",$string);
      $string = str_replace("º","",$string);
      $string = str_replace("°","",$string);
    }

    if ($tipo == 4) {

      $string = str_replace("/","",$string);
      $string = str_replace(",","",$string);
      $string = str_replace(" ","",$string);
      $string = str_replace("ª","",$string);
      $string = str_replace("º","",$string);
      $string = str_replace("°","",$string);
    }

    if ($tipo == 5) {
      $string = str_replace("@","",$string);
    }

    if ( $tipo == 6 ) {

      //remove sempre os acentos antes
      $string = $this->retiraAcento( trim($string) );
      $string = preg_replace("/[^a-zA-Z0-9ªº\-\s]/", "", $string);
      $retira_acentos = false;
    }

    // usar nos endereções
    if ($tipo == 7 ) {

      $string = $this->retiraAcento( trim($string) );
      $string = preg_replace("/[^a-zA-Z0-9ªº\-\s\/.,]/", "", $string);
      $retira_acentos = false;
    }

    if ( $tipo == 8 ) {

      $string = $this->retiraAcento( trim($string) );
      $string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
      $retira_acentos = false;
    }

    if ( $retira_acentos ) {
      $string = $this->retiraAcento($string);
    }
    $string = strtoupper($string);
    return $string;

  }

  function retiraAcento($string) {

    $acentos    = 'áéíóúÁÉÍÓÚàÀÂâÊêôÔüÜïÏöÖñÑãÃõÕçÇäÄ\'';
    $letras     = 'AEIOUAEIOUAAAAEEOOUUIIOONNAAOOCCAA ';
    $new_string = '';

    for ($x = 0; $x < strlen($string); $x++) {

      $let = substr($string, $x, 1);
      for ($y = 0; $y < strlen($acentos); $y++) {

        if ($let == substr($acentos, $y, 1)) {

          $let = substr($letras, $y, 1);
          break;

        }
      }
      $new_string = $new_string . $let;
    }
    return $new_string;
  }

  /**
   * Valida NIS (PIS/PASEP/NIT)
   *
   * @param   string $sNIS
   * @return  bool TRUE se é um NIS válido ou retorna falso
   */
  static function ValidaNIS($sNIS) {

  	// Formata numero
  	$sNIS = sprintf('%011s', preg_replace('{\D}', '', $sNIS));

  	// Valida tamanho
  	if ((strlen($sNIS) != 11)	|| (intval($sNIS) == 0)) {

  		return false;
  	}

  	// Valida digito verificador utilizando modulo 11
  	for ($d = 0, $p = 2, $c = 9; $c >= 0; $c--, ($p < 9) ? $p++ : $p = 2) {
  		$d += $sNIS[$c] * $p;
  	}

  	return ($sNIS[10] == (((10 * $d) % 11) % 10));
  }

  /**
   * Verifica Duplicidade de valores em array
   *
   * @param   array $aArray
   * @return  bool TRUE se nao houver duplicidade ou retorna falso
   */
  static function VerificaDuplicidade($aArray){

  	$aArrayTemp = array();

  	foreach($aArray as $iValor){

  		if (!in_array($iValor, $aArrayTemp)) {
  			if( trim($iValor) <> ''){
  				$aArrayTemp[] = $iValor;
  			}
  		}else{
  			return false;
  		}
  	}
  	return true;
  }

  /**
   * Retorna o último ano válido do censo
   * @return integer
   * @throws DBException
   */
  static function getUltimoAnoEtapaCenso() {

    $oDaoEtapaCenso = new cl_censoetapa();
    $sOrdemCenso    = "ed266_ano desc limit 1";
    $sSqlEtapaCenso = $oDaoEtapaCenso->sql_query_file(null, null, "ed266_ano", $sOrdemCenso);
    $rsEtapaCenso   = db_query($sSqlEtapaCenso);

    if ( !$rsEtapaCenso ) {
      throw new DBException("Não foi possível buscar último ano do censo:\n" . pg_last_error() );
    }

    if ( pg_num_rows($rsEtapaCenso) == 0 ) {
      throw new DBException("Nenhum ano cadastrado para o censo");
    }

    return db_utils::fieldsMemory( $rsEtapaCenso, 0 )->ed266_ano;
  }

  /**
   * Retorna todas as atividades complementares que não podem ser utilizadas em 2015
   * @return array
   */
  static function getAtividadesComplementaresExcluidasCenso2015() {
    return array(   31001, 31002, 31003, 31004, 31005, 31006, 31007, 31011, 31012, 31013, 91001, 91002, 13102, 13103
                  , 13201, 22010, 31008, 61002, 61003, 61004, 61005, 14106, 16105, 16104
                );
  }
}
