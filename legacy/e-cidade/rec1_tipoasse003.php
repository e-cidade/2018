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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_tipoasse_classe.php"));
include(modification("classes/db_portariatipo_classe.php"));
include(modification("classes/db_portariaenvolv_classe.php"));
include(modification("classes/db_portariatipoato_classe.php"));
include(modification("classes/db_portariaproced_classe.php"));
include(modification("classes/db_portariatipodocindividual_classe.php"));
include(modification("classes/db_portariatipodoccoletiva_classe.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cltipoasse        = new cl_tipoasse;
$clportariatipo    = new cl_portariatipo;
$clportariaenvolv  = new cl_portariaenvolv;
$clportariatipoato = new cl_portariatipoato;
$clportariaproced  = new cl_portariaproced;
$clrotulo          = new rotulocampo;

$clportariatipodocindividual = new cl_portariatipodocindividual;
$clportariatipodoccoletiva   = new cl_portariatipodoccoletiva;


$db_botao = false;
$db_opcao = 33;
$sqlerro  = false;

if(isset($excluir)){

  db_inicio_transacao();
  $db_opcao = 3;

  /**
   * Verica se o tipo de assentamento possui algum assentamento vinculado,
   * se possuir nao permite a exclusao
   */
  $oDaoAssenta = new cl_assenta();
  $sSqlAssenta = $oDaoAssenta->sql_query_file(null, "h16_codigo", null, "h16_assent = {$h12_codigo}");
  $rsAssenta   = db_query($sSqlAssenta);

  if (pg_num_rows($rsAssenta) > 0) {

    db_msgbox("Tipo de assentamento, já possui um assentamento vinculado. Não é possível realizar a exclusão.");
    db_redireciona("");
  }
  

  if (isset($h30_sequencial) && trim($h30_sequencial)!=""){
           
       $rsConsultaModIndividual = $clportariatipodocindividual->sql_record($clportariatipodocindividual->sql_query(null,"h37_sequencial",null," h37_portariatipo = {$h30_sequencial}"));
     if($clportariatipodocindividual->numrows > 0){
       $clportariatipodocindividual->excluir(null," h37_portariatipo = {$h30_sequencial} ");
     }
       
       $rsConsultaModColetiva   = $clportariatipodoccoletiva->sql_record($clportariatipodoccoletiva->sql_query(null,"h38_sequencial",null, "h38_portariatipo = {$h30_sequencial} "));         
       if($clportariatipodoccoletiva->numrows > 0){
       $clportariatipodoccoletiva->excluir(null," h38_portariatipo = {$h30_sequencial} ");
     }    
    
       $clportariatipo->excluir($h30_sequencial);

       if ($clportariatipo->erro_status == 0){
            $sqlerro  = true;
       }
  }       
  
  if (!$sqlerro) {                                                                          
    
    $oDaoTipoassedb_cadattdinamico = new cl_tipoassedb_cadattdinamico();
    $oDaoTipoassedb_cadattdinamico->excluir(null,null, "h79_tipoasse = {$h12_codigo}");

    if ($oDaoTipoassedb_cadattdinamico->erro_sql == '0') {
      $sqlerro = true;
    }

    if(!$sqlerro) {
      
      $db_cadattdinamicoatributos = new cl_db_cadattdinamicoatributos();
      
      if(isset($h79_db_cadattdinamico) && !empty($h79_db_cadattdinamico)) {

        $db_cadattdinamicoatributos->excluir(null, " db109_db_cadattdinamico = {$h79_db_cadattdinamico}");

        if($db_cadattdinamicoatributos->erro_sql == '0') {
          $sqlerro = true;
        }

        if(!$sqlerro) {

          $db_cadattdinamico = new cl_db_cadattdinamico();
          $db_cadattdinamico->excluir($h79_db_cadattdinamico);

          if($db_cadattdinamico->erro_sql == '0') {
            $sqlerro = true;
          }
        }
      }
    }
  }

  if (!$sqlerro) {

    $cltipoasse->excluir($h12_codigo);
    if ($cltipoasse->erro_status == 0){
         $sqlerro  = true;
    }
  }
  db_fim_transacao($sqlerro);

} else if(isset($chavepesquisa)){

  $db_opcao = 3;
  $result = $cltipoasse->sql_record($cltipoasse->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);

  $res_portariatipo = $clportariatipo->sql_record($clportariatipo->sql_query_func(null,"h30_sequencial,h42_descr","h42_sequencial","h30_tipoasse = ".@$h12_codigo));
  if ($clportariatipo->numrows > 0){
       db_fieldsmemory($res_portariatipo,0);
  }

  // Consulta Modelo de Portaria Individual
  if ( !empty($h30_sequencial) ) {

    $rsConsultaModIndividual = $clportariatipodocindividual->sql_record($clportariatipodocindividual->sql_query(null,"h37_modportariaindividual, db63_nomerelatorio as descrModIndividual",null," h37_portariatipo = {$h30_sequencial}"));

    if ($clportariatipodocindividual->numrows > 0) {

      db_fieldsmemory($rsConsultaModIndividual,0);
      $descrModIndividual = $descrmodindividual;    
    }
  }
  
  // Consulta Modelo de Portaria Coletiva
  if ( !empty($h30_sequencial) ) {
    
    $rsConsultaModColetiva   = $clportariatipodoccoletiva->sql_record($clportariatipodoccoletiva->sql_query(null,"h38_modportariacoletiva, db63_nomerelatorio as descrModColetiva",null, "h38_portariatipo = {$h30_sequencial} "));
    
    if ($clportariatipodoccoletiva->numrows > 0) {

      db_fieldsmemory($rsConsultaModColetiva,0);
      $descrModColetiva = $descrmodcoletiva; 
    }
  }

  $oDaoTipoassedb_cadattdinamico = new cl_tipoassedb_cadattdinamico();
  $sSqlTipoAsseCadDinamico       = $oDaoTipoassedb_cadattdinamico->sql_query(null,null, "h79_db_cadattdinamico", null, "h79_tipoasse = {$h12_codigo}");

  $rsTipoAsseCadDinamico         = db_query($sSqlTipoAsseCadDinamico);

  if (pg_num_rows($rsTipoAsseCadDinamico) > 0) {

    db_fieldsmemory($rsTipoAsseCadDinamico, 0);
  }

  /**
   * Verica se o tipo de assentamento possui algum assentamento vinculado,
   * se possuir criamos a variavel $lAssentamentoVinculado, para avisar o usuario no momento
   * que ele for efetuar a manutenção de Campos dinamicos
   */
  $oDaoAssenta = new cl_assenta();
  $sSqlAssenta = $oDaoAssenta->sql_query_file(null, "h16_codigo", null, "h16_assent = {$h12_codigo}");
  $rsAssenta   = db_query($sSqlAssenta);

  if (pg_num_rows($rsAssenta) > 0) {
    $lAssentamentoVinculado = true;
  }
  
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include(modification("forms/db_frmtipoasse.php"));
      ?>
      </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($cltipoasse->erro_status=="0"){
    $cltipoasse->erro(true,false);
  }else{
    $cltipoasse->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>