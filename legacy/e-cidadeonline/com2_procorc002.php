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
include("classes/db_pcproc_classe.php");
include("classes/db_pcprocitem_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_cgm_classe.php");

$clpcproc  = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clpcdotac   = new cl_pcdotac;
$clpcorcam   = new cl_pcorcam;
$clpcorcamitem   = new cl_pcorcamitem;
$clpcorcamitemproc= new cl_pcorcamitemproc;
$clpcorcamforne  = new cl_pcorcamforne;
$clcgm       = new cl_cgm;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$fornecedores = "";
$vir = "";
if(isset($forne) && $forne != "branco"){
  $arr_forne = split("forn_",$forne);
  for($i=1;$i<sizeof($arr_forne);$i++){
    $fornecedores .= $vir.$arr_forne[$i];
    $vir = ",";
  }
  $fornecedor_cgm = $fornecedores;
  $fornecedores   = " and z01_numcgm in ($fornecedores) ";
}else if(isset($forne) && $forne == "branco"){
  $imprimirbranco = "branco";
}

if (isset($cgm) && $cgm != "" && $fornecedores == "") {
	$fornecedor_cgm = $cgm;
  $fornecedores = " and z01_numcgm = {$fornecedor_cgm}";
}

$branco=false;

$sCampos = "distinct 
            pc20_codorc,
            pc20_dtate,
            pc20_hrate,
            pc20_obs,
            z01_nome,
            z01_numcgm,
            z01_cgccpf,
            z01_ender,
            z01_compl,
            z01_munic,
            z01_uf,
            z01_cep,
            z01_telef,
            z01_fax,
            z01_contato";
$result_pcorcamforne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_fornec(null,
                                                                                     $sCampos,
                                                                                     "",
                                                                                     "pc21_codorc = {$pc20_codorc} {$fornecedores}"));
$numrows_pcorcamforne = $clpcorcamforne->numrows;
if($numrows_pcorcamforne==0){
	if (isset($gera_branco)&&$gera_branco==true){
		$numrows_pcorcamforne = 1;
		$branco=true;
	}else{
  		db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ou orçamento sem fornecedores!");
	}
} 

$sCampos2 = "distinct
             pc11_codigo,
             pc11_quant,
             pc01_descrmater,
             pc11_resum,
             pc11_pgto,
             pc11_prazo,
             pc81_codprocitem,
             pc10_numero,
             pc81_codproc as pc80_codproc,
             m61_usaquant,
             m61_descr,
             pc17_codigo,
             pc17_quant,
             pc01_servico,
             pc23_valor,
             pc23_obs,
             pc23_vlrun,
             pc23_validmin";

$sWhere = "    pc22_codorc={$pc20_codorc} 
           and pc23_orcamforne in ( select pc21_orcamforne 
                                      from pcorcamforne 
                                     where pc21_numcgm in ({$fornecedor_cgm}) )";
                                     
$result_itens = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,
                                                                                       null,
                                                                                       $sCampos2,
                                                                                       "pc81_codprocitem",$sWhere));
$numrows_itens= $clpcorcamitemproc->numrows;
if($numrows_itens==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum item encontrado neste orçamento!");
}


$result_departs = $clpcorcam->sql_record($clpcorcam->sql_query_soldepto($pc20_codorc,"distinct pc10_numero as numdepart,coddepto,descrdepto"));
$numrows_departs = $clpcorcam->numrows;

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'13');
$pdf1->objpdf->SetTextColor(0,0,0);
$numcgm_ant = "";
 
for($i=0;$i<$numrows_pcorcamforne;$i++){
	if (isset($branco)&&$branco==true){
	} else {
		db_fieldsmemory($result_pcorcamforne,$i);
	}
  db_fieldsmemory($result_itens,0);

  $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query($pc80_codproc,"distinct pc80_codproc,descrdepto,pc80_data,pc80_resumo,fonedepto,emaildepto,faxdepto,ramaldepto"));
  if($clpcproc->numrows > 0){
    db_fieldsmemory($result_pcproc,0);
  }

  $pdf1->labdados        = "PROCESSO DE COMPRAS N";
  $pdf1->labtitulo       = "Proc. compras";

  $pdf1->prefeitura      = @$nomeinst;
  $pdf1->enderpref       = @$ender;
  $pdf1->municpref       = @$munic;
  $pdf1->telefpref       = @$telef;
  $pdf1->emailpref       = @$email;
  $pdf1->cgcpref         = @$cgc;
  $pdf1->faxpref         = @$fax;

  $pdf1->orccodigo       = @$pc20_codorc;
  $pdf1->orcdtlim        = db_formatar(@$pc20_dtate,"d");
  $pdf1->orchrlim        = @$pc20_hrate;
  $pdf1->orcobs          = @$pc20_obs;
  
  if(isset($z01_cep) && $z01_cep!=""){
    $ah = substr(@$z01_cep,0,5);
    $dh = substr(@$z01_cep,5,3);
    $z01_cep = $ah.'-'.$dh;
  }


  $pdf1->fonedepto       = @$fonedepto;
  $pdf1->ramaldepto      = @$ramaldepto;
  $pdf1->faxdepto        = @$faxdepto;
  $pdf1->emaildepto      = @$emaildepto;

  if(isset($imprimirbranco)){
  	$numrows_pcorcamforne = 1;
	  $z01_nome    = "";
	  $z01_numcgm  = ""; 
	  $z01_cgccpf  = "";
	  $z01_ender   = "";
	  $z01_compl   = "";
	  $z01_munic   = "";
	  $z01_uf      = "";
	  $z01_fax     = "";
	  $z01_contato = "";
	  $z01_cep     = "";
	  $z01_telef   = "";
  }

  $pdf1->nome            = @$z01_nome;
  $pdf1->numcgm          = @$z01_numcgm; 
  $pdf1->cnpj            = @$z01_cgccpf;
  $pdf1->ender           = @$z01_ender;
  $pdf1->compl           = @$z01_compl;
  $pdf1->munic           = @$z01_munic;
  $pdf1->uf              = @$z01_uf;
  $pdf1->fax             = @$z01_fax;
  $pdf1->contato         = @$z01_contato;
  $pdf1->cep             = @$z01_cep;
  $pdf1->telefone        = @$z01_telef;

  $pdf1->Scoddepto       = "coddepto";
  $pdf1->Sdescrdepto     = "descrdepto";
  $pdf1->Snumdepart      = "numdepart";
  $pdf1->recorddosdepart = @$result_departs;
  $pdf1->linhasdosdepart = @$numrows_departs;

  $pdf1->Snumero         = @$pc80_codproc;
  $pdf1->Sdepart         = @$descrdepto;
  $pdf1->Sdata           = @$pc80_data;  
  $pdf1->Sresumo         = @$pc80_resumo;
  $pdf1->telefpref       = @$telef;
  $pdf1->emailpref       = @$email;
  $pdf1->cgcpref         = @$cgc;
  $pdf1->faxpref         = @$fax;

  $pdf1->recorddositens  = @$result_itens;
  $pdf1->linhasdositens  = @$numrows_itens;
  $pdf1->item	           = 'pc81_codprocitem';
  $pdf1->quantitem       = 'pc11_quant';
  $pdf1->descricaoitem   = 'pc01_descrmater';
  $pdf1->sresum          = 'pc11_resum';
  $pdf1->sprazo          = 'pc11_prazo';
  $pdf1->spgto           = 'pc11_pgto';
  $pdf1->sunidade        = 'm61_descr';
  $pdf1->scodunid        = 'pc17_codigo';
  $pdf1->sservico        = 'pc01_servico';
  $pdf1->squantunid      = 'pc17_quant';
  $pdf1->susaquant       = 'm61_usaquant';
  $pdf1->valor           = 'pc23_valor';
  $pdf1->valorunit       = 'pc23_vlrun' ;
  $pdf1->marca           = 'pc23_obs' ;

  $pdf1->imprime();  
}

if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}

?>