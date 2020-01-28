<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_solicitemunid_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_pcparam_classe.php");
include("libs/db_utils.php");

$clsolicita      = new cl_solicita;
$clsolicitem     = new cl_solicitem;
$clsolicitemunid = new cl_solicitemunid;
$clpcdotac       = new cl_pcdotac;
$clpcorcam       = new cl_pcorcam;
$clpcorcamitem   = new cl_pcorcamitem;
$clpcorcamitemsol= new cl_pcorcamitemsol;
$clpcorcamforne  = new cl_pcorcamforne;
$clcgm           = new cl_cgm;
$clpcparam       = new cl_pcparam;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

$rsParam           = $clpcparam->sql_record($clpcparam->sql_query(null,"*",null,"pc30_instit = ".db_getsession("DB_instit")));
$oParam            = db_utils::fieldsMemory($rsParam,0);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$fornecedores = "";
$vir = "";
if(isset($forne) && $forne != "branco"){
  $arr_forne = split("forn_",$forne);
  for($i=1;$i<sizeof($arr_forne);$i++){
    $fornecedores .= $vir.$arr_forne[$i];
    $vir = ",";
  }
  $fornecedores = " and z01_numcgm in ($fornecedores) ";
}else if(isset($forne) && $forne == "branco"){
  $gera_branco    = true;
  $imprimirbranco = "branco";
}

if (isset($cgm) && $cgm != "" && $fornecedores == "") {
  $fornecedores = " and z01_numcgm = {$cgm}";
}

$branco = false;

$result_pcorcamforne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null,"pc20_codorc,pc20_dtate,pc20_hrate,pc20_obs,pc20_prazoentrega, pc20_validadeorcamento,z01_nome,z01_numcgm,z01_cgccpf,z01_ender,z01_compl,z01_munic,z01_uf,z01_cep,z01_telef,z01_fax,z01_contato","z01_numcgm","pc21_codorc=$pc20_codorc $fornecedores"));
$numrows_pcorcamforne = $clpcorcamforne->numrows;


if($numrows_pcorcamforne==0){
	if (isset($gera_branco)&&$gera_branco==true){
    $result_pcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file(null,"distinct pc20_dtate,pc20_hrate,pc20_obs,pc20_prazoentrega, pc20_validadeorcamento","","pc20_codorc=$pc20_codorc"));
    if ($clpcorcam->numrows > 0){
      db_fieldsmemory($result_pcorcam,0);
    }

		$numrows_pcorcamforne = 1;
		$branco=true;
  } else {
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ou or�amento sem fornecedores!");
  }
} 
$result_itens = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_pcmater(null,null,"pc11_codigo,pc11_quant,pc01_descrmater,pc11_resum,pc11_pgto,pc11_prazo,pc11_seq,pc10_numero,m61_usaquant,m61_descr,pc17_codigo,pc17_quant,pc01_servico,pc01_validademinima","pc11_seq","pc22_codorc=$pc20_codorc"));
$numrows_itens= $clpcorcamitemsol->numrows;
if($numrows_itens==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum item encontrado neste or�amento!");
}



$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,$oParam->pc30_modeloorcsol);
//$pdf1 = new db_impcarne($pdf,'13');
//$pdf1->modelo = 13;
$pdf1->objpdf->SetTextColor(0,0,0);
$numcgm_ant = "";

$sqlparag = "select db02_texto
	     from db_documento
	   	  inner join db_docparag on db03_docum = db04_docum
       		  inner join db_tipodoc on db08_codigo  = db03_tipodoc
	     	  inner join db_paragrafo on db04_idparag = db02_idparag
	     where db03_tipodoc = 1202 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
			 
$resparag = @pg_query($sqlparag);

if (@pg_numrows($resparag) > 0){
     db_fieldsmemory($resparag,0);
     $pdf1->declaracao = $db02_texto;
} else {
     $pdf1->declaracao = "";
}

for($i=0;$i<$numrows_pcorcamforne;$i++){
  if (isset($branco) && $branco == true){
  } else {
    db_fieldsmemory($result_pcorcamforne,$i);
  }

  db_fieldsmemory($result_itens,0);
 
  $result_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita($pc10_numero," pc10_numero,pc10_data,pc10_resumo,pc12_vlrap,descrdepto,coddepto,fonedepto,ramaldepto,emaildepto,faxdepto,nomeresponsavel,pc50_descr"));


  if($clsolicita->numrows > 0){
    db_fieldsmemory($result_solicita,0);
  }

/*
  die($clsolicitemunid->sql_query(null,"pc17_codigo,m61_descr","","ere pc17_codigo in (".$clpcorcamitemsol->sql_query_pcmater(null,null,"pc11_codigo","","pc22_codorc=$pc20_codorc").") "));
  $result_solicitemunid = $clsolicitemunid->sql_record($clsolicitemunid->sql_query(null,"pc17_codigo,m61_descr","","pc17_codigo in (".$clpcorcamitemsol->sql_query_pcmater(null,null,"pc11_codigo","","pc22_codorc=$pc20_codorc").") "));
*/

  $pdf1->labdados   = "SOLICITA��O DE COMPRAS N";
  $pdf1->labtitulo  = "Solicita��o";
  $pdf1->labtipo    = "Tipo";

  $pdf1->logo				= $logo; 
	$pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = trim($ender).",".$numero;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->cgcpref    = $cgc;
  $pdf1->faxpref    = $fax;
  $pdf1->coddepto   = $coddepto;
  $pdf1->orccodigo  = $pc20_codorc;
  $pdf1->orcdtlim   = db_formatar($pc20_dtate,"d");
  $pdf1->orchrlim   = $pc20_hrate;
  $pdf1->orcobs     = $pc20_obs;
  $pdf1->orcprazo    = $pc20_prazoentrega." dias";
  $pdf1->orcvalidade = $pc20_validadeorcamento." dias";


  if(isset($z01_cep) && $z01_cep!=""){
    $ah = substr($z01_cep,0,5);
    $dh = substr($z01_cep,5,3);
    $z01_cep = $ah.'-'.$dh;
  }

  if(isset($imprimirbranco)){
  	$numrows_pcorcamforne = 1;
	  $z01_nome = "";
	  $z01_numcgm = ""; 
	  $z01_cgccpf = "";
	  $z01_ender = "";
	  $z01_compl = "";
	  $z01_munic = "";
	  $z01_uf = "";
	  $z01_fax = "";
	  $z01_contato = "";
	  $z01_cep = "";
	  $z01_telef = "";
  }

  $pdf1->nome       = $z01_nome;
  $pdf1->numcgm     = $z01_numcgm; 
  $pdf1->cnpj       = $z01_cgccpf;
  $pdf1->ender      = $z01_ender;
  $pdf1->compl      = $z01_compl;
  $pdf1->munic      = $z01_munic;
  $pdf1->uf         = $z01_uf;
  $pdf1->fax        = $z01_fax;
  $pdf1->contato    = $z01_contato;
  $pdf1->cep        = $z01_cep;
  $pdf1->telefone   = $z01_telef;

  $pdf1->Snumero= $pc10_numero;
  $pdf1->Sdepart= $descrdepto;
  $pdf1->fonedepto = $fonedepto;
  $pdf1->ramaldepto = $ramaldepto;
  $pdf1->faxdepto = $faxdepto;
  $pdf1->emaildepto = $emaildepto;
  $pdf1->Sdata  = $pc10_data;  
  
  $pdf1->Svalor = $pc12_vlrap;
  $pdf1->Stipcom= $pc50_descr; 
  $pdf1->Sresumo= $pc10_resumo;
  $pdf1->recorddositens = $result_itens;
  $pdf1->linhasdositens = $numrows_itens;
  $pdf1->item	        = 'pc11_seq';
  $pdf1->quantitem      = 'pc11_quant';
  $pdf1->descricaoitem  = 'pc01_descrmater';
  $pdf1->sresum         = 'pc11_resum';
  $pdf1->sprazo         = 'pc11_prazo';
  $pdf1->spgto          = 'pc11_pgto';
  $pdf1->sunidade       = 'm61_descr';
  $pdf1->scodunid       = 'pc17_codigo';
  $pdf1->sservico       = 'pc01_servico';
  $pdf1->squantunid     = 'pc17_quant';
  $pdf1->susaquant      = 'm61_usaquant';
  $pdf1->Dvalidademin   = 'pc01_validademinima';
  

  $pdf1->imprime();
}
if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}
?>