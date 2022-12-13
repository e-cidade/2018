<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
 * Função para geração de Log
 *
 * @param  string  $sLog         Mensagem a ser registrada
 * @param  string  $sArquivo     Nome do arquivo para escrita
 * @param  integer $iTipo        Tipo de saida
 * @param  boolean $lLogDataHora Se registra data/hora do chamada
 * @param  boolean $lQuebraAntes Se quebra linha antes de registrar
 * @return void
 */
function db_log($sLog = "", $sArquivo = "", $iTipo = 0, $lLogDataHora = true, $lQuebraAntes = true) {

  $aDataHora  = getdate();
  $sQuebraAntes = $lQuebraAntes ? "\n" : "";

  if ($lLogDataHora) {
    $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes, $aDataHora ["mday"], $aDataHora ["mon"], $aDataHora ["year"], $aDataHora ["hours"], $aDataHora ["minutes"], $aDataHora ["seconds"], $sLog);
  } else {
    $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
  }

  // Se habilitado saida na tela
  if ($iTipo == 0 || $iTipo == 1) {
    echo $sOutputLog;
  }

  // Se habilitado saida para arquivo
  if ($iTipo == 0 || $iTipo == 2) {

    if (! empty($sArquivo)) {

      $fd = fopen($sArquivo, "a+");
      if ($fd) {

        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
    }
  }

  return $aDataHora;
}

/**
 * Função que exibe na tela a quantidade de registros processados
 * e a quandidade de memória utilizada
 *
 * @param integer $iIndice      Indice da linha que está sendo processada
 * @param integer $iTotalLinhas Total de linhas a processar
 * @param integer $iParamLog    Caso seja passado true é exibido na tela
 */
function logProcessamento($iIndice, $iTotalLinhas, $iParamLog){

  $nPercentual = round((($iIndice + 1) / $iTotalLinhas) * 100, 2);
  $nMemScript  = (float)round( (memory_get_usage()/1024 ) / 1024,2);
  $sMemScript  = $nMemScript ." Mb";
  $sMsg        = "".($iIndice+1)." de {$iTotalLinhas} Processando {$nPercentual}% Total de memoria utilizada: {$sMemScript} ";
  $sMsg        = str_pad($sMsg,100," ",STR_PAD_RIGHT);
  db_log($sMsg."\n",null,$iParamLog,true,false);
}

/**
 * Imprime o título do log
 *
 * @param string  $sTitulo
 * @param boolean $iParamLog  Caso seja passado true é exibido na tela
 */
function db_logTitulo($sTitulo="", $sArquivoLog="", $iParamLog=0) {

  db_log("",$sArquivoLog,$iParamLog);
  db_log("/".str_pad($sTitulo,85,"-",STR_PAD_BOTH)."/",$sArquivoLog,$iParamLog);
  db_log("",$sArquivoLog,$iParamLog);
}

/**
 * Verifica de existe alguma diferença entre os dois objetos apartir das
 * propriedades do primeiro objeto passado por parâmetro
 *
 * @param  object $oObject1
 * @param  object $oObject2
 * @return boolean
 */
function hasDiffObject($oObject1, $oObject2){

  $aPropriedades = get_object_vars($oObject1);
  $lDiff         = false;

  foreach ( $aPropriedades as $sNome => $sValor ) {

    if ( isset($oObject1->$sNome) && isset($oObject2->$sNome) ){

      if ( $oObject1->$sNome != $oObject2->$sNome ) {
        $lDiff = true;
      }
    }

  }

  return $lDiff;
}