<?
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

require("fpdf151/scpdf.php");
include("fpdf151/impfarmacia.php");
include("libs/db_sql.php");
include("classes/db_tfd_beneficiadosajudacusto_classe.php");
require_once('libs/db_utils.php');

$cltfd_beneficiadosajudacusto = new cl_tfd_beneficiadosajudacusto;

function getCns($iCgs) {

  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');
  $sSql             = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                                   ' s115_i_cgs = '.$iCgs
                                                  );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);
  if ($oDaoCgsCartaoSus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgsCartaoSus = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $sCartaoSus         = $oDadosCgsCartaoSus->s115_c_cartaosus;

  }  else {
    $sCartaoSus = '';
  }
  
  return $sCartaoSus;

}

$sqlpref  = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sCampos  = " tf15_f_valoremitido, a.z01_v_nome as retirado, cgs_und.z01_v_nome, a.z01_v_cgccpf, ";
$sCampos .= " tf14_i_cgsretirou, a.z01_v_ident, tf14_c_horarecebimento, tf14_d_datarecebimento, ";
$sCampos .= " tf14_i_login, nome, tf15_i_codigo, tf15_i_cgsund, sd63_c_nome, ";
$sCampos .= " cgs_und.z01_v_nome as z01_v_nome_pac, cgs_und.z01_i_cgsund as z01_i_cgsund_pac, cgs_und.z01_d_nasc as ";
$sCampos .= " z01_d_nasc_pac, cgs_und.z01_v_ident as z01_v_ident_pac, cgs_und.z01_v_cgccpf as z01_v_cgccpf_pac, ";
$sCampos .= " cgs_und.z01_v_mae as z01_v_mae_pac, cgs_und.z01_v_ender as z01_v_ender_pac, ";
$sCampos .= " cgs_und.z01_i_numero as z01_i_numero_pac, cgs_und.z01_v_compl as z01_v_compl_pac, ";
$sCampos .= " cgs_und.z01_v_bairro as z01_v_bairro_pac, cgs_und.z01_v_munic as z01_v_munic_pac, ";
$sCampos .= " cgs_und.z01_v_uf as z01_v_uf_pac, cgs_und.z01_v_cep as z01_v_cep_pac, cgs_und.z01_v_telef as ";
$sCampos .= " z01_v_telef_pac, cgs_und.z01_v_telcel as z01_v_telcel_pac, cgs_und.z01_v_sexo as z01_v_sexo_pac, ";
$sCampos .= " tf12_descricao, tf15_observacao";

$sSql   = $cltfd_beneficiadosajudacusto->sql_query2("", $sCampos, "","tf14_i_pedidotfd = $tf14_i_pedidotfd");
$result = $cltfd_beneficiadosajudacusto->sql_record($sSql);
                                                                                          
if ($cltfd_beneficiadosajudacusto->numrows>0) {  
   db_fieldsmemory($result,0);
} else {
   db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado ! ");
}
$numrows_beneficiadosajudacusto = $cltfd_beneficiadosajudacusto->numrows;

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,885);
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";

for ($contador=0;$contador<1;$contador++) {

  db_fieldsmemory($result,$contador);
  $pdf1->logo       = $logo; 
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
   
   
  $pdf1->Rretirou      = $tf14_i_cgsretirou;
  $pdf1->Rcgsretirou   = $retirado;
  $pdf1->Rcpf          = $z01_v_cgccpf;
  $pdf1->Ridentidade   = $z01_v_ident;
  $pdf1->Rhoratfd      = $tf14_c_horarecebimento;
  $pdf1->Rdatatfd      = $tf14_d_datarecebimento;
  $pdf1->Rcodatendente = $tf14_i_login;
  $pdf1->Ratendente    = $nome;
  $pdf1->Rnumero       = $tf14_i_pedidotfd;

  $sSexo = 'FEMININO';
  if ($z01_v_sexo_pac == 'M') {
    $sSexo = 'MASCULINO';
  } 
  $sCartaoSUS = getCns($z01_i_cgsund_pac);  
  
  $pdf1->sRNomePaciente     = $z01_v_nome_pac;
  $pdf1->iRsCnsPaciente     = $z01_i_cgsund_pac;
  $pdf1->dRNascPaciente     = $z01_d_nasc_pac;
  $pdf1->sRIdentPaciente    = $z01_v_ident_pac;
  $pdf1->sRCpfPaciente      = $z01_v_cgccpf_pac;
  $pdf1->sRCartSusPaciente  = $sCartaoSUS;
  $pdf1->sRMaePaciente      = $z01_v_mae_pac;
  $pdf1->sRSexoPaciente     = $sSexo;
  $pdf1->sREnderecoPaciente = $z01_v_ender_pac;
  $pdf1->sRNumeroPaciente   = $z01_i_numero_pac; 
  $pdf1->sRComplPaciente    = $z01_v_compl_pac;
  $pdf1->sRBairroPaciente   = $z01_v_bairro_pac;
  $pdf1->sRMunicPaciente    = $z01_v_munic_pac;
  $pdf1->sRUfPaciente       = $z01_v_uf_pac;
  $pdf1->sRCepPaciente      = $z01_v_cep_pac;
  $pdf1->sRTelPaciente      = $z01_v_telef_pac;
  $pdf1->sRCelPaciente      = $z01_v_telcel_pac;
  
  $pdf1->sRtf15_observacao  = $tf15_observacao;
  $pdf1->sRtf12_descricao   = $tf12_descricao;
  $pdf1->iRtf01_i_codigo    = $tf14_i_pedidotfd;
  
  
  $pdf1->rcodcgs       = "tf15_i_cgsund";
  $pdf1->rbeneficiado  = "z01_v_nome";
  $pdf1->rvalor        = "tf15_f_valoremitido";
  $pdf1->rprocedimento = "sd63_c_nome";
 
  $pdf1->recorddositens = $result;
  $pdf1->linhasdositens = $numrows_beneficiadosajudacusto;
  $pdf1->robsdositens   = 'tf15_observacao';
  
  $pdf1->imprime();
  $pdf1->Snumero_ant = $tf15_i_codigo;

}

if (isset($argv[1])) {
  $pdf1->objpdf->Output("/tmp/teste.pdf");
} else {
  $pdf1->objpdf->Output();
}
?>