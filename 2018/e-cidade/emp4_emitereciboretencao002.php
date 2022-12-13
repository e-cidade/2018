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
require_once(modification("fpdf151/scpdf.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_barras.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_db_bancos_classe.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/convenio.model.php"));

if (isset($listaordens)) {
  
  $aOrdens = explode(",", $listaordens);
  for ($i = 0; $i < count($aOrdens); $i++) {
    
    $sSqlRecibos  = "SELECT e21_descricao, ";
    $sSqlRecibos .= "       e21_retencaotipocalc , ";
    $sSqlRecibos .= "       case when k12_numnov<> k12_numpre then k12_numnov else k12_numpre end as codarrecad, ";
    $sSqlRecibos .= "       case when k12_numnov<> k12_numpre then 1 else 2 end as tiporecibo, ";
    $sSqlRecibos .= "       k12_valor, ";
    $sSqlRecibos .= "       e23_valorbase, ";
    $sSqlRecibos .= "       e23_aliquota, ";
    $sSqlRecibos .= "       e21_receita, ";
    $sSqlRecibos .= "       k02_descr, ";
    $sSqlRecibos .= "       case when e49_numcgm is null then e60_numcgm else e49_numcgm end as numcgm, ";
    $sSqlRecibos .= "       case when e49_numcgm is null then cgm.z01_nome else cgmordem.z01_nome end as nome, ";
    $sSqlRecibos .= "      k12_data, ";
    $sSqlRecibos .= "      e20_pagordem, ";
    $sSqlRecibos .= "      e60_codemp||'/'||e60_anousu as empenho, ";
    $sSqlRecibos .= "      (case when k00_tipo is null then ";
    $sSqlRecibos .= "        (select k00_tipo from recibo where recibo.k00_numpre = k12_numpre) ";
    $sSqlRecibos .= "      else k00_tipo  ";
    $sSqlRecibos .= "      end ) as k00_tipo ";     
    $sSqlRecibos .= " from retencaoreceitas  ";
    $sSqlRecibos .= "       inner join retencaopagordem         on e20_sequencial      = e23_retencaopagordem ";
    $sSqlRecibos .= "       inner join retencaocorgrupocorrente on e23_sequencial      = e47_retencaoreceita ";
    $sSqlRecibos .= "       inner join corgrupocorrente         on k105_sequencial     = e47_corgrupocorrente ";
    $sSqlRecibos .= "       inner join cornump                  on k12_data            = k105_data  ";
    $sSqlRecibos .= "                                          and k12_id              = k105_id ";
    $sSqlRecibos .= "                                          and k12_autent          = k105_autent ";
    $sSqlRecibos .= "      inner join pagordem                  on e20_pagordem        = e50_codord  ";
    $sSqlRecibos .= "      left  join pagordemconta             on e49_codord          = e50_codord  ";
    $sSqlRecibos .= "      left  join cgm cgmordem              on e49_numcgm          = cgmordem.z01_numcgm  ";
    $sSqlRecibos .= "      inner join empempenho                on e50_numemp          = e60_numemp  ";
    $sSqlRecibos .= "      inner join cgm                       on e60_numcgm          = cgm.z01_numcgm  ";
    $sSqlRecibos .= "      left join arrecant                   on k12_numpre          = k00_numpre ";
    $sSqlRecibos .= "      inner join retencaotiporec           on e23_retencaotiporec = e21_sequencial ";
    $sSqlRecibos .= "      inner join retencaoempagemov         on e23_sequencial      = e27_retencaoreceitas ";
    $sSqlRecibos .= "      inner join tabrec                    on e21_receita         = k02_codigo ";
    $sSqlRecibos .= " where e23_recolhido is true and e23_ativo is true  ";
    $sSqlRecibos .= "   and e27_empagemov  = {$aOrdens[$i]}";
    $sSqlRecibos .= "   and e27_principal is true";
    
    $rsRecibos    = db_query($sSqlRecibos);
    if ($rsRecibos) {
      $aRecibos = db_utils::getCollectionByRecord($rsRecibos);
    }

    if (count($aRecibos) == 0) {

      $sErroMsg = urlencode("Nenhum registro foi encontrado.");
      db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
    }

    $pdf  = new scpdf();
    foreach ($aRecibos as $oRecibo) {
      
      $DB_DATACALC = db_getsession("DB_datausu");
      $_POST["CHECK10"]          = "";
      $_POST["ver_inscr"]        = "";
      $_POST["ver_numcgm"]       = $oRecibo->numcgm;
      $_POST["numcgm"]           = $oRecibo->numcgm;
      $_POST["lGerarOutput"]     = "f";
      $_POST["k03_perparc"]      = "f";
      $_POST["numpre"]           = $oRecibo->codarrecad;
      $_POST["iNumpre"]          = $oRecibo->codarrecad;
      $_POST["k03_numpre"]        = $oRecibo->codarrecad;
      $_POST["k03_tipo"]         = $oRecibo->k00_tipo;
      $_POST["tipo_debito"]      = $oRecibo->k00_tipo;
      $_POST["tipo"]             = $oRecibo->k00_tipo;
      $_POST["k00_histtxt"]      = "";
      $_POST["k03_parcelamento"] = "f";
      $_POST["emrec"]            = "t";
      $_POST["reemite_recibo"]   = "1";
      $_POST["lReemissao"]       = true;

      if ($oRecibo->tiporecibo == 1) {

        unset($emite_recibo_protocolo);
        require_once(modification("cai3_reemiterecibo.php"));
      } else {
        require_once(modification("cai4_recibo003.php"));
      }
    }

    $pdf1->objpdf->output();
  }
  
} else {

  $DB_DATACALC = db_getsession("DB_datausu");
  $_POST["CHECK10"]    = $_GET["CHECK10"];
  $_POST["ver_inscr"]  = $_GET["ver_inscr"];
  $_POST["ver_numcgm"] = $_GET["ver_numcgm"];

  require_once(modification("cai3_reemiterecibo.php"));
}