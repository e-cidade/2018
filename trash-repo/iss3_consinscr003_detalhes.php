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
  
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("classes/db_issbase_classe.php");
  include("classes/db_isscadsimples_classe.php");
  include("classes/db_histocorrenciainscr_classe.php");
  include("classes/db_db_config_classe.php");
  include("libs/db_utils.php");

?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
  db_postmemory($HTTP_GET_VARS,0);

  $cldb_config = new cl_db_config;
  $iInstitSessao = db_getsession('DB_instit');
  $rs_db_config = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
  $oDBConfig = db_utils::fieldsMemory($rs_db_config, 0, null);

	$pesquisaLocalizada = false;
  if ($solicitacao == "Atividades") {
    //echo "Atividades";

	  $sql = " select q07_ativ as dl_Cod, q03_descr, q03_atmemo, q12_descr as dl_Classe, rh70_estrutural, q71_estrutural, db121_estrutural as dl_Codigo116, q07_datain, q07_datafi, q07_databx, q07_quant as dl_QTD, 
              case when q88_inscr is null then 'S'::char(1) else 'P'::char(1) end as q88_tipo, 
              q07_horaini, q07_horafim, 
              q11_processo as dl_Processo, case when q11_oficio = 'true' then 'NORMAL' when q11_oficio = 'false' then 'OFICIO' else '' end as q11_oficio ";

    if ($oDBConfig->db21_codcli == 19985) {
      $sql .= ", q07_processo, q07_dtprocesso";
    }

    $sql .= "              
		  	      from tabativ
                   left  join clasativ      on q07_ativ                       = q82_ativ
                   left  join classe        on q82_classe                     = q12_classe
                   inner join ativid        on q07_ativ                       = q03_ativ
                   left  join ativprinc     on ativprinc.q88_inscr            = tabativ.q07_inscr and ativprinc.q88_seq = tabativ.q07_seq
                   left  join tabativbaixa  on tabativ.q07_inscr              = tabativbaixa.q11_inscr and tabativ.q07_seq = tabativbaixa.q11_seq
                   left  join atividcbo     on atividcbo.q75_ativid           = ativid.q03_ativ
                   left  join rhcbo         on rhcbo.rh70_sequencial          = atividcbo.q75_rhcbo
                   left  join atividcnae    on atividcnae.q74_ativid          = ativid.q03_ativ
                   left  join cnaeanalitica on cnaeanalitica.q72_sequencial   = atividcnae.q74_cnaeanalitica
                   left  join cnae          on cnae.q71_sequencial            = cnaeanalitica.q72_cnae
                   left  join issgruposervicoativid on issgruposervicoativid.q127_ativid = ativid.q03_ativ
                   left  join issqn.issgruposervico on issgruposervico.q126_sequencial = issgruposervicoativid.q127_issgruposerviso
                   left  join configuracoes.db_estruturavalor on db_estruturavalor.db121_sequencial = issgruposervico.q126_db_estruturavalor
                   left  join configuracoes.db_estrutura on db_estruturavalor.db121_db_estrutura = db_estrutura.db77_codestrut
              where q07_inscr = $inscricao ";
	  $pesquisaLocalizada = true;
  } else if ($solicitacao == "Socios") {
     $clissbase = new cl_issbase;
     if ($oDBConfig->db21_codcli == 19985) {
       $sql = $clissbase->sqlinscricoes_socios($inscricao,0,"cgmsocio.z01_numcgm#cgmsocio.z01_cgccpf,cgmsocio.z01_nome#cgmsocio.z01_ender#cgmsocio.z01_numero#cgmsocio.z01_compl#cgmsocio.z01_bairro#cgmsocio.z01_munic#cgmsocio.z01_uf#q95_perc#q95_datainc");
     } else {
       $sql = $clissbase->sqlinscricoes_socios($inscricao,0,"cgmsocio.z01_numcgm#cgmsocio.z01_cgccpf,cgmsocio.z01_nome#cgmsocio.z01_ender#cgmsocio.z01_numero#cgmsocio.z01_compl#cgmsocio.z01_bairro#cgmsocio.z01_munic#cgmsocio.z01_uf#q95_perc");
     }
	   $pesquisaLocalizada = true;
  } else if ($solicitacao == "Simples") {
     $clisscadsimples = new cl_isscadsimples;
     $sql = $clisscadsimples->sql_query_baixa(null,"q38_sequencial, q38_dtinicial, q38_categoria, case when q38_categoria = 1 then 'Micro Empresa' when q38_categoria = 2 then 'Empresa de pequeno porte' when q38_categoria = 3 then 'MEI'end as categoria, q39_dtbaixa, q39_issmotivobaixa, q39_obs",null," q38_inscr = $inscricao");
	   $pesquisaLocalizada = true;
  } else if ($solicitacao == "Calculo###") {
    echo "Calculo ainda não implementado";
  	$sql = "";
  	$pesquisaLocalizada = true;
  } else if ($solicitacao == "TiposDeCalculo") {
    //echo "TiposDeCalculo";
   	$sql = "
          select distinct q81_codigo, q81_abrev,q81_qiexe,q81_qfexe,q81_valexe 
            from tabativ
           left  join tabativbaixa on q11_inscr   = q07_inscr 
                                  and q11_seq     = q07_seq 
           inner join ativtipo     on q80_ativ    = q07_ativ
           inner join tipcalc      on q80_tipcal  = q81_codigo
           inner join cadcalc      on q81_cadcalc = q85_codigo
          where (q11_inscr is null and q11_seq is null) and q07_inscr = $inscricao ";
  	$pesquisaLocalizada = true;

  } else if ($solicitacao == "Quantidades") {
     //echo "Quantidades";
     $sql = " select q30_anousu, q30_quant, q30_area, q30_mult 
                from issquant
               where q30_inscr = $inscricao ";
     $pesquisaLocalizada = true;

  } else if ($solicitacao == "Fixado") {
     //echo "Fixado";
     $sql = " select * from varfix where q33_inscr = $inscricao	";
     $pesquisaLocalizada = true;

  } else if ($solicitacao == "Observacoes") {
      //echo "Observacoes";
     	$sql = " select q02_obs
           		  from issbase
          		  where q02_inscr = $inscricao	";

	$pesquisaLocalizada = true;

  } else if ($solicitacao == "TextoAlvara") {
    //echo "TextoAlvara";
	  $sql = " select q02_memo
         		  from issbase
       		  where q02_inscr = $inscricao	";

	$pesquisaLocalizada = true;

  } else if ($solicitacao == "Ocorrencias") {

    $campos = "case when histocorrencia.ar23_tipo = '1' then 'Manual' else 'Automatica' end as Tipo, ";
    $campos .= "histocorrencia.ar23_descricao, ";
    $campos .= "histocorrencia.ar23_ocorrencia, ";
    $campos .= "histocorrencia.ar23_data, ";
    $campos .= "histocorrencia.ar23_hora, ";
    $campos .= "db_usuarios.login, ";
    $campos .= "db_modulos.nome_modulo ";
    $clhistocorrenciainscr = new cl_histocorrenciainscr;
    $sql = $clhistocorrenciainscr->sql_query("", "$campos", "histocorrencia.ar23_data", "histocorrenciainscr.ar26_inscr = " . $inscricao . " and ar23_instit = ". db_getsession("DB_instit"));
    
	  $pesquisaLocalizada = true;

  } else if ($solicitacao == "Manual") {

    //echo "TextoAlvara";

	$sql = " select q01_manual
             from isscalc
      		  where q01_inscr = $inscricao and q01_anousu = " . db_getsession("DB_anousu") . "
        	  limit 1 ";
    $result = db_query($sql);
    if(pg_numrows($result) > 0){
      db_fieldsmemory($result,0);
    }

?>

  <tr align="center">
    <td>
   	 <textarea class="db_area" rows="13" cols="90"><?=@$q01_manual?></textarea>
    </td>
  </tr>
  
<?

  } else if ($solicitacao == "Baixa") {
    $sql    = "select * from tabativbaixa where q11_inscr=$inscricao";
    $result = db_query($sql);
    $iLinhas = pg_numrows($result);
    
    if($iLinhas > 0) {

      echo "<table>";
      for ($i = 0; $i < $iLinhas; $i++) {

        db_fieldsmemory($result, $i);      

        $tipo = "Normal";
        if ($q11_oficio=='t'){
          $tipo = "Oficio";
        }
        echo "<tr>";
        echo "  <td class='bold' nowrap='nowrap'>Processo da Baixa: </td>";
        echo "  <td style='background-color:#FFF' class='field-size5'>{$q11_processo}</td>";
        echo "  <td class='bold'>Tipo:</td>";
        echo "  <td style='background-color:#FFF;'>{$tipo}</td>";
        echo "</tr>";
        echo "<tr>";
        echo "  <td class='bold'>Observação:</td>";
        echo "  <td style='background-color:#FFF' colspan='3'>{$q11_obs}</td>";
        echo "</tr>";

      }
      echo "</table>";
      db_fieldsmemory($result,0);
    }

  } else if ($solicitacao == 'OptanteSimples') {
  	
  	/*
	 * SQL que busca as informações sobre a opção de simples da inscrição
	 */
    $sql  = "SELECT isscadsimples.q38_sequencial,                                                                          ";
    $sql .= "       isscadsimples.q38_dtinicial,                                                                           ";
  	$sql .= "       CASE                                                                                                   ";
  	$sql .= "         WHEN isscadsimples.q38_categoria = 1 THEN 'Micro Empresa'                                            ";
	  $sql .= "		      WHEN isscadsimples.q38_categoria = 2 THEN 'Empresa de pequeno porte'                                 ";
	  $sql .= "		      WHEN isscadsimples.q38_categoria = 3 THEN 'MEI'                                                      ";
  	$sql .= "       END AS q38_categoria,                                                                                  ";
    $sql .= "       isscadsimplesbaixa.q39_dtbaixa,                                                                        ";
    $sql .= "       issmotivobaixa.q42_descr,                                                                              ";
    $sql .= "       isscadsimplesbaixa.q39_obs                                                                             ";
    $sql .= "  FROM isscadsimples                                                                                          ";
    $sql .= "       LEFT JOIN isscadsimplesbaixa ON isscadsimples.q38_sequencial  = isscadsimplesbaixa.q39_isscadsimples   ";
    $sql .= "       LEFT JOIN issmotivobaixa     ON issmotivobaixa.q42_sequencial = isscadsimplesbaixa.q39_issmotivobaixa  ";
    $sql .= " WHERE isscadsimples.q38_inscr = {$inscricao}                                                                 ";
	  $pesquisaLocalizada = true;
	  
  	/*
  	 * analiza se a inscrição informada é optante do simples ou não
  	 */
  	$result = db_query($sql) or die($sql);
    if (pg_num_rows($result) == 0) {
      echo '<p style="text-align:center; margin-top:40px;">Sem lançamentos de optante do simples para essa inscrição</p>';
    }
      
  }

  if ($pesquisaLocalizada) {

    $result = db_query($sql) or die($sql);
    if(pg_numrows($result) == 0){
      echo "<br><center><b>Sem registros a exibir!</b></center>";
    }else{
     	db_lovrot($sql,15,"","","");
    }
  }

?>

</body>

</html>