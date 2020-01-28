<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_abonofalta_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_regencia_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$resultedu         = eduparametros(db_getsession("DB_coddepto"));
$clabonofalta      = new cl_abonofalta;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clregencia        = new cl_regencia;
$db_opcao          = 1;
$db_botao          = true;
$clrotulo          = new rotulocampo;
$clrotulo->label("ed80_i_justificativa");
$result = $clregencia->sql_record($clregencia->sql_query("","ed232_c_descr","","ed59_i_codigo = $regencia"));
db_fieldsmemory($result,0);

if (isset($incluir)) {
	
  $ed80_i_codigo = "";
  db_inicio_transacao();
  $clabonofalta->incluir($ed80_i_codigo);
  db_fim_transacao();
  
}

if (isset($alterar)) {
	
  if ($ed80_i_numfaltas == "" || $ed80_i_numfaltas == 0) {
  	
    db_inicio_transacao();
    $clabonofalta->excluir($ed80_i_codigo);
    db_fim_transacao();
    
  } else {
  	
    db_inicio_transacao();
    $clabonofalta->alterar($ed80_i_codigo);
    db_fim_transacao();
    
  }
}

if (isset($excluir)) {
	
  db_inicio_transacao();
  $clabonofalta->excluir($ed80_i_codigo);
  db_fim_transacao();
  
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
.titulo {
	
  font-size: 11;
  color: #DEB887;
  background-color:#444444;
  font-weight: bold;

}

.cabec1 {
	
  font-size: 11;
  color: #000000;
  background-color:#999999;
  font-weight: bold;
																																																																	
}

.aluno {
	
  color: #000000;
  font-family : Tahoma;
  font-size: 9;

}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
$sql     = " SELECT ed60_c_situacao,ed72_i_codigo, ";
$sql    .= "        ed47_v_nome,ed47_i_codigo, ";																																																																
$sql    .= "        ed60_i_numaluno,ed72_i_valornota,ed60_i_codigo, ed60_matricula ";
$sql    .= " FROM matricula ";
$sql    .= "  inner join aluno on ed47_i_codigo = ed60_i_aluno ";
$sql    .= "  inner join diario on ed95_i_aluno = ed47_i_codigo ";
$sql    .= "  inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo ";
$sql    .= "  inner join regencia on ed59_i_codigo = ed95_i_regencia ";
$sql    .= " WHERE ed95_i_regencia = $regencia ";
$sql    .= " AND ed60_i_turma = ed59_i_turma ";
$sql    .= " AND ed95_c_encerrado = 'N' ";
$sql    .= " AND ed72_i_procavaliacao = $avaliacao ";
$sql    .= " AND ed72_c_amparo = 'N' ";
$sql    .= " AND ed60_c_situacao = 'MATRICULADO' ";
$sql    .= " AND ed72_i_numfaltas is not null ";
$sql    .= " ORDER BY ed47_v_nome ";
$result1 = pg_query($sql);
$linhas1 = pg_num_rows($result1);
//db_criatabela($result1);
//exit;
?>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td class='titulo'>&nbsp;&nbsp;
   Disciplina <?=@$ed232_c_descr?> - <?=$descrperiodo?>
  </td>
 </tr>
 <tr>
  <td align="center">
   <fieldset style="width:95%"><legend><b>Abono de Faltas</b></legend>
   <table width="98%" border="0" cellspacing="3" cellpadding="3" align="center">
    <tr>
     <td>
      <b>Selecione o aluno:</b><br>
      <select name="ed80_i_diarioavaliacao" id="ed80_i_diarioavaliacao" 
              onchange="js_alunoescolha(this.value,<?=$regencia?>,'<?=$descrperiodo?>',<?=$avaliacao?>);" 
              style="font-size:9px;width:330px;">
       <option value=""><?=$linhas1==0?"NENHUM ALUNO PARA ABONAR FALTAS NESTE PERÍODO.":""?></option>
       <?
       for ($i = 0; $i < $linhas1; $i++) {
       	
         db_fieldsmemory($result1,$i);
         ?>
         <option value='<?=$ed72_i_codigo?>' <?=$ed72_i_codigo==@$aluno?"selected":""?>>
              <?=$ed47_i_codigo?> - <?=$ed47_v_nome?>
         </option>
         <?
       }
       ?>
      </select>
      <?=$linhas1==0?"<input name='fechar' type='button' value='Fechar' onclick='parent.db_iframe_abono.hide();'>":""?>
     </td>
    </tr>
    <?if (isset($aluno)) {
    	
        $sql  = " SELECT ed72_i_numfaltas, ";
        $sql .= "        ed80_i_codigo, ";
        $sql .= "        ed80_i_numfaltas, ";
        $sql .= "        ed80_i_justificativa, ";
        $sql .= "        ed06_c_descr ";
        $sql .= " FROM diarioavaliacao ";
        $sql .= "  left join abonofalta on ed80_i_diarioavaliacao = ed72_i_codigo ";
        $sql .= "  left join justificativa on ed06_i_codigo = ed80_i_justificativa ";
        $sql .= " WHERE ed72_i_codigo = $aluno ";
     
        $result2 = pg_query($sql);
        db_fieldsmemory($result2,0);
        if (isset($ed80_i_codigo) && $ed80_i_codigo != "") {
        	
          $db_opcao = 2;
          ?><input type="hidden" name="ed80_i_codigo" value="<?=$ed80_i_codigo?>"><?
          
        }?>
        <tr>
         <td>
          Faltas no período: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="nulo" value="<?=@$ed72_i_numfaltas?>" type="text" 
                                                                  size="4" maxlength="3" readonly 
                                                                  style="text-align:center;background:#DEB887">
         </td>
        </tr>
        <tr>
         <td>
           N° de faltas abonadas : <input name="ed80_i_numfaltas" value="<?=@$ed80_i_numfaltas?>" type="text" 
                                          size="4" maxlength="3" onkeyup="js_verabono(this,<?=$ed72_i_numfaltas?>);"
                                          style="text-align:center;"><br><br>
         </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted80_i_justificativa?>">
            <?db_ancora(@$Led80_i_justificativa,"js_pesquisaed80_i_justificativa(true);",$db_opcao);?>
            <?db_input('ed80_i_justificativa',15,@$Ied80_i_justificativa,true,'text',$db_opcao,
                        " onchange='js_pesquisaed80_i_justificativa(false);'")?>
            <?db_input('ed06_c_descr',50,@$Ied06_c_descr,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
           <td align="center">
             <br><br>
             <?if ($db_opcao == 1) {?>
                 <input name="incluir" type="submit" id="db_opcao" value="Incluir">
             <?} else {?>
                 <input name="alterar" type="submit" id="db_opcao" value="Alterar">
                 <input name="excluir" type="submit" id="db_opcao" value="Excluir">
             <?}?>
               <input name='fechar' type='button' value='Fechar' onclick='parent.db_iframe_abono.hide();'>
           </td>
          </tr>
    <?}?>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<?
if (isset($incluir) || isset($alterar)) {
	
  if ($clabonofalta->erro_status == "0") {
  	 
    $clabonofalta->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clabonofalta->erro_campo != "") {
    	
      echo "<script> document.form1.".$clabonofalta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clabonofalta->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
   ?>
    <script>
      parent.parent.iframe_A<?=$avaliacao?>.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>"+
    	                                                    "&ed41_i_codigo=<?=$avaliacao?>";
      parent.db_iframe_abono.hide();
    </script>
   <?
  }
}

if (isset($excluir)) {
	
  if ($clabonofalta->erro_status == "0") {
    $clabonofalta->erro(true,false);
  } else {
  	
   ?>
    <script>
      parent.parent.iframe_A<?=$avaliacao?>.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>"+
    	                                                    "&ed41_i_codigo=<?=$avaliacao?>";
      parent.db_iframe_abono.hide();
    </script>
   <?
   
  }
}

?>
<script>
function js_pesquisaed80_i_justificativa(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_justificativa','func_justificativa.php?funcao_js=parent.js_mostrajustificativa1|'+
    	                'ed06_i_codigo|ed06_c_descr','Pesquisa de Justificativas',true);
    
  } else {
	  
    if (document.form1.ed80_i_justificativa.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_justificativa',
    	                  'func_justificativa.php?pesquisa_chave='+document.form1.ed80_i_justificativa.value+
    	                  '&funcao_js=parent.js_mostrajustificativa','Pesquisa',false);
      
    } else {
      document.form1.ed06_c_descr.value = '';
    }
  }
}

function js_mostrajustificativa(chave,erro) {
	
  document.form1.ed06_c_descr.value = chave;
  if (erro == true) {
	  
    document.form1.ed80_i_justificativa.focus();
    document.form1.ed80_i_justificativa.value = '';
    
  }
}

function js_mostrajustificativa1(chave1,chave2) {
	
  document.form1.ed80_i_justificativa.value = chave1;
  document.form1.ed06_c_descr.value         = chave2;
  db_iframe_justificativa.hide();
  
}

function js_alunoescolha(aluno,regencia,descrperiodo,avaliacao) {
	
  if (aluno != "") {
	  
    location.href = "edu1_abonofalta001.php?aluno="+aluno+"&regencia="+regencia+"&descrperiodo="+descrperiodo+
                    "&avaliacao="+avaliacao;
    
  }
}

function js_verabono(campo,faltas) {
	
  var expr = new RegExp("[^0-9]+");
  if (campo.value.match(expr)) {
	  
    alert("Falta deve ser um número inteiro!");
    campo.value = "";
    campo.focus();
    
  } else {
	  
    if (campo.value != "") {
        
      if (campo.value == 0) {
          
        alert("Número de faltas abonadas deve ser maior que zero!");
        document.form1.incluir.disabled = true;
        campo.value = "";        
        
      } else {
          
        if (campo.value > faltas) {
            
          alert("Número máximo de faltas para abonar: "+faltas);
          if (<?=$db_opcao?> == 1) {
            document.form1.incluir.disabled =true;
          } else {
        	document.form1.alterar.disabled =true;    
          }
          campo.value = "";         
          
        } else {
          if (<?=$db_opcao?> == 1) {                
        	  document.form1.incluir.disabled =false;
          } else {                
              document.form1.alterar.disabled =false;
          }
        }
      }
    }
  }
}
</script>