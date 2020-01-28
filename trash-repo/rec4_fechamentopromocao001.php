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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_rhpromocao_classe.php");
require_once("classes/db_rhavaliacao_classe.php");
require_once("dbforms/db_funcoes.php");


$clrotulo      = new rotulocampo;
$clrotulo->label("z01_nome");

$oDaoRHPromocao  = new cl_rhpromocao;
$oDaoRHPromocao->rotulo->label();
$oDaoRHPromocao->rotulo->tlabel();

db_app::load('scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js');
db_app::load('estilos.css');

$iCodigoPromocao = null;

$oGet	  = db_utils::postmemory($HTTP_GET_VARS);

?>
<style>
fieldset.form {
	margin:30px auto 5px auto;
	width: 540px;
}
</style>
</head>

<body bgcolor=#CCCCCC>

<center>
	<form name="form1" id="form1">
		<fieldset class="form">
			<legend>
				<strong>Fechamento de Interstício:</strong>
			</legend>
			
			<table align="center" width="540">
				<tr>
          <td nowrap title="<?=@$Th72_regist?>">
          <?
            db_ancora("<b>Matrícula:</b>","js_pesquisa(true);",1);
          ?>
          </td>
          <td> 
          <?
            db_input('h72_regist',10,$Ih72_regist,true,'text',1," onchange='js_pesquisa(false);'");
            db_input('z01_nome',50,$Iz01_nome,true,'text',3,'');
          ?>
          </td>
				</tr>
			</table>

		</fieldset>
		
		<input type="button" name="btnProcessar" id="btProcessar" value="Processar" onclick="js_processar()" /> 
	
	</form>
</center>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>
function js_pesquisa(lMostra){
  
  if(lMostra == true) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_rhpromocao',
                        'func_rhpromocao.php?funcao_js=parent.js_preenchepesquisa1|h72_regist|z01_nome&lAtivo=1',
                        'Pesquisa',
                        true);
  } else {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_rhpromocao',
                        'func_rhpromocao.php?pesquisa_chave='+document.form1.h72_regist.value+'&funcao_js=parent.js_preenchepesquisa&lAtivo=1',
                        'Pesquisa',
                        false);
  }
}

function js_preenchepesquisa1(chave1, chave2) {

  document.form1.h72_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpromocao.hide();  
}

function js_preenchepesquisa(chave, erro) {

  if (erro == true) {
    document.form1.h72_regist.value = "";
    document.form1.z01_nome.value   = chave;
  } else {
  
    document.form1.z01_nome.value   = chave;  
  }
  
}


function js_processar() {

  var iMatricula = $F('h72_regist');
  
  if(iMatricula == '') {
    alert('Selecione a Metrícula do Servidor');
    return false;     
  } else {
  
    location.href = 'rec4_fechamentopromocao002.php?iCodigoMatricula=' + iMatricula;
  }
  
}
</script>

</body>
</html