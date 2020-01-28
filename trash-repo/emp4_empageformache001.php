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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
require ("std/db_stdClass.php");
require ("libs/db_app.utils.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_libtxt.php");
include ("classes/db_empagetipo_classe.php");
include ("classes/db_empage_classe.php");
include ("classes/db_empagemov_classe.php");
include ("classes/db_empagegera_classe.php");
include ("classes/db_empageconf_classe.php");
include ("classes/db_empageconfche_classe.php");
include ("classes/db_empageconfgera_classe.php");
include ("classes/db_conplanoconta_classe.php");
include ("classes/db_empagepag_classe.php");
include ("classes/db_pagordem_classe.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_cfautent_classe.php");
include ("classes/db_db_bancos_classe.php");
include ("classes/db_caiparametro_classe.php");
include("libs/db_libcaixa.php");
$clempage = new cl_empage;
$clconplanoconta = new cl_conplanoconta;
$clempagetipo = new cl_empagetipo;
$clempagemov = new cl_empagemov;
$clempagegera = new cl_empagegera;
$clempageconf = new cl_empageconf;
$clempageconfche = new cl_empageconfche;
$clempageconfgera = new cl_empageconfgera;
$clempagepag = new cl_empagepag;
$clpagordem = new cl_pagordem;
$cldb_config = new cl_db_config;
$clcfautent = new cl_cfautent;
$cldb_bancos = new cl_db_bancos;
$clcaiparametro = new cl_caiparametro;

db_postmemory($HTTP_POST_VARS);

$db_opcao  = 1;
$db_botao  = false;
$lLiberado = true;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

// 
$verifica_cheques_duplicados = false; // permite duplicação de cheques, sem verificação de duplicidade
$result00 = $clcaiparametro->sql_record($clcaiparametro->sql_query_file(db_getsession("DB_instit")));
if ($clcaiparametro->numrows > 0 ){
   db_fieldsmemory($result00,0);
   if (isset($k29_chqduplicado) && $k29_chqduplicado=='f'){
        $verifica_cheques_duplicados = true;  // configura a variavel que ira verificar se ja existe cheque lançado com mesmo numero
   }  
}
//rotina que pega o nome do prefeito
$result00 = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), "pref as prefeito,munic as municipio"));
db_fieldsmemory($result00, 0);

//rotina que pega o nome do tesoureiro
$resu = $clcfautent->sql_record($clcfautent->sql_query_file(null, "k11_tipoimpcheque,k11_portaimpcheque,k11_tesoureiro as tesoureiro","","k11_ipterm='".db_getsession("DB_ip")."' and k11_instit=".db_getsession("DB_instit")));
if($clcfautent->numrows > 0) {
  db_fieldsmemory($resu, 0);
  if(trim($tesoureiro) == ""){
    $mensagem_mostra = "Preencha o Nome/cargo no cadastro de autenticadoras.";
    $lLiberado = false;
  }
} else {
  $mensagem_mostra = "Cadastre seu IP como autenticadora.";
  $lLiberado       = false;
}

$iTipoControleRetencaoMesAnterior = 0;
$aParametrosEmpenho = db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")));
if (count($aParametrosEmpenho) > 0) {
  $iTipoControleRetencaoMesAnterior = $aParametrosEmpenho[0]->e30_retencaomesanterior;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
<style>
.comcheque{
    background-color: #d1f07c;
}
.slip{
    background-color: #CCFFCC;
}
.comchequemarcado{
    background-color: #EFEFEF;
}
.slipmarcado{
    background-color: #EFEFEF;
}
.normalmarcado{ background-color:#EFEFEF}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table height='' width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td></td></tr>
</table>
      <?
      $clrotulo = new rotulocampo;
      $clrotulo->label("e80_data");

     // Sempre que ja existir agenda entra nesta opcao  
     include ("forms/db_frmempageformache.php");
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_cria(campo,valor){
  obj=document.createElement('input');
  obj.setAttribute('name',campo);
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',valor);
  document.form1.appendChild(obj);
}	   
function js_verso(ver){
  retorna = false;
  <?
  if(isset($imprimirverso)){
    echo "retorna = confirm('Emitir o verso do cheque?');\n";
  }
  if(empty($atualizar) && empty($prever)){
      $e83_codtipo = @$pritipo;
  }
  ?>
  if(retorna == true){
    obj=document.createElement('input');
    obj.setAttribute('name','emiteverso');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value',ver);
    document.form1.appendChild(obj);
    document.form1.submit();
  }else{
    location.href='emp4_empageformache001.php?dtin_dia=<?=$dtin_dia?>&dtin_mes=<?=$dtin_mes?>&dtin_ano=<?=$dtin_ano?>&e83_codtipo=<?=$e83_codtipo?>';
  }
}	 
</script>
<script>
<?
if (!$lLiberado) {

  echo "\$('processar').disabled = true;\n";
  echo "\$('prever').disabled = true;\n";
  echo "\$('pesquisar').disabled = true;\n";
  echo "alert('{$mensagem_mostra}');\n";
    
}
?>
</script>