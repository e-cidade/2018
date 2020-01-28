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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_editalrua_classe.php"));
include(modification("dbforms/db_funcoes.php"));
?>
<html>
  <head>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/organograma.css" rel="stylesheet" type="text/css">
    <style type=""> 

    .grupo,
    .pai, 
    .associado{
      background-color: #EFEFEF;
      -moz-border-radius:5px;
      text-align: center;
      -moz-user-select:none;
    }

    </style>
  </head>
  <body  style='background-color: #cccccc'>
		<center >
		    <div id="corpo" style='border:1px inset black;top:47px; width:98%;height:90%; position:absolute; overflow: scroll'>
          <div id="svg" class="svg-container">
            <svg id="area" currentScale="1" viewBox="0 0 300 100" preserveAspectRatio="xMinYMin meet" class="svg-content">
            </svg>
          </div>
          <button onclick="imprime();">Imprimir</button>
        </div>
		</center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>
  (function(){
    var sRPCArq = 'con1_organograma.RPC.php';
    var oParam  = new Object();
    oParam.exec = 'getOrganogramasTreeView';
    var oAjax   = new Ajax.Request(sRPCArq,
                                   {method: 'post',
                                    asynchronous: true,
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: createSvg
                                   }) ;
  })();

  function createSvg(oAjax){

    var oReturn        = eval("(" + oAjax.responseText + ")");

    // Elemento Div
    var oArea        = document.getElementById('area');
    var oSizes       = oArea.getBoundingClientRect();
    // Largura Disponivel
    var iWidth       = oSizes.width;
    // Altura Disponivel
    var iHeight      = oSizes.height;
    // Altura de cada retangulo
    var iConstHeight = 40;

    // variaveis.....
    var aTree       = new Array(); // Arvore em vetor utilizada para selecao
                                   // do hover dos elementos
    var aAux        = new Array(); // Vetor que armazena os objetos por nivel
    var aAuxNivel2  = new Array(); // Vetor que armazena os objetos do nivel2
                                   // para balancear a arvore

    // html que sera inserido na DIV
    var sTxt         = '';
    var x;
    var y;
    var MaxHeight = 0;

    // Pega os niveis iniciais
    oReturn.aGrupos.forEach(function(oItem){

      oItem.nivel                 = parseInt(oItem.nivel);
      oItem.codigogrupo           = parseInt(oItem.codigogrupo);
      oItem.filhos                = parseInt(oItem.filhos);
      oItem.filhos_associados     = parseInt(oItem.filhos_associados);
      oItem.conta_pai             = parseInt(oItem.conta_pai);
      oItem.departamento          = parseInt(oItem.departamento);
      oItem.descricaodepartamento = oItem.descricaodepartamento.urlDecode();
      oItem.descricaogrupo        = oItem.descricaogrupo.urlDecode();
      oItem.stroke                = ''; //tracos da linha

      // Caso o no seja o associado
      if(oItem.associado == 't'){

        oItem.stroke = 'stroke-dasharray="4"';
      }

      // Caso nao exista o nivel
      if(!aAux[oItem.nivel]){

        aAux[oItem.nivel]         = new Object();
        aAux[oItem.nivel].indices = new Array();
      }

      if(!aAux[oItem.nivel].indices[oItem.codigogrupo]){

        aAux[oItem.nivel].indices[oItem.codigogrupo]        = new Object();
        aAux[oItem.nivel].indices[oItem.codigogrupo].filhos = new Array();
      }

      if(oItem.nivel > 1){

        aAux[oItem.nivel-1].indices[oItem.conta_pai].filhos[oItem.codigogrupo] = oItem;
      }
      if(oItem.nivel == 2){

        aAuxNivel2.push(oItem);
      }
    });

    // Desenha os quadrados
    oReturn.aGrupos.forEach(function(oItem){

      if(oItem.nivel <= 5){

        if(aTree[oItem.nivel]){

        } else {

          aTree[oItem.nivel] = new Array();
        }
        aTree[oItem.nivel][oItem.codigogrupo] = oItem;
        var x  = 0;
        var y  = 0;

        if(oItem.nivel > 1){

          if(aTree[oItem.nivel-1][oItem.conta_pai]){

            y  = aTree[oItem.nivel-1][oItem.conta_pai].y_end + iConstHeight;
          } else {

            y  = ((oItem.nivel-1) * iConstHeight) + (((oItem.nivel-1) * iConstHeight));
          }
        }

        if(oItem.associado=='t'){

          y = y - (iConstHeight/2);
        }

        var iFontSize = 12;
        var iTextSize = (getWidthOfText(oItem.descricaogrupo, 'Arial', iFontSize)*1.2);
        var z  = 0;
        var z1 = 0;

        if(oItem.nivel == 1){

          x = (iWidth - iTextSize) / 2;

          aAux[oItem.nivel].indices[oItem.codigogrupo].area_inicio = 0;
          aAux[oItem.nivel].indices[oItem.codigogrupo].area_fim    = iWidth;
          aAux[oItem.nivel].indices[oItem.codigogrupo].area        = iWidth;
          aAux[oItem.nivel].indices[oItem.codigogrupo].centro      = (aAux[oItem.nivel].indices[oItem.codigogrupo].area_inicio + aAux[oItem.nivel].indices[oItem.codigogrupo].area_fim)/2;
          aAux[oItem.nivel].indices[oItem.codigogrupo].box_inicio  = aAux[oItem.nivel].indices[oItem.codigogrupo].centro - (iTextSize/2);
          aAux[oItem.nivel].indices[oItem.codigogrupo].area_comp   = iTextSize;
          aAux[oItem.nivel].indices[oItem.codigogrupo].area_alt    = iConstHeight;

        } else {

          if(oItem.nivel > 2){

            z  = (aAux[oItem.nivel-1].indices[oItem.conta_pai].area/count(aAux[oItem.nivel-1].indices[oItem.conta_pai].filhos));
            z1 = (z*(getPos(aAux[oItem.nivel-1].indices[oItem.conta_pai].filhos, oItem.codigogrupo)-1)) + aAux[oItem.nivel-1].indices[oItem.conta_pai].area_inicio;

          } else {

            // z  = (aAux[oItem.nivel-2].indices[oItem.conta_pai].area/count(aAux[oItem.nivel-2].indices[oItem.conta_pai].filhos));
            // z1 = (z*(getPos(aAux[oItem.nivel-2].indices[oItem.conta_pai].filhos, oItem.codigogrupo)-1)) + aAux[oItem.nivel-2].indices[oItem.conta_pai].area_inicio;
            // z  = aAux[oItem.nivel-2].indices[oItem.conta_pai].area;
            // z1 = 0;

            // Caso seja o nivel 2 e feita uma verificacao com a itencao de
            // otimizar a distruibuicao de area

            z  = (aAux[oItem.nivel-1].indices[oItem.conta_pai].area/count(aAux[oItem.nivel-1].indices[oItem.conta_pai].filhos));
            z1 = (z*(getPos(aAux[oItem.nivel-1].indices[oItem.conta_pai].filhos, oItem.codigogrupo)-1)) + aAux[oItem.nivel-1].indices[oItem.conta_pai].area_inicio;

            if(oItem.nivel == 2){

              if(aAux[oItem.nivel].indices[oItem.codigogrupo].filhos == 0){

                var iVazio     = 0;
                var iSomaVazio = 0;

                // Varredura inicial para definir a quantidade por largura
                // disponivel
                aAuxNivel2.forEach(function(oTmp){

                  if(oTmp.filhos == 0){

                    iVazio += 1;
                    iSomaVazio += (getWidthOfText(oTmp.descricaogrupo, 'Arial', iFontSize)*1.2)*2;
                  }
                });

                iAux  = 0;
                // seta valores
                aAuxNivel2.forEach(function(oTmp){

                  if(oTmp.filhos == 0){

                    oTmp.largura = (getWidthOfText(oTmp.descricaogrupo, 'Arial', iFontSize)*1.2)*2;
                    oTmp.x_inicio = iAux;
                    oTmp.x_fim = iAux + oTmp.largura;
                  }
                });

                var iAreaTotal = aAux[oItem.nivel-1].indices[oItem.conta_pai].area;
                var iPosTemp   = (getPos(aAux[oItem.nivel-1].indices[oItem.conta_pai].filhos, oItem.codigogrupo)-1);

              }
            }
          }

          aAux[oItem.nivel].indices[oItem.codigogrupo].area_inicio = z1;
          aAux[oItem.nivel].indices[oItem.codigogrupo].area_fim    = z1+z;
          aAux[oItem.nivel].indices[oItem.codigogrupo].area        = z;
          aAux[oItem.nivel].indices[oItem.codigogrupo].centro      = (aAux[oItem.nivel].indices[oItem.codigogrupo].area_inicio + aAux[oItem.nivel].indices[oItem.codigogrupo].area_fim)/2;
          aAux[oItem.nivel].indices[oItem.codigogrupo].box_inicio  = aAux[oItem.nivel].indices[oItem.codigogrupo].centro - (iTextSize/2);
          aAux[oItem.nivel].indices[oItem.codigogrupo].area_comp   = iTextSize;
          aAux[oItem.nivel].indices[oItem.codigogrupo].area_alt    = iConstHeight;

          x = aAux[oItem.nivel].indices[oItem.codigogrupo].box_inicio;
        }
        aTree[oItem.nivel][oItem.codigogrupo].x        = x;
        aTree[oItem.nivel][oItem.codigogrupo].y        = y;

        aTree[oItem.nivel][oItem.codigogrupo].x_center = x+(iTextSize/2);
        aTree[oItem.nivel][oItem.codigogrupo].y_center = y+(iConstHeight/2);

        aTree[oItem.nivel][oItem.codigogrupo].x_end    = x+iTextSize;
        aTree[oItem.nivel][oItem.codigogrupo].y_end    = y+iConstHeight;

        if(aTree[oItem.nivel][oItem.codigogrupo].y_end >= MaxHeight){

          MaxHeight = aTree[oItem.nivel][oItem.codigogrupo].y_end ;
        }

        // funcao de hover dos retangulos
        if(!oItem.old_pai){

          sTxt += '<g id="' + oItem.codigogrupo
            +'" onmouseout="DesactiveTree();" onmouseover="ActiveTree('
            + oItem.codigogrupo + ');" orgao="' + oItem.departamento
            + '" onclick="exibeInfos(' + oItem.departamento
            + ',' + oItem.codigogrupo + ');" class="tree-pai-'
            + oItem.conta_pai + '">';
        } else {

          sTxt += '<g id="' + oItem.codigogrupo
            + '" onmouseout="DesactiveTree();" onmouseover="ActiveTree('
            + oItem.codigogrupo + ');" orgao="' + oItem.departamento
            + '" onclick="exibeInfos(' + oItem.departamento + ','
            + oItem.codigogrupo + ' );" class="tree-pai-' + oItem.old_pai + '">';
        }

        if(oItem.associado == 'f'){

          sTxt += '<rect style="stroke-width:1; fill:#fff;stroke:#000;" x="'
            + x + '" y="' + y + '" width="'
            + aAux[oItem.nivel].indices[oItem.codigogrupo].area_comp
            + '" height="'
            + aAux[oItem.nivel].indices[oItem.codigogrupo].area_alt + '" />';
        } else {

          sTxt += '<rect style="stroke-width:1; fill:#fff;stroke:#000;" x="'
          + x + '" y="' + y + '" width="'
          + aAux[oItem.nivel].indices[oItem.codigogrupo].area_comp
          + '" height="'
          + aAux[oItem.nivel].indices[oItem.codigogrupo].area_alt + '" />';
        }
        // x    =  x + (iTextSize/16);
        sTxt += '<text fill="#000" x="' + aTree[oItem.nivel][oItem.codigogrupo].x_center + '" y="' + (y+(iConstHeight/2))
          + '" font-family="Verdana" font-size="' + iFontSize + '" width="'
          + iTextSize + '" height="' + iConstHeight
          + '" style="text-anchor: middle;"><tspan> ' + oItem.descricaogrupo
          + '</tspan> </text>'
        sTxt += '</g>';

      }
    });

    // desenha as linhas
    aTree.forEach(function(oItens){

      oItens.forEach(function(oItem){

        if(oItem.nivel > 1){

          // Caso nao seja associado e desenhado linha acima, em direcao ao centro do pai e acima novamente
          // criando o seguinte efeito
          //     C
          //  ___|__ -2
          //  |    |
          //  |    | -1
          //  A    B
          //  caso contrario, desenha 1 a linha tracejada em direcao do no pai
          //       C
          //  _ _ _|__ -2
          //  |      |
          //  A      | -1
          //         B

          iDiv = 2; //Variavel de controle da altura da linha do associado

          if(oItem.associado == 't'){
            iDiv = 4;
          }

          // Desenha linha reta no eixo X (2)- finaliza
          sTxt += '<line ' + oItem.stroke + ' style="stroke: rgb(0,0,0); stroke-width:1;" x1="'
            + aTree[oItem.nivel-1][oItem.conta_pai].x_center + '" y1="'
            + (aTree[oItem.nivel-1][oItem.conta_pai].y_end + (iConstHeight/iDiv))
            + '" x2="' + aTree[oItem.nivel-1][oItem.conta_pai].x_center
            + '" y2="' + aTree[oItem.nivel-1][oItem.conta_pai].y_end + '" />';

          // Desenha linha reta no eixo X (1)
          sTxt += '<line ' + oItem.stroke + ' style="stroke: rgb(0,0,0); stroke-width:1;" x1="'
            + oItem.x_center + '" y1="' + oItem.y + '" x2="' + oItem.x_center
            + '" y2="' + (aTree[oItem.nivel-1][oItem.conta_pai].y_end
            + (iConstHeight/iDiv)) + '"/>';

            // Desenha linha reta no eixo Y
            sTxt += '<line ' + oItem.stroke + ' style="stroke: rgb(0,0,0); stroke-width:1;" x1="'
            + oItem.x_center + '" y1="' + (aTree[oItem.nivel-1][oItem.conta_pai].y_end
            + (iConstHeight/iDiv)) + '" x2="'
            + aTree[oItem.nivel-1][oItem.conta_pai].x_center
            + '" y2="' + (aTree[oItem.nivel-1][oItem.conta_pai].y_end
            + (iConstHeight/iDiv)) + '"/>';
        }
      });
    });

    MaxHeight += iConstHeight/2;

    oArea.setAttribute('viewBox', "0 0 " + iWidth +  " " + MaxHeight)
    oArea.innerHTML = sTxt;
  }

  // Retorna a quantidade de elementos do vetor desordenado
  function count(aTmp){

    var count = 0;

    aTmp.forEach(function(oTmp){

      count++;
    })
    return count;
  }

  // Retorna a posicao do elemento no vetor desordenado
  function getPos(aTemp, iIndex){

    var count = 0;

    for(var i in aTemp){

      if(aTemp[i] != undefined){

        count++;
      }

      if(i==iIndex){

        return count;
      }
    }
    return count;
  }

  // Retorna o comprimento do texto em px
  function getWidthOfText(txt, fontname, fontsize){

    // Create a dummy canvas (render invisible with css)
    var c    = document.createElement('canvas');
    // Get the context of the dummy canvas
    var ctx  = c.getContext('2d');
    // Set the context.font to the font that you are using
    ctx.font = fontsize + 'px ' + fontname;
    // Measure the string
    // !!! <CRUCIAL>  !!!
    var length = ctx.measureText(txt).width;
    // !!! </CRUCIAL> !!!
    return length;
  }

  function imprime(){

    var container   = document.getElementById('svg');
    var width       = parseFloat(container.getAttribute("width"))
    var height      = parseFloat(container.getAttribute("height"))
    var printWindow = window.open('', 'PrintMap',
      'width=' + width + ',height=' + height);

    printWindow.document.writeln(container.innerHTML);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
  }

  function exibeInfos(el, iId){

    getUsuarios(el, iId);
  }

  function getUsuarios(iDepto, iId){

    sDeptos = '';
    sDeptos = GetOrgaos(iId);
    sDeptos = sDeptos.substring(0, sDeptos.length-2);

    windowUsuario(iDepto, sDeptos);
  }

  function windowUsuario(iDepto, sDeptos){

    if(!sDeptos){
      js_OpenJanelaIframe(
        '',
        'db_iframe_db_usuarios',
        'func_db_usuarios.php?campos=db_usuarios.id_usuario,db_usuarios.nome,db_usuarios.login'
        +'&pesquisaInstit=true&coddepto='+iDepto+'&'
        +'funcao_js=parent.js_mostraUsuarioLookUp|nome|id_usuario|login',
        'Pesquisa Usuários',
        true
      );
    } else {

      js_OpenJanelaIframe(
        '',
        'db_iframe_db_usuarios',
        'func_db_usuarios.php?campos=db_usuarios.id_usuario,db_usuarios.nome,db_usuarios.login'
          + '&pesquisaInstit=true&sDeptos='+sDeptos+'&'
          + 'funcao_js=parent.js_mostraUsuarioLookUp|nome|id_usuario|login',
        'Pesquisa Usuários',
        true
      );
    }
  }

  function GetOrgaos(el){

    sResult = "";
    var aTree = document.getElementsByClassName('tree-pai-'+el);

    for (var i = 0; i < aTree.length; i++) {

      aTreeAux = document.getElementsByClassName('tree-pai-'+aTree[i].id);

      sResult += document.getElementById(aTree[i].id).getAttribute("orgao");
      if(aTreeAux.length > 0){

        sResult += ", " + GetOrgaos(aTree[i].id);
      } else {
        if(sResult){

          sResult += ", ";
        }
      }
    }
    return sResult;
  }

  // Seleciona todos os Nos filhos do No selecionado
  function ActiveTree(el){

    var aTree = document.getElementsByClassName('tree-pai-'+el);
    for (var i = 0; i < aTree.length; i++) {

      aTree[i].classList.add('ativo');
      aTreeAux = document.getElementsByClassName('tree-pai-'+aTree[i].id);

      if(aTreeAux.length > 0){

        ActiveTree(aTree[i].id);
      }
    }
  }

  // Deseleciona todos os Nos filhos do No selecionado
  function DesactiveTree(){

    var aTree = document.getElementsByClassName('ativo');

    for (var i = aTree.length - 1; i >= 0; i--) {

      aTree[i].classList.remove('ativo');
    }
  }

</script>