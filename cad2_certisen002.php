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

include ("fpdf151/pdf3.php");
include ("fpdf151/impcarne.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptuisen_classe.php");
include("classes/db_isenproc_classe.php");
include("classes/db_cfiptu_classe.php");

//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);

$cliptuisen = new cl_iptuisen;
$clcfiptu   = new cl_cfiptu;

global $dia;
global $mes;
global $ano;

$dia = date("d",db_getsession("DB_datausu"));
$mes = db_mes(date("m",db_getsession("DB_datausu")),2);
$ano = date("Y",db_getsession("DB_datausu"));

//Busca nome do usuário
$sqlnome = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
$resnome = pg_query($sqlnome);
db_fieldsmemory($resnome,0);
	
	// Busca cabec_sec -- CABEÇALHO NOME SECRETARIA 
  	$sqlparag = "select db02_texto						
		from db_documento 
		inner join db_docparag on db03_docum = db04_docum
		inner join db_tipodoc on db08_codigo  = db03_tipodoc
		inner join db_paragrafo on db04_idparag = db02_idparag 
		where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
  	$resparag = pg_query($sqlparag);
	global $head1;
  	if ( pg_numrows($resparag) == 0 ) {
    	$head1 = 'SECRETARIA DE FINANÇAS';
  	}else{
    	db_fieldsmemory( $resparag, 0 );
     	$head1 = $db02_texto;
  	}
  	
	$pdf = new pdf3();
	$pdf->Open();
	$pdf1= new db_impcarne($pdf, '29');
	$pdf1->objpdf->AddPage();
	$pdf1->objpdf->SetTextColor(0, 0, 0);
		
	$sqlMunic = " select nomeinst,logo from db_config where codigo = ". db_getsession("DB_instit");
	$rsMunic = pg_query($sqlMunic);
	$numrowsmunic = pg_numrows($rsMunic);
    if ($numrowsmunic == 0){
     db_redireciona('db_erros.php?fechar=true&db_erro=Nome da instituição não encontrado!');
     exit; 
	}
	db_fieldsmemory($rsMunic,0);
	$pdf1->isenprefeitura = $nomeinst;
	$pdf1->isenlogo       = $logo;
	$sqlparag = "select *
		from db_documento 
		inner join db_docparag on db03_docum = db04_docum
		inner join db_tipodoc on db08_codigo  = db03_tipodoc
		inner join db_paragrafo on db04_idparag = db02_idparag 
		where db03_tipodoc = 1019 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
    $resparag = pg_query($sqlparag);
    $numrows = pg_numrows($resparag);
    if ( $numrows == 0 ) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento da certidão de isenção!');
     exit; 
	}

	for($cont=0;$cont<$numrows;$cont++){
	    db_fieldsmemory($resparag,$cont);
	    if($db04_ordem == 1){
		    $pdf1->isenmsg1 = db_geratexto($db02_texto);     		// Cabec CERTIDÃO
	    }
	    if($db04_ordem == 2){
	        $pdf1->isenmsg4 = db_geratexto($db02_texto);			// texto CERTIDÃO
	    }
	    if($db04_ordem == 3){
	    	  $pdf1->isenassinatura2 = db_geratexto($db02_texto);		// Assinatura Inspetor
	    }
	    if($db04_ordem == 4){
	        $pdf1->isenassinatura  = db_geratexto($db02_texto);		// Assinatura Secretario
	    }
	}
 
//    die($cliptuisen->sql_query_isen(null,"proprietario.*,isenproc.*,cgm.z01_nome as nomepromi, cgm.z01_cgccpf as cpfpromi, j45_obscertidao",null," j46_matric = $matric and j46_dtini = '".$dtini."' and j46_dtfim = '".$dtfim."'"));
    $rsIptuisen = $cliptuisen->sql_record($cliptuisen->sql_query_isen(null,"proprietario.*, cgm_propri.z01_cgccpf as cpfpropri, isenproc.*,cgm.z01_nome as nomepromi, cgm.z01_cgccpf as cpfpromi, j45_obscertidao",null," j46_matric = $matric and j46_dtini = '".$dtini."' and j46_dtfim = '".$dtfim."'"));
    $numrowsisen = $cliptuisen->numrows;
    if ($numrowsisen==0){
        db_redireciona('db_erros.php?fechar=true&db_erro=Não foi possível encontrar registros ');
        exit;
    }
	db_fieldsmemory($rsIptuisen,0);
	
    /* parametros do modulo */	
//  die($clcfiptu->sql_query_file(db_getsession('DB_anousu'),"*",null,""));
    $rsCfiptu  = $clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession('DB_anousu'),"*",null,""));
    $numrows   = $clcfiptu->numrows;
    if ($numrows==0){
        db_redireciona('db_erros.php?fechar=true&db_erro=Configure os parametros do modulo ');
        exit;
    }
	db_fieldsmemory($rsCfiptu,0);
	
	$pdf1->isenmatric  = $matric;
	
	$pdf1->isennome = $proprietario;  
	$pdf1->isencgc  = $cpfpropri;
	
  if(isset($j18_dadoscertisen) && $j18_dadoscertisen == 0){	
		$pdf1->isennome = $proprietario;
		$pdf1->isencgc  = $cpfpropri;
		
	}else if(isset($j18_dadoscertisen) && $j18_dadoscertisen == 1){
	    if(isset($nomepromi) && $nomepromi != ""){
			$pdf1->isennome = $nomepromi;
			$pdf1->isencgc  = $cpfpromi;
			
		}else{
			$pdf1->isennome = $proprietario;
			$pdf1->isencgc  = $cpfpropri;

		}
	}
    if(isset($j45_obscertidao) && $j45_obscertidao != ""){	
    	$pdf1->isenmsg3 = $j45_obscertidao;
	}else{
    	$pdf1->isenmsg3 = "";
	}
	
	$pdf1->isenender   = (isset($nomepri)&&$nomepri!=""?$nomepri.",".$j39_numero."/".$j39_compl:"Sem endereço cadastrado");
	$pdf1->isenbairro  = $j34_bairro;
	$pdf1->isendtini   = $dtini;
	$pdf1->isendtfim   = $dtfim;
	$pdf1->isenproc    = $j61_codproc;
	$pdf1->isensetor   = $j34_setor;
	$pdf1->isenquadra  = $j34_quadra;
	$pdf1->isenlote    = $j34_lote;

	$pdf1->j05_setorloc  = $j06_setorloc;
	$pdf1->j05_quadraloc = $j06_quadraloc;
	$pdf1->j05_loteloc   = $j06_lote;
	
	$pdf1->imprime();
	$pdf1->objpdf->Output();

?>