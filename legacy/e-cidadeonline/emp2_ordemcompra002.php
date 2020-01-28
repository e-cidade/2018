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

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empparametro_classe.php");

$clmatordem = new cl_matordem;
$clmatordemitem  = new cl_matordemitem;
$clempparametro = new cl_empparametro;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$txt_where='1=1';

if(isset($m51_codordem_ini) && $m51_codordem_ini!="" && isset($m51_codordem_fim) && $m51_codordem_fim!=""){
   $txt_where .= " and  m51_codordem between $m51_codordem_ini and  $m51_codordem_fim";
}else if(isset($m51_codordem_ini) && $m51_codordem_ini!=""){
   $txt_where .= " and  m51_codordem>$m51_codordem_ini";
}else  if(isset($m51_codordem_fim) && $m51_codordem_fim!=""){
   $txt_where .= " and  m51_codordem<$m51_codordem_fim";
}else if (isset($cods)&&$cods!=""){
    $txt_where.=" and m51_codordem in ($cods) ";
}

$result = $clmatordem->sql_record($clmatordem->sql_query(null,"*","","$txt_where"));
$num=$clmatordem->numrows;

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'10');
//$pdf1->modelo = 10;
//$pdf1->nvias= 2 ;
$pdf1->objpdf->SetTextColor(0,0,0);

for($i = 0;$i < $num;$i++){
  db_fieldsmemory($result,$i);
		  
	$resultitem = $clmatordemitem->sql_record($clmatordemitem->sql_query_ordcons(null,"distinct m52_codordem,m52_sequen,m52_quant,m52_numemp,m52_vlruni,m52_valor,pcmater.pc01_descrmater,pc01_codmater,e62_descr,empempenho.e60_codemp,empempenho.e60_anousu,e62_vltot,e62_quant,e54_conpag,e54_destin,pc81_codproc,pc11_numero,pc23_obs","","m52_codordem = $m51_codordem"));
	//die($clmatordemitem->sql_query_ordcons(null,"m52_codordem,m52_sequen,m52_quant,m52_numemp,m52_vlruni,m52_valor,pcmater.pc01_descrmater,pc01_codmater,e62_descr,empempenho.e60_codemp,empempenho.e60_anousu,e62_vltot,e62_quant,e54_conpag,e54_destin,pc81_codproc,pc11_numero","","m52_codordem = $m51_codordem"));
        //db_criatabela($resultitem); exit;

	$numrows=$clmatordemitem->numrows;
	$datahj=date("Y-m-d",db_getsession("DB_datausu"));

   $pdf1->prefeitura = $nomeinst;
   $pdf1->enderpref  = $ender;
   $pdf1->municpref  = $munic;
   $pdf1->uf         = $uf;
   $pdf1->telefpref  = $telef;
   $pdf1->emailpref  = $email;
   $pdf1->numordem   = $m51_codordem;
   $pdf1->dataordem  = $m51_data;
   $pdf1->coddepto   = $m51_depto;
   $pdf1->descrdepto = $descrdepto;
   $pdf1->numcgm     = $m51_numcgm;
   $pdf1->nome       = $z01_nome;
   $pdf1->email       = $z01_email;
   $pdf1->cnpj       = $z01_cgccpf;
   $pdf1->cgc        = $cgc;
   $pdf1->url        = $url;
   $pdf1->ender      = $z01_ender;
   $pdf1->munic      = $z01_munic;
   $pdf1->bairro     = $z01_bairro;
   $pdf1->cep        = $z01_cep;
   $pdf1->numero     = $z01_numero;
   $pdf1->compl      = $z01_compl;
   $pdf1->contato    = $z01_telcon;
   $pdf1->telef_cont  = $z01_telef;
   $pdf1->telef_fax   = $z01_fax;
   $pdf1->recorddositens = $resultitem;
   $pdf1->linhasdositens = $numrows;
   $pdf1->emissao = $datahj;
// $pdf1->item	      = 'm52_sequen';
   $pdf1->obs            = $m51_obs;
   $pdf1->empempenho      = 'e60_codemp';
   $pdf1->anousuemp       = 'e60_anousu';
   $pdf1->quantitem      = 'm52_quant';
   $pdf1->condpag        = 'e54_conpag';
   $pdf1->destino        = 'e54_destin';
//   $pdf1->quantitememp   = 'e62_quant';
$anousu=db_getsession("DB_anousu");
$result_numdec=$clempparametro->sql_record($clempparametro->sql_query_file($anousu));
if ($clempparametro->numrows>0){
	db_fieldsmemory($result_numdec,0);
}else{
	$e30_numdec = 4 ;
}  
   $pdf1->numdec         = $e30_numdec;
   $pdf1->valoritem      = 'm52_valor';
   $pdf1->vlrunitem      = 'm52_vlruni';
   $pdf1->descricaoitem  = 'pc01_descrmater';
   $pdf1->codmater       = 'pc01_codmater';
   $pdf1->observacaoitem = 'e62_descr';
   $pdf1->depto          = $m51_depto;
   $pdf1->prazoent       = $m51_prazoent;
   
   $pdf1->Snumeroproc    = "pc81_codproc";
   $pdf1->Snumero        = "pc11_numero";
   $pdf1->obs_ordcom_orcamval = "pc23_obs";

   $pdf1->imprime();
   
}





// if(isset($argv[1])){
//   $pdf1->objpdf->Output("/tmp/teste.pdf");
// }else{
 
 $pdf1->objpdf->Output();
// }




   
?>