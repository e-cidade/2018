<?php
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

class cl_liclicitacadattdinamicovalorgrupo {
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
   var $l16_sequencial = 0;
   var $l16_cadattdinamicovalorgrupo = 0;
   var $l16_liclicita = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 l16_sequencial = int4 = Código
                 l16_cadattdinamicovalorgrupo = int4 = Grupo de Valores
                 l16_liclicita = int4 = Licitação
                 ";
   //funcao construtor da classe
   function cl_liclicitacadattdinamicovalorgrupo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitacadattdinamicovalorgrupo");
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
       $this->l16_sequencial = ($this->l16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l16_sequencial"]:$this->l16_sequencial);
       $this->l16_cadattdinamicovalorgrupo = ($this->l16_cadattdinamicovalorgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["l16_cadattdinamicovalorgrupo"]:$this->l16_cadattdinamicovalorgrupo);
       $this->l16_liclicita = ($this->l16_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l16_liclicita"]:$this->l16_liclicita);
     }else{
       $this->l16_sequencial = ($this->l16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l16_sequencial"]:$this->l16_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l16_sequencial){
      $this->atualizacampos();
     if($this->l16_cadattdinamicovalorgrupo == null ){
       $this->erro_sql = " Campo Grupo de Valores não informado.";
       $this->erro_campo = "l16_cadattdinamicovalorgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l16_liclicita == null ){
       $this->erro_sql = " Campo Licitação não informado.";
       $this->erro_campo = "l16_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l16_sequencial == "" || $l16_sequencial == null ){
       $result = db_query("select nextval('liclicitacadattdinamicovalorgrupo_l16_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitacadattdinamicovalorgrupo_l16_sequencial_seq do campo: l16_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->l16_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from liclicitacadattdinamicovalorgrupo_l16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l16_sequencial)){
         $this->erro_sql = " Campo l16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l16_sequencial = $l16_sequencial;
       }
     }
     if(($this->l16_sequencial == null) || ($this->l16_sequencial == "") ){
       $this->erro_sql = " Campo l16_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitacadattdinamicovalorgrupo(
                                       l16_sequencial
                                      ,l16_cadattdinamicovalorgrupo
                                      ,l16_liclicita
                       )
                values (
                                $this->l16_sequencial
                               ,$this->l16_cadattdinamicovalorgrupo
                               ,$this->l16_liclicita
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo da licitação com os atributos dinamicos ($this->l16_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo da licitação com os atributos dinamicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo da licitação com os atributos dinamicos ($this->l16_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l16_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21718,'$this->l16_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3903,21718,'','".AddSlashes(pg_result($resaco,0,'l16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3903,21719,'','".AddSlashes(pg_result($resaco,0,'l16_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3903,21720,'','".AddSlashes(pg_result($resaco,0,'l16_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($l16_sequencial=null) {
      $this->atualizacampos();
     $sql = " update liclicitacadattdinamicovalorgrupo set ";
     $virgula = "";
     if(trim($this->l16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l16_sequencial"])){
       $sql  .= $virgula." l16_sequencial = $this->l16_sequencial ";
       $virgula = ",";
       if(trim($this->l16_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "l16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l16_cadattdinamicovalorgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l16_cadattdinamicovalorgrupo"])){
       $sql  .= $virgula." l16_cadattdinamicovalorgrupo = $this->l16_cadattdinamicovalorgrupo ";
       $virgula = ",";
       if(trim($this->l16_cadattdinamicovalorgrupo) == null ){
         $this->erro_sql = " Campo Grupo de Valores não informado.";
         $this->erro_campo = "l16_cadattdinamicovalorgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l16_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l16_liclicita"])){
       $sql  .= $virgula." l16_liclicita = $this->l16_liclicita ";
       $virgula = ",";
       if(trim($this->l16_liclicita) == null ){
         $this->erro_sql = " Campo Licitação não informado.";
         $this->erro_campo = "l16_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l16_sequencial!=null){
       $sql .= " l16_sequencial = $this->l16_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l16_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21718,'$this->l16_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l16_sequencial"]) || $this->l16_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3903,21718,'".AddSlashes(pg_result($resaco,$conresaco,'l16_sequencial'))."','$this->l16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l16_cadattdinamicovalorgrupo"]) || $this->l16_cadattdinamicovalorgrupo != "")
             $resac = db_query("insert into db_acount values($acount,3903,21719,'".AddSlashes(pg_result($resaco,$conresaco,'l16_cadattdinamicovalorgrupo'))."','$this->l16_cadattdinamicovalorgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l16_liclicita"]) || $this->l16_liclicita != "")
             $resac = db_query("insert into db_acount values($acount,3903,21720,'".AddSlashes(pg_result($resaco,$conresaco,'l16_liclicita'))."','$this->l16_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo da licitação com os atributos dinamicos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo da licitação com os atributos dinamicos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($l16_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l16_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21718,'$l16_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3903,21718,'','".AddSlashes(pg_result($resaco,$iresaco,'l16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3903,21719,'','".AddSlashes(pg_result($resaco,$iresaco,'l16_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3903,21720,'','".AddSlashes(pg_result($resaco,$iresaco,'l16_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from liclicitacadattdinamicovalorgrupo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l16_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l16_sequencial = $l16_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo da licitação com os atributos dinamicos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo da licitação com os atributos dinamicos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitacadattdinamicovalorgrupo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($l16_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from liclicitacadattdinamicovalorgrupo ";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitacadattdinamicovalorgrupo.l16_liclicita";
     $sql .= "      inner join db_cadattdinamicovalorgrupo  on  db_cadattdinamicovalorgrupo.db120_sequencial = liclicitacadattdinamicovalorgrupo.l16_cadattdinamicovalorgrupo";
     $sql .= "      inner join db_config  on  db_config.codigo = liclicita.l20_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql .= "      inner join licsituacao  on  licsituacao.l08_sequencial = liclicita.l20_licsituacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l16_sequencial)) {
         $sql2 .= " where liclicitacadattdinamicovalorgrupo.l16_sequencial = $l16_sequencial ";
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
   public function sql_query_file ($l16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from liclicitacadattdinamicovalorgrupo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l16_sequencial)){
         $sql2 .= " where liclicitacadattdinamicovalorgrupo.l16_sequencial = $l16_sequencial ";
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

}
