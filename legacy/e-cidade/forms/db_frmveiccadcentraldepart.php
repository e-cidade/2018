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

$clveiccadcentraldepart->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("descdepto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="790">
  <tr>
    <td nowrap align="right" title="<?=@$Tve37_veiccadcentral?>"><?=@$Lve37_veiccadcentral?></td>
    <td nowrap width="20">
    <?
      db_input("cod_depto",          10,0,true,"hidden",3);
      db_input("ve36_coddepto",      10,0,true,"hidden",3);
      db_input("ve37_veiccadcentral",10,0,true,"text",  3);
    ?>
    </td>
    <td nowrap>
    <?
       db_input('descrdepto',40,0,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="3" height="50" align="center">
      <input name="atualizar" type="button" id="db_opcao" value="Atualizar" onClick="js_verificar();">
    </td>
  </tr>
  <tr>
    <td nowrap colspan="3">
    <?
      $campos         = "ve37_sequencial,coddepto,descrdepto";
      $mostrar_campos = "coddepto,descrdepto";
      $sql            = $clveiccadcentraldepart->sql_query_depto(null,$campos,null,"ve36_coddepto is null and instit = ".db_getsession("DB_instit"));
      $sql_marca      = $clveiccadcentraldepart->sql_query_depto(null,$campos,null,"ve37_veiccadcentral = $ve37_veiccadcentral and ve37_coddepto <> $ve36_coddepto and instit = ".db_getsession("DB_instit"));
      $sql_disabled   = $clveiccadcentraldepart->sql_query_depto(null,$campos,"coddepto","ve37_veiccadcentral = $ve37_veiccadcentral and ve37_coddepto = $ve36_coddepto and instit = ".db_getsession("DB_instit"));

      $union     = " union ";
      $union_all = $union."all ";

      $sql .= $union_all.$sql_marca.$union.$sql_disabled;

      $cliframe_seleciona->campos        = $mostrar_campos;
      $cliframe_seleciona->legenda       = "Departamentos";
      $cliframe_seleciona->sql           = $sql;
      $cliframe_seleciona->sql_marca     = $sql_marca;
      $cliframe_seleciona->sql_disabled  = $sql_disabled;
      $cliframe_seleciona->iframe_height = "400";
      $cliframe_seleciona->iframe_width  = "700";
      $cliframe_seleciona->iframe_nome   = "depart";
      $cliframe_seleciona->chaves        = "coddepto";
      $cliframe_seleciona->js_marcador   = "";
      $cliframe_seleciona->dbscript      = "";
      $cliframe_seleciona->iframe_seleciona(1);
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_verificar(){

  var obj      = depart.document.form1;
  var contador = 0;
  var virg     = "";
  var coddepto = "";

  for(i = 0; i < obj.length; i++){
    if (obj.elements[i].checked == true){
      contador++;
      coddepto += virg+obj.elements[i].value;
      virg      = ",";
    }
  }

  if (contador == 0){
//    alert("Selecione algum departamento.");
//    exit;
  }

  document.form1.cod_depto.value = coddepto;
  document.form1.submit();
}
</script>