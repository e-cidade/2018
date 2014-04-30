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

include("classes/db_proctransand_classe.php");
$clproctransand=new cl_proctransand;
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
    <tr>
      <td>
        <fieldset>
           <legend><b>Dados da Transferencia</b></legend>
           <table>
             <tr align="center">
               <td>
                 <b>Cod. da Transferência:</b>
               </td>
               <td nowrap> 
                <?
                $departamento=db_getsession("DB_coddepto");
                if (isset($codtransfer)&& $codtransfer!="") {
                  
                  $sSqlTransferencia = $clproctransfer->sql_query_deps(null, 
                                                                       "p62_codtran,p62_dttran,p62_hora,
                                                                        usu_atual.nome as usu_atual,
                                                                        atual.descrdepto as depto_atual,
                                                                        usu_destino.nome as usu_destino,
                                                                        destino.descrdepto as depto_destino",
                                                                       null,
                                                                       "p62_codtran={$codtransfer} 
                                                                       and p62_coddepto={$departamento}"
                                                                      );
                  $result_proctransfer=$clproctransfer->sql_record($sSqlTransferencia);
                  if ($clproctransfer->numrows != 0) {
                    db_fieldsmemory($result_proctransfer, 0);
                  }
                }
                $arr                    = array();
                $sSqlTransferenciaDepto = $clproctransfer->sql_query_trans(null, 
                                                                           "p62_codtran as codtran ",
                                                                           "p62_codtran desc",
                                                                           "p62_coddepto={$departamento}
                                                                            and p64_codtran is null"
                                                                           );
                $result = $clproctransfer->sql_record($sSqlTransferenciaDepto);
                for ($y = 0; $y < $clproctransfer->numrows; $y++) {
                  
                  db_fieldsmemory($result,$y);
                  if ($y==0) {
                    $codigo = $codtran;
                  }
                  $arr[$codtran] = $codtran;
                }
                if (isset($codtransfer) && $codtransfer!="") {
                  
                  $sSqlDadosTransferencia = $clproctransfer->sql_query_deps(null, 
                                                                            "p62_codtran,p62_dttran,p62_hora,
                                                                             usu_atual.nome as usu_atual,
                                                                             atual.descrdepto as depto_atual,
                                                                             usu_destino.nome as usu_destino,
                                                                             destino.descrdepto as depto_destino",
                                                                            null,
                                                                            "p62_codtran={$codtransfer}
                                                                             and p62_coddepto={$departamento}"
                                                                            );
                  $result_proctransfer = $clproctransfer->sql_record($sSqlDadosTransferencia);
                  if ($clproctransfer->numrows != 0) {
                    db_fieldsmemory($result_proctransfer, 0);
                  }
                } else {
                  echo "<script>js_pesquisa({$codigo});</script>"; 
                }
                db_select("p62_codtran",$arr,true,1,"onchange='js_pesquisa(this.value);'");
                
                ?>
               </td>
               <td colspan=2>
                 <input name='cancel'  type='submit' value='Cancelar os Processos' onclick='return  js_mandadados();' >
               </td>
             </tr>
             <tr>
               <td>
                 <b>Data:</b>
               </td>
               <td>
                <?
                @$p62_dttran=db_formatar(@$p62_dttran,'d');
                db_input('p62_dttran',10,"",true,'text',3)
                ?>
               </td>
               <td>
                 <b>Hora:</b>
               </td>
               <td>
                <?
                db_input('p62_hora',6,"",true,'text',3)
                ?>
               </td>
             </tr>
             <tr>
               <td>
                  <b>Usuário Atual:</b>
               </td>
               <td>
                <?
                db_input('usu_atual',30,"",true,'text',3);
                ?>
               </td>
               <td>
                 <b>Departamento Atual:</b>
               </td>
               <td>
                <?
                db_input('depto_atual',30,"",true,'text',3);
                ?>
               </td>
             </tr>
             <tr>
               <td><b>Usuário Destino:</b></td>
               <td>
               <?
               db_input('usu_destino',30,"",true,'text',3);
               ?>
               </td>
               <td><b>Departamento Destino:</b></td>
               <td>
               <?
               db_input('depto_destino',30,"",true,'text',3);
               ?>
               </td>
             </tr>
              <tr align="center">
                <td colspan=4  nowrap>
                <fieldset style="border: 0px; border-top: 2px groove white">
                <legend><b>Processos da Transferência</b></legend>
                <?
                if (isset($codtransfer)&&$codtransfer!=""){
                ?>
                  <iframe name="iframe_proctrans" id="proctrans" marginwidth="0"
                          marginheight="0" frameborder="0" 
                          src="pro4_canceltranslist.php?cod=<?=@$codtransfer?>" width="100%" height="260"></iframe>
                <?
                }
                ?>
                </fieldset>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>        
</form>