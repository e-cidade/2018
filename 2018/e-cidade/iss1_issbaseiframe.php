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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgm_classe.php");
include("classes/db_issbase_classe.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo   = new rotulocampo;
$clcgm      = new cl_cgm;
$clcriaabas = new cl_criaabas;
$clissbase  = new cl_issbase;

// verifica se este cgm tem cnpj



if(isset($q02_numcgm)){
  $result01=$clcgm->sql_record($clcgm->sql_query_file($q02_numcgm,"z01_ident,z01_munic,z01_nome,z01_incest,z01_cgccpf,z01_cep,z01_ender,z01_bairro,z01_compl as q02_compl,z01_numero as q02_numero,z01_cxpostal as q02_cxpost"));
  if($clcgm->numrows!=1){
	db_redireciona('iss1_issbase004.php?invalido=true');
	exit;
  }else{
	db_fieldsmemory($result01,0);
	if($z01_cep==""){
	  db_redireciona('iss1_issbase004.php?cep=true');
	  exit;
	}
	if($z01_cgccpf==""){
	  db_redireciona('iss1_issbase004.php?cgccpf=true');
   	  exit;
	}else{
	  //para verificar se tem permissão de alterar alvara com cgm com cnpj
      db_verificapermissao($z01_cgccpf,"incluir");

	}
  }
  $db_opcao=1;
}else if(isset($alterar)){
  //para verificar se tem permissão de alterar alvara com cgm com cnpj
  $sqlCpnj = "select z01_cgccpf from cgm inner join issbase on z01_numcgm = q02_numcgm where q02_inscr=$q02_inscr";
  $rsCnpj= db_query($sqlCpnj);
  db_fieldsmemory($rsCnpj,0);
  db_verificapermissao($z01_cgccpf,"alterar");

      $result01=$clissbase->sql_record($clissbase->sql_query($q02_inscr,'z01_nome,q02_numcgm'));
      if($clissbase->numrows<1){
        db_redireciona('iss1_issbase005.php?invalido=true');
        exit;
      }
      db_fieldsmemory($result01,0);
      $db_opcao=2;
}else if(isset($excluir)){
      $result01=$clissbase->sql_record($clissbase->sql_query($q02_inscr,'z01_nome,q02_numcgm'));
      if($clissbase->numrows<1){
	db_redireciona('iss1_issbase006.php?invalido=true');
	exit;
      }
      db_fieldsmemory($result01,0);
      $db_opcao=3;
}
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
	#caracteristicas tr td, #caracteristicas tr td input{ width: 180px;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
     <td>
     <?
       $clcriaabas->identifica = array( "issbase"    => "Inscrição",
                                        "observacao" => "Observações",
                                        "atividades" => "Atividades",
       																  "socios"     => "Sócios",
       																  "calculo"    => "Cálculo",
                                        "documentos" => "Documentos",
                                        "liberacao"  => "Liberação",
																				"caracteristicas" => "Informações Complementares"
                                       );//nome do iframe e o label

       $clcriaabas->title      = array( "issbase"    => "Manutenção de inscrição",
                                        "observacao" => "Manutenção de Observações",
       																  "atividades" => "Manutenção de atividades",
       													        "socios"     => "Sócios cadastrados",
       													        "calculo"    => "Cálculo",
                                        "documentos" => "Documentos",
                                        "liberacao"  => "Liberação",
                                        "caracteristicas" => "Informações Complementares"
                                       );//nome do iframe e o label

       // $clcriaabas->corfundo   = array("atividades"=>"green");// nome do iframe e a cor do iframe
       // $clcriaabas->cortexto   = array("atividades"=>"yellow");// nome do iframe e a cor do iframe
       // $clcriaabas->src = array("socios"=>"cad_iptubase0021.php");  //nome do iframe e SRC
       $clcriaabas->cria_abas();
     ?>
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($db_opcao==1){
  echo "

	 <script>
	   function js_src(){
      iframe_issbase.location.href='iss1_issbase014.php?q02_numcgm=$q02_numcgm';\n
	    document.formaba.observacao.disabled=true;
      document.formaba.atividades.disabled=true;
	    document.formaba.socios.disabled=true;
	    document.formaba.calculo.disabled=true;
      document.formaba.documentos.disabled=true;
      document.formaba.liberacao.disabled=true;
      document.formaba.caracteristicas.disabled=true;
	   }
	   js_src();
         </script>
       ";
}else if($db_opcao==2){
  $z01_nome = addslashes($z01_nome);

  /** Extensao : Inicio [integracao-icad] */
  echo "
         <script>
     function js_src(){
      document.formaba.observacao.disabled=false;
      document.formaba.atividades.disabled=false;
      document.formaba.socios.disabled=false;
      document.formaba.calculo.disabled=false;
      document.formaba.documentos.disabled=false;
      document.formaba.liberacao.disabled=false;
      document.formaba.caracteristicas.disabled=false;
      iframe_issbase.location.href='iss1_issbase015.php?chavepesquisa=$q02_inscr';\n
      iframe_observacao.location.href='iss1_issbase017.php?chavepesquisa=$q02_inscr&opcao=2';\n
      iframe_atividades.location.href=\"iss1_tabativ004.php?z01_nome=$z01_nome&q07_inscr=$q02_inscr\";\n
      iframe_socios.location.href=\"iss1_socios004.php?z01_nome_inscr=$z01_nome&q07_inscr=$q02_inscr&z01_nome=$z01_nome&q95_cgmpri=$q02_numcgm\";\n
      iframe_calculo.location.href=\"iss1_isscalc004.php?q07_inscr=$q02_inscr&z01_nome=$z01_nome\";\n
      iframe_documentos.location.href=\"iss4_docalvaratab_001.php?aba=1&q123_inscr=$q02_inscr\";\n
      iframe_liberacao.location.href=\"iss4_liberacaoalvara_001.php?aba=1&q123_inscr=$q02_inscr\";\n
      iframe_caracteristicas.location.href=\"iss4_issbasecaracteristicas001.php?q123_inscr=$q02_inscr\";\n

     }
     js_src();
         </script>
       ";

  /** Extensao : Fim [integracao-icad] */

}else if($db_opcao==3){
  echo "
         <script>
	   function js_src(){
	   	document.formaba.observacao.disabled=false;
	    document.formaba.atividades.disabled=false;
	    document.formaba.socios.disabled=false;
      iframe_issbase.location.href='iss1_issbase016.php?chavepesquisa=$q02_inscr';\n
      iframe_observacao.location.href='iss1_issbase017.php?chavepesquisa=$q02_inscr&opcao=3';\n
      iframe_atividades.location.href=\"iss1_tabativ004.php?db_opcaoal=3&z01_nome=$z01_nome&q07_inscr=$q02_inscr\";\n
	    iframe_socios.location.href=\"iss1_socios004.php?db_opcaoal=3&q07_inscr=$q02_inscr&z01_nome=$z01_nome&q95_cgmpri=$q02_numcgm\";\n
	    iframe_documentos.location.href=\"iss4_docalvaratab_001.php?aba=1&q123_inscr=$q02_inscr\";\n
	    iframe_liberacao.location.href=\"iss4_liberacaoalvara_001.php?aba=1&q123_inscr=$q02_inscr\";\n
	    iframe_caracteristicas.location.href=\"iss4_issbasecaracteristicas003.php?aba=1&q123_inscr=$q02_inscr\";\n
	   }
	   js_src();
         </script>
       ";
}
function db_verificapermissao($cgccpf,$tipo){
  $tam = strlen($cgccpf);
  if($tam == 14){
    //verifica parametro
    $sqlParissqn = "select q60_tipopermalvara from parissqn";
    $rsParissqn = db_query($sqlParissqn);
    $q60_tipopermalvara = pg_result($rsParissqn,"q60_tipopermalvara");
   // db_fieldsmemory($rsParissqn,0);
    if($q60_tipopermalvara==1){
      //verifica se tem permissão para alterar alvara com CNPJ
      $temPermis = db_permissaomenu(db_getsession("DB_anousu"), db_getsession("DB_modulo"),608573);
      if($temPermis=="false"){
        if($tipo=="alterar"){
          db_redireciona('iss1_issbase005.php?permissao=true');
        }elseif($tipo=="incluir"){
          db_redireciona('iss1_issbase004.php?permissao=true');
        }
      }
    }
  }
}
?>