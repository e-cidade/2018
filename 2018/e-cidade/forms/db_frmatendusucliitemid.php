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
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap>
      <?
      $result_ususel  = $clatendusucliitemid->sql_record($clatendusucliitemid->sql_query_innerusu(null,"id_usuario, nome","nome","at83_usucliitem = ".$at83_usucliitem." and usuarioativo = '1'"));
      $result_usunsel = $clatendusucliitemid->sql_record($clatendusucliitemid->sql_query_leftusu (null,"id_usuario, nome","nome","at83_usucliitem is null and usuext = 0 and usuarioativo = '1'"));
      db_multiploselect("id_usuario","nome", "usunsel", "ususel", $result_usunsel, $result_ususel, 15, 250, "Usuários não envolvidos", "Usuários envolvidos");
      db_input('at83_usucliitem',8,0,true,'hidden',3);
      ?>
    </td>
  </tr>
</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="Enviar dados" onclick="return js_seleciona();">
<input name="fechar"  type="button" id="dbfechar" value="Fechar" onclick="parent.db_iframe_envolvidos.hide();">
</form>
<script>
function js_seleciona(){
  js_seleciona_combo(document.form1.ususel);
  return true;
}
</script>