<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include(modification("classes/db_pcmater_classe.php"));
include(modification("classes/db_pcmaterele_classe.php"));
include(modification("classes/db_pcgrupo_classe.php"));
include(modification("classes/db_pcsubgrupo_classe.php"));
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcmater    = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo    = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$clpcgrupo->rotulo->label();
$clpcsubgrupo->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc07_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
if(isset($o56_codele) and trim($o56_codele) != ''){
	$chave_pc07_codele = $o56_codele;
	
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_reload(){
  document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form1.chave_pc01_descrmater.focus();'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
    <table width="35%" border="0" align="center" cellspacing="3">
      <form name="form1" method="post" action="" >
      <tr> 
        <td width="4%" align="right" nowrap title="<?=$Tpc01_codmater?>"><?=$Lpc01_codmater?></td>
        <td width="46%" align="left" nowrap><?  db_input("pc01_codmater",6,$Ipc01_codmater,true,"text",4,"","chave_pc01_codmater"); ?> </td>
        <td width="4%" align="right" nowrap title="<?=$Tpc01_descrmater?>"> <?=$Lpc01_descrmater?></td>
        <td width="46%" align="left" nowrap><? db_input("pc01_descrmater",50,$Ipc01_descrmater,true,"text",4,"","chave_pc01_descrmater"); ?></td>
      </tr>
      <tr> 
        <td width="4%" align="right" nowrap title="<?=$Tpc07_codele?>"><?=$Lpc07_codele?></td>
        <td width="46%" align="left" nowrap><?  db_input("pc07_codele",6,$Ipc07_codele,true,"text",4,"","chave_pc07_codele"); ?> </td>
        <td width="4%" align="right" nowrap title="<?=$To56_descr?>"> <?=$Lo56_descr?></td>
        <td width="46%" align="left" nowrap><? db_input("o56_descr",50,$Io56_descr,true,"text",4,"","chave_o56_descr"); ?></td>
      </tr>
      <tr> 
        <td width="4%" align="right" nowrap title="<?=$To56_elemento?>"> <?=$Lo56_elemento?></td>
        <td width="46%" align="left" nowrap ><? db_input("o56_elemento",15,$Io56_elemento,true,"text",4,"","chave_o56_elemento"); ?></td>
        <td width="4%" align="right" nowrap title="Selecionar todos, ativos ou inativos"><b>Seleção por:</b></td>
        <td width="46%" align="left" nowrap>
        <?
        if(!isset($opcao)){
	      $opcao = "f";
        }
        if(!isset($opcao_bloq)){
        	$opcao_bloq = 1;
        }
        $arr_opcao = array("i"=>"Todos","f"=>"Ativos","t"=>"Inativos");
        db_select('opcao',$arr_opcao,true,$opcao_bloq,"onchange='js_reload();'"); 
        ?>
        </td>
      </tr>
      <tr>
        <td width="4%" align="right" nowrap title="<?=$Tpc03_codgrupo?>"><?=$Lpc03_codgrupo?></td>
        <td width="46%" align="left" nowrap>
	<?
	   $res_pcgrupo = $clpcgrupo->sql_record($clpcgrupo->sql_query_file(null,"pc03_codgrupo,pc03_descrgrupo","pc03_descrgrupo","pc03_ativo='t'"));
           db_selectrecord("pc03_codgrupo",$res_pcgrupo,true,4,"","chave_pc03_codgrupo","","0-Todos","js_reload()");
	?> 
	</td>
        <td width="4%" align="right" nowrap title="<?=$Tpc04_codsubgrupo?>"><?=$Lpc04_codsubgrupo?></td>
        <td width="46%" align="left" nowrap>
	<?
  if (!isset($chave_pc03_codgrupo)) {
    $chave_pc03_codgrupo = '0';
  }
	   $res_pcsubgrupo = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query_file(null,"pc04_codsubgrupo,  pc04_descrsubgrupo","pc04_descrsubgrupo","pc04_ativo='t' and pc04_codgrupo = ".@$chave_pc03_codgrupo));
           db_selectrecord("pc04_codsubgrupo",$res_pcsubgrupo,true,4,"","chave_pc04_codsubgrupo","","0-Todos","js_reload()");
	?> 
	</td>
      </tr>
      <tr> 
        <td colspan="4" align="center"> 
          <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
          <input name="limpar" type="reset" id="limpar" value="Limpar" >
          <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcmater.hide();">
        </td>
      </tr>
      </form>
    </table>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
       
       //echo($clpcmaterele->sql_query_file(null,null,"pc07_codmater as pc01_codmater","pc07_codmater"," pc07_codele=$o56_codele "));exit;
      $where_ativo = " 1 = 1 ";
      if(isset($opcao) && trim($opcao)!="i"){
        $where_ativo .= " and pc01_ativo='$opcao' ";
      }

      /**
       * calculamos o total de fornecedores da licitacao
       */
      $sSqlFornecedores  = "select coalesce(count(distinct pc21_numcgm), 0) as total_fornecedores";
      $sSqlFornecedores .= "  from pcprocitem";
      $sSqlFornecedores .= "       inner join liclicitem       on l21_codpcprocitem  = pc81_codprocitem";
      $sSqlFornecedores .= "       inner join solicitem        on pc11_codigo        = pc81_solicitem";
      $sSqlFornecedores .= "       inner join pcorcamitemlic   on pc26_liclicitem    = l21_codigo";
      $sSqlFornecedores .= "       inner join pcorcamitem      on pc22_orcamitem     = pc26_orcamitem";
      $sSqlFornecedores .= "       inner join pcorcamforne     on pc22_codorc        = pc21_codorc";
      $sSqlFornecedores .= " where  pc11_numero = {$iRegistroPreco} ";
      $rsTotalFornecedores = db_query($sSqlFornecedores);
      $iTotalFornecedores = 0;
      if ($rsTotalFornecedores && pg_num_rows($rsTotalFornecedores) > 0) {
        $iTotalFornecedores = db_utils::fieldsMemory($rsTotalFornecedores, 0)->total_fornecedores;
      }

      $dtDia         = date("Y-m-d", db_getsession("DB_datausu"));                         
      $where_ativo .= " and not exists (select 1 ";
      $where_ativo .= "  from registroprecomovimentacaoitens ";
      $where_ativo .= "       inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao";
      $where_ativo .= "       inner join pcorcamitem      on pc66_pcorcamitem   = pc22_orcamitem";
      $where_ativo .= "       inner join pcorcamitemlic   on pc22_orcamitem     = pc26_orcamitem";
      $where_ativo .= "       inner join liclicitem       on pc26_liclicitem    = l21_codigo";
      $where_ativo .= "       inner join pcprocitem       on l21_codpcprocitem  = pc81_codprocitem";
      $where_ativo .= "       inner join solicitem si     on pc81_solicitem     = si.pc11_codigo";
      $where_ativo .= "       inner join solicitempcmater on pc16_solicitem     = si.pc11_codigo";
      $where_ativo .= " where pc58_situacao         = 1 ";
      $where_ativo .= "   and pc58_tipo             = 3 ";
      $where_ativo .= "   and si.pc11_numero        = {$iRegistroPreco} ";
      $where_ativo .= "   and solicitem.pc11_codigo = registroprecomovimentacaoitens.pc66_solicitem ";      
      $where_ativo .= "   and pc16_codmater         = pc01_codmater";
      $where_ativo .= "   and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal)";


      $where_ativo .= " and (select count(*) ";
      $where_ativo .= "  from pcprocitem";
      $where_ativo .= "       inner join liclicitem       on l21_codpcprocitem  = pc81_codprocitem";
      $where_ativo .= "       inner join pcorcamitemlic   on pc26_liclicitem    = l21_codigo";
      $where_ativo .= "       inner join pcorcamitem      on pc22_orcamitem     = pc26_orcamitem";
      $where_ativo .= "       inner join pcorcamdescla    on pc32_orcamitem     = pc26_orcamitem";
      $where_ativo .= "       inner join solicitem si     on pc81_solicitem     = si.pc11_codigo";
      $where_ativo .= "       inner join solicitempcmater on pc16_solicitem     = si.pc11_codigo";
      $where_ativo .= " where si.pc11_numero        = {$iRegistroPreco} ";
      $where_ativo .= "   and solicitem.pc11_codigo = si.pc11_codigo";
      $where_ativo .= "   and pc16_codmater         = pc01_codmater";
      $where_ativo .= "   ) < {$iTotalFornecedores}";

      $where_ativo .= "   and solicitem.pc11_numero        = {$iRegistroPreco} ";
      if(!isset($pesquisa_chave)){
        if(empty($campos)){
           if(file_exists("funcoes/db_func_pcmatersolicita.php")==true){
             include(modification("funcoes/db_func_pcmatersolicita.php"));
           }else{
           $campos = "pcmater.*";
           }
        } 
      $campos = "pc11_codigo as pc11_codigo,
                 pcmater.pc01_codmater,
                 pcmater.pc01_descrmater,

                 pc11_resum,o56_codele,o56_elemento,substr(o56_descr,1,40) as o56_descr,
                 pcsubgrupo.pc04_descrsubgrupo as DB_pc04_descrsubgrupo,
                 pcmater.pc01_servico,
								 pcmater.pc01_veiculo";
				$repassa = array(
                         "chave_pc01_codmater"    => @$chave_pc01_codmater,
                         "chave_pc01_descrmater"  => @$chave_pc01_descrmater,
                         "chave_pc07_codele"      => @$chave_pc07_codele,
                         "chave_o56_descr"        => @$chave_o56_descr,
                         "chave_o56_elemento"     => @$chave_o56_elemento,
			 "chave_pc03_codgrupo"    => @$chave_pc03_codgrupo,
			 "chave_pc04_codsubgrupo" => @$chave_pc04_codsubgrupo
                        );
        if(isset($chave_pc01_codmater) && (trim($chave_pc01_codmater)!="") ){
	         $sql = $clpcmater->sql_query_desdobraregistropreco(null,$campos,"pc11_codigo","pc01_codmater=$chave_pc01_codmater and $where_ativo");
        }else if(isset($chave_pc01_descrmater) && (trim($chave_pc01_descrmater)!="") ){
	         $sql = $clpcmater->sql_query_desdobraregistropreco("",$campos,"pc01_descrmater"," pc01_descrmater like '$chave_pc01_descrmater%' and $where_ativo");
        }elseif(isset($chave_pc07_codele) && (trim($chave_pc07_codele)!="") ){
	         $sql = $clpcmater->sql_query_desdobraregistropreco(null,$campos,"pc11_codigo","pc07_codele=$chave_pc07_codele and $where_ativo");
        }else if(isset($chave_o56_descr) && (trim($chave_o56_descr)!="") ){
	         $sql = $clpcmater->sql_query_desdobraregistropreco("",$campos,"pc11_codigo"," o56_descr like '$chave_o56_descr%' and $where_ativo");
        }else if(isset($chave_o56_elemento) && (trim($chave_o56_elemento)!="") ){
	         $sql = $clpcmater->sql_query_desdobraregistropreco("",$campos,"pc11_codigo"," o56_elemento like '$chave_o56_elemento%' and $where_ativo");
        }else if(isset($chave_pc03_codgrupo) && trim($chave_pc03_codgrupo)!="" && $chave_pc03_codgrupo <> 0){
	         
          if(isset($chave_pc04_codsubgrupo) && trim($chave_pc04_codsubgrupo)!="" && $chave_pc04_codsubgrupo <> 0){
		        $where_subgrupo = " and pc04_codsubgrupo = $chave_pc04_codsubgrupo ";
		     } else {
		       $where_subgrupo = "";
		     }  
	       $sql = $clpcmater->sql_query_desdobraregistropreco("",$campos,"pc11_codigo","pc03_codgrupo = $chave_pc03_codgrupo and pc04_codsubgrupo = pc01_codsubgrupo and pc04_codgrupo = pc03_codgrupo and $where_ativo $where_subgrupo");
        } else {
          $sql = $clpcmater->sql_query_desdobraregistropreco(null,$campos,"pc11_codigo", "$where_ativo");
        }


        db_lovrot(@$sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpcmater->sql_record($clpcmater->sql_query_desdobraregistropreco(null,"pc01_descrmater,pc01_veiculo","","pc01_codmater=$pesquisa_chave and $where_ativo"));
          if($clpcmater->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc01_descrmater',false,'$pc01_veiculo');</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
<?
// CADASTRO DE PCMATER
// Quando o usuário for incluir um item, aparecerá a func_pcmater.php para caso ele queira pegar dados de um item
// já criado... EX.:o usuário ja tem um cadastro de caneta preta com Elemens,grupo e sub-grupo... Para o cadastro
// de uma caneta azul,usará os mesmos dados e mudará apenas a descrição do item... Então, quando ele selecionar o
// item caneta preta, a func retornará os dados para o usuário alterar apenas a descrição. Caso o item procurado 
// não exista  (numrows seja igual a zero), a func jogará para o cadastro apenas a descrição procurada...
if(isset($zero)){
  echo "parent.document.form1.pc01_descrmater.value = document.form1.chave_pc01_descrmater.value;";
  echo "parent.db_iframe_pcmater.hide();";
}
?>
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
