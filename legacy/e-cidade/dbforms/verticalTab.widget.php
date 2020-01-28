<?php
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

/**
 * Widget para montar um tabview vertical;
 * @author $Author: dbandrio.costa $
 * @revision $Revision: 1.7 $
 */

class verticalTab {


  /**
   * nome da tabviem
   * @var string
   */
  private $sName = "tabview1";

  /**
   * tamanho da tabviem;
   * @var integer
   */
  private $iHeight = 300;

  /**
   * Width da coluna de Opções em % (percentual)
   * @var integer
   */
  private $iWidthColunaOpcoes = 20;

  /**
   * conjunto de abas que irao fazer parte da tabview
   * @var array
   */
  private $aTabs  = array();
  /**
   *Controla a renderização do objeto;
  */
  private $sRender = null;
  /**
   * metodo construtor da classe
   * @param string  $sName              Nome da tabview
   * @param integer $iHeight            altura da tabview.
   * @param integer $iWidthColunaOpcoes largura do container de opções em % (percentual)
   * @return void;
   */

  function __construct($sName = "tabview1", $iHeight = 300, $iWidthColunaOpcoes = 20) {

    if ($sName != '' ) {
      $this->sName = $sName;
    }else{
      die('<b>Erro:</b> parametro $sName nao pode ser vazio. ');
    }
    $this->iHeight = $iHeight;
    $this->iWidthColunaOpcoes = $iWidthColunaOpcoes;
  }
  /**
   * adiciona nova aba.
   * @param $saName nome ou conjunto de Abas. Pode ser passado um array com as abas;
   * @param [string $sLabel label da aba]
   * @param [string $sTarget programa a se executado. somente e requerido se $sName nao e um conjunto]
   * @param [bool $lActive define se aba esta ativa, ou nao]
   */

  function add ($saName, $sLabel = '', $sUrl = '', $lActive = true) {

    /*
     * verficamos se o o primeiro paramentro passado é um array
     * caso seje um array, iteramos sobre ele para criar as abas.
     */

    if (is_array($saName) ) {

      $i = 1;
      foreach ($saName as $aAbas) {

        /*
         *verificamos existe as chaves obrigatorias ;
         */
        if (!isset($aAbas["sName"])) {
          die("<b>[ERRO]: Aba sem nome definido");
        } else {

           $lActive = true;
           $sLabel  = '';
           if (isset($aAbas["sLabel"])) {
             $sLabel = $aAbas["sLabel"];
           } else {
             $sLabel = "Aba {$i}";
           }
           $lActive  = isset($aAbas["lActive"])?$aAbas["lActive"]:true;
           $this->aAbas[] = array(
                                  "sName"   => $aAbas["sName"],
                                  "sUrl"    => $aAbas["sUrl"],
                                  "sLabel"  => $sLabel,
                                  "lActive" => $lActive
                                 );

        }
        $i++;
      }

    } else {
     //o usuario passou todos os paramentos.
      $this->aAbas[] = array(
                             "sName"   => $saName,
                             "sUrl"    => $sUrl,
                             "sLabel"  => $sLabel,
                             "lActive" => $lActive
                             );
    }
    return true;
  }
  /**
   * renderiza  a tabviem para o usuário
   */
  private function create() {

    /*
     * percorremos as abas adicionadas.
     * caso nao existe nenhuma, abortamos a executacao do metodo.
     */
     $sAbas = null;
     if (count($this->aAbas) == 0 ) {
       die("<b>[ERRO]:</b> não foram adicionado abas. ");
     } else {

       $i = 0;
       foreach ($this->aAbas as $aAba ) {

         $sClassName = "tabNormal";
         $sDisabled  = "";
         if ($i == 0) {
           $sClassName = "tabSelecionado";
         }
         if (!$aAba["lActive"]) {
           $sClassName = "tabDisabled";
           $sDisabled  = "return false;";
         }
         $sAbas .= "<a class='{$sClassName}' id='{$aAba["sName"]}' style='display:block;border-top:1px solid white'\n";
         $sAbas .= "   onclick='{$sDisabled} js_marcaTab(this);this.blur()'";
         $sAbas .= "   href='{$aAba["sUrl"]}'";
         $sAbas .= "   target='{$this->sName}Detalhes'>{$aAba["sLabel"]}</a>";
         $i++;
       }
     }

     $this->sRender  = "<table width='100%' cellspacing='0'>\n";
     $this->sRender .= "  <tr>\n";
     $this->sRender .= "    <td width='{$this->iWidthColunaOpcoes}%' valign='top' height='100%' rowspan='2'>\n";
     $this->sRender .= $sAbas;
     $this->sRender .= "    </td>\n";
     $this->sRender .= "    <td valign='top' height='100%' style='border:1px inset #cccccc'>\n ";
     $this->sRender .= "      <iframe height='{$this->iHeight}' name='{$this->sName}Detalhes'\n";
     $this->sRender .= "              frameborder='0' width='100%'\n";
     $this->sRender .= "      src='{$this->aAbas[0]["sUrl"]}'\n";
     $this->sRender .= "      style='background-color:#CCCCCC;'>\n";
     $this->sRender .= "      </iframe>";
     $this->sRender .= "   </tr>";
     $this->sRender .= " </table>";
     $this->sRender .= "<script>\n";
     $this->sRender .= "function js_marcaTab(obj) {\n";
     $this->sRender .= "    lista = document.getElementsByTagName('A');\n";
     $this->sRender .= "    for (i = 0;i < lista.length;i++) {\n";
     $this->sRender .= "      if (lista[i].className == 'tabSelecionado' ) {\n";
     $this->sRender .= "        lista[i].className  = 'tabNormal';\n";
     $this->sRender .= "        lista[i].style.left = '-2px';\n";
     $this->sRender .= "      }\n";
     $this->sRender .= "   }\n";
     $this->sRender .= "   obj.blur();\n";
     $this->sRender .= "   obj.className = 'tabSelecionado';\n";
     $this->sRender .= " }\n";
     $this->sRender .= " </script>";
     return $this->sRender;
  }

  function __tostring(){
    return  $this->create();
  }

  function show() {
    echo $this->create();
  }
}