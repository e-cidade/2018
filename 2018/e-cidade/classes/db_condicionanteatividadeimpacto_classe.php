<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: meioambiente
//CLASSE DA ENTIDADE condicionanteatividadeimpacto
class cl_condicionanteatividadeimpacto {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $am11_sequencial = 0;
   var $am11_condicionante = 0;
   var $am11_atividadeimpacto = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am11_sequencial = int4 = Condicionante/Atividade
                 am11_condicionante = int4 = Condicionante
                 am11_atividadeimpacto = int4 = Atividade
                 ";
   //funcao construtor da classe
   function cl_condicionanteatividadeimpacto() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("condicionanteatividadeimpacto");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->am11_sequencial = ($this->am11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am11_sequencial"]:$this->am11_sequencial);
       $this->am11_condicionante = ($this->am11_condicionante == ""?@$GLOBALS["HTTP_POST_VARS"]["am11_condicionante"]:$this->am11_condicionante);
       $this->am11_atividadeimpacto = ($this->am11_atividadeimpacto == ""?@$GLOBALS["HTTP_POST_VARS"]["am11_atividadeimpacto"]:$this->am11_atividadeimpacto);
     }else{
       $this->am11_sequencial = ($this->am11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am11_sequencial"]:$this->am11_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($am11_sequencial = null){
      $this->atualizacampos();
     if($this->am11_condicionante == null ){
       $this->erro_sql = " Campo Condicionante não informado.";
       $this->erro_campo = "am11_condicionante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am11_atividadeimpacto == null ){
       $this->erro_sql = " Campo Atividade não informado.";
       $this->erro_campo = "am11_atividadeimpacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am11_sequencial == "" || $am11_sequencial == null ){
       $result = db_query("select nextval('condicionanteatividadeimpacto_am11_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: condicionanteatividadeimpacto_am11_sequencial_seq do campo: am11_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am11_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from condicionanteatividadeimpacto_am11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am11_sequencial)){
         $this->erro_sql = " Campo am11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am11_sequencial = $am11_sequencial;
       }
     }
     if(($this->am11_sequencial == null) || ($this->am11_sequencial == "") ){
       $this->erro_sql = " Campo am11_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into condicionanteatividadeimpacto(
                                       am11_sequencial
                                      ,am11_condicionante
                                      ,am11_atividadeimpacto
                       )
                values (
                                $this->am11_sequencial
                               ,$this->am11_condicionante
                               ,$this->am11_atividadeimpacto
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Condicionante/Atividade ($this->am11_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Condicionante/Atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Condicionante/Atividade ($this->am11_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am11_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20850,'$this->am11_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3752,20850,'','".AddSlashes(pg_result($resaco,0,'am11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3752,20851,'','".AddSlashes(pg_result($resaco,0,'am11_condicionante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3752,20852,'','".AddSlashes(pg_result($resaco,0,'am11_atividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am11_sequencial=null) {
      $this->atualizacampos();
     $sql = " update condicionanteatividadeimpacto set ";
     $virgula = "";
     if(trim($this->am11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am11_sequencial"])){
       $sql  .= $virgula." am11_sequencial = $this->am11_sequencial ";
       $virgula = ",";
       if(trim($this->am11_sequencial) == null ){
         $this->erro_sql = " Campo Condicionante/Atividade não informado.";
         $this->erro_campo = "am11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am11_condicionante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am11_condicionante"])){
       $sql  .= $virgula." am11_condicionante = $this->am11_condicionante ";
       $virgula = ",";
       if(trim($this->am11_condicionante) == null ){
         $this->erro_sql = " Campo Condicionante não informado.";
         $this->erro_campo = "am11_condicionante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am11_atividadeimpacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am11_atividadeimpacto"])){
       $sql  .= $virgula." am11_atividadeimpacto = $this->am11_atividadeimpacto ";
       $virgula = ",";
       if(trim($this->am11_atividadeimpacto) == null ){
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "am11_atividadeimpacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am11_sequencial!=null){
       $sql .= " am11_sequencial = $this->am11_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am11_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20850,'$this->am11_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am11_sequencial"]) || $this->am11_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3752,20850,'".AddSlashes(pg_result($resaco,$conresaco,'am11_sequencial'))."','$this->am11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am11_condicionante"]) || $this->am11_condicionante != "")
             $resac = db_query("insert into db_acount values($acount,3752,20851,'".AddSlashes(pg_result($resaco,$conresaco,'am11_condicionante'))."','$this->am11_condicionante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am11_atividadeimpacto"]) || $this->am11_atividadeimpacto != "")
             $resac = db_query("insert into db_acount values($acount,3752,20852,'".AddSlashes(pg_result($resaco,$conresaco,'am11_atividadeimpacto'))."','$this->am11_atividadeimpacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Condicionante/Atividade não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Condicionante/Atividade não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am11_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am11_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20850,'$am11_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3752,20850,'','".AddSlashes(pg_result($resaco,$iresaco,'am11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3752,20851,'','".AddSlashes(pg_result($resaco,$iresaco,'am11_condicionante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3752,20852,'','".AddSlashes(pg_result($resaco,$iresaco,'am11_atividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from condicionanteatividadeimpacto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am11_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am11_sequencial = $am11_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Condicionante/Atividade não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Condicionante/Atividade não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:condicionanteatividadeimpacto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am11_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from condicionanteatividadeimpacto ";
     $sql .= "      inner join atividadeimpacto  on  atividadeimpacto.am03_sequencial = condicionanteatividadeimpacto.am11_atividadeimpacto";
     $sql .= "      inner join condicionante  on  condicionante.am10_sequencial = condicionanteatividadeimpacto.am11_condicionante";
     $sql .= "      inner join criterioatividadeimpacto  on  criterioatividadeimpacto.am01_sequencial = atividadeimpacto.am03_criterioatividadeimpacto";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am11_sequencial)) {
         $sql2 .= " where condicionanteatividadeimpacto.am11_sequencial = $am11_sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($am11_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from condicionanteatividadeimpacto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am11_sequencial)){
         $sql2 .= " where condicionanteatividadeimpacto.am11_sequencial = $am11_sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  /**
   * Buscamos as atividades vinculadas à Condicionante
   *
   * @param  integer $iCondicionante
   * @param  string  $sCampos
   *
   * @return string  Query pronta
   */
  public function sql_query_atividades( $iCondicionante, $sCampos = null ) {

    if ( empty($sCampos) ) {
      $sCampos = "am03_sequencial, am03_descricao";
    }

    $sSql  = " select {$sCampos}                                                             ";
    $sSql .= "   from condicionanteatividadeimpacto                                          ";
    $sSql .= "        inner join atividadeimpacto on am03_sequencial = am11_atividadeimpacto ";
    $sSql .= "  where am11_condicionante = {$iCondicionante}                                 ";

    return $sSql;
  }
}
