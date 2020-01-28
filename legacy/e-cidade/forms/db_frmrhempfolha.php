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

include("classes/db_gerfcom_classe.php");
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory( $_POST );
?>
<table align="center">
  <form name="form1" method="post" action="" onsubmit="return js_verifica();">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" nowrap title="Digite o Ano / Mes de competência" >
    <strong>Ano / Mês :&nbsp;&nbsp;</strong>
    </td>
    <td>
      <?
       if(!isset($DBtxt23) || (isset($DBtxt23) && (trim($DBtxt23) == "" || $DBtxt23 == 0))){
         $DBtxt23 = db_anofolha();
       }
       db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
      ?>
      &nbsp;/&nbsp;
      <?
       if(!isset($DBtxt25) || (isset($DBtxt23) && (trim($DBtxt25) == "" || $DBtxt25 == 0))){
         $DBtxt25 = db_mesfolha();
       }
       db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
      ?>
    </td>
  </tr>
  <tr>
    <td><b>Ponto:</b></td>
    <td>
     <?
       $x = array("s"=>"Salário","c"=>"Complementar","d"=>"13o. Salário","r"=>"Rescisão","a"=>"Adiantamento");
       db_select('ponto',$x,true,4,"onchange='document.form1.submit();'");
     ?>
    </td>
     </tr>
     <?
     if(isset($ponto) && $ponto == "c"){
       $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($DBtxt23,$DBtxt25,null,null,"distinct r48_semest as rh40_sequencia"));

       if($clgerfcom->numrows > 0){

         echo "<tr>";
         echo "  <td align='left' title='Nro. Complementar'><strong>Nro. Complementar:</strong></td>";
         echo "  <td>";
         echo "    <select name='rh40_sequencia'>";
         echo "      <option value = '0'>Todos</option> ";
         for($i=0; $i<$clgerfcom->numrows; $i++){

           $oDadosComplementar = db_utils::fieldsMemory( $result_semest, $i);
           db_fieldsmemory($result_semest, $i);

           $sSelecionado = "";

           if( $oDadosComplementar->rh40_sequencia == $oPost->rh40_sequencia ) {
             $sSelecionado = "selected";
           }
           echo "<option {$sSelecionado} value = '$rh40_sequencia'>{$rh40_sequencia}</option>";
         }
         echo "    </td>";
         echo "  </tr>";

       }else{
         echo "
               <tr>
                 <td colspan='2' align='center'>
                   <font color='red'>Sem complementar para este período.</font>
                 </td>
               </tr>
              ";
       }
     }
     ?>
  <tr>
    <td colspan="2" align = "center">
      <input  name="gera" id="gera" type="submit" value="Processar" onsubmit="js_verifica();">
    </td>
  </tr>
  </form>
</table>
</body>
<script>
function js_verifica(){
return true;
}
</script>
</html>
