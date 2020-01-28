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

$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
?>
<table  border="0" cellspacing="4" cellpadding="0">
  <form name="form1" method="post">
  <tr><td>&nbsp;</td></tr>
  <td>
<fieldset><legend><b>Gerar arquivo</b></legend>
<table  border="0">
  <tr>
    <td align="right"><b>Data do pedido:</b></td>
    <td align="left">
			<?
	    db_inputdata("pedido",date("d",db_getsession("DB_datausu")),date("m",db_getsession("DB_datausu")),date("Y",db_getsession("DB_datausu")),true,'text',1,"");
			?>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Data de efetivação:</b></td>
    <td align="left">
			<?
	    db_inputdata("efetiv",date("d",db_getsession("DB_datausu")),date("m",db_getsession("DB_datausu")),date("Y",db_getsession("DB_datausu")),true,'text',1,"");
			?>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Pedido:</b></td>
    <td align="left">
    <?
      $arr_pedido = Array("1"=>"Normal","2"=>"Complementar");
      db_select("pedido",$arr_pedido,true,1);
    ?>
    </td>
  </tr>
  </td>
  <td align="right"><b>Emitir Func. c/ valor zerado:</b></td>
    <td align="left">
      <?
       db_input('listazerados',1,"",false,'checkbox',1,"");
      ?>
    </td>
  </tr>

  </td>
  </fieldset>
  </table>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="js_retornacampos();">
    </td>
  </tr>
  <?
  db_input('opcaopesquisa',40,0,true,'hidden',3,'');
  db_input('lstzerado',3,0,true,'hidden',3,'');
  ?>
  </form>
</table>
<script>
function js_retornacampos(){
	x = document.form1;
	if(x.pedido_dia.value == "" || x.pedido_mes.value == "" || x.pedido_ano.value == ""){
  	alert("Informe a data do pedido.");
  	x.pedido_dia.select();
  	x.pedido_dia.focus();
  }else if(x.efetiv_dia.value == "" || x.efetiv_mes.value == "" || x.efetiv_ano.value == ""){
  	alert("Informe a data da efetivação do benefício.");
  	x.efetiv_dia.select();
  	x.efetiv_dia.focus();
  }else{
    if(x.listazerados.checked == true){
      x.lstzerado.value = ">=";
    }else{
      x.lstzerado.value = ">";
    }
	obj=document.createElement('input');
	obj.setAttribute('name' ,'gerarq');
	obj.setAttribute('type' ,'hidden');
	obj.setAttribute('value','gerarq');
	x.appendChild(obj);
	x.submit();
  }
}
</script>