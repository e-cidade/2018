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

include_once ("libs/db_sql.php");
include_once ("dbforms/db_funcoes.php");
include_once ("classes/db_protparam_classe.php");
include_once ("classes/db_certidao_classe.php");
include_once ("classes/db_certidaocgm_classe.php");
include_once ("classes/db_certidaoinscr_classe.php");
include_once ("classes/db_certidaomatric_classe.php");
include_once ("classes/db_numpref_classe.php");
include_once ("classes/db_db_docparag_classe.php");
include_once ("classes/db_db_usuarios_classe.php");
include_once ("libs/db_utils.php");
include_once ("std/db_stdClass.php");
require_once ("std/DBLargeObject.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (isset ($cadrecibo) && $cadrecibo == 't') {
	require_once ('fpdf151/scpdf.php');
} else {
	require_once ('fpdf151/pdf3.php');
}

$clcertidao            = new cl_certidao;
$clcertidaocgm         = new cl_certidaocgm;
$clcertidaoinscr       = new cl_certidaoinscr;
$clcertidaomatric      = new cl_certidaomatric;
$clnumpref             = new cl_numpref;
$cldb_docparag         = new cl_db_docparag;
$cldb_usuarios         = new cl_db_usuarios;
$iInstit      		     = db_getsession('DB_instit');
$iAnoUsu      		     = db_getsession('DB_anousu');

$dadosbaixaempresa     = "";
$dadosalvaraprovisorio = "";
$dadosbaixamatricula   = "";

//**************************************************************************************************************************//

$textarea =  db_stdClass::db_stripTagsJson($textarea);
if (isset ($textarea) && $textarea != "") {
	$historico = $textarea;
} else {
	$textarea = @ $historico;
}
if ($codproc != "") {
	if (strpos($codproc, "/") > 0) {
		$codproc = split("\/", $codproc);
		$exercicio = $codproc[1];
		$codproc = $codproc[0];
	} else {
		$codproc = $codproc;
		$exercicio = $iAnoUsu;
	}
} else {
	$codproc = "";
	$exercicio = 0;
}
$rescodimpresso = $clnumpref->sql_record($clnumpref->sql_query($iAnoUsu, $iInstit, "k03_tipocodcert"));
if ($clnumpref->numrows == 0){
	db_redireciona("db_erros.php?fechar=true&db_erro=Tipo de codifica��o da certid�o n�o configurada nos par�metros.");
	exit;
}
db_fieldsmemory($rescodimpresso, 0);

//******************************************** G R A V A   A   C E R T I D A O **********************************************//	
db_inicio_transacao();
if ($codproc && $codproc != "") {
	$proc = ",conforme processo N".chr(176)." $codproc, ";
}

if ($tipo == 1) {
	$clcertidao->p50_tipo       = "p";
} else if ($tipo == 2) {
	$clcertidao->p50_tipo       = "n";
} else {
	$clcertidao->p50_tipo       = "r";
}
$clcertidao->p50_idusuario    = db_getsession('DB_id_usuario');
$clcertidao->p50_data 			  = date("Y-m-d", db_getsession('DB_datausu'));
$clcertidao->p50_hora 			  = db_hora();
$clcertidao->p50_ip 			    = db_getsession('DB_ip');
if (isset ($historico) && $historico != "") {
	$clcertidao->p50_hist 		  = $historico. ($codproc != '' ? ", processo N".chr(176).": ".$codproc : '');
} else {                      
	$clcertidao->p50_hist 		  = " ". ($codproc != '' ? "Processo N".chr(176).": ".$codproc : '');
}                             
$clcertidao->p50_web 				  = 'false';
$clcertidao->p50_codproc 		  = $codproc;
$clcertidao->p50_exerc 			  = $exercicio;
$clcertidao->p50_codimpresso  = '';
$clcertidao->p50_instit       = $iInstit;
$clcertidao->p50_arquivo      = '0';
$clcertidao->p50_diasvalidade = '0';

/**
 * Adicionamos campo para armazenar o dias de validade da certid�o de acordo com
 * o parametro que estava setado quando emitida
 */
$sSql         = $clnumpref->sql_query_file ( $iAnoUsu, $iInstit, "k03_diasvalidadecertidao" );
$rsResultados = $clnumpref->sql_record( $sSql );
if ( pg_num_rows($rsResultados) > 0 ){
	
  db_fieldsmemory($rsResultados,0);
  
  if( isset($k03_diasvalidadecertidao) ){
  	$clcertidao->p50_diasvalidade = $k03_diasvalidadecertidao;
  }
}

$clcertidao->incluir(null);

if ($clcertidao->erro_status == '0') {
	$erro_msg = $clcertidao->erro_msg."--- Inclus�o Certid�o";
	db_redireciona("db_erros.php?fechar=true&db_erro=$erro_msg");
	db_fim_transacao(true);
}
if (isset ($titulo) && $titulo == 'CGM') {
	$numcgm = $origem;
	$clcertidaocgm->p49_sequencial = $clcertidao->p50_sequencial;
	$clcertidaocgm->p49_numcgm = $numcgm;
	$clcertidaocgm->incluir();
	if ($clcertidaocgm->erro_status == '0') {
		$erro_msg = $clcertidaocgm->erro_msg."--- Inclus�o Certid�o CGM";
		db_redireciona("db_erros.php?fechar=true&db_erro=$erro_msg");
		db_fim_transacao(true);
	}
} else if (isset ($titulo) && $titulo == 'MATRICULA') {
	$matric = $origem;
	$clcertidaomatric->p47_sequencial = $clcertidao->p50_sequencial;
	$clcertidaomatric->p47_matric = $matric;
	$clcertidaomatric->incluir();
	if ($clcertidaomatric->erro_status == '0') {
		$erro_msg = $clcertidaomatric->erro_msg."--- Inclus�o Certid�o Matricula";
		db_redireciona("db_erros.php?fechar=true&db_erro=$erro_msg");
		db_fim_transacao(true);
	}
} else if (isset ($titulo) && $titulo == 'INSCRICAO') {
	$inscr = $origem;
	$clcertidaoinscr->p48_sequencial = $clcertidao->p50_sequencial;
	$clcertidaoinscr->p48_inscr = $inscr;
	$clcertidaoinscr->incluir();
	if ($clcertidaoinscr->erro_status == '0') {
		$erro_msg = $clcertidaoinscr->erro_msg."--- Inclus�o Certid�o Inscri��o";
		db_redireciona("db_erros.php?fechar=true&db_erro=$erro_msg");
		db_fim_transacao(true);
	}
}
if ($k03_tipocodcert != 0) {
	if ($k03_tipocodcert == 5) {
		$codimpresso = $codproc."/".$exercicio;
	} else {
		$iInstit     = $iInstit;
		$iTipoCodigo = $k03_tipocodcert;
		$sTipoCertidao = $clcertidao->p50_tipo;
		$codimpresso = pg_result(db_query("select fc_numerocertidao($iInstit,$iTipoCodigo,'{$sTipoCertidao}', false)"),0);
	} 

	$clcertidaoalt = new cl_certidao;
	$clcertidaoalt->p50_sequencial = $clcertidao->p50_sequencial;
	$clcertidaoalt->p50_codimpresso = $codimpresso;
	$clcertidaoalt->alterar($clcertidao->p50_sequencial);
	if ($clcertidaoalt->erro_status == '0') {
		$erro_msg = $clcertidaoalt->erro_msg."--- Inclus�o do c�digo do processo de impress�o";
		db_redireciona("db_erros.php?fechar=true&db_erro=$erro_msg");
		db_fim_transacao(true);
	}
	// linha incluida para atualizar a classe clcertidao com o codigo a ser impresso pois abaixo o programa trata somente a clcertidao
	$clcertidao->p50_codimpresso = $clcertidaoalt->p50_codimpresso;
}


//**************************************************************************************************************************//
if (isset ($textarea) && $textarea != "") {
	$historico = $textarea;
} else {
	$textarea = @ $historico;
}

//busca o o tipo de certidao se � conjunta ou individualizada.
$resTipoCertidao = $clnumpref->sql_record($clnumpref->sql_query(db_getsession("DB_anousu"),db_getsession('DB_instit'),"k03_tipocertidao"));

if ($clnumpref->numrows > 0){
	db_fieldsmemory($resTipoCertidao, 0);	
}

if($k03_tipocertidao == '3'){ //caso o parametro esteja configurado para mostrar os 2 tipos de certidoes
   $k03_tipocertidao = $tipocertidao;
}


$codtipodoc = 0;
$sql = "select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".@ $GLOBALS["DB_instit"];
$result = db_query($sql);
db_fieldsmemory($result, 0);


/*
 * Monta os Sql's de acordo com o tipo de emiss�o de CDA(Inscri��o, Matricula ou Cgm)
 * 
 * O sql � utilizado para a gera��o das de tipo Negativas, Positivas e Positivas com efeito Negativo.
 *  
 */
if (isset ($inscr)) {
$sSqlInscr  = "select empresa.*,
                      cgm.z01_numcgm as z01_cgmpri, 
                      cgm.z01_nome   as z01_nomecompleto,
                      cgm.z01_ender  as cgmender, 
                      cgm.z01_numero as cgmnumero, 
                      cgm.z01_compl  as cgmcompl, 
                      cgm.z01_bairro as cgmbairro, 
                      cgm.z01_munic  as cgmmunic, 
                      cgm.z01_uf     as cgmuf, 
                      cgm.z01_cep    as cgmcep  
                 from empresa 
                inner join cgm on cgm.z01_numcgm = empresa.q02_numcgm 
                where q02_inscr = $inscr";
} else if (isset ($matric)) {
$sSqlMatric = "select * 
                 from proprietario 
                where j01_matric = $matric";

	db_sel_instit(null, "db21_usasisagua");
	
	if($db21_usasisagua == 't') {
	  $sSqlEndImovel = "select x01_matric      as j01_matric, 
	                           j14_nome, 
	                           x01_numero      as j39_numero, 
	                           x11_complemento as j39_compl, 
	                           j13_descr, 
	                           x01_quadra      as j34_quadra
	                      from aguabase
	                     inner join ruas       on j14_codigo = x01_codrua
	                     inner join bairro     on j13_codi   = x01_codbairro
	                      left join aguaconstr on x11_matric = x01_matric
	                     where x01_matric = $matric";  
	}

} else {                
$sSqlCgm    = "select trim(z01_nome) as z01_nome,
                      * 
                 from cgm 
                where z01_numcgm = $numcgm";
}
if ($tipo == 1) {
	// certidao positiva 
	$tipocer = "CERTID�O POSITIVA DE D�BITO";
	
	if (isset ($matric)) {
		
		$codtipodoc = $k03_tipocertidao == '1' ?  1028 : 2028 ;
		//$codtipodoc = 1028;
		$codtipo = 26;
		$result = db_query($sSqlMatric);
		db_fieldsmemory($result, 0);
		
		if($db21_usasisagua == 't') {
		  $rSqlEndImovel = db_query($sSqlEndImovel);
		  db_fieldsmemory($rSqlEndImovel, 0);
		}
		

	  if (isset ($j01_baixa) && $j01_baixa != "") {
      $situinscr           = "Situa��o da matr�cula : MATR�CULA BAIXADA ";
      $dadosbaixamatricula = "Matricula Baixada em: ".db_formatar($j01_baixa,'d');
    } else {
      $situinscr           = "Situa��o da matr�cula : MATR�CULA ATIVA ";
    }	
				
	} else	if (isset ($numcgm)) {
		
		$codtipodoc = $k03_tipocertidao == '1' ?  1030 : 2030 ;
		//$codtipodoc = 1030;
		$codtipo = 27;
		$result = db_query($sSqlCgm);
		db_fieldsmemory($result, 0);
					
	} else	if (isset ($inscr)) {
		
		$codtipodoc = $k03_tipocertidao == '1' ?  1029 : 2029 ;
		//$codtipodoc = 1029;
		$codtipo = 28;
		$result = db_query($sSqlInscr);
		db_fieldsmemory($result, 0);		
		
		if (isset ($q02_dtbaix) && $q02_dtbaix != "") {
			$situinscr         = "Situa��o do alvar� : ALVAR� BAIXADO ";
      $dadosbaixaempresa = "Alvar� Baixado em: ".db_formatar($q02_dtbaix,'d');
		} else {
			$situinscr         = "Situa��o do alvar� : ALVAR� ATIVO ";
		}				
		
    $sql2 = " select q07_inscr, 
                     q07_perman, 
                     min(q07_datain) as q07_datain, 
                     max(q07_datafi) as q07_datafi 
                from tabativ 
               where q07_inscr = {$inscr} 
                 and q07_perman = false 
            group by q07_inscr, q07_perman";
    $result2 = db_query($sql2);
	  
    if (pg_num_rows($result2) > 0) {
      db_fieldsmemory($result2, 0);
      $dadosalvaraprovisorio = "Alvar� Provis�rio V�lido entre : (".db_formatar($q07_datain,'d')." e ".db_formatar($q07_datafi,'d').")";
    }
    
	}
} else	if ($tipo == 2) {
	// certidao negativa
	$tipocer = "CERTID�O NEGATIVA";
	if (isset ($matric)) {
		
		$codtipodoc = $k03_tipocertidao == '1' ?  1022 : 2022 ;
		//$codtipodoc = 1022;
		$codtipo = 29;
		$result = db_query($sSqlMatric);
		db_fieldsmemory($result, 0);
		
    if($db21_usasisagua == 't') {
      $rSqlEndImovel = db_query($sSqlEndImovel);
      db_fieldsmemory($rSqlEndImovel, 0);
    }
		
		if (isset ($j01_baixa) && $j01_baixa != "") {
			$situinscr           = "Situa��o da matr�cula : MATR�CULA BAIXADA ";
			$dadosbaixamatricula = "Matricula Baixada em: ".db_formatar($j01_baixa,'d');
		} else {
			$situinscr           = "Situa��o da matr�cula : MATR�CULA ATIVA ";
		}
					
	} else	if (isset ($numcgm)) {
		
		$codtipodoc = $k03_tipocertidao == '1' ?  1024 : 2024 ;
		//$codtipodoc = 1024;
		$codtipo = 30;
		$result = db_query($sSqlCgm);
		db_fieldsmemory($result, 0);		
				
	} else if (isset ($inscr)) {
		$codtipodoc = $k03_tipocertidao == '1' ?  1023 : 2023 ;
		//$codtipodoc = 1023;
		$codtipo = 31;
		$result = db_query($sSqlInscr);
		db_fieldsmemory($result, 0);
		
		if (isset ($q02_dtbaix) && $q02_dtbaix != "") {
			$situinscr         = "Situa��o do alvar� : ALVAR� BAIXADO ";
			$dadosbaixaempresa = "Alvar� Baixado em: ".db_formatar($q02_dtbaix,'d');
		} else {
			$situinscr         = "Situa��o do alvar� : ALVAR� ATIVO ";
		}
						
    $sql2 = " select q07_inscr, 
                     q07_perman, 
                     min(q07_datain) as q07_datain, 
                     max(q07_datafi) as q07_datafi 
                from tabativ 
               where q07_inscr = {$inscr} 
                 and q07_perman = false 
            group by q07_inscr, q07_perman ";
    $result2 = db_query($sql2);
    
    if (pg_num_rows($result2) > 0) {
      db_fieldsmemory($result2, 0);
       $dadosalvaraprovisorio = "Alvar� Provis�rio V�lido entre : (".db_formatar($q07_datain,'d')." e ".db_formatar($q07_datafi,'d').")";
    } 	
		
	}
} else {
	// certidao regular
	$tipocer = "CERTID�O POSITIVA COM EFEITO DE NEGATIVA";
	
	if (isset ($matric)) {
		
		$codtipo = 32;
		$codtipodoc = $k03_tipocertidao == '1' ?  1025 : 2025 ;
		//$codtipodoc = 1025;
		$result = db_query($sSqlMatric);
		db_fieldsmemory($result, 0);
		
    if($db21_usasisagua == 't') {
      $rSqlEndImovel = db_query($sSqlEndImovel);
      db_fieldsmemory($rSqlEndImovel, 0);
    }
		
		if (isset ($j01_baixa) && $j01_baixa != "") {
			$situinscr           = "Situa��o da matr�cula : MATR�CULA BAIXADA ";
			$dadosbaixamatricula = "Matricula Baixada em: ".db_formatar($j01_baixa,'d');
		} else {
			$situinscr           = "Situa��o da matr�cula : MATR�CULA ATIVA ";
		}
		
	} else	if (isset ($numcgm)) {
		
		$codtipodoc = $k03_tipocertidao == '1' ?  1027 : 2027 ;
		//$codtipodoc = 1027;
		$codtipo = 33;
		$result = db_query($sSqlCgm);
		db_fieldsmemory($result, 0);	
					
	} else	if (isset ($inscr)) {
		
		$codtipo = 34;
		$codtipodoc = $k03_tipocertidao == '1' ?  1026 : 2026 ;
		//$codtipodoc = 1026;
		$result = db_query($sSqlInscr);
		db_fieldsmemory($result, 0);
		
		if (isset ($q02_dtbaix) && $q02_dtbaix != "") {
			$situinscr         = "Situa��o do alvar� : ALVAR� BAIXADO ";
			$dadosbaixaempresa = "Alvar� Baixado em: ".db_formatar($q02_dtbaix,'d');
		} else {
			$situinscr         = "Situa��o do alvar� : ALVAR� ATIVO ";
		}
		
    $sql2 = " select q07_inscr, 
                     q07_perman, 
                     min(q07_datain) as q07_datain, 
                     max(q07_datafi) as q07_datafi 
                from tabativ 
               where q07_inscr = {$inscr} 
                 and q07_perman = false 
            group by q07_inscr, q07_perman ";
    $result2 = db_query($sql2);
    
	  if (pg_num_rows($result2) > 0) {
      db_fieldsmemory($result2, 0);
      $dadosalvaraprovisorio = "Alvar� Provis�rio V�lido entre : (".db_formatar($q07_datain,'d')." e ".db_formatar($q07_datafi,'d').")";
    } 	
					
	}
}
db_fim_transacao(false);
//****************************************    P D F   ******************************************************// 

$sqlDbconfig = "select * from db_config where codigo = ".db_getsession('DB_instit');
$rsDbconfig = db_query($sqlDbconfig);
db_fieldsmemory($rsDbconfig, 0);

if (isset ($cadrecibo) && $cadrecibo == 't') {
	$pdf = new scpdf(); // abre a classe
} else {
	$pdf = new PDF3(); // abre a classe
}
$sqlparag = "select db02_texto
			   from db_documento
			    	inner join db_docparag on db03_docum = db04_docum
        			inner join db_tipodoc on db08_codigo  = db03_tipodoc
		     		inner join db_paragrafo on db04_idparag = db02_idparag
			 where db03_tipodoc = 1017 and db03_instit = " . $iInstit." order by db04_ordem ";
			 
$resparag = db_query($sqlparag);
if ( pg_numrows($resparag) == 0 ) {
	// $head1 = 'Departamento de Fazenda';
  //agu$head1 = 'SECRETARIA DE FINAN�AS';
}else{
  db_fieldsmemory( $resparag, 0 );
  $head1 = $db02_texto;
}
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetAutoPageBreak('on', 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255);
if (isset ($cadrecibo) && $cadrecibo == 't') {
	$pdf->settopmargin(1);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Image('imagens/files/Brasao.png', 20, 10, 15);	
	$pdf->sety(15);
	$pdf->setfont('Arial', 'B', 18);
	$pdf->Multicell(0, 8, $nomeinst, 0, "C", 0); // prefeitura
}
$y = $pdf->gety();
$pdf->sety($y);
$result = $cldb_docparag->sql_record($cldb_docparag->sql_query("","","db_docparag.*,db02_texto,db02_descr,db02_espaca,db02_alinha,db02_inicia","db04_ordem"," db03_tipodoc = $codtipodoc "));
$numrows = $cldb_docparag->numrows;   
if ($numrows==0){
	db_redireciona("db_erros.php?fechar=true&db_erro=Documento n�o configurado.");
	exit;
}
$logofundo = substr($logo,0,strpos($logo,"."));
/*   F U N D O   D O   D O C U M E N T O  */       

if (file_exists('imagens/files/Brasaocnd.jpg')){
	$pdf->Image('imagens/files/Brasaocnd.jpg',60,80,100);

}else {
  if($db21_imgmarcadagua != '') {
    
    try {
	    $imgMarcaDagua = db_stdClass::geraObjetoOid($db21_imgmarcadagua, $conn, 'marcadagua.jpg');
	    $pdf->Image($imgMarcaDagua,60,80,100);
    } catch (Exception $eErro) {
      db_redireciona("db_erros.php?fechar=true&db_erro={$eErro->getMessage()}");
	  exit;
    }

  }
}
$nome="";
$result_usu=$cldb_usuarios->sql_record($cldb_usuarios->sql_query(db_getsession("DB_id_usuario"),"nome"));
if ($cldb_usuarios->numrows>0){
	db_fieldsmemory($result_usu,0);
}
  $data= date("Y-m-d",db_getsession("DB_datausu")); 	
  $data=split('-',$data);
  $dia=$data[2];
  $mes=$data[1];
  $ano=$data[0];
  $mes=db_mes($mes);
  $data=" $dia de $mes de $ano ";
  $numer = "";
if ($k03_tipocodcert != 0) {
	$numer = " N� $codimpresso ";
}

$pdf->SetFont('Arial','b',15);  
$pdf->cell(0,10,$tipocer.$numer,0,1,"C",0);
$pdf->ln();
$pdf->ln();
for($i=0; $i<$numrows; $i++){
   db_fieldsmemory($result,$i);
   if ($db02_descr=='CODIGO PHP'){
   	   eval($db02_texto);
   }else{
	   $pdf->SetFont('Arial','',12);
	   $pdf->SetX($db02_alinha);   
	   $texto=db_geratexto($db02_texto);
	   $pdf->SetFont('Arial','',12);
	   $pdf->cell(15,6,"",0,0,"R",0);
	   $pdf->MultiCell("0",4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
	   $pdf->cell(0,6,"",0,1,"R",0);
   }
}
$pdf->SetX(@ $x +80);
$y = $pdf->GetY();
$x = $pdf->GetX();
$pdf->SetXY($x +80, $y +10);

//****************************************  	FIM PDF   ******************************************************//

/************************************   R O D A P E (recibo)   D A   C N D  *******************************************************/
if (isset ($cadrecibo) && $cadrecibo == 't') {
	$y = $pdf->w - 20;
} else {
	$y = $pdf->GetY() - 20;
}
//  $mostrarecibo => parametro q define se mostra ou naun mostra o recibo no rodape da cnd...
//	$cadrecibo = 't';
if (isset ($cadrecibo) && $cadrecibo == 't') {
	$dtimp = date("Y-m-d", db_getsession('DB_datausu'));
	$y = $pdf->w - 28;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y +3);
	$pdf->RoundedRect(5, $y +36, 80, 28, '', '1234');
	$pdf->Ln(17);
	$TamLetra = 7;
	$alt = 4;
	$b = 0;
	$rsRecibo = db_query("select * from recibo inner join tabrec on k00_receit = k02_codigo where k00_numpre = $k03_numpre");
	$intNumrows = pg_numrows($rsRecibo);
	if ($intNumrows == 0) {
		db_redireciona('db_erros.php?fechar=true&db_erro=Recibo n�o cadastrado');
	}
	$valortotal = 0;
	for ($ii = 0; $ii < $intNumrows; $ii ++) {
		db_fieldsmemory($rsRecibo, $ii);
		if ($ii == 0) {
			$taxa1 = $k02_drecei;
			$valor1 = $k00_valor;
		}
		if ($ii == 1) {
			$taxa2 = $k02_drecei;
			$valor2 = $k00_valor;
		}
		if ($ii == 2) {
			$taxa3 = $k02_drecei;
			$valor3 = $k00_valor;
		}
		$valortotal += $k00_valor;
	}
	
	//*******************************************************************************************************************//		

	$y = $pdf->GetY();
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y +18);
	$pdf->SetFont('Arial', 'B', $TamLetra -2);
	$pdf->cell(20, 3, "$titulo", $b, 0, "L", 0); //cgm matricula ou inscricao
	$pdf->cell(20, 3, "Dt impr.", $b, 0, "L", 0);
	$pdf->cell(20, 3, "Dt Venc", $b, 0, "L", 0);
	$pdf->cell(20, 3, "", $b, 1, "L", 0);

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->SetFont('Arial', '', $TamLetra);

	$pdf->SetFont('Arial', '', $TamLetra);
	$pdf->cell(20, $alt, "$origem", $b, 0, "L", 0); //cgm matricula ou inscricao
	$pdf->cell(20, $alt, db_formatar($dtimp, "d"), $b, 0, "L", 0);
	$pdf->cell(20, $alt, db_formatar($k00_dtvenc, "d"), $b, 0, "L", 0);

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(20, $alt, "Valor", $b, 0, "C", 0);
	$pdf->SetFont('Arial', 'B', $TamLetra +1);
	$pdf->cell(110, $alt, "DOCUMENTO V�LIDO SOMENTE APOS AUTENTICA��O MECANICA ", $b, 1, "C", 0);

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->SetFont('Arial', '', $TamLetra -1);

	if (isset ($taxa1) && $taxa1 != "") {
		$pdf->cell(60, $alt, "$taxa1", "B", 0, "L", 0);
		$pdf->cell(20, $alt, "$valor1", $b, 0, "C", 0);
		$pdf->SetFont('Arial', 'B', $TamLetra +1);
		$pdf->cell(110, $alt, "OU COMPROVANTE DE QUITA��O", $b, 1, "C", 0);
	} else {
		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
		$pdf->cell(20, $alt, "", $b, 0, "C", 0);
		$pdf->cell(110, $alt, "", $b, 1, "C", 0);
	}

	$pdf->SetFont('Arial', '', $TamLetra -1);

	if (isset ($taxa2) && $taxa2 != "") {
		$pdf->cell(60, $alt, "$taxa2", "B", 0, "L", 0);
		$pdf->cell(20, $alt, "$valor2", $b, 0, "C", 0);
	} else {
		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
		$pdf->cell(20, $alt, "", $b, 0, "C", 0);
	}

	$pdf->SetFont('Arial', 'B', $TamLetra +1);
	$pdf->cell(110, $alt, " A U T E N T I C A � � O   M E C � N I C A ", $b, 1, "C", 0);

	$pdf->SetFont('Arial', '', $TamLetra -1);
	if (isset ($taxa3) && $taxa3 != "") {
		$pdf->cell(60, $alt, "$taxa3", "B", 0, "L", 0);
		$pdf->cell(20, $alt, "$valor3", $b, 1, "C", 0);
	} else {
		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
	}

	$pdf->SetFont('Arial', 'B', $TamLetra -1);
	$pdf->cell(60, $alt, "Valor Total : ", $b, 0, "R", 0);
	$pdf->cell(20, $alt, "$valortotal", $b, 1, "C", 0);

	$y = $pdf->GetY();
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y +10);

	/******************************************************************************************************************************************/

	$pdf->RoundedRect(5, $y +9, 200, 41, 0, '', '1234');

	$pdf->SetFont('Arial', 'B', $TamLetra -2);
	$pdf->cell(110, 3, "", $b, 0, "L", 0);
	$pdf->cell(20, 3, "$titulo", $b, 0, "L", 0); //cgm matricula ou inscricao
	$pdf->cell(20, 3, "Dt impr.", $b, 0, "L", 0);
	$pdf->cell(20, 3, "Dt Venc", $b, 0, "L", 0);
	$pdf->cell(20, 3, "", $b, 1, "L", 0);

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(40, $alt, "CONTRIBUINTE: ", $b, 0, "L", 0);
	$pdf->SetFont('Arial', '', $TamLetra);
	$pdf->cell(70, $alt, @ $z01_nome, $b, 0, "L", 0);

	$pdf->SetFont('Arial', '', $TamLetra);
	$pdf->cell(20, $alt, "$origem", $b, 0, "L", 0); //cgm matricula ou inscricao
	$pdf->cell(20, $alt, db_formatar($dtimp, "d"), $b, 0, "L", 0);
	$pdf->cell(20, $alt, db_formatar($k00_dtvenc, "d"), $b, 0, "L", 0);

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(20, $alt, "Valor", $b, 1, "C", 0);

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(40, $alt, "ENDERE�O: ", $b, 0, "L", 0);
	$pdf->SetFont('Arial', '', $TamLetra);
	$pdf->cell(70, $alt, trim(@ $z01_ender).", ".trim(@ $z01_numero)."  ".trim(@ $z01_compl), $b, 0, "L", 0);

	$pdf->SetFont('Arial', '', $TamLetra -1);
	if (isset ($taxa1) && $taxa1 != "") {
		$pdf->cell(60, $alt, "$taxa1", "B", 0, "L", 0);
		$pdf->cell(20, $alt, "$valor1", $b, 1, "C", 0);
	} else {
		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
	}

	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(40, $alt, "MUNICIPIO:", $b, 0, "L", 0);
	$pdf->SetFont('Arial', '', $TamLetra);
	$pdf->cell(70, $alt, @ $z01_munic."/".@ $z01_uf." - ".substr(@ $z01_cep, 0, 5)."-".substr(@ $z01_cep, $alt, 3), $b, 0, "L", 0);

	$pdf->SetFont('Arial', '', $TamLetra -1);
	if (isset ($taxa2) && $taxa2 != "") {
		$pdf->cell(60, $alt, "$taxa2", "B", 0, "L", 0);
		$pdf->cell(20, $alt, "$valor2", $b, 1, "C", 0);
	} else {
		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
	}

	$pdf->cell(40, $alt, "", $b, 0, "L", 0);
	$pdf->cell(70, $alt, "", $b, 0, "L", 0);

	$pdf->SetFont('Arial', '', $TamLetra -1);
	if (isset ($taxa3) && $taxa3 != "") {
		$pdf->cell(60, $alt, "$taxa3", "B", 0, "L", 0);
		$pdf->cell(20, $alt, "$valor3", $b, 1, "C", 0);
	} else {
		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
	}

	$pdf->cell(40, $alt, "", $b, 0, "L", 0);
	$pdf->cell(70, $alt, "", $b, 0, "L", 0);
	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(60, $alt, "Valor Total : ", $b, 0, "R", 0);
	$pdf->cell(20, $alt, "$valortotal", $b, 1, "C", 0);

	$pdf->SetFont('Arial', '', $TamLetra +1);
	$pdf->cell(110, $alt, "$linhadigitavel", $b, 0, "C", 0);
	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(80, $alt, "", 0, 1, "C", 0);

	$pdf->cell(40, $alt, "", $b, 0, "L", 0);
	$pdf->cell(70, $alt, "", $b, 0, "L", 0);
	$pdf->SetFont('Arial', 'B', $TamLetra);
	$pdf->cell(80, $alt, " A U T E N T I C A � � O   M E C � N I C A  ", 0, 1, "C", 0);

	$y = $pdf->GetY();
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);

	$pdf->SetFillColor(000);
	$pdf->int25($x, $y -4, $codigobarras, 13, 0.341);
}

$sArquivoCertidao = $pdf->GeraArquivoTemp();
$pdf->Output($sArquivoCertidao);

/**
 * Grava arquivo pdf da certidao no banco
 */
db_inicio_transacao();
$iOid     			= DBLargeObject::criaOID( true );
$lSalvaArquivo  = DBLargeObject::escrita( $sArquivoCertidao, $iOid );
$lErro				  = false;

/**
 * Grava arquivo da certidao tamb�m na tabela certidao
 */
$clcertidao->p50_arquivo = $iOid;
$clcertidao->alterar( $clcertidao->p50_sequencial );

if ($clcertidao->erro_status == 0 && $lSalvaArquivo ) {
	
	db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao salvar Arquivo da Certid�o.');
	$lErro		 = true;
}
db_fim_transacao($lErro);

// Exclui arquivo temporario
if ( file_exists( $sArquivoCertidao ) ) {
	unlink( $sArquivoCertidao );
}