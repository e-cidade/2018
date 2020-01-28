<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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

class GrupoCaracteristicas {

  /**
   * C�odigo do grupo de caracter�sticas
   */
  private $codigo;

  /**
   * Descri��o do grupo de caracter�sticas
   */
  private $descricao;

  /**
   * Tipo do grupo de caracter�sticas
   */
  private $tipo;

  /**
   * Define o c�digo do grupo de caracter�sticas
   * @param Integer
   */
  public function setCodigo ($codigo) {
    $this->codigo = $codigo;
  }
  
  /**
   * Retorna o c�digo do grupo de caracter�sticas
   * @return Integer
   */
  public function getCodigo () {
    return $this->codigo; 
  }
  
  /**
   * Define a descri��o do grupo de caracter�sticas
   * @param String
   */
  public function setDescricao ($descricao) {
    $this->descricao = $descricao;
  }
  
  /**
   * Retorna a descri��o do grupo de caracter�sticas
   * @return String
   */
  public function getDescricao () {
    return $this->descricao; 
  }

  /**
   * Define o tipo do grupo de caracter�sticas
   * @param String
   */
  public function setTipo ($tipo) {
    $this->tipo = $tipo;
  }
  
  /**
   * Retorna o tipo do grupo de caracter�sticas
   * @return String
   */
  public function getTipo () {
    return $this->tipo; 
  }  
}
