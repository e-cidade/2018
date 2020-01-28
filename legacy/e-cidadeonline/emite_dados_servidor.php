<?
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

include("libs/db_utils.php");
require('fpdf151/pdf.php');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet  = db_utils::postmemory($_GET);
$oPost = db_utils::postmemory($_POST);

$sMatric    = $oGet->nummatricula;
$sNome      = $oGet->nome;
$sCgcCpf    = $oGet->numcpf;
$sDataNasc  = $oGet->datansc;
$sEmailServ = $oGet->emailsrv;
$sHeader    = $oGet->header;

          $sql = " select rh01_regist,
                          z01_numcgm,
                          z01_nome,
                          z01_cgccpf,
                          z01_nasc,
                          z01_mae,
                          z01_email,
                          email,
                          senha,
                          login
                     from rhpessoal
                          inner join cgm on rh01_numcgm = z01_numcgm
                          inner join db_usuarios on db_usuarios.nome  =  cgm.z01_nome
                    where rh01_regist = '{$sMatric}'
                      and usuext      = 1
                      and z01_nasc is not null
                      and z01_mae is not null limit 1 ";

  $result = db_query($sql);
  $total  = pg_num_rows($result);

  if($total > 0){
    db_fieldsmemory($result,0);
  }

  if ($total == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para esse(s) filtro(s).');
  }

//****************************************    P D F  Servidor Público ***********************************************//

if(isset($sHeader) && $sHeader == 'E'){
    $sImpHeader = "Esqueci Minha Senha";
} else if(isset($sHeader) && $sHeader == 'P') {
    $sImpHeader = "Pedido de Senha";
}

$head4 = "Informações Servidor Público";
$head5 = $sImpHeader;

$pdf = new PDF();
$pdf->Open();
$pdf->aliasNBpages();
$pdf->setfillcolor(235);
$pdf->Addpage();
$pdf->cell(30,5,''                             ,0,1,"L",0);
$pdf->cell(30,5,''                             ,0,1,"L",0);
$pdf->SetFont('Arial','b',8);
$pdf->cell(30,5,'Nº do CGM:'                   ,0,0,"L",1);
$pdf->cell(0,5,$z01_numcgm                     ,0,1,"L",1);
$pdf->cell(30,5,'Nome:'                        ,0,0,"L",0);
$pdf->cell(0,5,$z01_nome                       ,0,1,"L",0);
$pdf->cell(30,5,'Matricula:'                   ,0,0,"L",1);
$pdf->cell(0,5,$rh01_regist                    ,0,1,"L",1);

$sCgCcPf = trim($z01_cgccpf);
if (strlen($sCgCcPf) <= 11) {
  $sTipo = "cpf";
} else {
  $sTipo = "cnpj";
}

$pdf->cell(30,5,'CPF:'                         ,0,0,"L",0);
$pdf->cell(0,5,formataCpf($sCgCcPf, $sTipo)    ,0,1,"L",0);
$pdf->cell(30,5,'Data Nascimento:'             ,0,0,"L",1);
$pdf->cell(0,5,formataDataNasc($z01_nasc)      ,0,1,"L",1);

 if ($sEmailServ == '') {
   $pdf->cell(0,5,''                           ,0,1,"L",0);
 } else {

   $pdf->cell(30,5,'E-mail Informado:'         ,0,0,"L",0);
   $pdf->cell(0,5,$sEmailServ                  ,0,1,"L",0);
 }

$pdf->cell(0,5,''                              ,0,1,"L",1);
$pdf->cell(30,5,'Login:'                       ,0,0,"L",0);
$pdf->cell(0,5,$z01_numcgm                     ,0,1,"L",0);
$pdf->Output();

//****************************************   	FIM PDF Servidor Público   ********************************************//

function formataDataNasc($sData){

	$data         = str_replace("-", "", $sData);
	$datanasc_dia = substr($data, -2,2);
	$datanasc_mes = substr($data, -4,2);
	$datanasc_ano = substr($data, -8,4);
	$datanasc     = $datanasc_dia."/".$datanasc_mes."/".$datanasc_ano;

	return $datanasc;
}

function formataCpf($sString, $sTipo) {

	switch ($sTipo) {

	  case "cpf" :
	    $sRetorno = substr($sString, 0, 3).".".substr($sString, 3, 3).".".substr($sString, 6, 3)."-".substr($sString, 9, 2);
	    break;

	  case "cnpj" :
	    $sRetorno = substr($sString, 0, 2).".".substr($sString, 2, 3).".".substr($sString, 5, 3)."/".substr($sString, 8, 4)."-".substr($sString, 12, 2);
	    break;
	}

  return $sRetorno;
}
?>
