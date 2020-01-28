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
include("dbforms/db_funcoes.php");
include("classes/db_db_config_classe.php");
include("classes/db_db_textos_classe.php");

$cldbconfig = new cl_db_config;
$cldbtextos = new cl_db_textos;

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

if (isset($alterar)){
	if ((isset($instituicao))){
    $sqlerro  = false;
    db_inicio_transacao();
    $cldbtextos->id_instit     = $instituicao;
    $cldbtextos->descrtexto    = $descrtexto;
    $cldbtextos->conteudotexto = $conteudotexto;
    $cldbtextos->alterar($instituicao, $descrtexto);
    if($cldbtextos->erro_status == "0") {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
		db_msgbox($cldbtextos->erro_msg);
	}else{
		db_msgbox(_M('tributario.projetos.con1_manutexto.alteracao_abortada'));
	}
}else if(isset($incluir)){
	if ((isset($instituicao))&&($conteudotexto!="")&&($descrtexto!="")){
    $sqlerro  = false;
    db_inicio_transacao();
    $cldbtextos->id_instit     = $instituicao;
    $cldbtextos->descrtexto    = $descrtexto;
    $cldbtextos->conteudotexto = $conteudotexto;
    $cldbtextos->incluir($instituicao, $descrtexto);
    if($cldbtextos->erro_status == "0") {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
		db_msgbox($cldbtextos->erro_msg);
	}else{
		db_msgbox(_M('tributario.projetos.con1_manutexto.inclusao_abortada'));
	}
}
if (isset($db_opcao)) {
	$sql_db_opcao = ";
	select id_instit, descrtexto, conteudotexto
	from db_textos
	where id_instit  = ".db_getsession("DB_instit")."
    and descrtexto = '$db_opcao'
	";//
	$result_db_opcao = db_query($sql_db_opcao);
	$num_db_opcao = pg_numrows($result_db_opcao);
	if ($num_db_opcao!=0){
		$habilita_alteracao = true;
	}else{
		$habilita_alteracao = false;
	}
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >

<?
if (isset($db_opcao)){// parametro db_opcao responsavel por localizar qual o texto a ser trabalhado
?>
<form class="container" action="" name="form1" method="POST">
  <fieldset>
	<legend>Manutenção de textos</legend>
	<table class="form-container">
	  <tr>
	    <td>Instituição:
	      <?
	        $instituicao=db_getsession('DB_instit');
		    db_input("instituicao",30,0,true,"hidden",3);		
		    //---Pesquisao nome da instituição atual--//
		    $result_instit=$cldbconfig->sql_record($cldbconfig->sql_query_file(null,'nomeinst',null,"codigo=$instituicao"));
		    db_fieldsmemory($result_instit,0);	
	        //---Mostra o nome da instituição---// 
		    db_input("nomeinst",60,0,true,"text",3);	
	      ?>	
		</td>
	  </tr>
	  <tr>
	    <td>Descrição:
		<input type="hidden" name="descrtexto" value="<?=@$db_opcao?>" id="descrtexto">
		<input type="text" name="descrtexto2" id="descrtexto2" maxlength="20" size="22" value="<?=@$db_opcao?>" disabled>
		</td>
	  </tr>
	  <tr>
	    <td colspan="2">
	      <fieldset class="separator">
	       	<legend>Texto</legend>    
			<textarea name="conteudotexto" cols="140" rows="16" id="conteudotexto"><?=@$codigoclass?><?=@pg_result($result_db_opcao,0,"conteudotexto")?></textarea>
		  </fieldset>
		</td>
	  </tr>
    </table>
  </fieldset>
  <?
    if($habilita_alteracao){
  ?>
  <input type="submit" name="alterar" value="Alterar" >&nbsp;
  <?
    }else if(!$habilita_alteracao){
  ?>
  <input type="submit" name="incluir" value="Incluir" >&nbsp;
  <?
    }
  ?>
</form>
<?
  }else{ // se caiu aqui é porque o menu que chama esta pagina nao contem o parametro db_opcao
?>
  <table>
    <tr>
      <td align="center">
        <B>&nbsp;Esta página deve ser chamada através de um menu válido.</B>
	  </td>
    </tr>
  </table>
<?
}
?>


<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

$("nomeinst").addClassName("field-size9");
$("descrtexto2").addClassName("field-size4");

</script>