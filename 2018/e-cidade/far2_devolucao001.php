<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("fpdf151/impfarmacia.php");
require_once("libs/db_sql.php");
//include("classes/db_far_devolucao_classe.php");
//include("classes/db_far_devolucaomed_classe.php");
//include("classes/db_db_depart_classe.php");
require_once('libs/db_utils.php');
$oDaoFarDev       = db_utils::getdao("far_devolucao");//$clfar_devolucao    = new cl_far_devolucao;
$oDaoFarDevMed    = db_utils::getdao("far_devolucaomed");//$clfar_devolucaomed = new cl_far_devolucaomed;
$oDaoDbDepart     = db_utils::getdao("db_depart"); //$cldb_depart        = new cl_db_depart;
/*Variaveis e suas descriçoes 
*$iCodDev -> codigo da devolucao;
*
*/
$dDataRelatorio      = date("d/m/y");
$dHoraRelatorio      = date("H:i");
$iDepart             = $departamento;//departamento selecionado
$sSqlpref            = "select * from db_config where codigo = ".db_getsession("DB_instit");//sql da prefeitura
$resultpref          = db_query($sSqlpref);//resultset da do sql da prefeitura
$oDaoPref            = db_utils::fieldsmemory($resultpref,0);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$sSql                = $oDaoDbDepart->sql_query_file(null, "coddepto,descrdepto", "", "coddepto = $iDepart");
$rsDepart            = $oDaoDbDepart->sql_record($sSql);
$iLinhasDepart       = $oDaoDbDepart->numrows;
$oDepart     = db_utils::fieldsmemory($rsDepart,0);
$sCamposDev  = "fa22_d_data,fa22_i_codigo, fa22_i_login, nome, fa22_c_hora, fa22_i_cgsund, z01_v_nome";
$sWhereDev   = "z01_i_cgsund= $fa22_i_cgsund and  fa06_i_matersaude = $fa06_i_matersaude";
$sSqlDev     = $oDaoFarDev->sql_query_dev(null,$sCamposDev,"",$sWhereDev ); //pelo cgs e pelo medicamento que está sendo devolvido
$rsDevolv    = $oDaoFarDev->sql_record($sSqlDev);
$iLinhasDev  = $oDaoFarDev->numrows;

if ($iLinhasDev == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado ! ");
} 

$oPdf = new scpdf();
$oPdf->Open();
$oPdf1 = new db_impcarne($oPdf,886);
$oPdf1->objpdf->SetTextColor(0,0,0);
$oPdf1->Snumero_ant = "";

$sCamposDev2 = "fa22_d_data, fa22_i_cgsund ,fa06_f_quant, fa23_i_quantidade ,fa23_c_motivo, fa22_i_codigo, fa23_i_cancelamento,fa01_i_codigo,m60_descr";
$sSqlDevMed  = $oDaoFarDevMed->sql_query_devolve("",$sCamposDev2,"","$sWhereDev");
$rsItens     = $oDaoFarDevMed->sql_record($sSqlDevMed);
$iLinhasDev  = 1;

for ($iContador = 0; $iContador < $iLinhasDev; $iContador++) {

  $oDaoDevo             = db_utils::fieldsmemory($rsDevolv,$iContador);  
  $oPdf1->logo	        = $oDaoPref->logo; 
  $oPdf1->prefeitura    = $oDaoPref->nomeinst;
  $oPdf1->enderpref     = $oDaoPref->ender;
  $oPdf1->municpref     = $oDaoPref->munic;
  $oPdf1->telefpref     = $oDaoPref->telef;
  $oPdf1->emailpref     = $oDaoPref->email;
  $oPdf1->emissao       = date("Y-m-d",db_getsession("DB_datausu"));
  $oPdf1->cgcpref       = $oDaoPref->cgc;
  $oPdf1->Rnumero       = $oDaoDevo->fa22_i_codigo;
  $oPdf1->Rcoddepart    = $oDepart->coddepto;
  $oPdf1->Rdata         = $dDataRelatorio;
  $oPdf1->ratendente    = $oDaoDevo->fa22_i_login;
  $oPdf1->rcodatend     = $oDaoDevo->nome;
  $oPdf1->Rhora         = $dHoraRelatorio;
  $oPdf1->Rnomeus       = $oDaoDevo->fa22_i_cgsund." ".$oDaoDevo->z01_v_nome;
  $oPdf1->emissao       = date("Y-m-d",db_getsession("DB_datausu"));
  $oPdf1->Rdepart       = $oDepart->descrdepto;
  $oPdf1->recorddata    = "fa22_d_data";
  $oPdf1->rcodmaterial  = "fa01_i_codigo";
  $oPdf1->rdescmaterial = "m60_descr";
  $oPdf1->runidadesaida = "m61_descr";
  $oPdf1->rquantdeitens = "fa06_f_quant";
  $oPdf1->rquantatend   = "fa23_i_quantidade";
  $oPdf1->robsdositens  = "fa23_c_motivo";
  
  $iLinhasItens         = $oDaoFarDevMed->numrows;
  
  $oPdf1->recorddositens = $rsItens;
  $oPdf1->linhasdositens = $iLinhasItens;

  $oPdf1->imprime();
//  die($DaoDevoMed->fa22_i_codigo);
  $oPdf1->Snumero_ant = "fa22_i_codigo";
  
}
if(isset($argv[1])){
  $oPdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $oPdf1->objpdf->Output();
}
?>