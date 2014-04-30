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

$clrotulo = new rotulocampo;
?>
<script>
function js_pesquisa(codigo){
  document.form1.codtransfer.value = codigo;
  document.form1.submit();
}
function js_mandadados(){
  contador=0;
  vir="";
  cod="";
  listaproc="";
  for (i=0;i<iframe_proctrans.document.form1.length;i++){
    if (iframe_proctrans.document.form1.elements[i].type=='checkbox'){
      if (iframe_proctrans.document.form1.elements[i].checked==true){
        cod=iframe_proctrans.document.form1.elements[i].name.split("_");
	listaproc+=vir+cod[1];
	vir=",";
	contador++;
      }
    }
  }
  document.form1.listaproc.value=listaproc;
  document.form1.contador.value=contador;
  if (listaproc!=""){
    return true;
  }else return false;
}
</script>
<form name="form1" method="post" action="">
<center>
<table> 
   <tr>
     <td>
       <fieldset>
         <legend><b>Dados Da Transferência</b></legend>
         <table border="0">
           <tr align="center">
             <td nowrap> 
              <?
              db_input('listaproc',100,"",true,'hidden',3);
              db_input('codtransfer',10,"",true,'hidden',3);
              db_input('contador',10,"",true,'hidden',3);
              ?>
             </td>
           </tr>
         </table>
<table>
  <tr align="center">
    <td>
    <b>
    Cod. da Transferência:
    </b>
    </td>
    <td nowrap> 
    <?
    if (isset($codtransfer)&&$codtransfer!="") {
      
      $sSqlTransferencia = $clproctransferint->sql_query_andusu(null, 
                                                                "p88_codigo,
                                                                 p88_data,
                                                                 p88_hora,
                                                                 atual.nome as usu_atual,
                                                                 destino.nome as usu_destino",
                                                                 null,
                                                                 "p88_codigo={$codtransfer}"
                                                                 );
      $result_proctransferint = $clproctransferint->sql_record($sSqlTransferencia);
      if ($clproctransferint->numrows != 0) {
        db_fieldsmemory($result_proctransferint, 0);
      }
    }
    $arr    = array();
    $sSqlTransfValidas  = $clproctransferint->sql_query_file(null, 
                                                             "p88_codigo as codtran ",
                                                             "p88_codigo desc",
                                                             "p88_codigo not in (select p86_codtrans 
                                                                                   from procandamintand)"
                                                            );
    $result = $clproctransferint->sql_record($sSqlTransfValidas);
    for ($y = 0; $y < $clproctransferint->numrows; $y++) {

      db_fieldsmemory($result,$y);
      if ($y == 0) {
	      $codigo=$codtran;
      }
      $arr[$codtran] = $codtran;
    }
    if (isset($codtransfer) && $codtransfer!="") {
      
      $result_proctransferint = $clproctransferint->sql_record($clproctransferint->sql_query_andusu(null,"p88_codigo,p88_data,p88_hora,atual.nome as usu_atual,destino.nome as usu_destino",null,"p88_codigo=$codtransfer"));
      if ($clproctransferint->numrows!=0){
        db_fieldsmemory($result_proctransferint,0);
      }
    }else{
      echo "<script>js_pesquisa($codigo);</script>"; 
    }
    db_select("p88_codigo",$arr,true,1,"onchange='js_pesquisa(this.value);'");
    
    ?>
    </td>
    <td colspan=2>
    <input name='cancel'  type='submit' value='Cancelar os Processos' onclick='return  js_mandadados();' >
    </td>
  </tr>
  <tr>
    <td><b>Data:</b></td>
    <td>
    <?
    @$p88_data=db_formatar(@$p88_data,'d');
    db_input('p88_data',10,"",true,'text',3)
    ?>
    </td>
    <td><b>Hora:</b></td>
    <td>
    <?
    db_input('p88_hora',6,"",true,'text',3)
    ?>
    </td>
  </tr>
  <tr>
    <td><b>Usuário Atual:</b></td>
    <td>
    <?
    db_input('usu_atual',30,"",true,'text',3);
    ?>
    </td>
    <td><b>Usuário Destino:</b></td>
    <td>
    <?
    db_input('usu_destino',30,"",true,'text',3);
    ?>
    </td>
  </tr>
  </table>
 </fieldset>
</td>
</tr>  
  <tr align="center">
    <td colspan=4  nowrap>
    <fieldset><legend><b>Processos da Transferencia</b></legend>
    <?
    if (isset($codtransfer)&&$codtransfer!=""){
    ?>
      <iframe name="iframe_proctrans" id="proctrans" marginwidth="0" marginheight="0" frameborder="0" 
              src="pro4_canceltransintlist.php?cod=<?=@$codtransfer?>" width="700" height="260"></iframe>
    <?
    }
    ?>
    </fieldset>
    </td>
  </tr>
</table>
</form>