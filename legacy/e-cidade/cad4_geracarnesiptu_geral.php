<?php
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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS );

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldb_config = new cl_db_config;
$clmodcarne  = new cl_modcarne;
$clmassamat  = new cl_massamat;
$cliptubase  = new cl_iptubase;
$cldb_bancos = new cl_db_bancos;

$aParcelasSemInflatores = array();

$histinf 	    = "";
$unica 		    = 2;
$auxiliar     = "";
$quanti       = 0;
$intixxx      = 0 ;
$histinf      = '';
$iIniPag      = 1 ;
$iFimPag      = 0 ;
$msgvencida   = '';
$nomearquivos = '';
$QuebraPag    = 500;
$nomeTipoMod  = "Carnes";
$impmodelo    = 1;

$wherecidbranco = " where 1 = 1 ";
$debugar        = false;

$sqlTipo  = " select q92_tipo  as tipo_debito 					  ";
$sqlTipo .= "   from cadvencdesc 								  ";
$sqlTipo .= " 	     inner join cfiptu on j18_vencim = q92_codigo ";
$sqlTipo .= "  where j18_anousu = {$anousu} 					  ";

// se gera pdf com as parcelas do parcelado ou apenas as unicas
$gera_parcelado=1;

$rsTipo     = db_query($sqlTipo);
$linhasTipo = pg_num_rows($rsTipo);

if($linhasTipo>0){
	db_fieldsmemory($rsTipo,0);
}

?>
<html>
  <head>

        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js">
        </script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
  <table align="center" width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td >
    <?
      echo "<br><br>";
      db_criatermometro('termometro','Concluido...','blue',1);
	    db_atutermometro(1,100,'termometro');
    ?>
	</td>
  </tr>
  </table>
</body>
</html>
<?

if($debugar ==true){
  echo "Parametros recebido : <br>
    qtd ..............= $quantidade <br>
    ano ..............= $anousu <br>
    ordem.............= $ordem <br>
    tipo .............= $tipo <br>
    txtNumpreUnicaSelecionados = $txtNumpreUnicaSelecionados <br>
    especie...........= $especie <br>
    imobiliaria.......= $imobiliaria   <br>
    loteamento........= $loteamento    <br>
    ordem.............= $ordem         <br>
    cidadebranco......= $cidadebranco  <br>
    entregavalido.....= $entregavalido <br>
    proc massa falida = $proc          <br>
    barra unioca......= $barrasunica   <br>
    barra parc........= $barrasparc    <br>
    filtro princ......= $filtroprinc   <br>
    parc obrigat .....= $parcobrig     <br>
    intervalo ........= $intervalo     <br>
    ";
}

//
// carregar todas as matriculas do calculo do ano de emissao
// select * from iptunump where j20_anousu = <anousu>;
//
$H_ANOUSU = $anousu;
$H_DATAUSU = db_getsession("DB_datausu");
$DB_DATACALC = db_getsession("DB_datausu");
$forcarvencimento = false;
$db_datausu = date("Y-m-d", db_getsession("DB_datausu"));

// #####################   F I L T R O  ###############################
if(!isset($vlrminunica) || $vlrminunica == ""){
  $vlrminunica = 0;
}

if(!isset($vlrmaxunica) || $vlrmaxunica == ""){
  $vlrmaxunica = 999999999;
}
if(!isset($vlrmin) || $vlrmin == ""){
  $vlrmin = 0;
}

if(!isset($vlrmax) || $vlrmax == ""){
  $vlrmax = 999999999;
}
$intervalorvlrminimo = false;

$whereimobil = "";
if ($imobiliaria == "com") {
  $whereimobil = " and j44_matric is not null ";
} elseif ($imobiliaria == "sem") {
  $whereimobil = " and j44_matric is null ";
}

$whereloteam = "";
if ($loteamento == "com") {
  $whereloteam = " and loteloteam.j34_idbql is not null ";
} elseif ($loteamento == "sem") {
  $whereloteam = " and loteloteam.j34_idbql is null ";
}

if($cidadebranco!="on"){
  $wherecidbranco .= " and trim(substr(fc_iptuender,115,40)) <> ''
                       and (    trim(substr(fc_iptuender,168,20)) <> ''
                            and trim(substr(fc_iptuender,168,20)) <> '0' ) ";
}

if($entregavalido =="on"){
  $wherecidbranco .= "and (trim(substr(fc_iptuender,001,40)) <> '' or (trim(substr(fc_iptuender,168,20)) <> '' and trim(substr(fc_iptuender,168,20)) <> '0' )) ";
}

/**
 * Valida Matriculas
 */
$sWhereMatriculas = '';
if(!empty($listamatrics)){
  $sWhereMatriculas = " and iptucalc.j23_matric in ($listamatrics)";
}

/**
 * Valida Setores
 */
$sWhereSetor = '';
if(!empty($j34_setor)){
  $sWhereSetor = " and lote.j34_setor = '{$j34_setor}' ";
}

$sOrder = null;
switch ($ordem) {
  case "endereco":
    $sOrder = "j23_munic, j23_uf, j23_ender, j23_numero, j23_compl";
  break;
  case "bairroender":
    $sOrder = "j23_bairro, j23_ender, j23_numero, j23_compl";
  break;
  case "alfabetica":
    $sOrder = "z01_nome";
  break;
  case "zonaentrega":
    $sOrder = "j86_iptucadzonaentrega";
  break;
  case "setorquadralote":
    $sOrder = "j34_setor, j34_quadra, j34_lote";
  break;
  case "refant":
    $sOrder = "j40_refant";
  break;
  case "bairroalfa":
    $sOrder = "j23_bairro, z01_nome";
  break;
  default :
  $sOrder = "z01_nome";
  break;
}

if(isset($quantidade) and $quantidade!=0 and $quantidade!=""){
  $quantidade = " limit $quantidade";
}else{
  $quantidade = "";
}
$sqlprinc  = "select distinct ";
$sqlprinc .= "       j23_matric, ";
$sqlprinc .= "       j23_vlrter, ";
$sqlprinc .= "       j23_aliq, ";
$sqlprinc .= "       j86_iptucadzonaentrega, ";
$sqlprinc .= "       z01_nome as nome_contri, ";
$sqlprinc .= "       j20_numpre,";
$sqlprinc .= "       j01_idbql,";
$sqlprinc .= "       j23_arealo,";
$sqlprinc .= "       j23_m2terr,";
$sqlprinc .= "       j40_refant,";
$sqlprinc .= "       j34_setor,";
$sqlprinc .= "       j34_quadra,";
$sqlprinc .= "       j34_lote,";
$sqlprinc .= "       substr(fc_iptuender,001,40) as j23_ender, ";
$sqlprinc .= "       substr(fc_iptuender,042,10) as j23_numero, ";
$sqlprinc .= "       substr(fc_iptuender,053,20) as j23_compl, ";
$sqlprinc .= "       substr(fc_iptuender,074,40) as j23_bairro, ";
$sqlprinc .= "       substr(fc_iptuender,115,40) as j23_munic, ";
$sqlprinc .= "       substr(fc_iptuender,156,02) as j23_uf, ";
$sqlprinc .= "       substr(fc_iptuender,159,08) as j23_cep, ";
$sqlprinc .= "       substr(fc_iptuender,168,20) as j23_cxpostal ";
$sqlprinc .= " from ( ";
$sqlprinc .= "       select j23_matric, ";
$sqlprinc .= "              j23_vlrter, ";
$sqlprinc .= "              j23_aliq, ";
$sqlprinc .= "              j20_numpre, ";
$sqlprinc .= "              j86_iptucadzonaentrega, ";
$sqlprinc .= "              z01_nome, ";
$sqlprinc .= "              j01_idbql, ";
$sqlprinc .= "              j23_m2terr,";
$sqlprinc .= "              j23_arealo, ";
$sqlprinc .= "              j40_refant,";
$sqlprinc .= "              j34_setor,";
$sqlprinc .= "              j34_quadra,";
$sqlprinc .= "              j34_lote,";
$sqlprinc .= "              fc_iptuender(j23_matric) ";
$sqlprinc .= "         from iptucalc  ";
$sqlprinc .= "              inner join iptubase 		  on iptubase.j01_matric = iptucalc.j23_matric                                    ";
$sqlprinc .= "              inner join iptunump 		  on iptunump.j20_matric = iptubase.j01_matric  and iptunump.j20_anousu = $anousu ";
$sqlprinc .= "              inner join lote 		      on lote.j34_idbql = iptubase.j01_idbql                                          ";
$sqlprinc .= "              inner join cgm 			      on cgm.z01_numcgm = iptubase.j01_numcgm                                         ";
$sqlprinc .= "              left  join iptumatzonaentrega on iptumatzonaentrega.j86_matric = iptubase.j01_matric                      ";
$sqlprinc .= "              left  join imobil 	          on imobil.j44_matric = iptubase.j01_matric                                  ";
$sqlprinc .= "              left  join loteloteam 	      on loteloteam.j34_idbql = lote.j34_idbql                                    ";
$sqlprinc .= "              left  join iptuant     	      on iptuant.j40_matric   = iptubase.j01_matric                               ";
$sqlprinc .= "        where iptucalc.j23_anousu = $anousu                                                                             ";
$sqlprinc .= "        $whereimobil {$whereloteam} {$sWhereMatriculas} {$sWhereSetor} ) as x                                           ";
$sqlprinc .= " $wherecidbranco";
$sqlprinc .= " order by {$sOrder}";
$sqlprinc .= "    $quantidade ";
$sqlIptunump = $sqlprinc;

$rsIptunump  = db_query($sqlIptunump) or die($sql);

if($debugar ==true){
  echo "<br>SQL1 - Principal:<br> $sqlIptunump <br>";
}

$linhasIptunump = pg_num_rows($rsIptunump);

try {
  $oRegraEmissao = new regraEmissao($tipo_debito,4,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'));
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

$pdf1 = $oRegraEmissao->getObjPdf();

if($capa== "s"){
  $pdfcapa = new db_impcarne($oRegraEmissao->getSpdf(), 56);
}

if($linhasIptunump >0 ){

  $sqlTipo    = "select arretipo.k00_tipo,arretipo.k00_descr,arretipo.k03_tipo from iptunump inner join arrecad on k00_numpre = j20_numpre inner join arretipo on arrecad.k00_tipo =arretipo.k00_tipo where j20_anousu = $anousu limit 1";
  $rsTipo     = db_query($sqlTipo);
  $linhasTipo = pg_num_rows($rsTipo);
  if($linhasTipo >0){
    db_fieldsmemory($rsTipo ,0);
    $tipo_debito = $k00_tipo;
  }

  $sqltipo="select k00_descr,k00_codbco,k00_codage,k00_txban,k00_rectx,
    k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,
    k00_hist6,k00_hist7,k00_hist8
      from arretipo
      where k00_tipo = $tipo_debito ";
  $resulttipo = db_query($sqltipo);
  db_fieldsmemory($resulttipo, 0);
  if($debugar ==true){
    echo "<br>SQL5 - TIPO <br>$sqltipo <br>";
  }


  $sqlparag  = " select db02_texto ";
  $sqlparag .= "   from db_documento ";
  $sqlparag .= "        inner join db_docparag  on db03_docum   = db04_docum ";
  $sqlparag .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
  $sqlparag .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sqlparag .= " where db03_tipodoc = 1017 ";
  $sqlparag .= "   and db03_instit = ".db_getsession("DB_instit")." ";
  $sqlparag .= " order by db04_ordem ";
  $resparag = db_query($sqlparag);
  if($debugar ==true){
    echo "<br>SQL3 - Paragrafos <br> $sqlparag <br>";
  }
  // $pdf1 = db_criacarne($tipo_debito, db_getsession('DB_ip'), date("Y-m-d", db_getsession("DB_datausu")), db_getsession('DB_instit'), 1);

  if($debugar ==true){
    echo "<br> FOR DA MATRICULAS <BR>";
  }

  // ########################### F O R   P E R C O R R E   A S   M A T R I C U L A S  ######################
  for ($m =0;$m<$linhasIptunump;$m++) {
    db_fieldsmemory($rsIptunump,$m);
	db_atutermometro($m,$linhasIptunump,'termometro');

    if($debugar ==true){
      echo "<br> dentro fo for principal <br> endereco = $j23_ender <br>j23_matric =$j23_matric <br> j23_munic=$j23_munic";
    }

    // VERIFICA SE NUMERO DE MATRICULA JA é O SUFICIENTE PARA FECHAR O BLOCO
	if($debugar==true){
		echo "<br><br>intixxx = $intixxx
		          <br>iFimPag = $iFimPag
				  <br>iIniPag = $iIniPag
				  <br>";
	}
    if ($intixxx >= $QuebraPag) {
      if($debugar ==true){
        echo "<br> quebrou o bloco <br>";
      }
      $iFimPag += $intixxx;
      $arquivo       = "tmp/".$nomeTipoMod."_".str_replace(" ","",$k00_descr)."_de_".$iIniPag."_ate_".$iFimPag."_".date('His').".pdf";
      $nomearquivos .= $arquivo."#Dowload dos ".$nomeTipoMod." de ".$iIniPag." ate ".$iFimPag."|";

      $pdf1->objpdf->Output($arquivo, false, true);
      unset($pdf1->objpdf);
      unset($pdf1);
      unset($objpdf);
      unset($pdfcapa);
      unset($oRegraEmissao);

      try{
      $oRegraEmissao = new regraEmissao($tipo_debito,4,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'));
  		$pdf1		   = $oRegraEmissao->getObjPdf();
	  } catch (Exception $eExeption){
  		db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  		exit;
	  }

	  if($capa== "s"){
  		$pdfcapa = new db_impcarne($oRegraEmissao->getSpdf(), 56);
	  }

      $iIniPag=$iFimPag+1;
      $intixxx =0;
	  $quanti  = 0;
    }
	if($debugar==true){
		echo "<br><br>intixxx = $intixxx
		          <br>iFimPag = $iFimPag
				  <br>iIniPag = $iIniPag
				  <br>";
	}
    $imprimecapa = 0;

    // Select no arrecad preparando a string para que o programa funcione para baixo

    $sqlArrecad  = "   select k00_numpre,  				  ";
    $sqlArrecad .= "    	  k00_numpar,  				  ";
    $sqlArrecad .= " 	      k00_numcgm,  				  ";
    $sqlArrecad .= " 	      k00_dtoper,  				  ";
    $sqlArrecad .= " 	      k00_dtvenc,  				  ";
    $sqlArrecad .= " 	      k00_tipo as tipo_debito ,   ";
    $sqlArrecad .= " 	      sum(k00_valor) as k00_valor ";
    $sqlArrecad .= "     from arrecad   				  ";
    $sqlArrecad .= "    where k00_numpre = $j20_numpre    ";
    $sqlArrecad .= " group by k00_numpre,  				  ";
    $sqlArrecad .= "  	      k00_numpar, 				  ";
    $sqlArrecad .= " 	      k00_numcgm,  				  ";
    $sqlArrecad .= "  		  k00_dtoper, 				  ";
    $sqlArrecad .= "  		  k00_dtvenc,  				  ";
    $sqlArrecad .= "  		  k00_tipo   				  ";
    $sqlArrecad .= " order by k00_numpre,  				  ";
    $sqlArrecad .= "  	      k00_numpar  				  ";

    $rsArrecad     = db_query($sqlArrecad);
    $linhasArrecad = pg_num_rows($rsArrecad);

    if ($linhasArrecad > 0){
      db_fieldsmemory($rsArrecad,0);
    }

    if($debugar ==true){
      echo "<br>################ novo numpre... matricula #####################
	    <br> numpre  = $k00_numpre mat  = $j23_matric
        <br>SQL2 -sql Arrecad <br> $sqlArrecad <br>";
    }



    if (empty($proc)) {
      $clmassamat->sql_record($clmassamat->sql_query_file(null, $j23_matric));
      if ($clmassamat->numrows > 0) {
        continue;
      }
    }

    $intervalorvlrminimo = false;

    if($k00_valor < $vlrmin or $k00_valor > $vlrmax) { // se valor total do iptu for menor que valor minimo ou maior que o valor maximo
      continue;
    }

    if($k00_valor >= $vlrminunica and $k00_valor <= $vlrmaxunica) {
      $intervalorvlrminimo = true;
    }

    $gerarparcelado = true;

    if ($intervalo == "gerar") {

      if ($intervalorvlrminimo == true) {
        $gerarparcelado = true;
      } else {
        $gerarparcelado = false;
      }

    } elseif ($intervalo == "naogerar") {

      if ($intervalorvlrminimo == true) {
        $gerarparcelado = false;
      } else {
        $gerarparcelado = true;
      }

    }

    if($gerarparcelado==false){
      continue;
    }

    $resultmat = $cliptubase->proprietario_record($cliptubase->proprietario_query($j23_matric));
    if (pg_numrows($resultmat) == 0) {
      continue;
    }
    db_fieldsmemory($resultmat, 0);
    if ($especie == "predial" and $j01_tipoimp == "Territorial") {
      continue;
    } elseif ($especie == "territorial" and $j01_tipoimp == "Predial") {
      continue;
    }

    $passar = true;

    if (isset($parcobrig) and $parcobrig != "") {

      $sqlparcobrig = "select *
        from arrematric m
        inner join arrecad a on m.k00_numpre = a.k00_numpre
        where m.k00_numpre = $j20_numpre and
        k00_numpar = $parcobrig
        limit 1";
      $resultfinparcobrig = db_query($sqlparcobrig) or die($sqlparcobrig);

      if (pg_numrows($resultfinparcobrig) == 0) {
        $passar = false;
      }

    }

    if ($filtroprinc == "compgto") {

      $sqlfinpripaga = "select	distinct a.k00_numpar
        from arrematric m
        inner join arrecad a on m.k00_numpre = a.k00_numpre
        where 	m.k00_numpre = $j20_numpre and
        a.k00_dtvenc < '" . date("Y-m-d", db_getsession("DB_datausu")) . "'";
      $resultfinpripaga = db_query($sqlfinpripaga) or die($sqlfinpripaga);

      if (pg_numrows($resultfinpripaga) > 0) {
        $passar = false;
      }

    } elseif ($filtroprinc == "sempgto") {

      $sqlfinpripaga = "select	*
        from arrematric m
        inner join arrepaga a on m.k00_numpre = a.k00_numpre
        inner join arrecant t on m.k00_numpre = t.k00_numpre
        where m.k00_numpre = $j20_numpre
        limit 1";
      $resultfinpripaga = db_query($sqlfinpripaga) or die($sqlfinpripaga);

      if (pg_numrows($resultfinpripaga) == 1) {
        $passar = false;
      }

    }

    if ($passar == false) {
      continue;
    }

    $whereDatasUnicas = "";

    if ($txtNumpreUnicaSelecionados != ""){
      $datasUnicas = "'".implode("','", explode(',',$txtNumpreUnicaSelecionados) )."'";
      $unica = 1;
    }else{
      $datasUnicas = "";
    }

    $sqlpref = "select logo,db12_extenso,db12_uf,nomeinst as prefeitura, munic, to_char(tx_banc,'99.99') as tx_banc
	            from db_config
				inner join cgm on cgm.z01_numcgm = db_config.numcgm
				inner join db_uf on db12_uf=uf
				where db_config.codigo = ".db_getsession("DB_instit");
    $resul =db_query($sqlpref);
	$linhas = pg_num_rows($resul);
    //$resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"), "nomeinst as prefeitura, munic, to_char(tx_banc,'99.99') as tx_banc"));

    if($linhas>0){
      db_fieldsmemory($resul, 0); // pega o dados da prefa
    }else{
    	db_msgbox("kk Contate Suporte. A configuração do sistema não esta completa. $sqlpref");
        echo "<script>  parent.db_iframe_carne.hide(); </script> ";
        exit;
    }
    $pdf2->uf_config     = $db12_uf;
    $munic2     = $munic;
    $nomeinst2  = $prefeitura;
    $taxabancaria = $tx_banc;
    $msgvencida = "";
    $bql        = "";
    $obsdiver   = "";



    ////////////////////////////////////////////////////////////////////////////////
    ////////  C O M E Ç O   D A  G E R A Ç Â O  D O S   C A R N E S   //////////////
    ////////////////////////////////////////////////////////////////////////////////

    /********************* R O T I N A   P A R A   B U S C A R   O   M O D E L O   D E   C A R N E *****************************************************/

    $result = db_query("select * from db_config where codigo = ".db_getsession('DB_instit'));
    db_fieldsmemory($result, 0);

    /***************************************************************************************************************************************************/
    // FUNCAO Q RETORNA O PDF ESTANCIADO JA COM O MODELO CERTO TESTANDO AS RESTRIÇÕES

    $pdf1->uf_config  = $db12_uf;
    $pdf1->prefeitura = $nomeinst;

    // coloquei esse sql la em cima fora do for
    if (pg_numrows($resparag) == 0) {
      $pdf1->secretaria = 'SECRETARIA DE FINANÇAS';
    } else {
      db_fieldsmemory($resparag, 0);
      $pdf1->secretaria = $db02_texto;
    }

    $pdf1->tipodebito = $k00_descr;
    $pdf1->pretipodebito = $k00_descr;
    $pdf1->logo = $logo;

    db_query("BEGIN");
    db_postmemory($HTTP_POST_VARS);

    //
    // Aqui a string com o numpre e as parcelas tem que estar pronta
    //
    if($linhasArrecad >0){
      // monta o array  vt
      $NP = "";
      $valores= "";
      $n = "";
      for($ind=0;$ind<$linhasArrecad;$ind++){
        db_fieldsmemory($rsArrecad,$ind);
        $NP.=$n.$k00_numpre."P".$k00_numpar;
        $n = "N";
      }
      $vt['CHECK0']    = $NP;
    }

    $tam = sizeof($vt);
    reset($vt);
    $numpres = "";

    for ($i = 0; $i < $tam; $i ++) {
      if (db_indexOf(key($vt), "CHECK") > 0) {
        $registros = split("N",$vt[key($vt)]);
        for ($reg=0; $reg < sizeof($registros); $reg++) {
          if ($registros[$reg] == "") {
            continue;
          }
          $registro = split("R", $registros[$reg]);
          if (gettype(strpos($numpres, "N".$registro[0])) == "boolean") {
            $numpres .= "N".$registro[0];
          }
        }
      }
      next($vt);
    }
    $sounica = $numpres;

    $numpres = split("N", $numpres);
    if($debugar ==true){
      echo "<br>NUMPRES<br>
        <pre>";
      print_r($numpres);
      echo "</pre> <br>";
    }


    if (isset ($geracarne) && $geracarne == 'banco') {
      $pagabanco = 't';
    } else {
      $pagabanco = 't';
    }

     $ultimoNumpreProcessado = 0;

    /**************************   F O R   Q   M O N T A   O S   C A R N E S  ************************************/
    $aParcelasSemInflatores = array();
    if($debugar ==true){
      echo "<br> UNICA = $unica ( SE FOR 1 IMPRIME A UNICA... SE FOR 2 IMPRIME AS PARCELAS)";
    }
    if ($unica != "1"){
      if($debugar ==true){
        echo "<br> FOR NOS NUMPRES PRA MONTAR O CARNE <BR>";
      }
      for ($volta = 0; $volta < sizeof($numpres); $volta ++) {

        if ( $numpres[$volta] == "" || $numpres[$volta] == "0" ) {
          continue;
        }

        $aNumpre = split('P',$numpres[$volta]);

        $k00_numpre = $aNumpre[0];
        $k00_numpar = $aNumpre[1];

        if($debugar ==true){
        echo "<br>  n=$k00_numpre p=$k00_numpar <br>";
          echo "<br> debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento)";
        }

        $rsDadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento);

        db_fieldsmemory($rsDadosPagamento, 0);

        if ( isset($total) && $total < 0 ) {
          array_push($aParcelasSemInflatores,$k00_numpar);
        }

      }

    }

    if (count($aParcelasSemInflatores) > 0 ) {
      $sS = ( count($aParcelasSemInflatores)>1?'s':'' );
	  db_msgbox("Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
      echo "<script>  parent.db_iframe_carne.hide(); </script> ";
      exit;
    }


    for ($volta = 1; $volta < sizeof($numpres); $volta ++) {

      if ($numpres[$volta] == "") {
        continue;
      }

      $k00_numpre = substr($numpres[$volta], 0, strpos($numpres[$volta], 'P'));

      $proprietario = '';
      $xender 		= '';
      $xbairro 		= '';

      /***********************************************************************************************************************/

      $sqlorigem  = " select arrecad.k00_numpre, 												 ";
      $sqlorigem .= "        arrenumcgm.k00_numcgm as z01_numcgm, 								 ";
      $sqlorigem .= "        case 																 ";
      $sqlorigem .= "           when arrematric.k00_matric is not null  						 ";
      $sqlorigem .= "             then arrematric.k00_matric 									 ";
      $sqlorigem .= "           when arreinscr.k00_inscr is not null 							 ";
      $sqlorigem .= "             then arreinscr.k00_inscr 										 ";
      $sqlorigem .= "           else 															 ";
      $sqlorigem .= "             arrenumcgm.k00_numcgm 										 ";
      $sqlorigem .= "        end as origem, 													 ";
      $sqlorigem .= "        case 																 ";
      $sqlorigem .= "          when arrematric.k00_matric is not null   						 ";
      $sqlorigem .= "            then 'Matrícula' 												 ";
      $sqlorigem .= "          when arreinscr.k00_inscr is not null 							 ";
      $sqlorigem .= "            then 'Inscrição' 											 	 ";
      $sqlorigem .= "        else 																 ";
      $sqlorigem .= "         'CGM' 															 ";
      $sqlorigem .= "        end as descr 														 ";
      $sqlorigem .= "   from arrecad 															 ";
      $sqlorigem .= "        inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
      $sqlorigem .= "        left  join arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
      $sqlorigem .= "        left  join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre ";
      $sqlorigem .= " where arrecad.k00_numpre = $k00_numpre									 ";

      $rsOrigem   = db_query($sqlorigem) or die($sqlorigem);

      if($debugar ==true){
        echo "<br>SQL6 - ORIGEM <br>$sqlorigem <br>";
      }

      if (pg_numrows($rsOrigem) > 0) {
        db_fieldsmemory($rsOrigem, 0);
      } else {
        db_msgbox("Nao encontrou registros do numpre: $k00_numpre!");
      }

      if (!empty ($descr) && $descr == 'Matrícula') {
        if($debugar==true){
          echo "<br> IF MATRICULA <br>";
        }
        $Identificacao = db_query("select * from proprietario where j01_matric = $origem limit 1");

        if(pg_numrows($Identificacao)==0) {

		  db_msgbox("Problemas no Cadastro da Matricula  $origem");
          echo "<script>  parent.db_iframe_carne.hide(); </script> ";
          exit;
        }

        db_fieldsmemory($Identificacao, 0);
        $proprietario       = $nome_contri;
        $pdf1->bairropri    = $j13_descr;
        $pdf1->prebairropri = $z01_bairro;
        $pdf1->nomepriimo   = $nomepri;

        // trocado porque bage pediu
        if($oRegraEmissao->isCobranca()){
          $xender = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl);
        }else{
          $xender = $nomepri.', '.$j39_numero.'  '.$j39_compl;
        }
        $bql = '  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
        if (isset ($impmodelo) && $impmodelo == 30) {
          if ($k00_tipo != 6) {
            $numero = $j01_matric;
          } else {
            //$descr = "";
            $numero = "";
            $numero = $j01_matric;
          }
        } else {

          /**
           * Comentado SQL para não duplicar na guia do contribuinte
           */
          if ($k00_tipo != 6) {
            $numero = $j01_matric; //.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
          } else {

            $numero = "";
            $numero = $j01_matric; //.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
          }
        }
      } else {
        $Identificacao = db_query("select cgm.*,
            ''::bpchar as nomepri,
            ''::bpchar as j39_co
            from cgm
            where z01_numcgm = $origem");

        if(pg_numrows($Identificacao)==0) {

		  db_msgbox("Problemas no Cadastro do CGM $origem");
          echo "<script>  parent.db_iframe_carne.hide(); </script> ";
          exit;
        }


        db_fieldsmemory($Identificacao, 0);
        $numero = $origem;
      }
	  // AREA DA CONSTRUÇÃO
	  $j39_area=0;
	  $sqlareaconstr = "select 	sum(j39_area) as j39_area
                        from iptuconstr
                        where j39_dtdemo is null and
                        j39_matric = $j23_matric";
      $resultsqlareaconstr = db_query($sqlareaconstr) ;
	  $linhasareaconstr = pg_num_rows($resultsqlareaconstr);
	  if($linhasareaconstr>0){
	  	db_fieldsmemory($resultsqlareaconstr,0);
	  }

	  $sqlReceitaCapa = " select k00_receit,k02_descr,sum(k00_valor) as k00_valor
						from arrecad
						inner join tabrec on k00_receit = k02_codigo
						where k00_numpre = $k00_numpre
						group by k00_receit,k02_descr";
	  $resultReceitaCapa = db_query($sqlReceitaCapa);
	  $linhasReceitaCapa = pg_num_rows($resultReceitaCapa);




// *************************** CAPA DO CARNE *****************************

      if ( $auxiliar != $j23_matric && $capa == "s" ){

		                //limpa od dados da capa
						for($d=1;12>$d;$d++){
							$dados = "dados".$d;
							$pdfcapa->$dados = "";
						}

           	            $pdfcapa->Tfont = 6;

						$pdfcapa->logo			  = $logo;
						$pdfcapa->munic			  = $munic;
						$pdfcapa->contrcapa       = $nome_contri;
						$pdfcapa->tipodebito      = $k00_descr;
						$pdfcapa->prefeitura      = $nomeinst;
						$pdfcapa->titulo1		  = "Matricula";
						$pdfcapa->descr1		  = $numero;
						$pdfcapa->endcapa		  = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl);
						$pdfcapa->cidufcapa       = strtoupper($j23_munic).($j23_uf==""?"":" - ".$j23_uf);
						$pdfcapa->bairrocapa      = strtoupper($j23_bairro);
						$pdfcapa->cepcapa         = "CEP: ".$z01_cep;
						$pdfcapa->titulo4	      = "Dados";
						$pdfcapa->dados1  	      = "LOGRADOURO: ".strtoupper($nomepri).($j39_numero == ""?"": ', '.$j39_numero).($j39_compl==""?"":' / '.$j39_compl);
						$pdfcapa->dados2 	      = "NATUREZA: ".$j01_tipoimp;
						$pdfcapa->dados3  	      = "ALIQUOTA: $j23_aliq %";
						$pdfcapa->dados4  	      = "ÁREA DO LOTE: ".trim(db_formatar($j23_arealo,"f"))."m2";
						$pdfcapa->dados5  	      = "ÁREA EDIFICADA: ".trim(db_formatar( $j39_area,"f"))."m2";
						// mosta as receitas de taxas
                        for ($cp = 0; $cp < $linhasReceitaCapa; $cp ++) {
                          db_fieldsmemory($resultReceitaCapa, $cp);
						  $d = $cp + 6;
						  $dados = "dados".$d;
						  $pdfcapa->$dados = $k02_descr.":  R$".trim(db_formatar($k00_valor,"f"));
		                }

						$pdfcapa->imprime();

						$quanti ++;
						$pdfcapa->qtdcarne = $quanti ;
						$pdf1->qtdcarne = $quanti ;
						$pdf1->atualizaquant = false;
						$auxiliar = $j23_matric;
						$intixxx++;

				 }


//******************************************************************************************



      /************************************************************************************************************************************/
      /// S E   F O R   U N I C A...
      /************************************************************************************************/
      if ($unica == 1 && $datasUnicas != "" ) {

        $sql  = " select *, ";
        $sql .= "        substr(fc_calcula,2,13)::float8 as uvlrhis, ";
        $sql .= "        substr(fc_calcula,15,13)::float8 as uvlrcor, ";
        $sql .= "        substr(fc_calcula,28,13)::float8 as uvlrjuros, ";
        $sql .= "        substr(fc_calcula,41,13)::float8 as uvlrmulta, ";
        $sql .= "        substr(fc_calcula,54,13)::float8 as uvlrdesconto, ";
        $sql .= "        (substr(fc_calcula,15,13)::float8 + substr(fc_calcula,28,13)::float8 + substr(fc_calcula,41,13)::float8 - substr(fc_calcula,54,13)::float8) as utotal, ";
        $sql .= "         substr(fc_calcula,77,17)::float8 as qinfla, ";
        $sql .= "         substr(fc_calcula,94,4)::varchar(5) as ninfla ";
        $sql .= "   from ( select r.k00_numpre, ";
        $sql .= "                 r.k00_dtvenc as dtvencunic, ";
        $sql .= "                 r.k00_dtvenc as dtvencunicuni, ";
        $sql .= "                 r.k00_dtoper as dtoperunic, ";
        $sql .= "                 r.k00_percdes, ";
        $sql .= "                 fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").") ";
        $sql .= "            from recibounica r ";
        $sql .= "           where r.k00_numpre = ".$k00_numpre;
        if ($datasUnicas != "") {
          $sql .= "           and r.k00_dtvenc in ( $datasUnicas ) ";
        }
        $sql .= "             and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date ) as unica ";
        $sql .= "          order by dtvencunic ";

        if($debugar ==true){
          echo "<br> SQL4 - UNICA  <br> $sql";
        }

        $linha     = 220;
        $resultfin = db_query($sql) or die($sql);

        if ($resultfin != false) {

          for ($unicont = 0; $unicont < pg_numrows($resultfin); $unicont ++) {

            db_fieldsmemory($resultfin, $unicont);

            $vlrhis       = db_formatar($uvlrhis, 'f');
            $vlrdesconto  = db_formatar($uvlrdesconto, 'f');
            $utotal		   += $taxabancaria;
            $vlrtotal     = db_formatar($utotal, 'f');
            $vlrbar       = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

            if ($barrasunica == "seis") {
              $terceiro = "6";
            } else {
              $terceiro = "7";
            }

            $sqlvalor = "select k00_impval, k00_tercdigcarneunica from arretipo where k00_tipo = $tipo_debito";
            db_fieldsmemory(db_query($sqlvalor), 0);


            if ($k00_impval == 't') {

              $k00_valor = $utotal;
              $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
              $ninfla    = '';

              if ($utotal == 0) {
                $vlrbar   = "00000000000";
              }

            } else {

              $k00_valor = $qinfla;
              $vlrbar    = "00000000000";

            }

            $datavencimento = $dtvencunic;

            if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
              if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu')) {
                continue;
              }
            }

            if($oRegraEmissao->isCobranca()){

              if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( $ninfla_ant != "" && $ninfla_ant != "REAL")) {
                $k00_valor = 0;
                $especie   = $ninfla;
                $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
              }else{
                $especie   = 'R$';
                $histinf   = "";
              }

              if($datavencimento < date('Ymd',db_getsession('DB_datausu'))){
                $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
                $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
              }else{
                $msgvencida = "";
              }

            }

  	        try {
  	          $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k00_numpre,0,$k00_valor,$vlrbar,$datavencimento,$terceiro);
  	        } catch (Exception $eExeption){
  	    	    db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  	  		    exit;
  		      }

  	        $codigo_barras   = $oConvenio->getCodigoBarra();
  	        $linha_digitavel = $oConvenio->getLinhaDigitavel();

	          db_inicio_transacao();

            try {

              $oRecibo = new recibo(2, null, 5);
              $oRecibo->addNumpre($k00_numpre,0);
              $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
              $oRecibo->setDataRecibo($dtvencunic);
              $oRecibo->setDataVencimentoRecibo($dtvencunic);
              $oRecibo->emiteRecibo();
              $novo_numpre = $oRecibo->getNumpreRecibo();
            } catch ( Exception $eException ) {
              db_fim_transacao(true);
            	db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
              exit;
            }

            db_fim_transacao();

            if($oRegraEmissao->isCobranca()) {

              $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
              $pdf1->carteira 	     = $oConvenio->getCarteira();

              if(strlen(trim($oConvenio->getConvenioCobranca())) == 7) {
                $pdf1->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($k00_numpre,8,"0",STR_PAD_LEFT) . "00";
              } else {
                $pdf1->nosso_numero = $oConvenio->getNossoNumero();
              }

            }

            global $pdf;


            $pdf1->data_processamento = db_formatar($dtoperunic, 'd');
            $pdf1->titulo1 = $descr;
            $pdf1->descr1  = $numero;
            $pdf1->descr2  = db_numpre($novo_numpre, 0).'000'; //.db_formatar($k00_numpar,'s',"0",3,"e");

            if (isset ($obs)) {
              $pdf1->titulo13 = 'Observação';
              $pdf1->descr13  = $obs;
            }

            $pdf1->descr5 	   = 'UNICA';
            $pdf1->descr6 	   = db_formatar($dtvencunic,'d');
            $pdf1->predescr6   = $dtvencunic;
            $pdf1->predatacalc = $dtvencunic;
            $pdf1->titulo8     = $descr;
            $pdf1->pretitulo8  = $descr;
            $pdf1->descr8      = $numero;
            $pdf1->predescr8   = $numero;
            $pdf1->descr9      = db_numpre($novo_numpre, 0).'000';
            $pdf1->predescr9   = db_numpre($novo_numpre, 0).'000';
            $pdf1->descr10     = 'UNICA';
            $pdf1->tipo_exerc  = "$k00_tipo / ".substr($dtvencunic,0,4);

            if($debugar==true){
              echo "<br>else matricula que passa o endereço  <br> ";
            }

            $pdf1->pretipocompl  = 'Número:';
            $pdf1->tipocompl     = 'Número:';
            $pdf1->tipobairro	   = 'Bairro:';
            $pdf1->bairropri	   = $z01_bairro;
            $pdf1->descr11_1	   = $z01_numcgm." - ".$nome_contri;
            $pdf1->descr11_2     = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl) . " - " . $j23_bairro;
            $pdf1->descr11_3     = $xbairro;
            $pdf1->descr17       = $j23_munic . "/" . $j23_uf . (trim($j23_cep) != ""?" - CEP: " . $j23_cep:"");
            $pdf1->bairrocontri  = $j23_bairro;
            $pdf1->munic         = $j23_munic;
            $pdf1->premunic      = $j23_munic;
            $pdf1->uf            = $j23_uf;
            $pdf1->descr3_1      = $z01_numcgm." - ".$nome_contri;
            $pdf1->descr3_2      = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl) . " - " . $j23_bairro;
            $pdf1->predescr3_1   = $z01_numcgm." - ".$nome_contri;
            $pdf1->predescr3_2   = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl);
            $pdf1->descr3_3      = $j23_bairro;
            $pdf1->tipoinscr     = 'Cgm';
            $pdf1->nrinscr       =  $z01_numcgm;
            $pdf1->tipolograd    = 'Rua ';
            $pdf1->pretipolograd = 'Rua ';
            $pdf1->cep					 = $j23_cep;
            $pdf1->precep				 = $j23_cep;
            $pdf1->nomepri			 = $j23_ender;
            $pdf1->nomepriimo		 = $j23_ender;
            $pdf1->prenomepri		 = $j23_ender;
            $pdf1->nrpri				 = $j23_numero;
            $pdf1->prenrpri			 = $j23_numero;
            $pdf1->complpri			 = $j23_compl;
            $pdf1->precomplpri	 = $j23_compl;
            $pdf1->precgccpf     = $z01_cgccpf;
            $pdf1->cgccpf		     = $z01_cgccpf;


            /************  P E G A   A S   R E C E I T A S   C O M   O S   V A L O R E S  *****************/

            $sqlReceitas  = " select (substr(fc_calcula,15,13)::float8+ ";
            $sqlReceitas .= "		     substr(fc_calcula,28,13)::float8+ ";
            $sqlReceitas .= "        substr(fc_calcula,41,13)::float8- ";
            $sqlReceitas .= " 			 substr(fc_calcula,54,13)::float8) as valreceita, ";
            $sqlReceitas .= "        codreceita, ";
            $sqlReceitas .= " 			 descrreceita, ";
            $sqlReceitas .= " 			 reduzreceita ";
            $sqlReceitas .= "		 from (";
            $sqlReceitas .= " select k00_receit as codreceita, ";
            $sqlReceitas .= "        k02_descr  as descrreceita, ";
            $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec ";
            $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz ";
            $sqlReceitas .= "        end  as reduzreceita, ";
            $sqlReceitas .= "        k00_valor  as val, ";
            $sqlReceitas .= "        fc_calcula(recibounica.k00_numpre,0,a.k00_receit,recibounica.k00_dtvenc,recibounica.k00_dtvenc,".db_getsession('DB_anousu').")";
            $sqlReceitas .= "   from arrecad a";
            $sqlReceitas .= "        inner join recibounica on recibounica.k00_numpre = a.k00_numpre";
            $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit ";
            $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo ";
            $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu')."";
            $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
            $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."";
            $sqlReceitas .= " where a.k00_numpre = $k00_numpre ";
            $sqlReceitas .= "   and k00_numpar = 1 ";
            $sqlReceitas .= "   and recibounica.k00_dtvenc = '".$dtvencunicuni."' ) as c";
             if($debugar==true){
             	echo "<br> receitas : <br>$sqlReceitas <br>";
             }
            $rsReceitas = db_query($sqlReceitas);
            $intnumrows = pg_num_rows($rsReceitas);
            for ($x = 0; $x < $intnumrows; $x ++) {
              db_fieldsmemory($rsReceitas, $x);
              $pdf1->arraycodreceitas[$x]   = $codreceita;
              $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
              $pdf1->arraydescrreceitas[$x] = $descrreceita;
              $pdf1->arrayvalreceitas[$x]   = $valreceita;
            }

            if(isset($vlrjuros) && $vlrjuros != "" && $vlrjuros !=0){
              //      $x++;
              $pdf1->arraycodreceitas[$x]   = "";
              $pdf1->arrayreduzreceitas[$x] = "";
              $pdf1->arraydescrreceitas[$x] = "Juros : ";
              $pdf1->arrayvalreceitas[$x]   = $vlrjuros;
            }
            if(isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0){
              $x++;
              $pdf1->arraycodreceitas[$x]   = "";
              $pdf1->arrayreduzreceitas[$x] = "";
              $pdf1->arraydescrreceitas[$x] = "Multa : ";
              $pdf1->arrayvalreceitas[$x]   = $vlrmulta;
            }

            $pdf1->especie   = 'R$';

            /***********************************************************************************************/

            if($oRegraEmissao->isCobranca()){

              $pdf1->descr12_1 .= $pdf1->tipodebito."\n".
                $pdf1->titulo1." - ".$pdf1->descr1." / ".
                $pdf1->titulo4." ".$pdf1->descr4_1." Parcela única \n".
              (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
              (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $pdf1->pretipodebito."\n":"").
                $pdf1->titulo1." - ".$pdf1->descr1." / ".
                $pdf1->titulo4." ".$pdf1->descr4_1." Parcela única \n";
              (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
            }

            ///////// PEGA A MSG DE PAGAMENTO E AS INSTRUÇÕES DA TABELA NUMPREF
            $rsmsgcarne = db_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = ".db_getsession("DB_anousu"));
            if (pg_numrows($rsmsgcarne) > 0) {
              db_fieldsmemory($rsmsgcarne, 0);
            }
            /* busca as mensagens da arretipo */
            $sqlMsgCarne = " select k00_msguni2 from arretipo where k00_tipo = $k00_tipo ";
            $rsMsgCarneUnica = db_query($sqlMsgCarne);
            $intNumrowsMsgCarne = pg_numrows($rsMsgCarneUnica);
            if($intNumrowsMsgCarne > 0 ){
              db_fieldsmemory($rsMsgCarneUnica, 0);
            }
            if (isset ($k00_msguni2) && $k00_msguni2 != "") {
              $pdf1->predescr12_1 = $k00_msguni2; //msg unica, via contribuinte
            } else {
              //          $pdf1->descr12_1 = $k03_msgbanco." Não aceitar apos vencimento "; //msg unica, via contribuinte
            }

            $pdf1->descr14   = db_formatar($dtvencunic,'d');
            $pdf1->dtparapag = db_formatar($dtvencunic,'d');

            if ($terceiro == '7') {
              //////////////////// ISSQN VARIAVEL /////////////////////
              if ($k03_tipo == 3) {
                $sqlaliq = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
                $rsIssvarano = db_query($sqlaliq);
                $intNumrows = pg_numrows($rsIssvarano);
                if ($intNumrows == 0) {
                	 db_msgbox("Ano não encontrado na tabela issvar. Contate o suporte");
                     echo "<script>  parent.db_iframe_carne.hide(); </script> ";
                     exit;
                }
                db_fieldsmemory($rsIssvarano, 0);
                $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
                //$pdf1->descr4_1   = $k00_numpar.'a PARCELA   -   Alíquota '.pg_result(db_query($sqlaliq),"q05_aliq").'%     EXERCÍCIO : '.pg_result(db_query($sqlaliq),"q05_ano");
              }
              $pdf1->titulo7 = 'Valor Pago';
              $pdf1->titulo15 = 'Valor Pago';
              $pdf1->titulo13 = 'Valor da Receita Tributável';


              //*******************************************************************
              //alterado para passar os valores para o carnê (Anderson) ...

              $pdf1->descr7    = db_formatar($k00_valor, 'f');
              $pdf1->descr15   = db_formatar($k00_valor, 'f');
              $pdf1->valtotal  = db_formatar($k00_valor, 'f');
              $pdf1->predescr7 = db_formatar($k00_valor, 'f');
              //*******************************************************************


            } else {
              $pdf1->descr15   = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
              $pdf1->valtotal  = db_formatar($k00_valor, 'f');
              $pdf1->descr7    = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
              $pdf1->predescr7 = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
            }

            //$pdf1->valtotal            = db_formatar(($uvlrhis + $uvlrdesconto), 'f');
            $pdf1->totaldescunica = db_formatar($uvlrdesconto, 'f');

            $pdf1->descr12_1 = '- PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO - '.$descr.":".$origem;
            $pdf1->descr12_1 .= $bql;
            $pdf1->prehistoricoparcela = ' PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO';
            $pdf1->linha_digitavel = $linha_digitavel;
            $pdf1->codigo_barras = $codigo_barras;
            //debug($pdf1);exit;

            $sqlmsg = "select k00_tipo,k00_msguni,k00_msguni2 from arretipo where k00_tipo=".$k00_tipo;
            $resultmsg = db_query($sqlmsg);
            $linhasmsg = pg_num_rows($resultmsg);
            db_fieldsmemory($resultmsg, 0);
            $desconto = $k00_percdes;
            $texto  = db_geratexto($k00_msguni);
            $texto2 = db_geratexto($k00_msguni2);

            $pdf1->premsgunica =$texto;

            if ($texto != '' ) {
              $pdf1->descr12_2 = $texto;
            }

            if ($texto2 != '' ) {
              $pdf1->descr16_1 = substr($texto2, 0, 55);
              $pdf1->descr16_2 = substr($texto2, 55, 55);
              $pdf1->descr16_3 = substr($texto2, 110, 55);
            }


// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco

if($oRegraEmissao->isCobranca()){

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco			      = db_utils::fieldsMemory($rsConsultaBanco,0);
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;

  try{
  	$pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
  	db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }

}

//#############################################################
      /**
       * Imprime UNICAS
       */
      $pdf1->imprime();
			$intixxx++;
			if($capa=="s"){
  			  $quanti ++;
              $pdf1->qtdcarne	 = $quanti ;
              $pdfcapa->qtdcarne  = $quanti ;
              $pdf1->atualizaquant  = false;
			}
          }
        }
        $unica = 2;
        $pdf1->descr12_1 = '';
        $pdf1->premsgunica = '';

        if ($sounica == '') {

          $pdf1->objpdf->Output();
          exit;
        }

        if($debugar==true){
          echo "<br>FIM PARCELA UNICA<br>";
        }

      }

      /******************************************** FIM PARCELA UNICA *********************************************/

      if ($gera_parcelado == 1) {

        if ($k00_codbco == "" || $k00_codage == "") {

          $errobco = "Código do banco e ou agência zerado ou nulo!";
      db_msgbox("Verifique cadastro do tipo de débito - $tipo_debito <br> $errobco");
          echo "<script>  parent.db_iframe_carne.hide(); </script> ";
          exit;

        }

        $valores    = split("P", $numpres[$volta]);
        $k00_numpre = $valores[0];
        $k00_numpar = split("R", $valores[1]);
        $k00_numpar = $k00_numpar[0];
        $k03_anousu = $H_ANOUSU;

        if($debugar==true){
          echo "numpre =$k00_numpre e par = $k00_numpar ";
        }

        $novo_numpre = 0;
        /*
	        $ssqlnumprenovo = " select nextval('numpref_k03_numpre_seq') as novo_numpre ";
	        $rsnumprenovo   = db_query($ssqlnumprenovo);
	        $onumprenovo    = db_fieldsmemory($rsnumprenovo,0);
	        $inumpar        = $k00_numpar;
	        global $k03_numpre;
	        $k03_numpre = 0;
        */

        $DadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento);
        db_fieldsmemory($DadosPagamento, 0);

        if ( $total < 0 ) {
          db_msgbox("Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
          echo "<script>  parent.db_iframe_carne.hide(); </script> ";
          exit;

        }

        $total += $taxabancaria;
        $ninfla_ant = $ninfla;

        if($k03_numpre == 0 ){
          $recibopaga = false;
        }else{
          $recibopaga = true;
        }

        if($recibopaga == false){

          $sql1 = "select k00_dtvenc as datavencimento,
                    k00_dtvenc,
                    k00_numtot,
                    k00_dtoper
                   from arrecad
                  where k00_numpre = $k00_numpre
                  and k00_numpar = $k00_numpar
                  limit 1";
        }else{

          $sql1 = "select k00_dtvenc as datavencimento,
                    k00_dtvenc,
                    k00_numtot,
                    k00_dtoper
                   from recibopaga
                  where k00_numnov = $k03_numpre
                  limit 1";

        }

        db_fieldsmemory(db_query($sql1), 0);

        $k00_dtvenc = db_formatar($k00_dtvenc, 'd');
        $pdf1->data_processamento = db_formatar($k00_dtoper,'d'); // agora  a data de operação


        if ($barrasparc == "seis") {
          $terceiro = "6";
        } else {
          $terceiro = "7";
        }

        // alterei para buscar o terceiro digito pelo tipo de debito da tabela arretipo
        $sqlvalor = "select k00_impval,k00_tercdigcarnenormal from arretipo where k00_tipo = $tipo_debito";
        db_fieldsmemory(db_query($sqlvalor), 0);

        $ss = $ninfla;

        if ($k00_impval == 't') {

          $ninfla_ant = $ninfla;
          if ($total > 0){
            $k00_valor = $total;
            $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
            $ninfla = '';
          }else{
            $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format(0, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
            $k00_valor = $qinfla;
          }

          $k00_valor = $total;

          if (($total == 0) || (substr($k00_dtvenc, 6, 4) > date("Y", $H_DATAUSU) +1 )) {
            if ($ninfla_ant == "REAL") {

              $vlrbar   = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

            } else {

              $vlrbar   = "00000000000";

              if ($total != 0) {
                $k00_valor = $qinfla;
                $ninfla    = $ss;
              }

            }
          }

        } else {
          $k00_valor = $qinfla;
          $vlrbar    = "00000000000";
        }

        $datavencimento = substr($k00_dtvenc, 6, 4).substr($k00_dtvenc, 3, 2).substr($k00_dtvenc, 0, 2);
        $tmpdt 		  = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);

        if ($tmpdt > $datavencimento) {
          $datavencimento = $tmpdt;
        }

        if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
          if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu')) {
            continue;
          }
        }

        $ninfla_ant = $ninfla;

        if($oRegraEmissao->isCobranca()){

          if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( $ninfla_ant != "" && $ninfla_ant != "REAL")) {
            $k00_valor = 0;
            $especie   = $ninfla;
            $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
          }else{
            $especie   = 'R$';
            $histinf   = "";
          }

          if($datavencimento < date('Ymd',db_getsession('DB_datausu'))){
            $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
            $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
          }else{
            $msgvencida = "";
          }


          if($recibopaga==false){
            $iNumpre = $k00_numpre;
            $iNumpar = $k00_numpar;
          } else {
            $iNumpre = $k03_numpre;
            $iNumpar = 0;
          }

        } else {
          $iNumpre = $k00_numpre;
          $iNumpar = 0;
        }


        db_inicio_transacao();

        try {

          $oRecibo = new recibo(2, null, 5);
          $oRecibo->addNumpre($k00_numpre,$k00_numpar);
          $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
          $datavencimento = substr($k00_dtvenc, 6, 4).substr($k00_dtvenc, 3, 2).substr($k00_dtvenc, 0, 2);
          $oRecibo->setDataVencimentoRecibo($datavencimento);
          $oRecibo->emiteRecibo();
          $novo_numpre = $oRecibo->getNumpreRecibo();
          if(!$oRegraEmissao->isCobranca()){
            $iNumpre = $novo_numpre;
            $iNumpar = 0;
          }
        } catch ( Exception $eException ) {
          db_fim_transacao(true);
        	db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
          exit;
        }

        db_fim_transacao();

        try {
          $oConvenio = new convenio($oRegraEmissao->getConvenio(),$iNumpre,$iNumpar,$k00_valor,$vlrbar,$datavencimento,$terceiro);
        } catch (Exception $eExeption){
          db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
          exit;
        }

        $codigo_barras   = $oConvenio->getCodigoBarra();
        $linha_digitavel = $oConvenio->getLinhaDigitavel();

        $pdf1->agencia_cedente  = $oConvenio->getAgenciaCedente();
        $pdf1->carteira         = $oConvenio->getCarteira();

        if($oRegraEmissao->isCobranca()){

          if(strlen(trim($oConvenio->getConvenioCobranca())) == 7) {
            $pdf1->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($k00_numpre,8,"0",STR_PAD_LEFT) . str_pad($k00_numpar,2,"0",STR_PAD_LEFT);
          } else {
            $pdf1->nosso_numero = $oConvenio->getNossoNumero();
          }

        }


        global $pdf;

        $pdf1->descr12_2  = '';
        $pdf1->titulo1    = $descr;
        $pdf1->descr1     = $numero;
        $pdf1->descr2     = db_numpre($novo_numpre, 0).db_formatar(0, 's', "0", 3, "e");
        $pdf1->tipo_exerc = "$k00_tipo / ".substr($k00_dtoper,0,4);

        /************  P E G A   A S   R E C E I T A S   C O M   O S   V A L O R E S  *****************/

        if($recibopaga==false){

          $sqlReceitas  = " select k00_receit as codreceita, ";
          $sqlReceitas .= "        k02_descr  as descrreceita, ";
          $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec ";
          $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz ";
          $sqlReceitas .= "        end  as reduzreceita, ";
          $sqlReceitas .= "        k00_valor  as valreceita, ";
          $sqlReceitas .= "       (select (select k02_codigo from tabrec where k02_recjur = k00_receit or k02_recmul = k00_receit limit 1) is not null ) as codtipo,";
          $sqlReceitas .= "        fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,a.k00_dtvenc,a.k00_dtvenc,".db_getsession('DB_anousu').") ";
          $sqlReceitas .= "   from arrecad a";
          $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit ";
          $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo ";
          $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu')."";
          $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
          $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."";
          $sqlReceitas .= " where k00_numpre = $k00_numpre ";
          $sqlReceitas .= "   and k00_numpar = $k00_numpar ";

        }else{

          $sqlReceitas  = " select k00_tipo as codtipo
            from arrecad where k00_numpre = $k00_numpre";
          $rsReceitas = db_query($sqlReceitas);
          if(pg_numrows($rsReceitas)==0){
            db_msgbox("Não encontrado arrecad ($k00_numpre).");
            exit;
          }
          db_fieldsmemory($rsReceitas,0);

          $sqlReceitas  = " select k00_receit as codreceita, ";
          $sqlReceitas .= "        k02_descr  as descrreceita, ";
          $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec ";
          $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz ";
          $sqlReceitas .= "        end  as reduzreceita, ";
          $sqlReceitas .= "        k00_valor  as valreceita, ";
          $sqlReceitas .= "        k00_valor as fc_calcula";
          $sqlReceitas .= "   from recibopaga a";
          $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit ";
          $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo ";
          $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu')."";
          $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
          $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."";
          $sqlReceitas .= " where k00_numnov = $k03_numpre ";

        }

        //die($sqlReceitas);
        $rsReceitas = db_query($sqlReceitas);
        $intnumrows = pg_num_rows($rsReceitas);
        for ($x = 0; $x < $intnumrows; $x ++) {
          db_fieldsmemory($rsReceitas, $x);
          $pdf1->arraycodreceitas[$x]   = $codreceita;
          $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
          $pdf1->arraydescrreceitas[$x] = $descrreceita;

          if($recibopaga==false){
            $pdf1->arrayvalreceitas[$x]   = (float)substr($fc_calcula,14,13);
          }else{
            $pdf1->arrayvalreceitas[$x]   = $fc_calcula; //$valreceita;
          }

          $pdf1->arraycodtipo[$x]       = $codtipo;
          //$pdf1->arrayvalreceitas[$x]   = $valreceita; //   (float)substr($fc_calcula,14,13); //$valreceita;
        }
        if(isset($vlrjuros) && $vlrjuros != "" && $vlrjuros !=0){
          //      $x++;
          $pdf1->arraycodreceitas[$x]   = "";
          $pdf1->arrayreduzreceitas[$x] = "";
          $pdf1->arraydescrreceitas[$x] = "Juros : ";
          $pdf1->arrayvalreceitas[$x]   = "$vlrjuros";
          $pdf1->arraycodtipo[$x]       = $codtipo;
        }else{
          $pdf1->arraycodreceitas[$x]   = "";
          $pdf1->arrayreduzreceitas[$x] = "";
          $pdf1->arraydescrreceitas[$x] = "Juros : ";
          $pdf1->arrayvalreceitas[$x]   = "";
          $pdf1->arraycodtipo[$x]       = $codtipo;
        }
        if(isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0){
          $x++;
          $pdf1->arraycodreceitas[$x]   = "";
          $pdf1->arrayreduzreceitas[$x] = "";
          $pdf1->arraydescrreceitas[$x] = "Multa : ";
          $pdf1->arrayvalreceitas[$x]   = "$vlrmulta";
          $pdf1->arraycodtipo[$x]       = $codtipo;
        }else{
          $x++;
          $pdf1->arraycodreceitas[$x]   = "";
          $pdf1->arrayreduzreceitas[$x] = "";
          $pdf1->arraydescrreceitas[$x] = "Multa : ";
          $pdf1->arrayvalreceitas[$x]   = "";
          $pdf1->arraycodtipo[$x]       = $codtipo;
        }

        /***********************************************************************************************/


        if($debugar == true){
          echo "else matricula não unica <br>
            <br> ender =  $j23_ender
            <br> numer =  $j23_numero
            <br> compl =  $j23_compl
            $j23_bairro --
            $j23_munic --
            $j23_uf --
            $j23_cep --
            $j23_cxpostal";

        }
        $pdf1->descr11_1     = $z01_numcgm." - ".$nome_contri;
        $pdf1->descr11_2     = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl) . " - " . $j23_bairro;
        $pdf1->descr11_3     = $j23_bairro;
        $pdf1->descr17       = $j23_munic . "/" . $j23_uf . (trim($j23_cep) != ""?" - CEP: " . $j23_cep:"");
        $pdf1->descr3_1      = $z01_numcgm." - ".$nome_contri;
        $pdf1->descr3_2      = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl) . " - " . $j23_bairro;
        $pdf1->predescr3_1   = $z01_numcgm." - ".$nome_contri;
        $pdf1->predescr3_2   = strtoupper($j23_ender). ($j23_numero == "" ? "" : ', '.$j23_numero.'  '.$j23_compl);
        $pdf1->descr3_3      = $j23_bairro;
        $pdf1->bairrocontri  = $j23_bairro;
        $pdf1->prebairropri  = $j23_bairro;
        $pdf1->bairropri     = $j23_bairro;
        $pdf1->cep           = $j23_cep;
        $pdf1->precep        = $j23_cep;
        $pdf1->precgccpf     = $z01_cgccpf;
        $pdf1->cgccpf        = $z01_cgccpf;
        $pdf1->uf            = $j23_uf;
        $pdf1->tipoinscr     = 'Cgm';
        $pdf1->nrinscr       = $z01_numcgm;
        $pdf1->munic         = $j23_munic;
        $pdf1->premunic    	 = $j23_munic;
        $pdf1->tipolograd    = 'Rua ';
        $pdf1->pretipolograd = 'Rua ';
        $pdf1->nomepri       = $j23_ender;
        $pdf1->prenomepri    = $j23_ender;


        if ($k00_hist1 == '' || $k00_hist2 == '') {
          $pdf1->descr4_1 = $k00_numpar.'a PARCELA';
          $pdf1->historicoparcela = $k00_numpar.'a PARCELA';
          $pdf1->prehistoricoparcela = $k00_numpar.'a PARCELA';
        }else{
          if (isset ($k00_hist1) && $k00_hist1 != "" && $k00_hist1 != ".") {
            $pdf1->descr4_1 = $k00_hist1;
          }
          if (isset ($k00_hist2) && $k00_hist2 != "" && $k00_hist2 != ".") {
            $pdf1->descr4_2 = $k00_hist2;
            $pdf1->predescr4_2 = $k00_hist2;
          }
        }

        if (isset ($obs)) {
          $pdf1->titulo13 = 'Observação';
          $pdf1->descr13 = $obs;
        }
        $pdf1->descr5 = $k00_numpar.' / '.$k00_numtot;

        $tmpdta    = split("/",$k00_dtvenc);
        $tmpdtvenc = $tmpdta[2]."-".$tmpdta[1]."-".$tmpdta[0];
        if($db_datausu > $tmpdtvenc){
          $pdf1->dtparapag    = db_formatar($db_datausu,'d');
          $pdf1->datacalc    = db_formatar($db_datausu,'d');
          $pdf1->predatacalc    = db_formatar($db_datausu,'d');
          $pdf1->confirmdtpag = 't';
        }else{
          $pdf1->dtparapag    = $k00_dtvenc;
          $pdf1->datacalc     = $k00_dtvenc;
          $pdf1->predatacalc     = $k00_dtvenc;
          $pdf1->confirmdtpag = 't';
        }
        $pdf1->descr6 = $k00_dtvenc;
        $pdf1->predescr6 = $k00_dtvenc;

        $pdf1->titulo8 = $descr;
        $pdf1->pretitulo8 = $descr;
        $pdf1->descr8  = $numero;
        $pdf1->predescr8  = $numero;
        if($recibopaga ==false){
          $pdf1->descr9  = db_numpre($novo_numpre, 0).db_formatar(0, 's', "0", 3, "e");
          $pdf1->predescr9  = db_numpre($novo_numpre, 0).db_formatar(0, 's', "0", 3, "e");
        }else{
          $pdf1->descr9  = db_numpre($novo_numpree, 0).db_formatar(0, 's', "0", 3, "e");
          $pdf1->predescr9  = db_numpre($novo_numpre, 0).db_formatar(0, 's', "0", 3, "e");
        }
        $pdf1->descr10 = $k00_numpar.' / '.$k00_numtot;
        $pdf1->descr14 = $k00_dtvenc;

        $sqlmsg = "select k00_tipo,k00_msgparc,k00_msgparc2 from arretipo where k00_tipo=".$k00_tipo;
              $resultmsg = db_query($sqlmsg);
              $linhasmsg = pg_num_rows($resultmsg);
              db_fieldsmemory($resultmsg, 0);

        if ($total == 0) {
          //////////// ISSQN VARIAVEL ///////////
          if ($k03_tipo == 3) {
            $sqlaliq = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
            $rsIssvarano = db_query($sqlaliq);
            $intNumrows = pg_numrows($rsIssvarano);
            if ($intNumrows == 0) {
              db_msgbox("Ano não encontrado na tabela issvar. Contate o suporte");
              echo "<script>  parent.db_iframe_carne.hide(); </script> ";
              exit;

            }
            db_fieldsmemory($rsIssvarano, 0);
            $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
          }
          $pdf1->titulo7 = 'Valor Pago';
          $pdf1->titulo15 = 'Valor Pago';
          $pdf1->titulo13 = 'Valor da Receita Tributável';
          $pdf1->descr15 = '';
          $pdf1->valtotal='';
          $pdf1->descr7 = '';
          $pdf1->predescr7 = '';
        } else {

          $pdf1->descr15   = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
          $pdf1->valtotal  = db_formatar($k00_valor, 'f'); //$k00_valor;
          $pdf1->descr7    = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
          $pdf1->predescr7 = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
        }

        if($oRegraEmissao->isCobranca()){

          $pdf1->descr12_1 .= $pdf1->tipodebito."\n".
            $pdf1->titulo1." - ".$pdf1->descr1." / ".
            $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
            $k00_numpar."/".$k00_numtot."\n".
            (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
            (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
          (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $pdf1->pretipodebito:"")."\n".
            $pdf1->titulo1." - ".$pdf1->descr1." / ".
            $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
            $k00_numpar."/".$k00_numtot."\n".
            (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
            (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
        }

        $rsmsgcarne = db_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = ".db_getsession("DB_anousu"));
        if (pg_numrows($rsmsgcarne) > 0) {
          db_fieldsmemory($rsmsgcarne, 0);
        }

        if ($pagabanco == 't') {
          if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
            if (isset ($k00_msgparcvenc2) && $k00_msgparcvenc2 != "") {

              $pdf1->descr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
              $pdf1->predescr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
            }
          } else {

            if (isset ($k00_msgparc2) && $k00_msgparc2 != "") {

              $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida;
              (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida:"");
            } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {

              $pdf1->descr12_1 .= $k03_msgbanco." Não aceitar após vencimento.";
              $pdf1->predescr12_1 .= $k03_msgbanco." Não aceitar após vencimento.";
            }
          }
        } else {
          if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
            $pdf1->descr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
          } elseif (isset ($k00_msgparc2) && $k00_msgparc2 != "") {
            $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida;
          } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {
            $pdf1->descr12_1 .= $k03_msgbanco." Após o vencimento cobrar juros de 1%a.m e multa de 2% ";
          } else {
            $pdf1->descr12_1 .= '- O PAGAMENTO DEVERÁ SER EFETUADO SOMENTE NA PREFEITURA.'." ".$histinf." ".$msgvencida;
          }
        }

        $sqlparag = "select db02_texto
          from db_documento
          inner join db_docparag on db03_docum = db04_docum
          inner join db_paragrafo on db04_idparag = db02_idparag
          where db03_docum = 27
          and db02_descr ilike '%MENSAGEM CARNE%'
          and db03_instit = ".db_getsession("DB_instit");
        $resparag = db_query($sqlparag);

        if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
          if (isset ($k00_msgparcvenc) && $k00_msgparcvenc != "") {
            if (strlen($k00_msgparcvenc) > 50) {
              $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strrpos(substr($k00_msgparcvenc, 0, 50), ' '));
            } else {
              $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strlen($k00_msgparcvenc));
            }
            if (strlen($k00_msgparcvencvenc) > 100) {
              $part2 = substr(substr($k00_msgparcvenc, strlen($part1), 50), 0, strrpos(substr($k00_msgparcvenc, strlen($part1), strlen($k00_msgparcvenc)), ' '));
            } else {
              $part2 = substr(substr($k00_msgparcvenc, strlen($part1) + 1, 50), 0, strlen($k00_msgparcvenc));
            }
            if (strlen($k00_msgparcvenc) > 105) {
              $part3 = substr(substr($k00_msgparcvenc, strlen($part2), 50), 0, strlen($k00_msgparcvenc));
            }
            $pdf1->descr16_1 = $part1;
            $pdf1->descr16_2 = $part2;
            $pdf1->descr16_3 = $part3;
            $pdf1->predescr16_1 = $part1;
            $pdf1->predescr16_2 = $part2;
            $pdf1->predescr16_3 = $part3;
          }

        } elseif (isset ($k00_msgparc) && $k00_msgparc != "") {
          $pdf1->descr16_1 = substr($k00_msgparc, 0, 50);
          $pdf1->descr16_2 = substr($k00_msgparc, 50, 50);
          $pdf1->descr16_3 = substr($k00_msgparc, 100, 50);
          $pdf1->predescr16_1 = substr($k00_msgparc, 0, 50);
          $pdf1->predescr16_2 = substr($k00_msgparc, 50, 50);
          $pdf1->predescr16_3 = substr($k00_msgparc, 100, 50);
        } else {
          if (isset ($k03_msgcarne) && $k03_msgcarne != "") {
            $pdf1->descr16_1 = substr($k03_msgcarne, 0, 50);
            $pdf1->descr16_2 = substr($k03_msgcarne, 50, 50);
            $pdf1->descr16_3 = substr($k03_msgcarne, 100, 50);
            $pdf1->predescr16_1 = substr($k03_msgcarne, 0, 50);
            $pdf1->predescr16_2 = substr($k03_msgcarne, 50, 50);
            $pdf1->predescr16_3 = substr($k03_msgcarne, 100, 50);
          } else {
            if (pg_numrows($resparag) == 0) {
              $db02_texto = "";
            } else {
              db_fieldsmemory($resparag, 0);
            }
            $pdf1->descr16_1 = "  ";
            $pdf1->descr16_1 = substr($db02_texto, 0, 55);
            $pdf1->descr16_2 = substr($db02_texto, 55, 55);
            $pdf1->descr16_3 = substr($db02_texto, 110, 55);
            $pdf1->predescr16_1 = substr($db02_texto, 0, 55);
            $pdf1->predescr16_2 = substr($db02_texto, 55, 55);
            $pdf1->predescr16_3 = substr($db02_texto, 110, 55);
          }
        }
        $pdf1->texto = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
        $imprimircodbar=true;
        $sqltermo = "select k40_forma from termo
          inner join cadtipoparc on k40_codigo = v07_desconto
          where v07_numpre = $k00_numpre";
        $resulttermo = db_query($sqltermo) or die($sqltermo);
        if (pg_numrows($resulttermo) > 0) {
          db_fieldsmemory($resulttermo, 0);
          if ($k40_forma == 2 and $k00_numpar == $k00_numtot) {
            $imprimircodbar=false;
          }
        }
        if ($imprimircodbar == true) {
          $pdf1->linha_digitavel = $linha_digitavel;
          $pdf1->codigo_barras = $codigo_barras;
        } else {
          $pdf1->linha_digitavel = null;
          $pdf1->codigo_barras = null;
        }
        db_sel_instit();
        $pdf1->enderpref = $ender;
        $pdf1->municpref = $munic;
        $pdf1->telefpref = $email;
        $pdf1->emailpref = $telef;
        //	die($z01_munic);


        @$pdf1->especie = @$especie;

        // VERIFICA SE É UM PARCELAMENTO COM DESCONTO, SE FOR MOSTRAR O DESCONTO NO CARNE.

        $sqlVerParcel = "select k00_numpre,k00_numpar,k00_receit,k00_valor,k00_dtvenc,k00_tipo,v07_totpar,k40_aplicacao
          from termo
          inner join arrecad     on v07_numpre = k00_numpre
          inner join cadtipoparc on k40_codigo = v07_desconto
          where k00_numpre = $k00_numpre and k00_numpar = $k00_numpar  ";
        //echo "<br> $sqlVerParcel <br>";
        $resultVerParcel = db_query($sqlVerParcel);
        $linhasVerParcel = pg_num_rows($resultVerParcel);
        if($linhasVerParcel>0 ) {
          db_fieldsmemory($resultVerParcel,0);
          //calcula o desconto.
          $datahoje = date("Y-m-d");
          if($k00_dtvenc > $datahoje){
            $sqlDesconto = "select  fc_recibodesconto($k00_numpre,$k00_numpar,$v07_totpar,$k00_receit,$k00_tipo,'$k00_dtvenc','$k00_dtvenc') as percento";
            //echo "<br>$sqlDesconto<br>";
            $resultDesconto = db_query($sqlDesconto);
            $linhasDesconto = pg_num_rows($resultDesconto);
            $pdf1->descr4_2 ="";

            if($linhasDesconto >0){
              db_fieldsmemory($resultDesconto,0);
              if($percento != 0 ){
                $desc          = 100 - $percento;
                $valortotal    = round(($total * 100) / $desc,2);
                $valorDesconto = round($valortotal - $total,2);
                $pdf1->descr4_2 = "Valor da parcela                    R$".db_formatar($valortotal,"f")." \nDesconto até o vencimento R$".db_formatar($valorDesconto,"f")."";
              }else{
                $pdf1->descr4_2="";
              }
            }
          }
        }


  // ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
  //verifica se é ficha e busca o codigo do banco

  if($oRegraEmissao->isCobranca()){

    $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
    $oBanco			      = db_utils::fieldsMemory($rsConsultaBanco,0);
    $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
    $pdf1->banco      = $oBanco->db90_abrev;

    try{
      $pdf1->imagemlogo = $oConvenio->getImagemBanco();
    } catch (Exception $eExeption){
      db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
    }

  }

  //#############################################################
      /**
       * Imprime Carnes
       */
      $pdf1->imprime();
      $intixxx++;

        if($capa=="s"){
                $quanti ++;
                $pdf1->qtdcarne = $quanti ;
                $pdfcapa->qtdcarne = $quanti ;
                $pdf1->atualizaquant = false;
              }

        $pdf1->descr12_1 = '';
      $pdf1->predescr12_1 = '';
      $pdf1->descr4_1 = '';
      $pdf1->descr4_2 = '';

      }

    }

    db_query("COMMIT");

  }// final do for das matriculas

}else{// do if
  db_msgbox("Nenhum regristro encontrado para este filtro.");
  echo "<script>  parent.db_iframe_carne.hide(); </script> ";
  exit;
}

if($passar==false){
  db_msgbox("Nenhum regristro encontrado para este filtro.");
  echo "<script>  parent.db_iframe_carne.hide(); </script> ";
  exit;

}
// 4 - carne ficha de compensacao
// 33 - pre-impresso bagea
// 1 ou 2 normal

$iFimPag += $intixxx;

$arquivo       = "tmp/".$nomeTipoMod."_".str_replace(" ","",$k00_descr)."_de_".$iIniPag."_ate_".$iFimPag."_".date('His').".pdf";
$nomearquivos .= "tmp/".$nomeTipoMod."_".str_replace(" ","",$k00_descr)."_de_".$iIniPag."_ate_".$iFimPag."_".date('His').".pdf#Dowload dos ".$nomeTipoMod." de ".$iIniPag." ate ".$iFimPag."|";


$pdf1->objpdf->Output($arquivo, false, true);

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";