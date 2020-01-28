<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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


class cl_permissaodotacao {
  var $dotacoes = null;
  var $campos = " fc_estruturaldotacao(o58_anousu,o58_coddot) as o50_estrutdespesa, o58_coddot ";
  var $result = null;

  function cl_permissaodotacao($anousu, $id_usuario) {
    global $db20_orgao, $db20_unidade, $db20_funcao, $db20_subfuncao, $db20_programa, $db20_projativ, $db20_codele, $db20_codigo;
    $sql ="select 'usuario' as tipo ,p.*
    from db_permemp p
    inner join db_usupermemp u on u.db21_codperm = p.db20_codperm and
    u.db21_id_usuario = $id_usuario
    where db20_anousu = $anousu
    union all
    select 'setor' as tipo ,p.*
    from db_permemp p
    /* seleciona os departamentos do usuario */
    inner join db_depusu e on e.id_usuario = $id_usuario
    inner join db_depusuemp d on d.db22_codperm = p.db20_codperm and
    d.db22_coddepto = e.coddepto
    ";
    $result = db_query($sql);
    $this->dotacoes = "select ".$this->campos."
    from orcdotacao
    where ";
    for ($i = 0; $i < pg_numrows($result); $i ++) {
      db_fieldsmemory($result, $i);
      $tem_and = "";
      if ($db20_orgao > 0) {
        $this->dotacoes .= " o58_orgao = $db20_orgao ";
        $tem_and = " and ";
      }
      if ($db20_unidade > 0) {
        $this->dotacoes .= $tem_and." o58_unidade = $db20_unidade ";
        $tem_and = " and ";
      }
      if ($db20_funcao > 0) {
        $this->dotacoes .= $tem_and." o58_funcao = $db20_funcao ";
        $tem_and = " and ";
      }
      if ($db20_subfuncao > 0) {
        $this->dotacoes .= $tem_and." o58_subfuncao = $db20_subfuncao ";
        $tem_and = " and ";
      }
      if ($db20_programa > 0) {
        $this->dotacoes .= $tem_and." o58_programa = $db20_programa ";
        $tem_and = " and ";
      }
      if ($db20_projativ > 0) {
        $this->dotacoes .= $tem_and." o58_projativ = $db20_projativ ";
        $tem_and = " and ";
      }
      if ($db20_codele > 0) {
        $this->dotacoes .= $tem_and." o58_codele = $db20_codele ";
        $tem_and = " and ";
      }
      if ($db20_codigo > 0) {
        $this->dotacoes .= $tem_and." o58_codigo = $db20_codigo ";
      }
    }
    $this->dotacoes .= " order by o50_estrutdespesa ";
  }

  function retornarecord() {

    $this->result = db_query($this->dotacoes);

    return $this->result;

  }

  function mostradotacao($funcao_js = null) {

    db_lovrot($this->dotacoes, 15, "()", "", $funcao_js);

  }

}

//|00|//cl_estrutura
//|10|//pega a picture de um determinado campo do orcparametro e gera um input text com a formatacao da mesma
//|15|//[variavel] = new cl_estrutura;
class cl_estrutura {
  // cria variaveis de erro
  var $nomeform = "form1";
  var $reload = false;
  var $size = '50';
  var $mascara = true;
  var $input = false;
  var $db_opcao = 1;
  var $funcao_onchange = null;
  var $autocompletar = false;
  function estrutura($picture = null) {
    $rotuloc = new rotulocampo;
    $clorcparametro = new cl_orcparametro;
    $rotuloc->label($picture);
    $title = "T".$picture;
    $label = "L".$picture;

    global $$label, $$title, $$picture, $mascara;
    if (!class_exists('cl_orcparametro')) {
      db_msgbox('Classe orcparametro não incluida!');
      exit;
    }
    $result = $clorcparametro->sql_record($clorcparametro->sql_query_file(db_getsession("DB_anousu"), "$picture as mascara"));
    if ($clorcparametro->numrows > 0) {
      db_fieldsmemory($result, 0);
      $tamanho = strlen($mascara);
    } else {
      db_msgbox('Tabela Parametros Vazia, verifique as configurações do sistema ! ');
      exit;
    }
    if ($this->funcao_onchange != null) {
      if ($this->autocompletar == false && $this->reload == false) {
        $funcao = $this->funcao_onchange;
      } else {
        $funcao = "onChange='js_mascara02_$picture(this.value);".$this->funcao_onchange.";'";
      }
    } else {
      $funcao = "onChange=\"js_mascara02_$picture(this.value);\"";
    }
    if ($this->mascara == true) {
      ?>
      <tr>
        <td nowrap title="Máscara do campo <?=@$picture?>">
          <b>Máscara:</b>
        </td>
        <td>

          <input name="mascara"  readonly disabled size='<?=$this->size?>' type="text"  value="<?=$mascara?>"    >
        </td>
      </tr>
      <?


    }
  if ($this->input == false) {
    ?>
    <tr>
      <td nowrap title="<?=@$$title?>">
        <?=@$$label?>
      </td>
      <td>
        <?


        }
        ?>
        <input title="<?=@$$title?>" name="<?=$picture?>" maxlength='<?=$tamanho?>' size='<?=$this->size?>' type="text"  value="<?=@$$picture?>" onKeyPress="return js_mascara01_<?=$picture?>(event,this.value);"  <?=$funcao?> <?=($this->db_opcao==22||$this->db_opcao==33||$this->db_opcao==3?"readonly style=\"background-color:#DEB887\" ":"")?> >
        <?

        if ($this->input == false) {
        ?>
      </td>
    </tr>
    <?


  }
    ?>
    <script>
      function js_mascara01_<?=$picture?>(evt,obj){
        var evt = (evt) ? evt : (window.event) ? window.event : "";
        if(evt.charCode >47 && evt.charCode <58 ){//8:backspace|46:delete|190:.
          str='<?=$mascara?>';
          tam=obj.length;
          dig=str.substr(tam,1);
          if(dig=="."){
            document.<?=$this->nomeform?>.<?=$picture?>.value=obj+".";
          }
          return true;
        }else if(evt.charCode=='0'){
          return true;
        }else{
          return false;
        }
      }
      function js_mascara02_<?=$picture?>(obj){
        obj=document.<?=$this->nomeform?>.<?=$picture?>.value;
        while(obj.search(/\./)!='-1'){
          obj=obj.replace(/\./,'');
        }
        <?


        if ($this->autocompletar == true) {
        ?>
        tam = <?=strlen(str_replace(".","",$mascara))?>;
        for(i=obj.length; i<tam; i++){
          obj=obj+"0";
        }
        <?


        }
        ?>
        //analise da estrutura passada
        str='<?=$mascara?>';
        nada='';
        matriz=str.split(nada);
        tam=matriz.length;
        arr=new Array();
        cont=0;
        for(i=0; i<tam; i++){
          if(matriz[i]=='.'){
            arr[cont]=i;
            cont++;
          }
        }
        //fim
        for(i=0; i<arr.length; i++){
          pos=arr[i];
          strpos=obj.substr(pos,1);
          if(strpos!='' && strpos!='.'){
            ini=obj.slice(0,pos);
            fim=obj.slice(pos);
            obj=ini+"."+fim;
          }
        }
        document.<?=$this->nomeform?>.<?=$picture?>.value=obj;
        <?


        if ($this->reload == true) {
        ?>
        obj=document.createElement('input');
        obj.setAttribute('name','atualizar');
        obj.setAttribute('type','hidden');
        obj.setAttribute('value',"atualizar");
        document.<?=$this->nomeform?>.appendChild(obj);
        document.<?=$this->nomeform?>.submit();
        <?


        }
        ?>
      }
    </script>
    <?


  }
}
function db_selinstit($dbclick = '', $largura = 500, $altura = 100) {
  //#00#//db_selinstit
  //#10#//Esta funcao mostra as instituiçoes para os usuarios que forem da prefeitura
  //#15#//db_selinstit($dbclick='',$largura=500, $altura=100)
  //#20#//$dbclick    : Funcao java script que será executada no onclick do da selecao dentro do iframa na func_selinstit.php
  //#20#//$largura    : Largura em px que será gerado o iframe
  //#20#//$altura     : Altura em px que será gerado o iframe
  //#99#//Esta funcao lista as instituicoe para que o usuário possa selecionar os filtros nos relatiorios
  //#99#//Para os usuário da prefeitura, sempre será listado as instituições.
  $sql = "select * from db_config where codigo = ".db_getsession("DB_instit")." and prefeitura = true";
  $result = db_query($sql);
  if (pg_numrows($result) > 0) {
    echo "<input name='db_selinstit' type='hidden' value='' ><br>";
    echo "<strong>Selecione a(s) Instituição(ões):</strong><br>";
    echo "<iframe name='db_selinstit_iframe' width='".$largura."px' height='".$altura."px' src='func_selinstit.php?funcao=$dbclick'></iframe>";
  } else {

    echo "<input name='db_selinstit' type='hidden' value='".db_getsession("DB_instit")."'>";

  }

}

function db_selorcbalanco($balanco = true, $orcamento = true, $empliqpag = false) {

  if ($balanco == true) {
    echo "<tr>\n";
    echo "  <td align=\"center\" colspan=\"2\">\n";
    echo "  <fieldset id=\"tabelabalanco\"  align=\"center\">\n";
    echo "  <legend align=\"center\"><strong>Balanço</strong></legend>\n";
    echo "	  <table>\n";
    if ($empliqpag == true) {
      echo " 	   <tr>\n";

      // a variavel que identifica o empenhado, liquidado, pago deve ser tratada na funcao javascript que
      // roda no programas, funcap js_emite cfe exemplo orc2_despsecretaria001.php.

      echo " 	   <td align='center' colspan='2'>\n";
      echo "         <input type='radio' name='qual_tipo_balanco' value='2'  checked>\n";
      echo " 	   <strong>Empenhado</strong>&nbsp&nbsp&nbsp\n";
      echo "         <input type='radio' name='qual_tipo_balanco' value='3' >\n";
      echo " 	   <strong>Liquidado</strong>&nbsp&nbsp&nbsp\n";
      echo "         <input type='radio' name='qual_tipo_balanco' value='4' >\n";
      echo " 	   <strong>Pago</strong>\n";
      echo " 	   </td>\n";
      echo " 	   </tr>\n";
    }

    echo " 	   <tr>\n";
    /*    echo "	    <td>\n";
    echo "	     <table border=\"0\" width=\"220\"  height=\"100\" style=\"border: 1px solid black\" cellpadding=\"0\" cellspacing=\"1\" >\n";
    echo "	      <tr>\n";
    echo "          <td align=\"center\" colspan=\"2\" title=\"Gera o saldo em um intervalo de meses\"><strong>Saldo Por Mês</strong></td>\n";
    echo "	      </tr>\n";
    echo "	      <tr>\n";
    echo "           <td align=\"right\" ><strong>Mês Início :</strong> </td>\n";
    echo "	         <td>\n";
    echo "            <script>\n";
    echo "             function js_criames(obj){\n";
      echo "               for(i=1;i<document.form1.mesfin.length;i){\n";
        echo "                  document.form1.mesfin.options[i] = null;\n";
      echo "               }\n";
      echo "               var dth = new Date(".db_getsession("DB_anousu").",document.form1.mesini.value,'1');\n";
      echo "	             var nummes = dth.getMonth();\n";
      echo "	             if(nummes == 0){\n";
        echo "		       nummes = 12;\n";
      echo "		     }\n";
      echo "               var teste = 0;\n";
      echo "               for(j=nummes;j<13;j++){\n";
        echo "		       if(teste > 12){\n";
          echo " 		         teste = 1;\n";
        echo "		       }\n";
        echo "                 var dt = new Date(".db_getsession("DB_anousu").",j,'1');\n";
        echo "                 document.form1.mesfin.options[teste] = new Option(db_mes(j),dt.getMonth());\n";
        echo "		       teste += 1;\n";
      echo "               }\n";
      echo "               document.form1.mesfin.options[0].selected = true;\n";
    echo "             }\n";
    echo "           </script>\n";
    $result1=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
    db_select("mesini",$result1,true,2,'onchange="js_criames(this)"',"","","","");
    echo "	       </td>\n";
    echo "	     </tr>\n";
    echo "	     <tr>\n";
    echo "          <td align=\"right\" ><strong>Mês Fim :</strong></td>\n";
    echo "	        <td>\n";
    echo "           <select  name=\"mesfin\" id=\"mes\" >\n";
    echo "            <option value=\"mes\">Mês Final</option>\n";
    echo "            <script>\n";
    echo "              js_criames(document.form1.mesini);\n";
    echo "            </script>\n";
    echo "           </select>\n";
    echo "	       </td>\n";
    echo "	     </tr>\n";
    echo "       <tr>\n";
    echo "         <td colspan=\"2\" align = \"center\">\n";
    echo "           <input  name=\"emite2\" id=\"emite2\" type=\"button\" value=\"Processar\" onclick=\"js_emite(2,'B');\" >\n";
    echo "         </td>\n";
    echo "       </tr>\n";
    echo "	   </table>\n";
    echo "	 </td>\n";*/
    echo "	 <td>\n";
    echo "	   <table border=\"0\"  width=\"240\" height=\"100\" style=\"border: 1px solid black\" cellpadding=\"0\" cellspacing=\"1\" >\n";
    echo "	     <tr>\n";
    echo "         <td align=\"center\" colspan=\"2\" title=\"Gera o saldo em um intervalo de datas\"><strong>Saldo Por Datas</strong></td>\n";
    echo "	     </tr>\n";
    echo "       <tr>\n";
    echo "         <td nowrap align=\"right\" title=\"".$GLOBALS["TDBtxt21"]."\">\n";
    echo $GLOBALS["LDBtxt21"];
    echo "	       </td>\n";
    echo "	       <td>\n";
    $DBtxt21_ano = db_getsession("DB_anousu");
    $DBtxt21_mes = '01';
    $DBtxt21_dia = '01';
    db_inputdata('DBtxt21', $DBtxt21_dia, $DBtxt21_mes, $DBtxt21_ano, true, 'text', 4);
    echo "	       </td>";
    echo "	      </tr>";
    echo "	      <tr>";
    echo "	        <td nowrap align=\"right\" title=\"".$GLOBALS["TDBtxt22"]."\">";
    echo $GLOBALS["LDBtxt22"];
    echo "	        </td>";
    echo "	        <td>";
    $DBtxt22_ano = db_getsession("DB_anousu");
    $DBtxt22_mes = date("m", db_getsession("DB_datausu"));
    $DBtxt22_dia = date("d", db_getsession("DB_datausu"));
    db_inputdata('DBtxt22', $DBtxt22_dia, $DBtxt22_mes, $DBtxt22_ano, true, 'text', 4);
    echo "	        </td>";
    echo "        </tr>";
    echo "        <tr>";
    echo "          <td colspan=\"2\" align = \"center\">";
    echo "            <input  name=\"emite2\" id=\"emite2\" type=\"button\" value=\"Processar\" onclick=\"js_emite(3,'B');\" >";
    echo "          </td>";
    echo "        </tr>";
    echo "       </table>";
    echo "	    </td>";
    echo "	    </tr>";
    echo "	  </table>";
    echo "   </fieldset>";
    echo "  </td>";
  }
  if ($orcamento == true) {
    echo "  <td align=\"center\" colspan=\"2\">";
    echo "	 <fieldset id=\"tabelabalanco\"  align=\"center\">";
    echo "   <legend align=\"center\"><strong>Orçamento</strong></legend>";
    echo "	   <table border=\"0\"  width=\"220\" height=\"100\" style=\"border: 1px solid black\" cellpadding=\"0\" cellspacing=\"1\" >";
    echo "      <tr>";
    echo "        <td align = \"center\">";
    echo "          <input  name=\"emite2\" id=\"emite2\" type=\"button\" value=\"Processar\" onclick=\"js_emite(1,'O');\" >";
    echo "        </td>";
    echo "      </tr>";
    echo "     </table>";
    echo "	 </fieldset>";
    echo "  </td>";
  }
  echo "</tr>";
}
function db_le_mae_rec_sin($codigo, $nivel = false) {
  $retorno = "";
  $conta_mae = "";
  if (substr($codigo, 13, 2) != '00') {
    if ($nivel == true) {
      $retorno = 10;
    } else {
      $retorno = substr($codigo, 0, 11).'00';
      $conta_mae = substr($codigo, 0, 11);
    }
  }
  if (substr($codigo, 11, 4) != '0000') {
    if ($nivel == true) {
      $retorno = 9;
    } else {
      $retorno = substr($codigo, 0, 11).'0000';
      $conta_mae = substr($codigo, 0, 11);
    }
  }
  if ($retorno == "" && substr($codigo, 9, 6) != '000000') {
    if ($nivel == true) {
      $retorno = 8;
    } else {
      $retorno = substr($codigo, 0, 9).'000000';
      $conta_mae = substr($codigo, 0, 9);
    }
  }
  if ($retorno == "" && substr($codigo, 7, 8) != '00000000') {
    if ($nivel == true) {
      $retorno = 7;
    } else {
      $retorno = substr($codigo, 0, 7).'00000000';
      $conta_mae = substr($codigo, 0, 7);
    }
  }
  if ($retorno == "" && substr($codigo, 5, 10) != '0000000000') {
    if ($nivel == true) {
      $retorno = 6;
    } else {
      $retorno = substr($codigo, 0, 5).'0000000000';
      $conta_mae = substr($codigo, 0, 5);
    }
  }
  if ($retorno == "" && substr($codigo, 4, 11) != '00000000000') {
    if ($nivel == true) {
      $retorno = 5;
    } else {
      $retorno = substr($codigo, 0, 4).'00000000000';
      $conta_mae = substr($codigo, 0, 4);
    }
  }
  if ($retorno == "" && substr($codigo, 3, 12) != '000000000000') {
    if ($nivel == true) {
      $retorno = 4;
    } else {
      $retorno = substr($codigo, 0, 3).'000000000000';
      $conta_mae = substr($codigo, 0, 3);
    }
  }
  if ($retorno == "" && substr($codigo, 2, 13) != '0000000000000') {
    if ($nivel == true) {
      $retorno = 3;
    } else {
      $retorno = substr($codigo, 0, 2).'0000000000000';
      $conta_mae = substr($codigo, 0, 2);
    }
  }
  if ($retorno == "" && substr($codigo, 1, 14) != '00000000000000') {
    if ($nivel == true) {
      $retorno = 2;
    } else {
      $retorno = substr($codigo, 0, 1).'00000000000000';
      $conta_mae = substr($codigo, 0, 1);
    }
  }
  if ($retorno == "") {
    if ($nivel == true) {
      $retorno = 1;
    } else {
      $retorno = $codigo;
    }
  }
  return $conta_mae;
}

function db_le_mae_rec($codigo, $nivel = false) {
  $retorno = "";
  if (substr($codigo, 13, 2) != '00') {
    if ($nivel == true) {
      $retorno = 10;
    } else {
      $retorno = substr($codigo, 0, 11).'00';
    }
  }
  if (substr($codigo, 11, 4) != '0000') {
    if ($nivel == true) {
      $retorno = 9;
    } else {
      $retorno = substr($codigo, 0, 11).'0000';
    }
  }
  if ($retorno == "" && substr($codigo, 9, 6) != '000000') {
    if ($nivel == true) {
      $retorno = 8;
    } else {
      $retorno = substr($codigo, 0, 9).'000000';
    }
  }
  if ($retorno == "" && substr($codigo, 7, 8) != '00000000') {
    if ($nivel == true) {
      $retorno = 7;
    } else {
      $retorno = substr($codigo, 0, 7).'00000000';
    }
  }
  if ($retorno == "" && substr($codigo, 5, 10) != '0000000000') {
    if ($nivel == true) {
      $retorno = 6;
    } else {
      $retorno = substr($codigo, 0, 5).'0000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 4, 11) != '00000000000') {
    if ($nivel == true) {
      $retorno = 5;
    } else {
      $retorno = substr($codigo, 0, 4).'00000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 3, 12) != '000000000000') {
    if ($nivel == true) {
      $retorno = 4;
    } else {
      $retorno = substr($codigo, 0, 3).'000000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 2, 13) != '0000000000000') {
    if ($nivel == true) {
      $retorno = 3;
    } else {
      $retorno = substr($codigo, 0, 2).'0000000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 1, 14) != '00000000000000') {
    if ($nivel == true) {
      $retorno = 2;
    } else {
      $retorno = substr($codigo, 0, 1).'00000000000000';
    }
  }
  if ($retorno == "") {
    if ($nivel == true) {
      $retorno = 1;
    } else {
      $retorno = $codigo;
    }
  }
  return $retorno;
}

function db_le_mae($codigo, $nivel = false) {
  $retorno = "";
  if (substr($codigo, 11, 2) != '00') {
    if ($nivel == true) {
      $retorno = 9;
    } else {
      $retorno = substr($codigo, 0, 11).'00';
    }
  }
  if ($retorno == "" && substr($codigo, 9, 4) != '0000') {
    if ($nivel == true) {
      $retorno = 8;
    } else {
      $retorno = substr($codigo, 0, 9).'0000';
    }
  }
  if ($retorno == "" && substr($codigo, 7, 6) != '000000') {
    if ($nivel == true) {
      $retorno = 7;
    } else {
      $retorno = substr($codigo, 0, 7).'000000';
    }
  }
  if ($retorno == "" && substr($codigo, 5, 8) != '00000000') {
    if ($nivel == true) {
      $retorno = 6;
    } else {
      $retorno = substr($codigo, 0, 5).'00000000';
    }
  }
  if ($retorno == "" && substr($codigo, 4, 9) != '000000000') {
    if ($nivel == true) {
      $retorno = 5;
    } else {
      $retorno = substr($codigo, 0, 4).'000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 3, 10) != '0000000000') {
    if ($nivel == true) {
      $retorno = 4;
    } else {
      $retorno = substr($codigo, 0, 3).'0000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 2, 11) != '00000000000') {
    if ($nivel == true) {
      $retorno = 3;
    } else {
      $retorno = substr($codigo, 0, 2).'00000000000';
    }
  }
  if ($retorno == "" && substr($codigo, 1, 12) != '000000000000') {
    if ($nivel == true) {
      $retorno = 2;
    } else {
      $retorno = substr($codigo, 0, 1).'000000000000';
    }
  }
  if ($retorno == "") {
    if ($nivel == true) {
      $retorno = 1;
    } else {
      $retorno = $codigo;
    }
  }
  return $retorno;
}


function estruturalNivel($sEstrutural) {

  $iNiveis = array();
  $iAux    = 1;
  $iNiveis = explode(".", $sEstrutural);
  $iLaco   = count($iNiveis);

  for ($i = 1; $i < $iLaco; $i++) {

    if ($iNiveis[$i] != 0 ) {
      $iAux = $i+1;
    }
  }
  return $iAux;
}

function criaContaMae($string) {

  $string = db_formatar($string,"sistema");
  $iNivel = estruturalNivel($string);
  $stringnova = "";
  $aNiveis = explode(".", $string);
  for ($i = 0;  $i < $iNivel; $i++) {

    $stringnova .=  $aNiveis[$i];
  }
  return $stringnova;
}

///////// cria estrutura de balancete para as receitas do ppa

function db_receitappa($anoini, $db_where = false, $retsql = false) {

  $ano1 = $anoini;
  $ano2 = $anoini +1;
  $ano3 = $anoini +2;
  $ano4 = $anoini +3;

  if ($db_where != false) {
    $where = $db_where;
  } else {
    $where = '';
  }

  $sql = "
  select
  o57_fonte  as estrut_mae,
  o57_fonte  as estrut,
  o57_descr  as descr_rece,
  o70_codigo as recurso,
  o15_descr  as descr_recu,
  sum(case when o27_exercicio = ".$ano1." then o27_valor else 0 end) as a1,
  sum(case when o27_exercicio = ".$ano2." then o27_valor else 0 end) as a2,
  sum(case when o27_exercicio = ".$ano3." then o27_valor else 0 end) as a3,
  sum(case when o27_exercicio = ".$ano4." then o27_valor else 0 end) as a4
  from
  (select
  o57_fonte  ,
  o57_descr  ,
  o70_codigo ,
  o15_descr  ,
  o27_exercicio,
  case when substr(o57_fonte,1,2) = '49'
  then  ABS(o27_valor) * (-1)
  else o27_valor end as o27_valor
  from orcpparec
  left join orcfontes on o57_codfon = o27_codfon and o57_anousu = ".db_getsession("DB_anousu")."
  left join orcreceita on o70_codfon = o27_codfon and o70_anousu = ".db_getsession("DB_anousu")."
  inner join orctiporec on o15_codigo = o70_codigo
  $where ) as x
  group by o57_fonte,
  o57_descr,
  o70_codigo,
  o15_descr
  order by o57_fonte
  ";
  //echo $sql;exit;
  // db_criatabela(db_query($sql));exit;
  db_query("create temporary table work_pl(
  estrut_mae varchar(15),
  estrut varchar(15),
  descr_rece varchar(50),
  recurso integer,
  descr_recu varchar(50),
  a1 float8,
  a2 float8,
  a3 float8,
  a4 float8
  ) ");
  //   db_query("create temporary table work_plano as $sql");
  db_query("create index work_pl_estrut on work_pl(estrut)");
  db_query("create index work_pl_estrutmae on work_pl(estrut_mae)");
  $result = db_query($sql);
  //  db_criatabela($result);exit;
  $tot_a1 = 0;
  $tot_a2 = 0;
  $tot_a3 = 0;
  $tot_a4 = 0;

  GLOBAL $seq;
  GLOBAL $estrut_mae;
  GLOBAL $estrut;
  GLOBAL $descr_rece;
  GLOBAL $recurso;
  GLOBAL $descr_recu;
  GLOBAL $a1;
  GLOBAL $a2;
  GLOBAL $a3;
  GLOBAL $a4;

  GLOBAL $saldo_anterior;
  GLOBAL $saldo_anterior_debito;
  GLOBAL $saldo_anterior_credito;
  GLOBAL $saldo_final;
  GLOBAL $result_estrut;
  GLOBAL $sinal_anterior;
  GLOBAL $sinal_final;

  $work_planomae = array ();
  $work_planoestrut = array ();
  $work_plano = array ();
  $seq = 0;

  for ($i = 0; $i < pg_numrows($result); $i ++) {
    //  for($i = 0;$i < 20;$i++){
    db_fieldsmemory($result, $i);

    $tot_a1 = $a1;
    $tot_a2 = $a2;
    $tot_a3 = $a3;
    $tot_a4 = $a4;

    $key = array_search("$estrut_mae", $work_planomae);
    if ($key === false) { // não achou
      $work_planomae[$seq] = $estrut_mae;
      $work_planoestrut[$seq] = $estrut;
      $work_plano[$seq] = array (0 => "$descr_rece", 1 => "$recurso   ", 2 => "$descr_recu", 3 => "$a1        ", 4 => "$a2        ", 5 => "$a3        ", 6 => "$a4        ");
      $seq = $seq +1;
    } else {
      $work_plano[$key][3] += $a1;
      $work_plano[$key][4] += $a2;
      $work_plano[$key][5] += $a3;
      $work_plano[$key][6] += $a4;
    }
    $estrutural = $estrut;
    for ($ii = 1; $ii < 10; $ii ++) {
      $estrutural = db_le_mae_conplano($estrutural);
      $nivel = db_le_mae_conplano($estrutural, true);

      $key = array_search("$estrutural", $work_planomae);
      if ($key === false) { // não achou
        // busca no banco e inclui
        //echo "\n".$estrutural;
        //echo "\n".$descr_rece;exit;
        $res = db_query("select c60_descr as descr_rece,c60_finali,c60_codcon from conplano where c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut = '$estrutural'");
        if ($res == false || pg_numrows($res) == 0) {
          db_redireciona("db_erros.php?fechar=true&db_erro=Está faltando cadastrar esse estrutural na contabilidade. Nível : $nivel  Estrutural : $estrutural - ano: " + db_getsession("DB_anousu"));
          exit;
        }
        db_fieldsmemory($res, 0);

        $work_planomae[$seq] = $estrutural;
        $work_planoestrut[$seq] = '';
        $work_plano[$seq] = (array (0 => $descr_rece, 1 => $recurso, 2 => $descr_recu, 3 => $a1, 4 => $a2, 5 => $a3, 6 => $a4));
        $seq ++;
      } else {
        $work_plano[$key][3] += $tot_a1;
        $work_plano[$key][4] += $tot_a2;
        $work_plano[$key][5] += $tot_a3;
        $work_plano[$key][6] += $tot_a4;
      }
      if ($nivel == 1)
        break;
    }
  }

  for ($i = 0; $i < sizeof($work_planomae); $i ++) {
    $mae = $work_planomae[$i];
    $estrut = $work_planoestrut[$i];
    $descr_rece = $work_plano[$i][0];
    $recurso = $work_plano[$i][1];
    $descr_recu = $work_plano[$i][2];
    $a1 = $work_plano[$i][3];
    $a2 = $work_plano[$i][4];
    $a3 = $work_plano[$i][5];
    $a4 = $work_plano[$i][6];

    $sql = "insert into work_pl
      values ('$mae',
      '$estrut',
      '$descr_rece',
      $recurso,
      '$descr_recu',
      $a1,
      $a2,
      $a3,
      $a4)

      ";
    db_query($sql);
  }

  $sql = "select *
    from work_pl
    order by estrut_mae,estrut";

  if ($retsql == false) {
    $result_final = db_query($sql);
    //      db_criatabela($result_final); exit;
    return $result_final;
  } else {
    return $sql;
  }
}

//#00#// db_rpsaldo()
//#10#// Esta funcao retorna o recordset dos restos a Pagar, reclamações > Carlos
//#15#// db_rpsaldo($anousu,$w_instit,$dt_ini,$dt_fin)
//#20#//  $anousu  $w_instit  $dt_ini   $dt_fim
//#20#//  $where = exemplo ->  " and o58_funcao=10"
function db_rpsaldo($anousu = "", $w_instit = "=1", $dt_ini = "", $dt_fin = "", $where = "1=1", $coddoc = "") {
  $sql = "
    select
    e91_codtipo,
    e90_descr,
    e60_anousu,
    round((e91_vlremp -e91_vlranu- e91_vlrliq),2) as anterior_a_liquidar,
    round((e91_vlrliq - e91_vlrpag ),2) as anterior_liquidado,
    vlranu,
    vlrliq,
    vlrpag,
    round(case when vlrpag > vlrliq
    then vlrpag-vlrliq
    else
    0
    end,2) as pago_naoprocessado,
    round(case when vlrpag > vlrliq
    then vlrliq
    else vlrpag
    end,2) as pago_processado
    from (
    select e91_codtipo,
    e90_descr,
    e60_anousu,
    sum(vlranu) as vlranu,
    sum(vlrliq) as vlrliq,
    sum(vlrpag) as vlrpag,
    sum( e91_vlremp) as e91_vlremp,
    sum(e91_vlranu) as e91_vlranu,
    sum(e91_vlrliq) as e91_vlrliq,
    sum(e91_vlrpag) as e91_vlrpag
    from (
    select e91_numemp, e91_vlremp,e91_vlranu,
    e91_vlrliq,e91_vlrpag,
    e91_recurso,o15_descr, vlranu,
    vlrliq,vlrpag,e91_codtipo,
    e90_descr, z01_nome,
    e60_numemp,e60_codemp,e60_emiss,e60_anousu
    from (
    select e91_numemp,e91_codtipo,
    e90_descr,o15_descr, e91_vlremp,
    e91_vlranu,e91_vlrliq,e91_vlrpag,e91_recurso,
    vlranu,vlrliq,vlrpag
    from empresto
    inner join emprestotipo on e91_codtipo = e90_codigo
    inner join orctiporec on e91_recurso = o15_codigo
    left outer join (
    select c75_numemp,
    sum( case when c53_tipo = 11 then c70_valor else 0 end) as vlranu,
    sum( case when c53_tipo = 20 then c70_valor
    else (
    case when c53_tipo = 21 then c70_valor*-1 else 0 end) end) as vlrliq,
    sum( case when c53_tipo = 30 then c70_valor
    else (
    case when c53_tipo = 31 then c70_valor*-1 else 0 end) end) as vlrpag
    from conlancamemp
    inner join conlancamdoc on c71_codlan = c75_codlan
    inner join conhistdoc on c53_coddoc = c71_coddoc
    inner join conlancam on c70_codlan = c75_codlan
    inner join empempenho on e60_numemp = c75_numemp
    where e60_anousu < $anousu and c75_data between '$dt_ini' and '$dt_fin'
    and e60_instit $w_instit $coddoc
    group by c75_numemp
    ) as x on x.c75_numemp = e91_numemp
    where e91_anousu = $anousu
    ) as x
    inner join empempenho on e60_numemp = e91_numemp
    inner join orcdotacao on o58_coddot = e60_coddot and o58_anousu=e60_anousu
    inner join cgm on z01_numcgm = e60_numcgm
    where e60_instit $w_instit
    and $where
    order by e91_codtipo, e91_numemp
    ) as x
    group by e91_codtipo,
    e90_descr,
    e60_anousu
    ) as rps

    ";

  $result = db_query($sql);
  return $result;
}

/////////////////////////

// funcao copiada por causa do codigo do programa em carazinho que é zero (0)
// quando for zero o sistema trata como se não existisse e portanto da erro nos relatorios
// tive que colocar em vez de zero, -1 para nao ficar fora de ordem
// para funcionar corretamente deverá ser alterado todos os relatorios que utilizam esta funcao
function db_dotacaosaldo($nivel = 8, $tipo_nivel = 1, $tipo_saldo = 2, $descr = true, $where = '', $anousu = null, $dataini = null, $datafim = null, $primeiro_fim = 8, $segundo_inicio = 0, $retsql = false, $tipo_balanco = 1, $desmembra_segundo_inicio = true, $subelemento = 'nao') {

  if ($anousu == null)
    $anousu = db_getsession("DB_anousu");

  if ($dataini == null)
    $dataini = date('Y-m-d', db_getsession('DB_datausu'));

  if ($datafim == null)
    $datafim = date('Y-m-d', db_getsession('DB_datausu'));

  if ($where != '') {
    $condicao = " and ".$where;
  } else {
    $condicao = "";
  }

  if ($tipo_balanco == 1) {
    $tipo_pa = 'dot_ini';
  }elseif ($tipo_balanco == 2) {
    $tipo_pa = 'empenhado - anulado';
  } elseif ($tipo_balanco == 3) {
    $tipo_pa = 'liquidado';
  } else {
    $tipo_pa = 'pago';
  }

  //#00#//db_dotacaosaldo
  //#10#//Esta funcao retorna o recordset do saldo das dotações
  //#15#//db_dotacaosaldo($nivel=8, $tipo_nivel=1, $tipo_saldo=2, $descr=true, $where='', $anousu=null, $dataini=null, $datafim=null)
  //#20#//$nivel      : Até qual o nível será apurado o saldo, pode ser:
  //#20#//              1 - órgão
  //#20#//              2 - unidade
  //#20#//              3 - função
  //#20#//              4 - subfuncao
  //#20#//              5 - programa
  //#20#//              6 - projeto de atividade
  //#20#//              7 - elemento
  //#20#//              8 - recurso
  //#20#//
  //#20#//              ex. quando solicitar nivel=8 usar tipo_nivel=2 para evitar duplicação de valores
  //#20#//
  //#20#//$tipo_nivel : especifica a maneira de como será apurado o resultado, pode ser:
  //#20#//              1 - traz a árvore de elementos até o nível solicitado
  //#20#//                  Ex.: 01                  300
  //#20#//                       01.01               100
  //#20#//                       01.01.01             50
  //#20#//              2 - traz o saldo do nível escolhido
  //#20#//                  Ex.: 01.01.01             50
  //#20#//              3 - totaliza o saldo pelo nível escolhido
  //#20#//                  Ex.: 00.00.01           1000
  //#20#//
  //#20#//
  //#20#//$tipo_saldo : 1 - dotação inicial
  //#20#//              2 - saldo no mes da dataini
  //#20#//              3 - saldo por período
  //#20#//              4 - saldo por período + acumulado do período
  //#20#//
  //#20#//$descr      : retorna o record set com as descrições ou não, o default é 'true'
  //#20#//
  //#20#//$where      : condição
  //#20#//
  //#20#//$anousu     : ano do orçamento
  //#20#//
  //#20#//$dataini    : data inicial do intervalo
  //#20#//
  //#20#//$datafim    : data final do intervalo
  //#20#//
  //#20#//$subelemento : algumas funcoes do pad usam esse parametro como "sim",para orcamentos no desdobramento
  //#20#//
  //#20#//
  //#20#//
  //#99#//
  //#99#//  dot_ini   			: datacao inicial (valor do orcamento)
  //#99#//  saldo_anterior		: saldo anterior ao intervalo de tempo
  //#99#//  empenhado			: empenhado no intervalo
  //#99#//  anulado			: anulado no intervalo
  //#99#//  liquidado			: liquidado no intervalo
  //#99#//  pago				: pago no intervalo
  //#99#//  suplementado			: suplementado no intervalo
  //#99#//  reduzido			: reduzido no intervalo
  //#99#//  atual				: saldo atual
  //#99#//  reservado			: reservado
  //#99#//  atual_menos_reservado		: saldo atual menos o reservado
  //#99#//  atual_a_pagar			:
  //#99#//  atual_a_pagar_liquidado
  //#99#//  empenhado_acumulado
  //#99#//  anulado_acumulado
  //#99#//  liquidado_acumulado
  //#99#//  pago_acumulado
  //#99#//  suplementado_acumulado
  //#99#//  reduzido_acumulado
  //#99#//
  //#99#//
  //#99#//
  //#99#//
  //#99#//
  //#99#//
  // funcao para gerar work
  // db_query('begin');
  //   substr(o56_elemento,1,7) as o58_elemento,
  //   9999999 as o58_coddot,

  db_query("drop table if exists work_dotacao;");
  $sql = "
     CREATE TEMP TABLE IF NOT EXISTS work_dotacao (
       o58_anousu integer,
       o58_orgao integer,
       o58_unidade integer,
       o58_funcao integer,
       o58_subfuncao integer,
       o58_programa integer,
       o58_projativ integer,
       o58_codele integer,
       o58_coddot integer,
       o58_elemento character varying,
       o58_codigo integer,
       dot_ini double precision,
       saldo_anterior double precision,
       empenhado double precision,
       anulado double precision,
       liquidado double precision,
       pago double precision,
       suplementado double precision,
       reduzido double precision,
       atual double precision,
       reservado double precision,
       atual_menos_reservado double precision,
       atual_a_pagar double precision,
       atual_a_pagar_liquidado double precision,
       empenhado_acumulado double precision,
       anulado_acumulado double precision,
       liquidado_acumulado double precision,
       pago_acumulado double precision,
       suplementado_acumulado double precision,
       reduzido_acumulado double precision,
       suplemen double precision,
       suplemen_acumulado double precision,
       especial double precision,
       especial_acumulado double precision,
       transfsup double precision,
       transfsup_acumulado double precision,
       transfred double precision,
       transfred_acumulado double precision,
       reservado_manual_ate_data double precision,
       reservado_automatico_ate_data double precision,
       reservado_ate_data double precision,
       o55_tipo integer,
       o15_tipo integer,
       proj double precision,
       ativ double precision,
       oper double precision,
       ordinario double precision,
       vinculado double precision
     );
     TRUNCATE work_dotacao;
   ";

  $sql.="INSERT INTO work_dotacao
    select *,
    (case when o55_tipo  = 1 then $tipo_pa else 0 end) as proj,
    (case when o55_tipo  = 2 then $tipo_pa else 0 end) as ativ,
    (case when o55_tipo  = 3 then $tipo_pa else 0 end) as oper,
    (case when o15_tipo  =  1 then $tipo_pa else 0 end) as ordinario,
    (case when o15_tipo  <> 1 then $tipo_pa else 0 end) as vinculado
    from
    (select o58_anousu,
    o58_orgao,
    o58_unidade,
    o58_funcao,
    o58_subfuncao,
    o58_programa,
    o58_projativ,
    o56_codele as o58_codele,
    case when '$subelemento'='sim' then
    9999999
    else o58_coddot
    end as o58_coddot,
    case when '$subelemento'='sim'  then
    substr(o56_elemento,1,7)
    else o56_elemento
    end as o58_elemento,
    o58_codigo,
    substr(fc_dotacaosaldo,3,12)::float8   as dot_ini,
    substr(fc_dotacaosaldo,16,12)::float8  as saldo_anterior,
    substr(fc_dotacaosaldo,29,12)::float8  as empenhado,
    substr(fc_dotacaosaldo,42,12)::float8  as anulado,
    substr(fc_dotacaosaldo,55,12)::float8  as liquidado,
    substr(fc_dotacaosaldo,68,12)::float8  as pago,
    substr(fc_dotacaosaldo,81,12)::float8  as suplementado,
    substr(fc_dotacaosaldo,094,12)::float8 as reduzido,
    substr(fc_dotacaosaldo,107,12)::float8 as atual,
    substr(fc_dotacaosaldo,120,12)::float8 as reservado,
    substr(fc_dotacaosaldo,133,12)::float8 as atual_menos_reservado,
    substr(fc_dotacaosaldo,146,12)::float8 as atual_a_pagar,
    substr(fc_dotacaosaldo,159,12)::float8 as atual_a_pagar_liquidado,
    substr(fc_dotacaosaldo,172,12)::float8 as empenhado_acumulado,
    substr(fc_dotacaosaldo,185,12)::float8 as anulado_acumulado,
    substr(fc_dotacaosaldo,198,12)::float8 as liquidado_acumulado,
    substr(fc_dotacaosaldo,211,12)::float8 as pago_acumulado,
    substr(fc_dotacaosaldo,224,12)::float8 as suplementado_acumulado,
    substr(fc_dotacaosaldo,237,12)::float8 as reduzido_acumulado,
    substr(fc_dotacaosaldo,250,12)::float8 as suplemen,
    substr(fc_dotacaosaldo,263,12)::float8 as suplemen_acumulado,
    substr(fc_dotacaosaldo,276,12)::float8 as especial,
    substr(fc_dotacaosaldo,289,12)::float8 as especial_acumulado,
		substr(fc_dotacaosaldo,303,12)::float8 as transfsup,
		substr(fc_dotacaosaldo,316,12)::float8 as transfsup_acumulado,
		substr(fc_dotacaosaldo,329,12)::float8 as transfred,
		substr(fc_dotacaosaldo,342,12)::float8 as transfred_acumulado,
		substr(fc_dotacaosaldo,355,12)::float8 as reservado_manual_ate_data,
		substr(fc_dotacaosaldo,368,12)::float8 as reservado_automatico_ate_data,
		substr(fc_dotacaosaldo,381,12)::float8 as reservado_ate_data,
		o55_tipo,
    o15_tipo
    from(select *, fc_dotacaosaldo($anousu,o58_coddot,$tipo_saldo,'$dataini','$datafim')
    from orcdotacao w
    inner join orcelemento e   on w.o58_codele   = e.o56_codele
                              and e.o56_anousu = w.o58_anousu
                              and e.o56_orcado is true
    inner join orcprojativ ope on w.o58_projativ = ope.o55_projativ
                              and ope.o55_anousu = w.o58_anousu
    inner join orctiporec      on orctiporec.o15_codigo = w.o58_codigo

    where o58_anousu = $anousu
    $condicao
    order by
    o58_orgao,
    o58_unidade,
    o58_funcao,
    o58_subfuncao,
    o58_programa,
    o58_projativ,
    o56_codele,
    o56_elemento,
    o58_coddot,
    o58_codigo
    ) as x
    ) as xxx
    ";

  $result1 = db_query($sql);

  /////// nivel 8 ///////////////

  if (8 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 8 || $desmembra_segundo_inicio == false && $segundo_inicio == 8)) {
    $xnivel8 = '';
    if ($nivel >= 8) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel8 ="o58_orgao,
          o58_unidade,
          o58_funcao,
          o58_subfuncao,
          o58_programa,
          o58_projativ,
          o58_codele,
          o58_elemento,
          o58_coddot, ";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel8 = "   -1 as o58_orgao,
          -1 as o58_unidade,
          -1 as o58_funcao,
          -1 as o58_subfuncao,
          -1 as o58_programa,
          -1 as o58_projativ,
          -1 as o58_codele,
          ''::varchar as o58_elemento,
          -1 as o58_coddot, ";
      }
    }
    $nivel8 = "select
      $xnivel8
      o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      $xnivel8
      o58_codigo,
      sum(dot_ini)																		as dot_ini,
      sum(saldo_anterior)															as saldo_anterior,
      sum(empenhado)																	as empenhado,
      sum(anulado)																		as anulado,
      sum(liquidado)																	as liquidado,
      sum(pago)																				as pago,
      sum(suplementado+transfsup)											as suplementado,
      sum(reduzido+transfred)													as reduzido,
      sum(atual)																			as atual,
      sum(reservado)																	as reservado,
      sum(atual_menos_reservado)											as atual_menos_reservado,
      sum(atual_a_pagar)															as atual_a_pagar,
      sum(atual_a_pagar_liquidado)										as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)												as empenhado_acumulado,
      sum(anulado_acumulado)													as anulado_acumulado,
      sum(liquidado_acumulado)												as liquidado_acumulado,
      sum(pago_acumulado)															as pago_acumulado,
      sum(suplementado_acumulado+transfsup_acumulado) as suplementado_acumulado,
      sum(reduzido_acumulado+transfred_acumulado)			as reduzido_acumulado,
      sum(proj)																				as proj,
      sum(ativ)																				as ativ,
      sum(oper)																				as oper,
      sum(ordinario)																	as ordinario,
      sum(vinculado)																	as vinculado,
      sum(suplemen+transfsup)													as suplemen,
      sum(suplemen_acumulado+transfsup_acumulado)		  as suplemen_acumulado,
      sum(especial)																		as especial,
      sum(especial_acumulado)													as especial_acumulado,
      sum(reservado_manual_ate_data)									as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data)							as reservado_automatico_ate_data,
      sum(reservado_ate_data)                         as reservado_ate_data
      from work_dotacao
      group by ";
    if ($tipo_nivel != 3) {
      $nivel8 .= "o58_orgao,
        o58_unidade,
        o58_funcao,
        o58_subfuncao,
        o58_programa,
        o58_projativ,
        o58_codele,
        o58_elemento,
        o58_coddot ,";
    }
    $nivel8 .= "o58_codigo
      ) as i";

  } else {
    $nivel8 = '';
  }

  /////// nivel 7 ///////////////

  if (7 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 7 || $desmembra_segundo_inicio == false && $segundo_inicio == 7)) {
    $xnivel7 = '';
    if ($nivel >= 7) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel7 = " o58_orgao,
          o58_unidade,
          o58_funcao,
          o58_subfuncao,
          o58_programa,
          o58_projativ,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel7 = "   -1 as o58_orgao,
          -1 as o58_unidade,
          -1 as o58_funcao,
          -1 as o58_subfuncao,
          -1 as o58_programa,
          -1 as o58_projativ,";
      }
    }
    $nivel7 = "select
      $xnivel7
      o58_codele,
      o58_elemento,
      0 as o58_coddot,
      0 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      $xnivel7
      o58_codele,
      o58_elemento,
      sum(dot_ini)																		as dot_ini,
      sum(saldo_anterior)															as saldo_anterior,
      sum(empenhado)																	as empenhado,
      sum(anulado)																		as anulado,
      sum(liquidado)																	as liquidado,
      sum(pago)																				as pago,
      sum(suplementado+transfsup)											as suplementado,
      sum(reduzido+transfred)													as reduzido,
      sum(atual)																			as atual,
      sum(reservado)																	as reservado,
      sum(atual_menos_reservado)											as atual_menos_reservado,
      sum(atual_a_pagar)															as atual_a_pagar,
      sum(atual_a_pagar_liquidado)										as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)												as empenhado_acumulado,
      sum(anulado_acumulado)													as anulado_acumulado,
      sum(liquidado_acumulado)												as liquidado_acumulado,
      sum(pago_acumulado)															as pago_acumulado,
      sum(suplementado_acumulado+transfsup_acumulado) as suplementado_acumulado,
      sum(reduzido_acumulado+transfred_acumulado)		  as reduzido_acumulado,
      sum(proj)																				as proj,
      sum(ativ)																				as ativ,
      sum(oper)																				as oper,
      sum(ordinario)																	as ordinario,
      sum(vinculado)																	as vinculado,
      sum(suplemen+transfsup)												  as suplemen,
      sum(suplemen_acumulado+transfsup_acumulado)			as suplemen_acumulado,
      sum(especial)																		as especial,
      sum(especial_acumulado)													as especial_acumulado,
      sum(reservado_manual_ate_data)									as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data)              as reservado_automatico_ate_data,
      sum(reservado_ate_data)                         as reservado_ate_data
      from work_dotacao
      group by ";
    if ($tipo_nivel != 3) {
      $nivel7 .= "o58_orgao,
        o58_unidade,
        o58_funcao,
        o58_subfuncao,
        o58_programa,
        o58_projativ,";
    }
    $nivel7 .= "   o58_codele,
      o58_elemento
      ) as g";

  } else {
    $nivel7 = '';
  }

  /////// nivel 6 ///////////////

  if (6 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 6 || $desmembra_segundo_inicio == false && $segundo_inicio == 6)) {
    $xnivel6 = '';
    if ($nivel >= 6) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel6 = " o58_orgao,
          o58_unidade,
          o58_funcao,
          o58_subfuncao,
          o58_programa,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel6 = "-1 as o58_orgao,
          -1 as o58_unidade,
          -1 as o58_funcao,
          -1 as o58_subfuncao,
          -1 as o58_programa,";
      }
    }
    $nivel6 = "select
      $xnivel6
      o58_projativ,
      -1 as o58_codele,
      ''::varchar as o58_elemento,
      -1 as o58_coddot,
      -1 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      $xnivel6
      o58_projativ,
      sum(dot_ini)                       as dot_ini,
      sum(saldo_anterior)                as saldo_anterior,
      sum(empenhado)                     as empenhado,
      sum(anulado)                       as anulado,
      sum(liquidado)                     as liquidado,
      sum(pago)                          as pago,
      sum(suplementado)                  as suplementado,
      sum(reduzido)                      as reduzido,
      sum(atual)                         as atual,
      sum(reservado)                     as reservado,
      sum(atual_menos_reservado)         as atual_menos_reservado,
      sum(atual_a_pagar)                 as atual_a_pagar,
      sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)           as empenhado_acumulado,
      sum(anulado_acumulado)             as anulado_acumulado,
      sum(liquidado_acumulado)           as liquidado_acumulado,
      sum(pago_acumulado)                as pago_acumulado,
      sum(suplementado_acumulado)        as suplementado_acumulado,
      sum(reduzido_acumulado)            as reduzido_acumulado,
      sum(proj)                          as proj,
      sum(ativ)                          as ativ,
      sum(oper)                          as oper,
      sum(ordinario)                     as ordinario,
      sum(vinculado)                     as vinculado,
      sum(suplemen)                      as suplemen,
      sum(suplemen_acumulado)            as suplemen_acumulado,
      sum(especial)                      as especial,
      sum(especial_acumulado)            as especial_acumulado,
      sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
      sum(reservado_ate_data)            as reservado_ate_data
      from work_dotacao
      group by ";
    if ($tipo_nivel != 3) {
      $nivel6 .= "o58_orgao,
        o58_unidade,
        o58_funcao,
        o58_subfuncao,
        o58_programa, ";
    }
    $nivel6 .= "   o58_projativ
      ) as f";

  } else {
    $nivel6 = '';
  }

  /////// nivel 5 ///////////////

  if (5 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 5 || $desmembra_segundo_inicio == false && $segundo_inicio == 5)) {
    $xnivel5 = '';
    if ($nivel >= 5) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel5 = "o58_orgao,
          o58_unidade,
          o58_funcao,
          o58_subfuncao,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel5 ="-1 as o58_orgao,
          -1 as o58_unidade,
          -1 as o58_funcao,
          -1 as o58_subfuncao,";
      }
    }
    $nivel5 = "select
      $xnivel5
      o58_programa,
      -1 as o58_projativ,
      -1 as o58_codele,
      ''::varchar as o58_elemento,
      -1 as o58_coddot,
      -1 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      $xnivel5
      o58_programa,
      sum(dot_ini)                       as dot_ini,
      sum(saldo_anterior)                as saldo_anterior,
      sum(empenhado)                     as empenhado,
      sum(anulado)                       as anulado,
      sum(liquidado)                     as liquidado,
      sum(pago)                          as pago,
      sum(suplementado)                  as suplementado,
      sum(reduzido)                      as reduzido,
      sum(atual)                         as atual,
      sum(reservado)                     as reservado,
      sum(atual_menos_reservado)         as atual_menos_reservado,
      sum(atual_a_pagar)                 as atual_a_pagar,
      sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)           as empenhado_acumulado,
      sum(anulado_acumulado)             as anulado_acumulado,
      sum(liquidado_acumulado)           as liquidado_acumulado,
      sum(pago_acumulado)                as pago_acumulado,
      sum(suplementado_acumulado)        as suplementado_acumulado,
      sum(reduzido_acumulado)            as reduzido_acumulado,
      sum(proj)                          as proj,
      sum(ativ)                          as ativ,
      sum(oper)                          as oper,
      sum(ordinario)                     as ordinario,
      sum(vinculado)                     as vinculado,
      sum(suplemen)                      as suplemen,
      sum(suplemen_acumulado)            as suplemen_acumulado,
      sum(especial)                      as especial,
      sum(especial_acumulado)            as especial_acumulado,
      sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
      sum(reservado_ate_data)            as reservado_ate_data
      from work_dotacao
      group by ";
    if ($tipo_nivel != 3) {
      $nivel5 .= "o58_orgao,
        o58_unidade,
        o58_funcao,
        o58_subfuncao, ";
    }
    $nivel5 .= " o58_programa
      ) as e";

  } else {
    $nivel5 = '';
  }

  /////// nivel 4 ///////////////

  if (4 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 4 || $desmembra_segundo_inicio == false && $segundo_inicio == 4)) {
    $xnivel4 = '';
    if ($nivel >= 4) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel4 = "o58_orgao,
          o58_unidade,
          o58_funcao,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel4 = "-1 as o58_orgao,
          -1 as o58_unidade,
          -1 as o58_funcao,";
      }
    }
    $nivel4 = "select
      $xnivel4
      o58_subfuncao,
      -1 as o58_programa,
      -1 as o58_projativ,
      -1 as o58_codele,
      ''::varchar as o58_elemento,
      -1 as o58_coddot,
      -1 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      $xnivel4
      o58_subfuncao,
      sum(dot_ini)                       as dot_ini,
      sum(saldo_anterior)                as saldo_anterior,
      sum(empenhado)                     as empenhado,
      sum(anulado)                       as anulado,
      sum(liquidado)                     as liquidado,
      sum(pago)                          as pago,
      sum(suplementado)                  as suplementado,
      sum(reduzido)                      as reduzido,
      sum(atual)                         as atual,
      sum(reservado)                     as reservado,
      sum(atual_menos_reservado)         as atual_menos_reservado,
      sum(atual_a_pagar)                 as atual_a_pagar,
      sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)           as empenhado_acumulado,
      sum(anulado_acumulado)             as anulado_acumulado,
      sum(liquidado_acumulado)           as liquidado_acumulado,
      sum(pago_acumulado)                as pago_acumulado,
      sum(suplementado_acumulado)        as suplementado_acumulado,
      sum(reduzido_acumulado)            as reduzido_acumulado,
      sum(proj)                          as proj,
      sum(ativ)                          as ativ,
      sum(oper)                          as oper,
      sum(ordinario)                     as ordinario,
      sum(vinculado)                     as vinculado,
      sum(suplemen)                      as suplemen,
      sum(suplemen_acumulado)            as suplemen_acumulado,
      sum(especial)                      as especial,
      sum(especial_acumulado)            as especial_acumulado,
      sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
      sum(reservado_ate_data)            as reservado_ate_data
      from work_dotacao
      group by ";
    if ($tipo_nivel != 3) {
      $nivel4 .= "o58_orgao,
        o58_unidade,
        o58_funcao, ";
    }
    $nivel4 .= "o58_subfuncao
      ) as d";

  } else {
    $nivel4 = '';
  }

  if (3 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 3 || $desmembra_segundo_inicio == false && $segundo_inicio == 3)) {

    $xnivel3 = '';
    if ($nivel >= 3) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel3 = " o58_orgao,
          o58_unidade,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel3 = " -1 as o58_orgao,
          -1 as o58_unidade,";
      }
    }
    $nivel3 = "select
      $xnivel3
      o58_funcao,
      -1 as o58_subfuncao,
      -1 as o58_programa,
      -1 as o58_projativ,
      -1 as o58_codele,
      ''::varchar as o58_elemento,
      -1 as o58_coddot,
      -1 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      $xnivel3
      o58_funcao,
      sum(dot_ini)                       as dot_ini,
      sum(saldo_anterior)                as saldo_anterior,
      sum(empenhado)                     as empenhado,
      sum(anulado)                       as anulado,
      sum(liquidado)                     as liquidado,
      sum(pago)                          as pago,
      sum(suplementado)                  as suplementado,
      sum(reduzido)                      as reduzido,
      sum(atual)                         as atual,
      sum(reservado)                     as reservado,
      sum(atual_menos_reservado)         as atual_menos_reservado,
      sum(atual_a_pagar)                 as atual_a_pagar,
      sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)           as empenhado_acumulado,
      sum(anulado_acumulado)             as anulado_acumulado,
      sum(liquidado_acumulado)           as liquidado_acumulado,
      sum(pago_acumulado)                as pago_acumulado,
      sum(suplementado_acumulado)        as suplementado_acumulado,
      sum(reduzido_acumulado)            as reduzido_acumulado,
      sum(proj)                          as proj,
      sum(ativ)                          as ativ,
      sum(oper)                          as oper,
      sum(ordinario)                     as ordinario,
      sum(vinculado)                     as vinculado,
      sum(suplemen)                      as suplemen,
      sum(suplemen_acumulado)            as suplemen_acumulado,
      sum(especial)                      as especial,
      sum(especial_acumulado)            as especial_acumulado,
      sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
      sum(reservado_ate_data)            as reservado_ate_data
      from work_dotacao
      group by ";
    if ($tipo_nivel != 3) {
      $nivel3 .= "      o58_orgao,
        o58_unidade,";
    }
    $nivel3 .= "	      o58_funcao
      ) as c";

  } else {
    $nivel3 = '';
  }

  /////// nivel 2 ///////////////

  if (2 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 2 || $desmembra_segundo_inicio == false && $segundo_inicio == 2)) {

    $nivel2 = "  select ";
    $nivel2 .= "   o58_orgao,
      o58_unidade,";
    $nivel2 .= "
      -1 as o58_funcao,
      -1 as o58_subfuncao,
      -1 as o58_programa,
      -1 as o58_projativ,
      -1 as o58_codele,
      ''::varchar as o58_elemento,
      -1 as o58_coddot,
      -1 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select
      o58_orgao,
      o58_unidade,
      sum(dot_ini)                       as dot_ini,
      sum(saldo_anterior)                as saldo_anterior,
      sum(empenhado)                     as empenhado,
      sum(anulado)                       as anulado,
      sum(liquidado)                     as liquidado,
      sum(pago)                          as pago,
      sum(suplementado)                  as suplementado,
      sum(reduzido)                      as reduzido,
      sum(atual)                         as atual,
      sum(reservado)                     as reservado,
      sum(atual_menos_reservado)         as atual_menos_reservado,
      sum(atual_a_pagar)                 as atual_a_pagar,
      sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)           as empenhado_acumulado,
      sum(anulado_acumulado)             as anulado_acumulado,
      sum(liquidado_acumulado)           as liquidado_acumulado,
      sum(pago_acumulado)                as pago_acumulado,
      sum(suplementado_acumulado)        as suplementado_acumulado,
      sum(reduzido_acumulado)            as reduzido_acumulado,
      sum(proj)                          as proj,
      sum(ativ)                          as ativ,
      sum(oper)                          as oper,
      sum(ordinario)                     as ordinario,
      sum(vinculado)                     as vinculado,
      sum(suplemen)                      as suplemen,
      sum(suplemen_acumulado)            as suplemen_acumulado,
      sum(especial)                      as especial,
      sum(especial_acumulado)            as especial_acumulado,
      sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
      sum(reservado_ate_data)            as reservado_ate_data
      from work_dotacao
      group by
      o58_orgao,
      o58_unidade
      ) as b";

  } else {
    $nivel2 = '';
  }

  ///////  nivel 1  /////////////////

  if (1 <= $primeiro_fim || ($desmembra_segundo_inicio == true && $segundo_inicio <= 1 || $desmembra_segundo_inicio == false && $segundo_inicio == 1)) {
    $nivel1 = " select ";
    $nivel1.= "  o58_orgao,
      -1 as o58_unidade,
      -1 as o58_funcao,
      -1 as o58_subfuncao,
      -1 as o58_programa,
      -1 as o58_projativ,
      -1 as o58_codele,
      ''::varchar as o58_elemento,
      -1 as o58_coddot,
      -1 as o58_codigo,
      dot_ini,
      saldo_anterior,
      empenhado,
      anulado,
      liquidado,
      pago,
      suplementado,
      reduzido,
      atual,
      reservado,
      atual_menos_reservado,
      atual_a_pagar,
      atual_a_pagar_liquidado,
      empenhado_acumulado,
      anulado_acumulado,
      liquidado_acumulado,
      pago_acumulado,
      suplementado_acumulado,
      reduzido_acumulado,
      proj,
      ativ,
      oper,
      ordinario,
      vinculado,
      suplemen,
      suplemen_acumulado,
      especial,
      especial_acumulado,
      reservado_manual_ate_data,
      reservado_automatico_ate_data,
      reservado_ate_data
      from (
      select o58_orgao,
      sum(dot_ini)                       as dot_ini,
      sum(saldo_anterior)                as saldo_anterior,
      sum(empenhado)                     as empenhado,
      sum(anulado)                       as anulado,
      sum(liquidado)                     as liquidado,
      sum(pago)                          as pago,
      sum(suplementado)                  as suplementado,
      sum(reduzido)                      as reduzido,
      sum(atual)                         as atual,
      sum(reservado)                     as reservado,
      sum(atual_menos_reservado)         as atual_menos_reservado,
      sum(atual_a_pagar)                 as atual_a_pagar,
      sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
      sum(empenhado_acumulado)           as empenhado_acumulado,
      sum(anulado_acumulado)             as anulado_acumulado,
      sum(liquidado_acumulado)           as liquidado_acumulado,
      sum(pago_acumulado)                as pago_acumulado,
      sum(suplementado_acumulado)        as suplementado_acumulado,
      sum(reduzido_acumulado)            as reduzido_acumulado,
      sum(proj)                          as proj,
      sum(ativ)                          as ativ,
      sum(oper)                          as oper,
      sum(ordinario)                     as ordinario,
      sum(vinculado)                     as vinculado,
      sum(suplemen)                      as suplemen,
      sum(suplemen_acumulado)            as suplemen_acumulado,
      sum(especial)                      as especial,
      sum(especial_acumulado)            as especial_acumulado,
      sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
      sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
      sum(reservado_ate_data)            as reservado_ate_data
      from work_dotacao
      group by o58_orgao) as a ";
  } else {
    $nivel1 = '';
  }
  $sql = '';

  if ($nivel >= 1) {
    if ($nivel1 != '') {
      $sql .= $nivel1;
      if ($tipo_nivel > 1)
        $sql = $nivel1;
    }
  }

  if ($nivel >= 2) {
    if ($nivel2 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel2;
      if ($tipo_nivel > 1)
        $sql = $nivel2;
    }
  }

  if ($nivel >= 3) {
    if ($nivel3 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel3;
      if ($tipo_nivel > 1)
        $sql = $nivel3;
    }
  }
  if ($nivel >= 4) {
    if ($nivel4 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel4;
      if ($tipo_nivel > 1)
        $sql = $nivel4;
    }
  }
  if ($nivel >= 5) {
    if ($nivel5 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel5;
      if ($tipo_nivel > 1)
        $sql = $nivel5;
    }
  }
  if ($nivel >= 6) {
    if ($nivel6 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel6;
      if ($tipo_nivel > 1)
        $sql = $nivel6;
    }
  }
  if ($nivel >= 7) {
    if ($nivel7 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel7;
      if ($tipo_nivel > 1)
        $sql = $nivel7;
    }
  }
  if ($nivel >= 8) {
    if ($nivel8 != '') {
      if ($sql != '')
        $sql .= " union all ";
      $sql .= $nivel8;
      if ($tipo_nivel > 1)
        $sql = $nivel8;
    }

  }

  $sql .= " order by
    o58_orgao,
    o58_unidade,
    o58_funcao,
    o58_subfuncao,
    o58_programa,
    o58_projativ,
    o58_elemento,
    o58_coddot
    ";

  //$sql = " select * from ( $sql ) as l $condicao ";
  //echo $sql;
  //$result = db_query($sql);
  //db_criatabela($result);exit;

  $xordem = '';
  $junta = '';
  // pesquisa as despesas
  if ($primeiro_fim >= 1) {
    $junta .= "case when o58_orgao = -1 then 0 else o58_orgao end as o58_orgao,o40_descr,";
    $xordem .= "o58_orgao,o40_descr,";
  }
  if ($primeiro_fim >= 2) {
    $junta .= "case when o58_unidade = -1 then 0 else o58_unidade end as o58_unidade,o41_descr,";
    $xordem .= "o58_unidade,o41_descr,";
  }
  if ($primeiro_fim >= 3) {
    $junta .= "case when o58_funcao = -1 then 0 else o58_funcao end as o58_funcao,o52_descr,";
    $xordem .= "o58_funcao,o52_descr,";
  }
  if ($primeiro_fim >= 4) {
    $junta .= "case when o58_subfuncao = -1 then 0 else o58_subfuncao end as o58_subfuncao,o53_descr,";
    $xordem .= "o58_subfuncao,o53_descr,";
  }
  if ($primeiro_fim >= 5) {
    $junta .= "case when o58_programa = -1 then 0 else o58_programa end as o58_programa,o54_descr,";
    $xordem .= "o58_programa,o54_descr,";
  }
  if ($primeiro_fim >= 6) {
    $junta .= "case when o58_projativ = -1 then 0 else o58_projativ end as o58_projativ,o55_descr,o55_finali,";
    $xordem .= "o58_projativ,o55_descr,o55_finali,";
  }
  if ($primeiro_fim >= 7) {
    $junta .= "o58_elemento,o56_descr,";
    $xordem .= "o58_elemento,o56_descr,";
  }
  if ($primeiro_fim >= 8) {
    $junta .= "case when o58_coddot = -1 then 0 else o58_coddot end as o58_coddot,case when o58_codigo = -1 then 0 else o58_codigo end as o58_codigo,o15_descr,";
    $xordem .= "o58_codigo,o15_descr,o58_coddot ";
  }

  $virg = '';
  if ($primeiro_fim < 8) {
    $tu_para = false;
    if ($segundo_inicio <= 1) {
      $junta .= $virg."case when o58_orgao = -1 then 0 else o58_orgao end::integer as o58_orgao,o40_descr";
      $xordem .= "o58_orgao,o40_descr";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';
    }
    if ($segundo_inicio <= 2 && $tu_para == false) {
      $junta .= $virg."case when o58_unidade = -1 then 0 else o58_unidade end::integer as o58_unidade,o41_descr";
      $xordem .= $virg."o58_unidade,o41_descr";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';

    }
    if ($segundo_inicio <= 3 && $tu_para == false) {
      $junta .= $virg."case when o58_funcao = -1 then 0 else o58_funcao end::integer as o58_funcao,o52_descr";
      $xordem .= $virg."o58_funcao,o52_descr";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';

    }
    if ($segundo_inicio <= 4 && $tu_para == false) {
      $junta .= $virg."case when o58_subfuncao = -1 then 0 else o58_subfuncao end::integer as o58_subfuncao,o53_descr";
      $xordem .= $virg."o58_subfuncao,o53_descr";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';

    }
    if ($segundo_inicio <= 5 && $tu_para == false) {
      $junta .= $virg."case when o58_programa = -1 then 0 else o58_programa end::integer as o58_programa,o54_descr";
      $xordem .= $virg."o58_programa,o54_descr";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';

    }
    if ($segundo_inicio <= 6 && $tu_para == false) {
      $junta .= $virg."case when o58_projativ = -1 then 0 else o58_projativ end::integer as o58_projativ,o55_descr,o55_finali";
      $xordem .= $virg."o58_projativ,o55_descr,o55_finali";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';

    }
    if ($segundo_inicio <= 7 && $tu_para == false) {
      $junta .= $virg."o58_elemento,o56_descr";
      $xordem .= $virg."o58_elemento,o56_descr";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';
    }
    if ($segundo_inicio <= 8 && $tu_para == false) {
      $junta .= $virg."case when o58_coddot = -1 then 0 else o58_coddot end::integer as o58_coddot,case when o58_codigo = -1 then 0 else o58_codigo end::integer as o58_codigo,o15_descr";
      $xordem .= $virg."o58_codigo,o15_descr,o58_coddot ";
      if ($desmembra_segundo_inicio == false) {
        $tu_para = true;
      }
      $virg = ',';
    }

  }
  $junta .= $virg;

  $sql2 = "select ".$junta."
    sum(dot_ini)                       as dot_ini,
    sum(saldo_anterior)                as saldo_anterior,
    sum(empenhado)                     as empenhado,
    sum(anulado)                       as anulado,
    sum(liquidado)                     as liquidado,
    sum(pago)                          as pago,
    sum(suplementado)                  as suplementado,
    sum(reduzido)                      as reduzido,
    sum(atual)                         as atual,
    sum(reservado)                     as reservado,
    sum(atual_menos_reservado)         as atual_menos_reservado,
    sum(atual_a_pagar)                 as atual_a_pagar,
    sum(atual_a_pagar_liquidado)       as atual_a_pagar_liquidado,
    sum(empenhado_acumulado)           as empenhado_acumulado,
    sum(anulado_acumulado)             as anulado_acumulado,
    sum(liquidado_acumulado)           as liquidado_acumulado,
    sum(pago_acumulado)                as pago_acumulado,
    sum(suplementado_acumulado)        as suplementado_acumulado,
    sum(reduzido_acumulado)            as reduzido_acumulado,
    sum(proj)                          as proj,
    sum(ativ)                          as ativ,
    sum(oper)                          as oper,
    sum(ordinario)                     as ordinario,
    sum(vinculado)                     as vinculado,
    sum(suplemen)                      as suplemen,
    sum(suplemen_acumulado)            as suplemen_acumulado,
    sum(especial)                      as especial,
    sum(especial_acumulado)            as especial_acumulado,
    sum(reservado_manual_ate_data)     as reservado_manual_ate_data,
    sum(reservado_automatico_ate_data) as reservado_automatico_ate_data,
    sum(reservado_ate_data)            as reservado_ate_data
    from( ( $sql ) as xx
    left  outer join orcorgao      o on o40_anousu 	 = $anousu and o.o40_orgao = o58_orgao
    left  outer join orcunidade    u on o41_anousu 	 = $anousu and u.o41_orgao = o58_orgao and u.o41_unidade= o58_unidade
    left  outer join orcfuncao     f on f.o52_funcao 	 = o58_funcao
    left  outer join orcsubfuncao  s on o53_subfuncao 	 = o58_subfuncao
    left  outer join orcprograma   p on o54_anousu 	 = $anousu and o54_programa = o58_programa
    left  outer join orcprojativ  pa on o55_anousu 	 = $anousu and o55_projativ = o58_projativ
    left  outer join orcelemento  oe on oe.o56_elemento = o58_elemento and
    oe.o56_anousu = $anousu
    left  outer join orctiporec  otr on o15_codigo   	 = o58_codigo
    ) as x
    group by ".$xordem."
    order by ".$xordem;

  if ($descr == true) {
    if ($retsql == false) {
      $resultdotacao = db_query($sql2);
    } else {
      $resultdotacao = $sql2;
    }
  } else {
    if ($retsql == false) {
      $resultdotacao = db_query($sql);
    } else {
      $resultdotacao = $sql;
    }
  }

  return $resultdotacao;

}

function db_receitasaldo($nivel = 11, $tipo_nivel = 1, $tipo_saldo = 2, $descr = true, $where = '', $anousu = null, $dataini = null, $datafim = null, $query = false, $campos = ' * ', $comit = true,$nivel_agrupar=0) {

  if ($anousu == null || $anousu == "")
    $anousu = db_getsession("DB_anousu");

  if ($dataini == null)
    $dataini = date('Y-m-d', db_getsession('DB_datausu'));

  if ($datafim == null)
    $datafim = date('Y-m-d', db_getsession('DB_datausu'));

  if ($where != '') {
    $condicao = " and ".$where;
  } else {
    $condicao = "";
  }

  //$instit = db_getsession("DB_instit");

  //#00#//db_receitaosaldo
  //#10#//Esta funcao retorna o recordset do saldo das receitas
  //#15#//db_receitasaldo($nivel=8, $tipo_nivel=1, $tipo_saldo=2, $descr=true, $where='', $anousu=null, $dataini=null, $datafim=null,$query=false)
  //#20#//$nivel      : Até qual o nível será apurado o saldo, pode ser:
  //#20#//              1 - classe
  //#20#//              2 - grupo
  //#20#//              3 - subgrupo
  //#20#//              4 - elemento
  //#20#//              5 - subelemento
  //#20#//              6 - item
  //#20#//              7 - subitem
  //#20#//              8 - desdobramento1
  //#20#//              9 - desdobramento2
  //#20#//             10 - desdobramento3
  //#20#//             11 - recurso
  //#20#//
  //#20#//$tipo_nivel : especifica a maneira de como será apurado o resultado, pode ser:
  //#20#//              1 - traz a árvore de elementos até o nível solicitado
  //#20#//                  Ex.: 4.1                  200
  //#20#//                       4.1.1                100
  //#20#//                       4.1.1.1               50
  //#20#//                       4.1.1.2               50
  //#20#//                       4.1.2                100
  //#20#//                       4.1.2.1               50
  //#20#//                       4.1.2.2               50
  //#20#//              2 - traz o saldo do nível escolhido
  //#20#//                  Ex.: 4.1.1.1               50
  //#20#//              3 - totaliza o saldo pelo nível escolhido
  //#20#//                  Ex.: 0.0.0.1             1000
  //#20#//
  //#20#//$tipo_saldo :
  //#20#//	      1 - SALDO INICIAL DA RECEITA - ORCAMENTO
  //#20#//	      2 - SALDO DA RECEITA  MENOS O ARRECADADO
  //#20#//   	      3 - SALDO DA RECEITA  PELA CONTABILIDADE ...
  //#20#//   	      4 - SALDO ACUMULADO POR MES
  //#20#//
  //#20#//$descr         : retorna o record set com as descrições ou não, o default é 'true'
  //#20#//
  //#20#//$where         : condição
  //#20#//
  //#20#//$anousu        : ano do orçamento
  //#20#//
  //#20#//$dataini       : data inicial do intervalo
  //#20#//
  //#20#//$datafim       : data final do intervalo
  //#20#//
  //#20#//$query         : retorna somente o sql, o default é retornar o recordset
  //#20#//
  //#20#//$nivel_agrupar : 0 - normal
  //#20#//                 1 - agrupa na receita
  //#20#//                 2 - nao agrupa na receita


  //$nivel      = 2;
  //$tipo_nivel = 1;
  //$dataini    = date('Y-m-d',db_getsession("DB_datausu"));
  //$datafim    = date('Y-m-d',db_getsession("DB_datausu"));
  //$anousu     = db_getsession("DB_anousu");

  // funcao para gerar work

  if ($comit == true) {
    db_query('begin');
  }


  if( $anousu > 2007 ){


    $sql = "
    create temp table work_receita as
    ";
    if( $nivel_agrupar == 1 ) {
      $sql .= "

      select classe,
             grupo,
             subgrupo,
             elemento,
             subelemento,
             item,
             subitem,
             desdobramento1,
             desdobramento2,
             desdobramento3,
             0 as o70_codrec,
             '0' as o70_concarpeculiar,
             0 as o70_codigo,
             sum(saldo_inicial) as saldo_inicial,
             sum(saldo_prevadic_acum) as saldo_prevadic_acum,
             sum(saldo_inicial_prevadic) as saldo_inicial_prevadic,
             sum(saldo_anterior) as saldo_anterior,
             sum(saldo_arrecadado) as saldo_arrecadado,
             sum(saldo_a_arrecadar) as saldo_a_arrecadar,
             sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado,
             sum(saldo_prev_anterior) as saldo_prev_anterior

      from (

    ";
    }
    $sql .= "
    select ";
    if( $nivel_agrupar == 1 || $nivel_agrupar == 2 ) {
      $sql .= "
         case when substr(o57_fonte,1,1)='9' then '4' else substr(o57_fonte,1,1) end::int4 as classe,
      ";
    }else if( $nivel_agrupar == 0 ){
      $sql .= " substr(o57_fonte,1,1)::int4 as classe, ";
    }
    $sql .= "
    substr(o57_fonte,2,1)::int4  as grupo,
    substr(o57_fonte,3,1)::int4  as subgrupo,
    substr(o57_fonte,4,1)::int4  as elemento,
    substr(o57_fonte,5,1)::int4  as subelemento,
    substr(o57_fonte,6,2)::int4  as item,
    substr(o57_fonte,8,2)::int4  as subitem,
    substr(o57_fonte,10,2)::int4 as desdobramento1,
    substr(o57_fonte,12,2)::int4 as desdobramento2,
    substr(o57_fonte,14,2)::int4 as desdobramento3,
    o70_codrec,
    o70_concarpeculiar,
    o70_codigo,
    cast(coalesce(nullif(substr(fc_receitasaldo, 3,12),''),'0') as float8) as saldo_inicial,
    cast(coalesce(nullif(substr(fc_receitasaldo,16,12),''),'0') as float8) as saldo_prevadic_acum,
    cast(coalesce(nullif(substr(fc_receitasaldo,29,12),''),'0') as float8) as saldo_inicial_prevadic,
    cast(coalesce(nullif(substr(fc_receitasaldo,42,12),''),'0') as float8) as saldo_anterior,
    cast(coalesce(nullif(substr(fc_receitasaldo,55,12),''),'0') as float8) as saldo_arrecadado,
    cast(coalesce(nullif(substr(fc_receitasaldo,68,12),''),'0') as float8) as saldo_a_arrecadar,
    cast(coalesce(nullif(substr(fc_receitasaldo,81,12),''),'0') as float8) as saldo_arrecadado_acumulado,
    cast(coalesce(nullif(substr(fc_receitasaldo,94,12),''),'0') as float8) as saldo_prev_anterior
    from(select o70_anousu, o70_codrec, o70_codfon, o70_codigo, o70_valor, o70_reclan,
                o70_instit, o70_concarpeculiar,
                o57_codfon, o57_anousu, o57_fonte, o57_descr, o57_finali,
                fc_receitasaldo($anousu,o70_codrec,$tipo_saldo,'$dataini','$datafim')
    from orcreceita d
    inner join orcfontes e on d.o70_codfon = e.o57_codfon and e.o57_anousu = d.o70_anousu
    where o70_anousu = $anousu
    $condicao
    order by o57_fonte
    ) as x ";



    if( $nivel_agrupar == 1 ) {
      $sql .= "
      ) as x
      group by classe,
               grupo,
               subgrupo,
               elemento,
               subelemento,
               item,
               subitem,
               desdobramento1,
               desdobramento2,
               desdobramento3,
               o70_codrec,
               o70_codigo

      ";
    }

    $result = db_query($sql);
    //$result = db_query("select * from work_receita");
    //db_criatabela($result);exit;

    //$nivel = 11;
    //$tipo_nivel = 2;

    ///////// Nivel 11 /////////////

    $xnivel11 = '';
    if ($nivel >= 11) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel11 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,
        desdobramento1,
        desdobramento2,
        desdobramento3,
        o70_codrec,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel11 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,
        0 as desdobramento1,
        0 as desdobramento2,
        0 as desdobramento3,
        0 as o70_codrec,";
      }
    }
    $nivel11 = " select
    $xnivel11
    o70_concarpeculiar,
    o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel11
    o70_concarpeculiar,
    o70_codigo,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel11 .= "     classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,
      desdobramento1,
      desdobramento2,
      desdobramento3,
      o70_codrec,
";
    }
    $nivel11 .= "
    o70_codigo,
    o70_concarpeculiar
    ) as m
     ";

    //$result = db_query($nivel11);
    //db_criatabela($result);exit;

    ///////// Nivel 10 /////////////

    $xnivel10 = '';
    if ($nivel >= 10) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel10 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,
        desdobramento1,
        desdobramento2,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel10 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,
        0 as desdobramento1,
        0 as desdobramento2,";
      }
    }
    $nivel10 = " select
    $xnivel10
    desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel10
    desdobramento3,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel10 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,
      desdobramento1,
      desdobramento2,
      ";
    }
    $nivel10 .= "	      desdobramento3
    ) as l";

    ///////// Nivel 9 /////////////

    $xnivel9 = '';
    if ($nivel >= 9) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel9 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,
        desdobramento1,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel9 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,
        0 as desdobramento1,";
      }
    }
    $nivel9 = " select
    $xnivel9
    desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior

    from (
    select
    $xnivel9
    desdobramento2,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel9 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,
      desdobramento1,";
    }
    $nivel9 .= "	      desdobramento2
    ) as i";

    ///////// Nivel 8 //////////////

    $xnivel8 = '';
    if ($nivel >= 8) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel8 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel8 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,";
      }
    }
    $nivel8 = " select
    $xnivel8
    desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel8
    desdobramento1,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel8 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,";
    }
    $nivel8 .= "	      desdobramento1
    ) as h";

    ///////// Nivel 7 //////////////

    $xnivel7 = '';
    if ($nivel >= 7) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel7 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel7 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,";
      }
    }
    $nivel7 = " select
    $xnivel7
    subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel7
    subitem,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel7 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,";
    }
    $nivel7 .= "	      subitem
    ) as g";

    ///////// Nivel 6 //////////////

    $xnivel6 = '';
    if ($nivel >= 6) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel6 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel6 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,";
      }
    }
    $nivel6 = " select
    $xnivel6
    item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel6
    item,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel6 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,";
    }
    $nivel6 .= "	      item
    ) as f";

    ///////// Nivel 5 //////////////

    $xnivel5 = '';
    if ($nivel >= 5) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel5 = " classe,
        grupo,
        subgrupo,
        elemento,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel5 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,";
      }
    }
    $nivel5 = " select
    $xnivel5
    subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel5
    subelemento,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel5 .= "      classe,
      grupo,
      subgrupo,
      elemento,";
    }
    $nivel5 .= "	      subelemento
    ) as e";

    ///////// Nivel 4 //////////////

    $xnivel4 = '';
    if ($nivel >= 4) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel4 = " classe,
        grupo,
        subgrupo,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel4 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,";
      }
    }
    $nivel4 = " select
    $xnivel4
    elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel4
    elemento,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel4 .= "      classe,
      grupo,
      subgrupo,";
    }
    $nivel4 .= "	      elemento
    ) as d";

    ///////// Nivel 3 //////////////

    $xnivel3 = '';
    if ($nivel >= 3) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel3 = " classe,
        grupo,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel3 = " 0 as classe,
        0 as grupo,";
      }
    }
    $nivel3 = " select
    $xnivel3
    subgrupo,
    0 as elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel3
    subgrupo,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel3 .= "      classe,
      grupo,";
    }
    $nivel3 .= "	      subgrupo
    ) as c";

    ///////// Nivel 2 //////////////

    $nivel2 = " select ";
    $nivel2 .= " classe,
    grupo,
    0 as subgrupo,
    0 as elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    classe,
    grupo,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by
    classe,
    grupo
    ) as a ";

    ///////// Nivel 1 //////////////

    $nivel1 = " select ";
    $nivel1 .= " classe,
    0 as grupo,
    0 as subgrupo,
    0 as elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    '0' as o70_concarpeculiar,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select classe,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by classe) as a ";

    $sql = '';

    if ($nivel >= 1) {
      $sql .= $nivel1;
      if ($tipo_nivel > 1)
        $sql = $nivel1;
    }
    if ($nivel >= 2) {
      $sql .= " union all ";
      $sql .= $nivel2;
      if ($tipo_nivel > 1)
        $sql = $nivel2;
    }
    if ($nivel >= 3) {
      $sql .= " union all ";
      $sql .= $nivel3;
      if ($tipo_nivel > 1)
        $sql = $nivel3;
    }
    if ($nivel >= 4) {
      $sql .= " union all ";
      $sql .= $nivel4;
      if ($tipo_nivel > 1)
        $sql = $nivel4;
    }
    if ($nivel >= 5) {
      $sql .= " union all ";
      $sql .= $nivel5;
      if ($tipo_nivel > 1)
        $sql = $nivel5;
    }
    if ($nivel >= 6) {
      $sql .= " union all ";
      $sql .= $nivel6;
      if ($tipo_nivel > 1)
        $sql = $nivel6;
    }
    if ($nivel >= 7) {
      $sql .= " union all ";
      $sql .= $nivel7;
      if ($tipo_nivel > 1)
        $sql = $nivel7;
    }
    if ($nivel >= 8) {
      $sql .= " union all ";
      $sql .= $nivel8;
      if ($tipo_nivel > 1)
        $sql = $nivel8;

    }
    if ($nivel >= 9) {
      $sql .= " union all ";
      $sql .= $nivel9;
      if ($tipo_nivel > 1)
        $sql = $nivel9;

    }
    if ($nivel >= 10) {
      $sql .= " union all ";
      $sql .= $nivel10;
      if ($tipo_nivel > 1)
        $sql = $nivel10;

    }

    if ($nivel >= 11) {
      $sql .= " union all ";
      $sql .= $nivel11;
      if ($tipo_nivel > 1){
        $sql = $nivel11;
      }
    }

    //$res =  db_query($nivel11);
    //db_criatabela($res);exit;

    $sql .= " order by
    classe,
    grupo,
    subgrupo,
    elemento,
    subelemento,
    item,
    subitem,
    desdobramento1,
    desdobramento2,
    desdobramento3,
    o70_codrec,
    o70_concarpeculiar,
    o70_codigo";
    //echo $sql;
    //   $result = db_query($sql);
    //  db_criatabela($result);exit;

    if ($nivel == 11 && $tipo_nivel == 3) {
      $demaiscampos = '';
      $demaiscampos1 = '';
    } else {
      if( $nivel_agrupar == 2 ){
        $demaiscampos = "distinct on (o57_fonte,o70_concarpeculiar) o57_fonte,o70_concarpeculiar, ";
        $demaiscampos1 = "o57_fonte,o70_concarpeculiar,";
      }else{
        $demaiscampos = "distinct on (o57_fonte,o70_concarpeculiar) o57_fonte,o70_concarpeculiar, ";
        $demaiscampos1 = "o57_fonte,o70_concarpeculiar,";
      }
    }

    $sql2 = "
    select $campos from (
    select $demaiscampos o70_codrec,
    o70_codigo,o57_descr,
    o15_descr,
    saldo_inicial		as saldo_inicial,
    saldo_prevadic_acum		as saldo_prevadic_acum,
    saldo_inicial_prevadic	as saldo_inicial_prevadic,
    saldo_anterior		as saldo_anterior,
    saldo_arrecadado		as saldo_arrecadado,
    saldo_a_arrecadar		as saldo_a_arrecadar,
    saldo_arrecadado_acumulado	as saldo_arrecadado_acumulado,
    saldo_prev_anterior		as saldo_prev_anterior
    from
    ( select distinct
    (cast(classe      as varchar)||
     cast(grupo       as varchar)||
     cast(subgrupo    as varchar)||
     cast(elemento    as varchar)||
     cast(subelemento as varchar)||
    lpad(item,2,0)||
    lpad(subitem,2,0)||
    lpad(desdobramento1,2,0)||
    lpad(desdobramento2,2,0)||
    lpad(desdobramento3,2,0))::varchar(15) as o57_fonte,
    c60_descr as o57_descr,
    o15_descr,
    x.*
    from ($sql) as x
    left outer join conplano y on
    y.c60_estrut =
    cast(classe       as varchar)||
    cast(grupo        as varchar)||
    cast(subgrupo     as varchar)||
    cast(elemento     as varchar)||
    cast(subelemento  as varchar)||
    lpad(item,2,0)||
    lpad(subitem,2,0)||
    lpad(desdobramento1,2,0)||
    lpad(desdobramento2,2,0)||
    lpad(desdobramento3,2,0)
    and c60_anousu = $anousu
    left join conplanoreduz on c61_codcon = c60_codcon and c61_anousu = c60_anousu
    left outer join orctiporec on o70_codigo = o15_codigo

    where c61_reduz is null

    order by
    classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3,
    o70_codrec
    )as yy
    order by $demaiscampos1 o70_codrec desc,o57_descr,

    o70_codigo,
    o15_descr,
    saldo_inicial		,
    saldo_prevadic_acum	,
    saldo_inicial_prevadic,
    saldo_anterior		,
    saldo_arrecadado	,
    saldo_a_arrecadar	,
    saldo_arrecadado_acumulado	,
    saldo_prev_anterior
    ) as xx";

    if ($nivel == 11) {
      $sql2 .=" union all
								select $demaiscampos
											 o70_codrec,
											 o70_codigo,
											 o57_descr,
											 o15_descr,
											 saldo_inicial,
											 saldo_prevadic_acum,
											 saldo_inicial_prevadic,
											 saldo_anterior,
											 saldo_arrecadado,
											 saldo_a_arrecadar,
											 saldo_arrecadado_acumulado,
											 saldo_prev_anterior
									from work_receita
											 left outer join orcfontes y on  y.o57_fonte =
											 cast(classe      as varchar)||
											 cast(grupo       as varchar)||
											 cast(subgrupo    as varchar)||
											 cast(elemento    as varchar)||
											 cast(subelemento as varchar)||
											 lpad(item,2,0)||
											 lpad(subitem,2,0)||
											 lpad(desdobramento1,2,0)||
											 lpad(desdobramento2,2,0)||
											 lpad(desdobramento3,2,0)
											 and o57_anousu = $anousu
											 left outer join orctiporec on o70_codigo = o15_codigo";

    }

    if ( $demaiscampos != ""){
      $sql2 .= " order by o57_fonte";
    }

    //    echo $sql2;
    //    $res =  db_query($sql2);
    //    db_criatabela($res);exit;



  }else{

    // aqui inicia o codigo de 2007

    $sql = "create temp table work_receita as
    select
    substr(o57_fonte,1,1)::int4  as classe,
    substr(o57_fonte,2,1)::int4  as grupo,
    substr(o57_fonte,3,1)::int4  as subgrupo,
    substr(o57_fonte,4,1)::int4  as elemento,
    substr(o57_fonte,5,1)::int4  as subelemento,
    substr(o57_fonte,6,2)::int4  as item,
    substr(o57_fonte,8,2)::int4  as subitem,
    substr(o57_fonte,10,2)::int4 as desdobramento1,
    substr(o57_fonte,12,2)::int4 as desdobramento2,
    substr(o57_fonte,14,2)::int4 as desdobramento3,
    o70_codrec,
    o70_codigo,
    cast(coalesce(nullif(substr(fc_receitasaldo, 3,12),''),'0') as float8) as saldo_inicial,
    cast(coalesce(nullif(substr(fc_receitasaldo,16,12),''),'0') as float8) as saldo_prevadic_acum,
    cast(coalesce(nullif(substr(fc_receitasaldo,29,12),''),'0') as float8) as saldo_inicial_prevadic,
    cast(coalesce(nullif(substr(fc_receitasaldo,42,12),''),'0') as float8) as saldo_anterior,
    cast(coalesce(nullif(substr(fc_receitasaldo,55,12),''),'0') as float8) as saldo_arrecadado,
    cast(coalesce(nullif(substr(fc_receitasaldo,68,12),''),'0') as float8) as saldo_a_arrecadar,
    cast(coalesce(nullif(substr(fc_receitasaldo,81,12),''),'0') as float8) as saldo_arrecadado_acumulado,
    cast(coalesce(nullif(substr(fc_receitasaldo,94,12),''),'0') as float8) as saldo_prev_anterior
    from(select *, fc_receitasaldo($anousu,o70_codrec,$tipo_saldo,'$dataini','$datafim')
    from orcreceita d
    inner join orcfontes e on d.o70_codfon = e.o57_codfon and e.o57_anousu = d.o70_anousu
    where o70_anousu = $anousu
    $condicao
    order by o57_fonte
    ) as x
    ";

    $result = db_query($sql);
    //db_criatabela($result); exit;
    //$result = db_query("select * from work_receita");db_criatabela($result);exit;

    //$nivel = 11;
    //$tipo_nivel = 2;

    ///////// Nivel 11 /////////////

    $xnivel11 = '';
    if ($nivel >= 11) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel11 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,
        desdobramento1,
        desdobramento2,
        desdobramento3,
        o70_codrec,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel11 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,
        0 as desdobramento1,
        0 as desdobramento2,
        0 as desdobramento3,
        0 as o70_codrec,";
      }
    }
    $nivel11 = " select
    $xnivel11
    o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel11
    o70_codigo,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel11 .= "     classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,
      desdobramento1,
      desdobramento2,
      desdobramento3,
      o70_codrec,";
    }
    $nivel11 .= "
    o70_codigo
    ) as m";
    ///////// Nivel 10 /////////////

    $xnivel10 = '';
    if ($nivel >= 10) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel10 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,
        desdobramento1,
        desdobramento2,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel10 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,
        0 as desdobramento1,
        0 as desdobramento2,";
      }
    }
    $nivel10 = " select
    $xnivel10
    desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel10
    desdobramento3,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel10 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,
      desdobramento1,
      desdobramento2,
      ";
    }
    $nivel10 .= "	      desdobramento3
    ) as l";

    ///////// Nivel 9 /////////////

    $xnivel9 = '';
    if ($nivel >= 9) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel9 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,
        desdobramento1,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel9 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,
        0 as desdobramento1,";
      }
    }
    $nivel9 = " select
    $xnivel9
    desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior

    from (
    select
    $xnivel9
    desdobramento2,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel9 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,
      desdobramento1,";
    }
    $nivel9 .= "	      desdobramento2
    ) as i";

    ///////// Nivel 8 //////////////

    $xnivel8 = '';
    if ($nivel >= 8) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel8 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,
        subitem,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel8 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,
        0 as subitem,";
      }
    }
    $nivel8 = " select
    $xnivel8
    desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel8
    desdobramento1,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel8 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,
      subitem,";
    }
    $nivel8 .= "	      desdobramento1
    ) as h";

    ///////// Nivel 7 //////////////

    $xnivel7 = '';
    if ($nivel >= 7) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel7 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,
        item,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel7 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,
        0 as item,";
      }
    }
    $nivel7 = " select
    $xnivel7
    subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel7
    subitem,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel7 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,
      item,";
    }
    $nivel7 .= "	      subitem
    ) as g";

    ///////// Nivel 6 //////////////

    $xnivel6 = '';
    if ($nivel >= 6) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel6 = " classe,
        grupo,
        subgrupo,
        elemento,
        subelemento,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel6 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,
        0 as subelemento,";
      }
    }
    $nivel6 = " select
    $xnivel6
    item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel6
    item,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel6 .= "      classe,
      grupo,
      subgrupo,
      elemento,
      subelemento,";
    }
    $nivel6 .= "	      item
    ) as f";

    ///////// Nivel 5 //////////////

    $xnivel5 = '';
    if ($nivel >= 5) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel5 = " classe,
        grupo,
        subgrupo,
        elemento,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel5 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,
        0 as elemento,";
      }
    }
    $nivel5 = " select
    $xnivel5
    subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel5
    subelemento,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel5 .= "      classe,
      grupo,
      subgrupo,
      elemento,";
    }
    $nivel5 .= "	      subelemento
    ) as e";

    ///////// Nivel 4 //////////////

    $xnivel4 = '';
    if ($nivel >= 4) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel4 = " classe,
        grupo,
        subgrupo,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel4 = " 0 as classe,
        0 as grupo,
        0 as subgrupo,";
      }
    }
    $nivel4 = " select
    $xnivel4
    elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel4
    elemento,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel4 .= "      classe,
      grupo,
      subgrupo,";
    }
    $nivel4 .= "	      elemento
    ) as d";

    ///////// Nivel 3 //////////////

    $xnivel3 = '';
    if ($nivel >= 3) {
      if ($tipo_nivel == 1 || $tipo_nivel == 2) {
        $xnivel3 = " classe,
        grupo,";
      }
      elseif ($tipo_nivel == 3) {
        $xnivel3 = " 0 as classe,
        0 as grupo,";
      }
    }
    $nivel3 = " select
    $xnivel3
    subgrupo,
    0 as elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    $xnivel3
    subgrupo,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by ";

    if ($tipo_nivel != 3) {
      $nivel3 .= "      classe,
      grupo,";
    }
    $nivel3 .= "	      subgrupo
    ) as c";

    ///////// Nivel 2 //////////////

    $nivel2 = " select ";
    $nivel2 .= " classe,
    grupo,
    0 as subgrupo,
    0 as elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select
    classe,
    grupo,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by
    classe,
    grupo
    ) as a ";

    ///////// Nivel 1 //////////////

    $nivel1 = " select ";
    $nivel1 .= " classe,
    0 as grupo,
    0 as subgrupo,
    0 as elemento,
    0 as subelemento,
    0 as item,
    0 as subitem,
    0 as desdobramento1,
    0 as desdobramento2,
    0 as desdobramento3,
    0 as o70_codrec,
    0 as o70_codigo,
    saldo_inicial,
    saldo_prevadic_acum,
    saldo_inicial_prevadic,
    saldo_anterior,
    saldo_arrecadado,
    saldo_a_arrecadar,
    saldo_arrecadado_acumulado,
    saldo_prev_anterior
    from (
    select classe,
    sum(saldo_inicial)     		as saldo_inicial,
    sum(saldo_prevadic_acum) 		as saldo_prevadic_acum,
    sum(saldo_inicial_prevadic) 		as saldo_inicial_prevadic,
    sum(saldo_anterior)    		as saldo_anterior,
    sum(saldo_arrecadado)  		as saldo_arrecadado,
    sum(saldo_a_arrecadar) 		as saldo_a_arrecadar,
    sum(saldo_arrecadado_acumulado) 	as saldo_arrecadado_acumulado,
    sum(saldo_prev_anterior) 		as saldo_prev_anterior
    from work_receita
    group by classe) as a ";

    $sql = '';

    if ($nivel >= 1) {
      $sql .= $nivel1;
      if ($tipo_nivel > 1)
        $sql = $nivel1;
    }
    if ($nivel >= 2) {
      $sql .= " union all ";
      $sql .= $nivel2;
      if ($tipo_nivel > 1)
        $sql = $nivel2;
    }
    if ($nivel >= 3) {
      $sql .= " union all ";
      $sql .= $nivel3;
      if ($tipo_nivel > 1)
        $sql = $nivel3;
    }
    if ($nivel >= 4) {
      $sql .= " union all ";
      $sql .= $nivel4;
      if ($tipo_nivel > 1)
        $sql = $nivel4;
    }
    if ($nivel >= 5) {
      $sql .= " union all ";
      $sql .= $nivel5;
      if ($tipo_nivel > 1)
        $sql = $nivel5;
    }
    if ($nivel >= 6) {
      $sql .= " union all ";
      $sql .= $nivel6;
      if ($tipo_nivel > 1)
        $sql = $nivel6;
    }
    if ($nivel >= 7) {
      $sql .= " union all ";
      $sql .= $nivel7;
      if ($tipo_nivel > 1)
        $sql = $nivel7;
    }
    if ($nivel >= 8) {
      $sql .= " union all ";
      $sql .= $nivel8;
      if ($tipo_nivel > 1)
        $sql = $nivel8;

    }
    if ($nivel >= 9) {
      $sql .= " union all ";
      $sql .= $nivel9;
      if ($tipo_nivel > 1)
        $sql = $nivel9;

    }
    if ($nivel >= 10) {
      $sql .= " union all ";
      $sql .= $nivel10;
      if ($tipo_nivel > 1)
        $sql = $nivel10;

    }
    if ($nivel >= 11) {
      $sql .= " union all ";
      $sql .= $nivel11;
      if ($tipo_nivel > 1)
        $sql = $nivel11;

    }

    $sql .= " order by
    classe,
    grupo,
    subgrupo,
    elemento,
    subelemento,
    item,
    subitem,
    desdobramento1,
    desdobramento2,
    desdobramento3,
    o70_codrec,
    o70_codigo";
    //echo $sql;
    //$result = db_query($sql);
    //db_criatabela($result);exit;

    if ($nivel == 11 && $tipo_nivel == 3) {
      $demaiscampos = '';
    } else {
      $demaiscampos = "distinct on (o57_fonte,o57_descr) o57_fonte,o57_descr, o70_codrec,";
    }

    $sql2 = "
    select $campos from (
    select $demaiscampos
    o70_codigo,
    o15_descr,
    saldo_inicial		as saldo_inicial,
    saldo_prevadic_acum		as saldo_prevadic_acum,
    saldo_inicial_prevadic	as saldo_inicial_prevadic,
    saldo_anterior		as saldo_anterior,
    saldo_arrecadado		as saldo_arrecadado,
    saldo_a_arrecadar		as saldo_a_arrecadar,
    saldo_arrecadado_acumulado	as saldo_arrecadado_acumulado,
    saldo_prev_anterior		as saldo_prev_anterior
    from
    ( select distinct
    (cast(classe      as varchar)||
     cast(grupo       as varchar)||
     cast(subgrupo    as varchar)||
     cast(elemento    as varchar)||
     cast(subelemento as varchar)||
    lpad(item,2,0)||
    lpad(subitem,2,0)||
    lpad(desdobramento1,2,0)||
    lpad(desdobramento2,2,0)||
    lpad(desdobramento3,2,0))::varchar(15) as o57_fonte,
    o57_descr,
    o15_descr,
    x.*
    from ($sql) as x
    left outer join orcfontes y on
    y.o57_fonte =
    cast(classe      as varchar)||
    cast(grupo       as varchar)||
    cast(subgrupo    as varchar)||
    cast(elemento    as varchar)||
    cast(subelemento as varchar)||
    lpad(item,2,0)||
    lpad(subitem,2,0)||
    lpad(desdobramento1,2,0)||
    lpad(desdobramento2,2,0)||
    lpad(desdobramento3,2,0)
    and o57_anousu = $anousu
    left outer join orctiporec on o70_codigo = o15_codigo

    order by
    classe,
    grupo,
    subgrupo,
    elemento,
    subelemento,
    item,
    subitem,
    desdobramento1,
    desdobramento2,
    desdobramento3,
    o70_codrec desc)as yy) as xx";

    // aqui termina o codigo de 2007
  }


  //db_criatabela(db_query($sql2));
  //exit;
  if ($comit == true) {
    db_query('commit');
  }

  /**
   * Validacao geracao do pad, quando pcasp ativo e rodar o balancete em ano anterior
   */
  $iAnoPcasp = 2013;
  if ( file_exists("config/pcasp.txt") ) {

    $aArquivo  = file("config/pcasp.txt");
    if ($aArquivo[0] != '' && $aArquivo[0] > 2013) {
      $iAnoPcasp = $aArquivo[0];
    }
  }

  $_SESSION["DB_ano_pcasp"] = $iAnoPcasp;
  $sControlePcasp = $_SESSION["DB_use_pcasp"];
  if ($anousu < intval($iAnoPcasp) ) {
    $_SESSION["DB_use_pcasp"] = 'f';
  }

  /**
   * Adicionado validação para que só realize o parse caso o ano desejado seja superior ao ano de inicio do PCASP.
   * Essa validação foi criada devido a criação do arquivo do PAD BALREC_ANT estava dando problema
   */
  if ((int)$anousu >= (int)$iAnoPcasp) {

    $sql2 = analiseQueryPlanoOrcamento($sql2, $anousu);
    $sql  = analiseQueryPlanoOrcamento($sql, $anousu);
  }


  if ($descr == true) {
    if ($query == false) {
      $resultreceita = db_query($sql2);
    } else {
      $resultreceita = $sql2;
    }
  } else {
    if ($query == false) {
      $resultreceita = db_query($sql);
    } else {
      $resultreceita = $sql;
    }
  }
  $_SESSION["DB_use_pcasp"] = $sControlePcasp;
  return $resultreceita;


}

class cl_permusuario_dotacao {
  var $recordset = null;
  var $numrows = null;
  var $sql = null;
  var $orgaos = null;
  var $depart = null;
  var $secretaria = null;
  var $departamento = null;
  var $msg_erro = null;
  function cl_permusuario_dotacao($anousu, $idusuario, $elemento = null, $secretaria = null, $departamento = null,$tipoperm='M', $instit="", $sWhere =null) {
    global $db20_orgao, $db20_unidade, $db20_funcao, $db20_subfuncao, $db20_programa, $db20_projativ, $db20_codele, $db20_codigo;

    $instituicoes = empty($instit)?db_getsession("DB_instit"):$instit;

    if ($secretaria != null) {
      $this->secretaria = $secretaria;
    }
    if ($departamento != null) {
      $this->departamento = $departamento;
    }
    if ($tipoperm!=""){
      $tipoperm = "db20_tipoperm='$tipoperm' and";
    } else {
      $tipoperm = "";
    }
    $sqle = " SELECT o50_subelem
      FROM orcparametro
      WHERE o50_anousu = $anousu	";

    $result = db_query($sqle);
    if (pg_numrows($result) == 0) {
      $this->msg_erro = "Parametro do orçamento não encontrado para o exercício: $anousu";
      return false;
    }
    $subele = pg_result($result, 0, 0);
    if ($subele == 'f') {
      $elemento = substr($elemento, 0, 7);
    }
    $sqle = " SELECT 'USUARIO' AS TIPO ,DB_PERMEMP.*
      FROM DB_PERMEMP
      INNER JOIN DB_USUPERMEMP U ON U.DB21_CODPERM = DB20_CODPERM AND U.DB21_ID_USUARIO = $idusuario
      LEFT OUTER JOIN DB_DEPARTORG D ON DB20_ORGAO = D.DB01_ORGAO AND D.DB01_ANOUSU = ".db_getsession("DB_anousu")."
      WHERE $tipoperm DB20_ANOUSU = $anousu
      ";
    if (isset ($secretaria)) {
      $sqle .= " and DB20_ORGAO = $secretaria ";
    }
    if (isset ($departamento)) {
      $sqle .= " and db01_coddepto = $departamento ";
    }

    $sqle .= "
      UNION
      SELECT 'SETOR' AS TIPO ,DB_PERMEMP.*
      FROM DB_PERMEMP
      /* seleciona todos os departamentos liberados para o usuario */
      INNER JOIN DB_DEPUSU  ON ID_USUARIO = $idusuario
      /* verifica se ha permissoes liberadas para algum departamento do usuario */
      INNER JOIN DB_DEPUSUEMP  ON DB22_CODPERM = DB20_CODPERM AND DB22_CODDEPTO = CODDEPTO
      /* traz os departamentos do orgao espedificado  */
      LEFT OUTER JOIN DB_DEPARTORG  ON DB01_ORGAO = DB20_ORGAO AND DB01_ANOUSU = ".db_getsession("DB_anousu")."
      /* traz os departamentos do orgao espedificado  */
      LEFT OUTER JOIN DB_DEPART  ON DB20_ORGAO = DB01_ORGAO AND DB01_ANOUSU = ".db_getsession("DB_anousu")."
      WHERE $tipoperm DB20_ANOUSU = $anousu
      ";
    if(isset($secretaria)){
      $sqle .= " and DB20_ORGAO = $secretaria ";
    }
    if(isset($departamento)){
      $sqle .= " and db01_coddepto = $departamento ";
    }

    $result = db_query($sqle);

    if ($result != false && pg_numrows($result) > 0) {
      $tem_and = "";
      $dotacoes = " select fc_estruturaldotacao(o58_anousu,o58_coddot) as o50_estrutdespesa,
        o58_coddot,
        o41_unidade,o41_descr,
        o55_descr,
        o55_finali,
        o56_descr
        from orcdotacao
         inner join orctiporec on o58_codigo = o15_codigo
        inner join orcelemento on o56_codele = o58_codele
        and o56_anousu = o58_anousu
        inner join orcunidade  on o58_anousu = o41_anousu
        and o58_unidade = o41_unidade
        and o58_orgao = o41_orgao
        inner join orcprojativ on o58_projativ = o55_projativ
        and o58_anousu = o55_anousu
        where (( ";
      $secretar = " select distinct o40_orgao,o40_descr
        from orcdotacao
        inner join orcorgao on o40_anousu = ".db_getsession("DB_anousu")." and o40_orgao = o58_orgao
        inner join orcelemento on o56_codele = o58_codele and
        o56_anousu = o58_anousu
        where ( (  ";
      $departam = " select distinct coddepto,descrdepto
        from orcdotacao
        inner join orcorgao on o40_anousu = ".db_getsession("DB_anousu")." and o40_orgao = o58_orgao
        inner join db_departorg on db01_orgao    = o40_orgao and db01_anousu = o40_anousu
        inner join db_depart on db01_coddepto = coddepto and (limite is null or limite >= '" . date('Y-m-d', db_getsession('DB_datausu')) . "')
        inner join orctiporec on o58_codigo = o15_codigo
        inner join orcelemento on o56_codele = o58_codele and
        o56_anousu = o58_anousu
        where ( ( ";
      $sql_dotacoes = "";
      for ($i = 0; $i < pg_numrows($result); $i ++) {
        db_fieldsmemory($result, $i);
        if (!empty ($tem_and)) {
          $sql_dotacoes .= " OR ( ";
          $tem_and = "";
        }
        if ($db20_orgao > 0) {
          $sql_dotacoes .= $tem_and." o58_orgao = $db20_orgao ) ";
          $tem_and = " and ";
        }
        if ($db20_unidade > 0) {
          $sql_dotacoes .= $tem_and." o58_unidade = $db20_unidade ";
          $tem_and = " and ";
        }
        if ($db20_funcao > 0) {
          $sql_dotacoes .= $tem_and." o58_funcao = $db20_funcao ";
          $tem_and = " and ";
        }
        if ($db20_subfuncao > 0) {
          $sql_dotacoes .= $tem_and." o58_subfuncao = $db20_subfuncao ";
          $tem_and = " and ";
        }
        if ($db20_programa > 0) {
          $sql_dotacoes .= $tem_and." o58_programa = $db20_programa ";
          $tem_and = " and ";
        }
        if ($db20_projativ > 0) {
          $sql_dotacoes .= $tem_and." o58_projativ = $db20_projativ ";
          $tem_and = " and ";
        }
        if ($db20_codele > 0) {
          $sql_dotacoes .= $tem_and." o58_codele = $db20_codele ";
          $tem_and = " and ";
        }
        if ($db20_codigo > 0) {
          $sql_dotacoes .= $tem_and." o58_codigo = $db20_codigo ";
          $tem_and = " and ";
        }
        $sql_dotacoes .= " ";
      }
      $dotacoes .= $sql_dotacoes;
      $secretar .= $sql_dotacoes;
      $departam .= $sql_dotacoes;

      if ($dotacoes != "") {
        $dotacoes .= " ) ";
        $secretar .= " ) ";
        $departam .= " ) ";
        if ($elemento != null || $elemento != "") {
          $dotacoes .= " and o56_elemento like '$elemento%'";
          $secretar .= " and o56_elemento like '$elemento%'";
          $departam .= " and o56_elemento like '$elemento%'";
        }

        //$dotacoes .= " and o58_anousu = $anousu and o58_instit = ".db_getsession("DB_instit");
        $dotacoes .= " and o58_anousu = $anousu and o58_instit in ($instituicoes) ";
        if ($sWhere != null) {

          $dotacoes .= " and {$sWhere}";
        }
        require_once(modification("std/db_stdClass.php"));
        $aParametroCompras = db_stdClass::getParametro("pcparam", array(db_getsession("DB_instit")));
        if ($this->departamento != "" && isset($aParametroCompras[0]->pc30_dotacaopordepartamento)
          && $aParametroCompras[0]->pc30_dotacaopordepartamento == 't') {

          $sSqlUnidade = "select * from db_departorg where db01_coddepto= $this->departamento and db01_anousu = {$anousu}";
          $rsUnidade   = db_query($sSqlUnidade);
          require_once(modification("libs/db_utils.php"));
          if (pg_num_rows($rsUnidade) > 0) {

            $iCodigoUnidade = db_utils::fieldsMemory($rsUnidade, 0)->db01_unidade;
            if ($iCodigoUnidade != "") {
              $dotacoes .= " and o58_unidade = {$iCodigoUnidade}";
            }
          }
        }
        $dotacoes .= " ORDER BY O50_ESTRUTDESPESA ";
        //$secretar .= " and o58_anousu = $anousu and o58_instit = ".db_getsession("DB_instit");
        $secretar .= " and o58_anousu = $anousu and o58_instit in ($instituicoes) ";
        $secretar .= " ORDER BY O40_ORGAO ";
        //$departam .= " and o58_anousu = $anousu and o58_instit = ".db_getsession("DB_instit");
        $departam .= " and o58_anousu = $anousu and o58_instit in ($instituicoes)";
        $departam .= " ORDER BY CODDEPTO ";
        //echo $dotacoes;
        $result = db_query($dotacoes);
        if ($result == false || pg_numrows($result) == 0) {
          return false;
        } else {
          $this->recordset = $result;
          $this->numrows = pg_numrows($result);
          $this->sql = $dotacoes;
          $this->orgaos = $secretar;
          $this->depart = $departam;
          return true;
        }
      } else {
        return false;
      }
    }
  }
}
/**
 *  usada para gerar a condição de pesquisa
 *  retornada pela func_selorcdotacao_aba.php
 *
 */
class cl_selorcdotacao {
  var $filtra_despesa = null;
  var $instit = null;
  var $orgao = null;
  var $unidade = null;
  var $funcao = null;
  var $subfuncao = null;
  var $programa = null;
  var $projativ = null;
  var $elemento = null;
  var $desdobramento = null;
  var $recurso = null;

  /* gets */
  function getInstit() {
    return $this->instit;
  }
  function getElemento() {
    return $this->elemento;
  }
  function getDesdobramento() {
    return $this->desdobramento;
  }
  function getDadosReceita($alias = true) {
    $txt = "1=1";
    if ($alias == true) {
      if ($this->recurso != null)
        $txt .= " and d.o70_codigo in ".$this->recurso;
    } else {
      if ($this->recurso != null)
        $txt .= " and o70_codigo in ".$this->recurso;
    }
    return $txt;
  }
  //00//
  //10// se $elemento = true default, retorna elemento, caso contratio nao retorna, nesse caso use o getElemento() para obte-lo
  //10//
  function getDados($alias = true, $ret_elemento=true) {
    $txt = "1=1";
    if ($alias == true) {
      if ($this->orgao != null)
        $txt .= " and w.o58_orgao in".$this->orgao;
      if ($this->unidade != null)
        $txt .= " and ".$this->unidade;
      if ($this->funcao != null)
        $txt .= " and w.o58_funcao in".$this->funcao;
      if ($this->subfuncao != null)
        $txt .= " and w.o58_subfuncao in".$this->subfuncao;
      if ($this->programa != null)
        $txt .= " and w.o58_programa in".$this->programa;
      if ($this->projativ != null)
        $txt .= " and w.o58_projativ in".$this->projativ;
      /*
              if ($this->elemento != null && $ret_elemento ==true)
              $txt .= " and e.o56_codele in".$this->elemento;
      */
      if ($this->elemento != null  && $ret_elemento ==true)
        if (trim($this->elemento)!=""){
          $vet_elementos = split(",",$this->elemento);
          $and           = " and ";
          $or            = " or ";
          $elementos     = "e.o56_elemento like ";
          $str           = "";
          for ($xx = 0; $xx < count($vet_elementos); $xx++){
            if ($xx == 0){
              $str .= $and."(".$elementos."'".trim($vet_elementos[$xx])."%'";
            } else {
              $str .= $or.$elementos."'".trim($vet_elementos[$xx])."%'";
            }
          }
          $str .= ")";
          $txt .= $str;
        }
      if ($this->recurso != null)
        $txt .= " and w.o58_codigo in".$this->recurso;
    } else {
      if ($this->orgao != null)
        $txt .= " and o58_orgao in".$this->orgao;
      if ($this->unidade != null)
        $txt .= " and ".$this->unidade;
      if ($this->funcao != null)
        $txt .= " and o58_funcao in".$this->funcao;
      if ($this->subfuncao != null)
        $txt .= " and o58_subfuncao in".$this->subfuncao;
      if ($this->programa != null)
        $txt .= " and o58_programa in".$this->programa;
      if ($this->projativ != null)
        $txt .= " and o58_projativ in ".$this->projativ;
      if ($this->elemento != null  && $ret_elemento ==true)
        if (trim($this->elemento)!=""){
          $vet_elementos = split(",",$this->elemento);
          $and           = " and ";
          $or            = " or ";
          $elementos     = "o56_elemento like ";
          $str           = "";
          for ($xx = 0; $xx < count($vet_elementos); $xx++){
            if ($xx == 0){
              $str .= $and."(".$elementos."'".trim($vet_elementos[$xx])."%'";
            } else {
              $str .= $or.$elementos."'".trim($vet_elementos[$xx])."%'";
            }
          }
          $str .= ")";
          $txt .= $str;
        }
      if ($this->recurso != null)
        $txt .= " and o58_codigo in ".$this->recurso;
    }
    return $txt;
  }
  function getParametros($nomes = false) {
    global $nomeinst, $o40_descr, $o41_unidade, $o41_descr, $o15_descr;
    if ($nomes == true) {
      $txt = "Instituição : ".$this->instit."\n";
      $txt .= "Orgao : ".$this->orgao."\n";
      $txt .= "Unidade: ".$this->unidade."\n";
      $txt .= "Função: ".$this->funcao."\n";
      $txt .= "Sub-Função: ".$this->subfuncao."\n";
      $txt .= "Programa: ".$this->programa."\n";
      $txt .= "Proj-Ativ: ".$this->projativ."\n";
      $txt .= "Elemento: ".$this->elemento."\n";
      $txt .= "Desdobramentos: ".$this->desdobramento."\n";
      $txt .= "Recurso: ".$this->recurso."\n";
      return $txt;
    } else {
      $it = "";
      $og = "";
      $un = "";
      $rec = "";

      $sql = "select nomeinst from db_config where codigo in ".$this->instit;
      $rr = db_query($sql);
      $sp = "";
      for ($x = 0; $x < pg_numrows($rr); $x ++) {
        db_fieldsmemory($rr, $x);
        $it = $it.$sp.$nomeinst;
        $sp = ", ";
      }
      $sql = "select o40_descr from orcorgao where o40_anousu=".db_getsession("DB_anousu")." and o40_orgao in ".$this->orgao;
      $rr = @ db_query($sql);
      if ($rr != false) {
        $sp = "";
        for ($x = 0; $x < @ pg_numrows($rr); $x ++) {
          db_fieldsmemory($rr, $x);
          $og = $og.$sp.$o40_descr;
          $sp = ", ";
        }
      }

      $sql = "select distinct o41_descr
                  from orcunidade
                 inner join orcdotacao on o58_unidade = o41_unidade
                                      and o58_orgao   = o41_orgao
                                      and o58_anousu  = o41_anousu
                 where o41_anousu = ".db_getsession("DB_anousu")."
                   and ".$this->unidade;

      $rr = @ db_query($sql);
      if ($rr != false) {
        $sp = "";
        for ($x = 0; $x < @ pg_numrows($rr); $x ++) {
          db_fieldsmemory($rr, $x);
          $un = $un.$sp.$o41_descr;
          $sp = ", ";
        }
      }
      $sql = "select o15_descr from orctiporec
        where o15_codigo in ".$this->recurso;
      $rr = @ db_query($sql);
      if ($rr != false) {
        $sp = "";
        for ($x = 0; $x < @ pg_numrows($rr); $x ++) {
          db_fieldsmemory($rr, $x);
          $rec = $rec.$sp.$o15_descr;
          $sp = ", ";
        }
      }

      $txt = "Instituição : ".$it."\n";
      $txt .= "Orgao : ".$og."\n";
      $txt .= "Unidade: ".$un."\n";
      $txt .= "Função: ".$this->funcao."\n";
      $txt .= "Sub-Função: ".$this->subfuncao."\n";
      $txt .= "Programa: ".$this->programa."\n";
      $txt .= "Proj-Ativ: ".$this->projativ."\n";
      $txt .= "Elemento: ".$this->elemento."\n";
      $txt .= "Recurso: ".$rec."\n";
      return $txt;
    }
  }
  /* sets */
  function setDados($filtro) {
    $this->filtra_despesa = $filtro;
    $this->processa();
  }
  function processa() {
    // executa
    $sele_work = "";
    $sepi = "";
    $sep = "";
    $sepu = "";
    $sepf = "";
    $sepsf = "";
    $sepo = "";
    $sepp = "";
    $sepe = "";
    $sepc = "";
    $sepdes = ""; // separador do desdobramento
    $sele_work_instit = " ( ";
    $sele_work_orgao = " ( ";
    $sele_work_unidade = " ( ";
    $sele_work_funcao = " ( ";
    $sele_work_subfuncao = " ( ";
    $sele_work_programa = " ( ";
    $sele_work_projativ = " ( ";
    $sele_work_elemento = " ";
    $sele_work_desdobramento = " ( ";
    $sele_work_recurso = " ( ";
    $condicao_uniao = " ";
    $estrut    = "";
    if ($this->filtra_despesa != "geral") {
      $qual_filtro = split('-', $this->filtra_despesa);
      for ($f = 0; $f < sizeof($qual_filtro); $f ++) {
        $ver = split("_", $qual_filtro[$f]);

        if ($ver[0] == "instit") {
          $sele_work_instit .= $sepi.$ver[1];
          $sepi = ",";
        }
        if ($ver[0] == "orgao") {
          $sele_work_orgao .= $sep.$ver[1];
          $sep = ",";
        }
        if ($ver[0] == "unidade") {
          $sele_work_unidade .= $sepu." ( o58_orgao = ".$ver[1]." and o58_unidade = ".$ver[2]." ) ";
          $sepu = " or ";
        }
        if ($ver[0] == "funcao") {
          $sele_work_funcao .= $sepf.$ver[1];
          $sepf = ",";
        }
        if ($ver[0] == "subfuncao") {
          $sele_work_subfuncao .= $sepsf.$ver[1];
          $sepsf = ",";
        }
        if ($ver[0] == "programa") {
          $sele_work_programa .= $sepo.$ver[1];
          $sepo = ",";
        }
        if ($ver[0] == "projativ") {
          $sele_work_projativ .= $sepp.$ver[1];
          $sepp = ",";
        }
        if ($ver[0] == "des") {
          $sele_work_desdobramento .= $sepdes.$ver[1];
          $sepdes = ",";
        }
        if ($ver[0] == "ele") {
          $sql_elem = "select o56_elemento from orcelemento where o56_anousu = ".db_getsession("DB_anousu")." and
                                                                    o56_codele = ".$ver[1];
          $rr       = @ db_query($sql_elem);
          $numrows  = @ pg_numrows($rr);
          if ($rr != false) {
            for ($xx = 0; $xx < $numrows; $xx++){
              $estrut = substr(pg_result($rr,$xx,"o56_elemento"),0,7);
              $sele_work_elemento .= $sepe.$estrut;
              $sepe = ",";
            }
          }
        }
        if ($ver[0] == "recurso") {
          $sele_work_recurso .= $sepc.$ver[1];
          $sepc = ",";
        }
        $condicao_uniao = " and ";
      }

      $sele_work_instit .= ")";
      $sele_work_orgao .= ")";
      $sele_work_unidade .= ")";
      $sele_work_funcao .= ")";
      $sele_work_subfuncao .= ")";
      $sele_work_programa .= ")";
      $sele_work_projativ .= ")";
      //        $sele_work_elemento .= ")";
      $sele_work_desdobramento .= ")";
      $sele_work_recurso .= ")";
      //echo $sele_work_elemento; exit;
    }
    // atualiza instituição
    if ($sepi != "")
      $this->instit = $sele_work_instit;
    if ($sep != "")
      $this->orgao = $sele_work_orgao;
    if ($sepu != "")
      $this->unidade = $sele_work_unidade;
    if ($sepf != "")
      $this->funcao = $sele_work_funcao;
    if ($sepsf != "")
      $this->subfuncao = $sele_work_subfuncao;
    if ($sepo != "")
      $this->programa = $sele_work_programa;
    if ($sepp != "")
      $this->projativ = $sele_work_projativ;
    if ($sepe != "")
      $this->elemento = $sele_work_elemento;
    if ($sepdes != "")
      $this->desdobramento = $sele_work_desdobramento;
    if ($sepc != "")
      $this->recurso = $sele_work_recurso;
  }
}
?>