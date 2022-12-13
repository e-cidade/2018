<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("classes/db_saltes_classe.php");

$clsaltes = new cl_saltes;

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

$motivo = "";
// Dados

if (USE_PCASP) {

  $sql = "select k152_sequencial,
                 upper(k152_descricao) as k152_descricao,
                 slip.*,
                 z01_numcgm , 
                 z01_nome , 
                 c50_codhist as db_hist, 
                 c50_descr   as descr_hist,
                 k18_motivo,
                 coalesce(k18_codigo,0)  as k18_codigo,
                 case when 
                   k153_slipoperacaotipo not in (1, 2, 9, 10, 13, 14) 
                     then 
                       case when 
                         k153_slipoperacaotipo in (5, 6) 
                           then saltes_debito.k13_descr
                         else conta_debito.c60_descr end
                   else conta_debito.c60_descr end as descr_debito,
                 case when 
                   k153_slipoperacaotipo in (1, 2, 5, 6, 9, 10, 13, 14) 
                     then saltes_credito.k13_descr
                   else conta_credito.c60_descr end as descr_credito
            from slip
                 left join sliptipooperacaovinculo         on sliptipooperacaovinculo.k153_slip = slip.k17_codigo
                 left join sliptipooperacao                on sliptipooperacaovinculo.k153_slipoperacaotipo = sliptipooperacao.k152_sequencial
                 left join slipanul                        on slip.k17_codigo          = slipanul.k18_codigo
                 left join slipnum                         on slip.k17_codigo          = slipnum.k17_codigo
                 left join cgm                             on slipnum.k17_numcgm       = cgm.z01_numcgm
                 left join conhist                         on slip.k17_hist             = conhist.c50_codhist
                 
                 left join conplanoreduz as reduz_debito   on slip.k17_debito          = reduz_debito.c61_reduz 
                                                          and reduz_debito.c61_instit  = ".db_getsession('DB_instit')."
                                                          and reduz_debito.c61_anousu  = ".db_getsession("DB_anousu")."
                 left join conplano as conta_debito        on reduz_debito.c61_codcon  = conta_debito.c60_codcon 
                                                          and conta_debito.c60_anousu  = ".db_getsession("DB_anousu")."
                 left join saltes saltes_debito            on slip.k17_debito          = saltes_debito.k13_reduz
                 left join conplanoreduz as reduz_credito  on slip.k17_credito           = reduz_credito.c61_reduz 
                                                          and reduz_credito.c61_instit  = ".db_getsession('DB_instit')." 
                                                          and reduz_credito.c61_anousu  = ".db_getsession("DB_anousu")."
                 left join conplano as conta_credito       on reduz_credito.c61_codcon  = conta_credito.c60_codcon 
                                                          and conta_credito.c60_anousu  = ".db_getsession("DB_anousu")."
                 left join saltes saltes_credito           on slip.k17_credito          = saltes_credito.k13_reduz
           where slip.k17_codigo = $numslip and k17_instit = ".db_getsession('DB_instit');
} else {
  
  $sql = "select slip.*,
          z01_numcgm ,
          z01_nome ,
          c60_descr as descr_debito,
          p2.k13_descr as descr_credito,
          c50_codhist as db_hist,
          c50_descr as descr_hist,
          k18_motivo,
          coalesce(k18_codigo,0) as k18_codigo
          from slip
          left outer join slipanul    on slip.k17_codigo = slipanul.k18_codigo
          left outer join slipnum     on slip.k17_codigo = slipnum.k17_codigo
          left outer join cgm     on slipnum.k17_numcgm = cgm.z01_numcgm
          left outer join conplanoreduz   on slip.k17_debito = c61_reduz and
          c61_instit     = ".db_getsession('DB_instit')." and
          c61_anousu = ".db_getsession("DB_anousu")."
          left outer join conplano  on c61_codcon = c60_codcon and
          c60_anousu = ".db_getsession("DB_anousu")."
          left outer join saltes p2   on slip.k17_credito = p2.k13_reduz
          left outer join conhist     on slip.k17_hist = conhist.c50_codhist
          where slip.k17_codigo = $numslip and k17_instit = ".db_getsession('DB_instit');
}

try {

  $dados = db_query($sql);
  $sEvento = "";

  if (pg_numrows($dados) > 0){
    
    $aMotivo = db_fieldsMemory($dados,0);
    $motivo  = $k18_motivo;
    if (USE_PCASP) {
     
      $sEvento = $k152_sequencial . " - " . $k152_descricao;
    }
  }


  // seleciona os recursos envolvidos, ligados a conta recebedora do slip
  $sql = "select k29_recurso,
                 o15_descr,
                 k29_valor
          from sliprecurso
        inner join orctiporec on o15_codigo = k29_recurso
    where k29_slip= $numslip 
    order by k29_recurso
         ";
  $recursos  = db_query($sql);       
  // se houverem registros, monta um array
  $array_recursos =  array();
  if (pg_numrows($recursos)>0){
      for($x=0;$x < pg_numrows($recursos);$x++){ 
      db_fieldsmemory($recursos,$x);
          $array_recursos[] = "$k29_recurso#$o15_descr#$k29_valor";
      }

  }
  // print_r($array_recursos); exit;


  if (pg_numrows($dados) == 0) {
    throw new Exception('Documento de Slip não Cadastrado.');
  }

  db_fieldsmemory($dados,0);

  $sqlcai = "select * from caiparametro where k29_instit = ".db_getsession('DB_instit');
  $resultcai = db_query($sqlcai) or die($sqlcai);
  if (pg_numrows($resultcai) == 0) {
    $k29_modslipnormal = 36;
    $k29_modsliptransf = 36;
  } else {
    db_fieldsmemory($resultcai, 0);
    if ($k29_modslipnormal != 36 and $k29_modslipnormal != 37 and $k29_modslipnormal != 381) {
      $k29_modslipnormal = 36;
    }
    if ($k29_modsliptransf != 36 and $k29_modsliptransf != 37 and $k29_modslipnormal != 381) {
      $k29_modsliptransf = 36;
    }
  }

  $quantdeb = 0;
  if ($k17_debito > 0) {
    $clsaltes->sql_record($clsaltes->sql_query_file($k17_debito)); 
    $quantdeb = $clsaltes->numrows;
  }

  $quantcre = 0;
  if ($k17_credito > 0) {
    $clsaltes->sql_record($clsaltes->sql_query_file($k17_credito)); 
    $quantcre = $clsaltes->numrows;
  }

  if ($quantdeb > 0 and $quantcre > 0) {
    $codmodelo = $k29_modsliptransf;
  } else {
    $codmodelo = $k29_modslipnormal;
  }

  $pdf1 = new scpdf();
  $pdf1->Open();
  $pdf = new db_impcarne($pdf1, $codmodelo);
  $pdf->objpdf->AddPage();
  $pdf->objpdf->SetTextColor(0, 0, 0);

  // trecho para relatorio
  $head1 = "Texto numero 1";
  $head2 = "Texto numero 2";
  $head3 = "Texto numero 3";
  $head4 = "Texto numero 4";
  //$head5 = "Texto numero 5";
  $head6 = "Texto numero 6";
  $head7 = "Texto numero 7";
  $head8 = "Texto numero 8";
  $head9 = "Texto numero 9";
  $head10 = "Texto numero 10";
  // trecho para relatorio

  $sql = "select * from db_config where codigo = ".db_getsession('DB_instit');
  $dadospref = db_query($sql);
  db_fieldsmemory($dadospref, 0);

  $pdf->dados    = $dados;
  $pdf->recursos = $array_recursos;

  /**
   * dados bancarios do credor 
   */
  $iNumCgmCredor       = $z01_numcgm;
  $oDaoPcfornecon      = db_utils::getDao('pcfornecon');
  $sCamposDadosCredor  = "pc63_banco, pc63_agencia, pc63_agencia_dig, pc63_conta, pc63_conta_dig,";
  $sCamposDadosCredor .= "(select db90_descr from db_bancos where db90_codban = pc63_banco) as descricrao_banco ";
  $sWhereDadosCredor   = "pc63_numcgm = {$iNumCgmCredor}";
  $sSqlDadosCredor     = $oDaoPcfornecon->sql_query_padrao(null, $sCamposDadosCredor, null, $sWhereDadosCredor);
  $rsDadosCredor       = db_query($sSqlDadosCredor);

  /**
   * Erro no sql 
   */
  if ( !$rsDadosCredor ) {

    $sMensagemErro = "Erro ao buscar dados do credor.\n\n" . pg_last_error();
    throw new Exception($sMensagemErro);
  } 

  /**
   * Dados bancarios da conta padrao
   */
  if ( pg_num_rows($rsDadosCredor) > 0 ) {

    $oDadosCredor         = db_utils::fieldsMemory($rsDadosCredor, 0);
    $oDadosBancarioCredor = new StdClass();

    $oDadosBancarioCredor->iBanco         = $oDadosCredor->pc63_banco;
    $oDadosBancarioCredor->sBanco         = $oDadosCredor->descricrao_banco;
    $oDadosBancarioCredor->iAgencia       = $oDadosCredor->pc63_agencia;
    $oDadosBancarioCredor->iAgenciaDigito = $oDadosCredor->pc63_agencia_dig;
    $oDadosBancarioCredor->iConta         = $oDadosCredor->pc63_conta;
    $oDadosBancarioCredor->iContaDigito   = $oDadosCredor->pc63_conta_dig;

    $pdf->oDadosBancarioCredor = $oDadosBancarioCredor;
  }

  $pdf->logo     = $logo;
  $pdf->nomeinst = $nomeinst;
  $pdf->ender    = $ender;
  $pdf->munic    = $munic;
  $pdf->telef    = $telef;
  $pdf->email    = $email;
  $pdf->logo     = $logo;
  $pdf->motivo   = $motivo;
  $pdf->sEvento  = $sEvento;
  $pdf->objpdf->AliasNbPages();
  $pdf->objpdf->settopmargin(1);

  $pdf->imprime();
  $pdf->objpdf->Output();

} catch (Exception $oErro) {

  $sErro = str_replace("\n", '\n', $oErro->getMessage());

  $sMensagemErro .= "<script>                                 ";
  $sMensagemErro .= "  alert('{$sErro}'); ";
  $sMensagemErro .= "  window.close();                        ";
  $sMensagemErro .= "</script>                                ";

  echo $sMensagemErro;
}