<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


require_once ('model/PadArquivoSigap.model.php');
/**
 * Prove dados para a geração do arquivo das rubricas para o SIGAP
 * @package Pad
 * @author Iuri Guncthnigg
 * @version $Revision: 1.2 $
 */
final class PadArquivoSigapRubrica extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "RubricaDespesa";
    $this->aDados       = array();
  }
  
  /**
   * Gera os dados para utilizacao posterior. Metodo geralmente usado 
   * em conjuto com a classe PadArquivoEscritorXML
   * @return true;
   */
  public function gerarDados() {
    
    if (empty($this->sDataInicial)) {
      throw new Exception("Data inicial nao informada!");
    }
    
    if (empty($this->sDataFinal)) {
      throw new Exception("Data final não informada!");
    }
    /**
     * Separamos a data do em ano, mes, dia
     */
    $sWhereInstit = " and o58_instit = ".db_getsession("DB_instit");
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    
    $sSqlRubrica  = "select distinct on (o56_anousu,elemento) elemento,";
    $sSqlRubrica .= "       o56_anousu as ano,";
    $sSqlRubrica .= "       tipo,";
    $sSqlRubrica .= "       o56_descr,";
    $sSqlRubrica .= "       nivel";
    $sSqlRubrica .= "  from (";
    $sSqlRubrica .= "        select o56_anousu,";
    $sSqlRubrica .= "               case when o56_anousu >= 2005 then";
    $sSqlRubrica .= "                    substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15)";
    $sSqlRubrica .= "                else";
    $sSqlRubrica .= "                   substr(trim(o56_elemento)||'000000000',1,15)::varchar(15)";
    $sSqlRubrica .= "                end as elemento,";
    $sSqlRubrica .= "               case when o56_anousu >= 2005 then";
    $sSqlRubrica .= "                  case when c61_anousu is null then";
    $sSqlRubrica .= "                      'S' else 'A'";
    $sSqlRubrica .= "                  end";
    $sSqlRubrica .= "               else";
    $sSqlRubrica .= "                  case when o58_anousu is null then";
    $sSqlRubrica .= "                    'S' else 'A'";
    $sSqlRubrica .= "                 end";
    $sSqlRubrica .= "               end as tipo,";
    $sSqlRubrica .= "               o56_descr,";
    $sSqlRubrica .= "               case when o56_anousu >= 2005 then";
    $sSqlRubrica .= "                    fc_nivel_plano2005(substr(o56_elemento,2,12)::varchar(15)||'000')";
    $sSqlRubrica .= "               else";
    $sSqlRubrica .= "                    fc_nivel_plano2005(substr(trim(o56_elemento)||'000000000',1,15)::varchar(15))";
    $sSqlRubrica .= "               end as nivel";
    $sSqlRubrica .= "          from orcelemento";
    $sSqlRubrica .= "               left join conplanoreduz  on o56_codele = c61_codcon";
    $sSqlRubrica .= "                                       and o56_anousu = c61_anousu";
    $sSqlRubrica .= "               left join orcdotacao     on o58_anousu = o56_anousu";
    $sSqlRubrica .= "                                       and o58_codele = o56_codele";
    $sSqlRubrica .= "         where o56_anousu <= {$iAno}";             
    $sSqlRubrica .= "         order by o56_anousu,elemento";
    $sSqlRubrica .= "      ) as x "; 
    $rsRubrica    = db_query(analiseQueryPlanoOrcamento($sSqlRubrica));
    $iTotalLinhas = pg_num_rows($rsRubrica); 
    for ($i = 0; $i < $iTotalLinhas; $i++) {
          
      $sDiaMesAno    =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oRubrica        = db_utils::fieldsMemory($rsRubrica, $i);
      if (($oRubrica->elemento+0) == 0) { 
       continue;
      }
      $oRubricaRetorno                     = new stdClass();
      $oRubricaRetorno->rubCodigoEntidade  = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oRubricaRetorno->rubMesAnoMovimento = $sDiaMesAno;
      $oRubricaRetorno->rubExercicio       = $oRubrica->ano;
      $oRubricaRetorno->rubCodigoRubrica   = str_pad($oRubrica->elemento, 15, "0", STR_PAD_RIGHT);
      $oRubricaRetorno->rubEspecificacao   = substr($oRubrica->o56_descr, 0, 110);
      $oRubricaRetorno->rubTipoNivel       = $oRubrica->tipo;
      $oRubricaRetorno->rubNumeroNivel     = str_pad($oRubrica->nivel, 2, "0", STR_PAD_LEFT);
      array_push($this->aDados, $oRubricaRetorno);
      
    }
    return true;
  }
  
  /**
   * Publica quais elementos/Campos estão disponiveis para 
   * o uso no momento da geração do arquivo
   *
   * @return array com elementos disponibilizados para a geração dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                        "rubCodigoEntidade",
                        "rubMesAnoMovimento",
                        "rubExercicio",
                        "rubCodigoRubrica",
                        "rubEspecificacao",
                        "rubTipoNivel",
                        "rubNumeroNivel",
                       );
    return $aElementos;  
  }
  
}

?>