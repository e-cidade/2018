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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_iptucalc_classe.php"));
require_once(modification("classes/db_iptunump_classe.php"));
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_massamat_classe.php"));
require_once(modification("classes/db_iptuender_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_db_docparag_classe.php"));
require_once(modification("classes/db_arrematric_classe.php"));
require_once(modification("classes/db_listadoc_classe.php"));
require_once(modification("classes/db_db_layouttxtgeracao_classe.php"));
require_once(modification("classes/db_cadconvenio_classe.php"));
require_once(modification("model/convenio.model.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/recibo.model.php"));

$cliptucalc      	     = new cl_iptucalc;
$cliptuender     	     = new cl_iptuender;
$cliptunump      	     = new cl_iptunump;
$clmassamat     	     = new cl_massamat;
$cldb_config   		     = new cl_db_config;
$cldb_docparag   	     = new cl_db_docparag;
$clarrematric    	     = new cl_arrematric;
$cllistadoc     	     = new cl_listadoc;
$cldb_layouttxtgeracao = new cl_db_layouttxtgeracao;
$clcadconvenio 		     = new cl_cadconvenio;
$sqlerro               = false;

/*

evandro como tu me falou q nem tem como executar esse prog,
tu tem q dar uma conferida se estao mesmo sendo usadas todas essas classe dos includes 
acima pois eu nao sei...

aproveitei para colocar a barra de progresso no teu prog

*/

?> 

  <html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="100%" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td width="100%" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="100%"  align="center">
    <?
      db_criatermometro('termometro','Concluido...','blue',1); 
    ?>    
    </td>
  </tr>
</table>
<form name='form1'>
</body> 
 
<?


    $modelo = 2;
    $result = db_query("begin") or die("erro begin");
    $sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
    db_fieldsmemory(db_query($sqlinst),0,true);
    $sqlvenc = "select current_date + '30 days'::interval as db_datausu";
    $resultvenc = db_query($sqlvenc) or die($sqlvenc);
    db_fieldsmemory($resultvenc, 0);
    
    $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
    
    $sqltipo = "select q92_tipo, k00_descr 
    from cfiptu 
    inner join cadvencdesc on q92_codigo = j18_vencim
    inner join arretipo on q92_tipo = k00_tipo
    where j18_anousu = $anousu";
    $resulttipo = db_query($sqltipo) or die($sqltipo);
    db_fieldsmemory($resulttipo, 0);
    
    $tipos			= $q92_tipo;
    $descrtipo		= $k00_descr;
    $somentedivida	= false;
    $somenteparc	= false;
    $somenteiptu	= true;
    
    $xtipo    = 'Matrícula';
    $xcodigo  = 'k22_matric';
    $xcodigo1 = 'j01_matric';
    $xxcodigo1 = 'k55_matric';
    $xcampos = ' substr(fc_proprietario_nome,1,7) as z01_numcgm, substr(fc_proprietario_nome,8,40) as z01_nome ';
    $xxmatric = ' inner join notimatric on k22_matric = k55_matric ';
    $xxmatric2 = '';
    $xxcodigo = 'k55_notifica';
    
    if($ordem == 'a'){
      $xxordem = ' order by z01_nome ';
      $xxxordem = ' order by substr(fc_proprietario_nome,8,40)';
    } else {
      $xxordem = ' order by '.$xxcodigo1;
      $xxxordem = ' order by '.$xxcodigo1;
    }
    
    $sqlprinc = "select j23_matric,
					    j23_vlrter,
					    j23_aliq,
					    j86_iptucadzonaentrega,
					    z01_nome,
					    j01_idbql,
					    substr(fc_iptuender,001,40) as j23_ender,
					    substr(fc_iptuender,042,10) as j23_numero,
					    substr(fc_iptuender,053,20) as j23_compl,
					    substr(fc_iptuender,074,40) as j23_bairro,
					    substr(fc_iptuender,115,40) as j23_munic,
					    substr(fc_iptuender,156,02) as j23_uf,
					    substr(fc_iptuender,159,08) as j23_cep,
					    substr(fc_iptuender,168,20) as j23_cxpostal 
				   from ( select j23_matric,
							     j23_vlrter,
							     j23_aliq,
							     j86_iptucadzonaentrega,
							     z01_nome,
							     j01_idbql,
							     fc_iptuender(j23_matric)
    						from iptucalc 
							     inner join iptubase 	on iptubase.j01_matric = iptucalc.j23_matric 
							     inner join lote 		on lote.j34_idbql	   = iptubase.j01_idbql 
							     inner join cgm 		on cgm.z01_numcgm      = iptubase.j01_numcgm 
							     left  join iptumatzonaentrega 	on iptumatzonaentrega.j86_matric = iptubase.j01_matric
    					   where iptucalc.j23_anousu = $anousu"
    					. ($quantidade != ""?" limit $quantidade":"") . ") as x
    		 order by " . ($ordem == "endereco"?"j23_ender, j23_numero, j23_compl":($ordem == "alfabetica"?"z01_nome":"j86_iptucadzonaentrega"));
    					
    $resultlistadeb = $cliptucalc->sql_record($sqlprinc);
    if ($resultlistadeb == false || $cliptucalc->numrows == 0) {
      $erro = true;
      $descricao_erro = "Não existe cálculo efetuado!";
    }
    
    $quantescolhida = $quantidade;
    
    $anos=$anousu;
    
    $nomearq 		= "tmp/emissaogeraliptu_" . $anousu . "_" . str_replace("-","",date("Y-m-d",db_getsession("DB_datausu"))) . "_" . str_replace(":","",db_hora()) . ".txt";
	$db55_layouttxt = 13;
	$cldb_layouttxr = new db_layouttxt($db55_layouttxt,$nomearq, "A B C D E F G K L S T U V W X Y Z");
    $lista 			= $anousu;
    $db55_obs   	= "LISTA: $lista\n";
    
    db_sel_instit(null,"nomeinst, munic, uf, ender, cgc, cep, bairro");
    
    $desconto = 0;
    $maxvenc	= "2006-12-31"; ////
    
    $resultgeracao=$cldb_layouttxtgeracao->sql_record($cldb_layouttxtgeracao->sql_query_file(null,"max(db55_seqlayout) as db55_seqlayout",null," db55_layouttxt = $db55_layouttxt group by db55_layouttxt"));
    if ($cldb_layouttxtgeracao->numrows == 0) {
      $db55_seqlayout = 0;
    } else {
      db_fieldsmemory($resultgeracao, 0);
    }
    
    $db55_seqlayout++;
		
	$numeroremessa				= $db55_seqlayout;
	$datageracao				= date("Y-m-d",db_getsession("DB_datausu"));;
	$numeroconvenio				= 760880;
	$tipoformulario				= 2;
	$indicadorpostagem			= "S";
	$cnpjconvenente				= $cgc;
	$nomeconvenente				= $nomeinst;
	$enderecoconvenente			= $ender;
	$cepconvenente				= $cep;
	$cidadeconvenente			= $munic;
	$bairroconvenente			= $bairro;
	$ufconvenente				= $uf;
	$segmentofebraban			= $segmento;
	$identfebraban				= $numbanco;
	$moedaconvenio				= 6;
	$casasdecimais				= 2;
	$autorizarecaposvcto		= "S";
		
		$denominacaocontrib		= "CONTRIBUINTE";
		$siglacontrib			= "MATRICULA";
		$identificacaoobj		=	"MATRICULA";
		$significadosigla		= "MATRICULA=MATRICULA DO CADASTRO IMOBILIARIO";
		$tipoidentificador		= "N"; // numerico
		$tamanhoidentificador   = 17;
		$mascaraidentificador	= str_repeat("9",$tamanhoidentificador);
		$denominacaotransm		= ""; // nao utilizado
		$nomerecebimento		= "IMPOSTO PREDIAL E TERRITORIAL URBANO";
		$siglarecebimento		= "IPTU";

		$resultparag = $cldb_docparag->sql_record($cldb_docparag->sql_query_doc(null, null, "db02_texto", null, " db03_tipodoc = 1017"));
		$unidadeconvenente = "";
		if ($cldb_docparag->numrows > 0) {
			db_fieldsmemory($resultparag, 0);
			$unidadeconvenente = $db02_texto;
		}

      
		$sqlparc = "	select distinct k00_numpar 
						  from iptunump
							   inner join arrecad on j20_numpre = k00_numpre
						 where j20_anousu = $anousu";
						 
		$resultparc = db_query($sqlparc) or die($sqlparc);

		
		$denominacaoexercicio = $anos;
		$exercicio		  	  = $anos;
		$totalopcoespagamento = pg_numrows($resultparc);
		$parcelamentoreceb	  = pg_numrows($resultparc);
		$valorminimo		  = 0;
		$juros				  = 0;
		$quantidadedatas	  = 0;
		$numerodiasentredatas = 0;
		$titulocomposicao	  = "COMPOSICAO ATE HOJE";
		$formacalculo		  = "";
		$identificacaobarra	  = "P";
		$denominacaoidentif	  = "";
		$zerarvalorbarra	  = "N";
		$prazoexpurgo		  = 0;
		$checardatabarras	  = "S";
		$checarvalorbarras	  = "S";
 	    $tiporemessa 		  = 1; 
		
		
		$sqltipo = "select j18_vencim as k00_tipo
					  from cfiptu
					  	   inner join cadvencdesc on q92_codigo = j18_vencim
					 where j18_anousu = $anousu";
		$resulttipo = db_query($sqltipo) or die($sqltipo);
		db_fieldsmemory($resulttipo, 0);
    
    
    
    global $quantidadegeral, $sequencialregistro;
    $quantidadegeral		= 1;
    $sequencialregistro	=	1;
    
    global $contador;
    $contador = 0;
    
    $identificacao = "0000";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "A");
    $sequencialregistro++;
    
    $identificacao = "0000";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "B");
    $sequencialregistro++;
    
    $identificacao = "0000";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "C");
    $sequencialregistro++;
    
    $identificacao = "0000";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "D");
    $sequencialregistro++;
    
    $identificacao = "0000";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "E");
    $sequencialregistro++;
    
    // segmento F
    $denominacaoobj				= "IPTU";
    $siglaidentobj				= "IPTU";
    $significadoidentobj	= "IMPOSTO PREDIAL E TERRITORIAL URBANO";
    
    $identificacao = "0000";
    $tipoident				= "N";
    $tamanhoidentobj	=	17;
    $mascaraidentobj 	= str_repeat("9",$tamanhoidentobj);
    $titulocaractobj	= "EXERCICIOS ENVOLVIDOS";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "F");
    $sequencialregistro++;
    
    $identificacao				= "0001";
    $numerocaractobj			= 1;
    $denominacaocaractobj = "INICIAL";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "G");
    $sequencialregistro++;
    
    $identificacao				= "0002";
    $numerocaractobj			= 2;
    $denominacaocaractobj = "FINAL";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "G");
    $sequencialregistro++;
    
    // primeira composicao
    $identificacao				= "0001";
    $numeroreceita				=	1;
    $codigoreceita				= 1;
    $descricaoreceita			= "VALOR CORRIGIDO";
    $valorpercentualaliq	= 0;
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "K");
    $sequencialregistro++;
    
    // segunda composicao
    $identificacao				= "0002";
    $numeroreceita				=	2;
    $codigoreceita				= 1;
    $descricaoreceita			= "ENCARGOS";
    $valorpercentualaliq	= 0;
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,1, "K");
    $sequencialregistro++;
    
    db_preparageratxt($lista, $k00_tipo);

    
    $processados = 1;
    $numrowslistadeb = pg_numrows($resultlistadeb);
    for ($x=0; $x < $numrowslistadeb; $x++) {
    	
      db_fieldsmemory($resultlistadeb, $x);
      db_atutermometro($x,$numrowslistadeb,'termometro');
      
      $sqlfin    = "select * from iptunump where j20_anousu = $anousu and j20_matric = $j23_matric";
      $resultfin = db_query($sqlfin) or die($sqlfin);

  	  if (pg_numrows($resultfin) == 0) {
		continue;
	  }
      db_fieldsmemory($resultfin, 0);

	  $sql			  = "select * from arrecad where k00_numpre = $j20_numpre";
	  $resulttipoparc = db_query($sql) or die($sql);
	  
	  if (pg_numrows($resulttipoparc) == 0){
		continue;
	  }
      
      $k22_exerc_ini = $anousu;
      $k22_exerc_fim = $anousu;
      
      $k22_vlrcor   = 0;
 	  $k22_encargos = 0;
			
      global $registrooriginal;
      
      $registrooriginal = "VALOR CORRIGIDO: " . trim(db_formatar($k22_vlrcor,'f')) . " - ENCARGOS: " . trim(db_formatar($k22_encargos, 'f')) . " - TOTAL GERAL: " . trim(db_formatar($k22_vlrcor + $k22_encargos,'f'));
      
      $identificacaoguia    = $processados;
      $emissaoguia		    = date("Y-m-d",db_getsession("DB_datausu"));
      $validadeguia		   	= $maxvenc;
      $totalopcoespag		= pg_numrows($resulttipoparc);
      $parcelasrecebimento	=	pg_numrows($resulttipoparc);
      $valortotalreceb		= $k22_vlrcor + $k22_encargos;
      $indicadorendcorresp	= "C";
      $codatividadecontrib	= "";
      $descratividcontrib	= "";
      
      $resultarrematric=$clarrematric->sql_record($clarrematric->sql_query(null, $j20_matric, "iptubase.*, cgm.*"));
      db_fieldsmemory($resultarrematric, 0);
      $cnpjcpf = $z01_cgccpf;
      if (strlen($z01_cgccpf) == 11) {
        $tipopessoatransmit = 1;
      } elseif (strlen($z01_cgccpf) == 14) {
        $tipopessoatransmit = 2;
      } else {
        $tipopessoatransmit = 0;
        $cnpjcpf = "";
      }
      
      $identtransmreceita		= $z01_cgccpf;
      $nometransmitente			= "";
      $nomecartorio					= "";
      $identificacao				= "0000";
      db_setaPropriedadesLayoutTxt($cldb_layouttxr,3, "S");
      $sequencialregistro++;
      
      $nomecontrib					= $z01_nome;
      
      $identificacaocontrib	= $j20_matric;
      
      $tipopessoa							= $tipopessoatransmit;
      $identcontribreceita		= $cnpjcpf;
      $enderecocontrib				= trim($z01_ender) . (trim($z01_numero) != ""?", " . $z01_numero:"") . (trim($z01_compl) != ""?"/".$z01_compl:"");
      $cepcontrib							= $z01_cep;
      $cidadecontrib					= $z01_munic;
      $bairrocontrib					= trim($z01_munic) . (strlen(trim($z01_bairro)) == 0?"":"/B: " . trim($z01_bairro));
      $ufcontrib							= $z01_uf;
      $codativcontrib					= 0;
      $cod2ativcontrib				= 0;
      $cod3ativcontrib				= 0;
      $identificacao					= "0000";
      db_setaPropriedadesLayoutTxt($cldb_layouttxr,3, "T");
      $sequencialregistro++;
      
      // segmento U //
      $identificacao					= "0000";
      
      $valor1rec							= $k22_vlrcor;
      $aliq1rec								= 0;
      
      $valor2rec							= $k22_encargos;
      $aliq2rec								= 0;
      
      db_setaPropriedadesLayoutTxt($cldb_layouttxr,3, "U");
      $sequencialregistro++;
      
      db_setaPropriedadesLayoutTxt($cldb_layouttxr,3, "V");
      $sequencialregistro++;
      
      $identificacao					= "0001";
      $conteudo1caract				= $k22_exerc_ini;
      $conteudo2caract				= $k22_exerc_fim;
      db_setaPropriedadesLayoutTxt($cldb_layouttxr,3, "W");
      $sequencialregistro++;
      
      $mensagem1guia = "";
      $mensagem2guia = "";
      $mensagem3guia = "";

	  $k64_docum = 1; //// ver
      
      $resultparag = $cldb_docparag->sql_record($cldb_docparag->sql_query($k64_docum));
      $identificacao = 1;
      for ($parag=0; $parag < $cldb_docparag->numrows; $parag++) {
        db_fieldsmemory($resultparag, $parag);
        
        if ($db02_descr == "MENSAGEM1") {
          db_separainstrucao($db02_texto, 0, $cldb_layouttxr, 3, "X", 4, 0);
          $sequencialregistro++;
          $identificacao++;
        } elseif ($db02_descr == "MENSAGEM2") {
          db_separainstrucao($db02_texto, 0, $cldb_layouttxr, 3, "X", 4, 0);
          $sequencialregistro++;
          $identificacao++;
        } elseif ($db02_descr == "MENSAGEM3") {
          db_separainstrucao($db02_texto, 0, $cldb_layouttxr, 3, "X", 4, 0);
          $sequencialregistro++;
          $identificacao++;
        }
        
      }

      for ($contador2=0; $contador2 < pg_numrows($resulttipoparc); $contador2++) {
        db_fieldsmemory($resulttipoparc, $contador2);

      	try {
		      $oRegraEmissao = new regraEmissao($k00_tipo,11,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
		    } catch (Exception $eExeption){
		      db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
		      exit;      	
		    }

		    db_inicio_transacao();
		    
		    try {       
          $oRecibo = new recibo(2, null, 20);        
          $oRecibo->addNumpre($k00_numpre,$k00_numpar);
          $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
          $oRecibo->setDataRecibo($k00_dtvenc);
          $oRecibo->setDataVencimentoRecibo(db_vencimento()); 
          $oRecibo->emiteRecibo();
          $k03_numpre_calcula = $oRecibo->getNumpreRecibo();
        } catch ( Exception $eException ) {
          db_fim_transacao(true);
        	db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
          exit;
        }    
        
        db_fim_transacao();
        
        $sql = "select sum(k00_valor) as k00_valor from recibopaga where k00_numnov = $k03_numpre_calcula";
        $resultrecibo = db_query($sql) or die($sql);
        db_fieldsmemory($resultrecibo, 0);
        
        $identificacao				= $processados;
        $tipopag							= "P";
        $numeropag						= $contador2 + 1;
        $vencimentopag				= $k00_dtvenc;
        $valorpag							= $k00_valor;
        
        // codigo de barras
        $db_numpre = $k03_numpre_calcula;
        $db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($valorpag,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
        
        $datavencimento = $k00_dtvenc;
        
        $sqlvalor = "select k00_tercdigrecnormal from arretipo 
										 where k00_tipo = $k00_tipo";
        $resultvalor = db_query($sqlvalor) or die($sqlvalor);
        db_fieldsmemory($resultvalor,0);
        
        if(!isset($k00_tercdigrecnormal) || $k00_tercdigrecnormal == ""){
          db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
        }

        
        try {
		  $oConvenio      = new convenio($oRegraEmissao->getConvenio(),$db_numpre,0,$db_vlrbar,$datavencimento,$k00_tercdigrecnormal);
		  $codigobarras   = $oConvenio->getCodigoBarra();
	 	  $linhadigitavel = $oConvenio->getLinhaDigitavel();
	    } catch (Exception $eExeption){
     	  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  		  exit;
	    }
        
        $matrizbarras		= split(" ", $linhadigitavel);
        $barras			    = $matrizbarras[0] . $matrizbarras[1] . $matrizbarras[2] . $matrizbarras[3];
        
        $parte1codbarraspag = substr($barras, 00, 11);
        $digitoverifparte1	= substr($barras, 11, 01);
        $parte2codbarraspag	= substr($barras, 12, 11);
        $digitoverifparte2	=	substr($barras, 23, 01);
        $parte3codbarraspag	= substr($barras, 24, 11);
        $digitoverifparte3	= substr($barras, 35, 01);
        $parte4codbarraspag	= substr($barras, 36, 11);
        $digitoverifparte4	= substr($barras, 47, 01);
        
        $identificacao = $contador2 + 1;
        db_setaPropriedadesLayoutTxt($cldb_layouttxr,3, "Y");
        $sequencialregistro++;
        
      }
      
      db_preparageratxt($lista, $k00_tipo);
      
      $processados++;
      
      if ((int) $quantidade > 0) {
        
        if ($processados == $quantidade) {
          break;
        }
        
      }
      
    }
    
    $quantidaderegistros = $sequencialregistro;
    $identificacao = "9999";
    db_setaPropriedadesLayoutTxt($cldb_layouttxr,5, "Z");

    
    $gravarconteudo = file($nomearq);
    $gravarconteudo = implode($gravarconteudo);
    
    $cldb_layouttxtgeracao->db55_layouttxt = $db55_layouttxt;
    $cldb_layouttxtgeracao->db55_seqlayout = $db55_seqlayout;
    $cldb_layouttxtgeracao->db55_data	= date("Y-m-d", db_getsession("DB_datausu"));
    $cldb_layouttxtgeracao->db55_hora = db_hora();
    $cldb_layouttxtgeracao->db55_usuario = db_getsession("DB_id_usuario");
    $cldb_layouttxtgeracao->db55_nomearq = $nomearq;
    $cldb_layouttxtgeracao->db55_obs = $db55_obs;
    $cldb_layouttxtgeracao->db55_conteudo = $gravarconteudo;
    $cldb_layouttxtgeracao->incluir(null);
    if ($cldb_layouttxtgeracao->erro_status == "0") {
      $sqlerro=true;
    }
    
    if ($sqlerro == true) {
      die("erro: " . $cldb_layouttxtgeracao->erro_msg);
    } else {
      $result = db_query("commit") or die("erro ao comitar");
    }

    echo "<script>";
    echo "  listagem = '$nomearq#Download do Arquivo - $nomearq';";
    echo "  parent.js_montarlista(listagem,'form1');";
    echo "</script>";

?>
