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
//MODULO: licitacao
//CLASSE DA ENTIDADE licitacaoreservacotas
class cl_licitacaoreservacotas {
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
   var $l19_sequencial = 0;
   var $l19_liclicitemorigem = 0;
   var $l19_liclicitemreserva = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 l19_sequencial = int4 = Sequencial
                 l19_liclicitemorigem = int4 = Item de Origem
                 l19_liclicitemreserva = int4 = Item Reservado
                 ";
   //funcao construtor da classe
   function cl_licitacaoreservacotas() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("licitacaoreservacotas");
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
       $this->l19_sequencial = ($this->l19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l19_sequencial"]:$this->l19_sequencial);
       $this->l19_liclicitemorigem = ($this->l19_liclicitemorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["l19_liclicitemorigem"]:$this->l19_liclicitemorigem);
       $this->l19_liclicitemreserva = ($this->l19_liclicitemreserva == ""?@$GLOBALS["HTTP_POST_VARS"]["l19_liclicitemreserva"]:$this->l19_liclicitemreserva);
     }else{
       $this->l19_sequencial = ($this->l19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l19_sequencial"]:$this->l19_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l19_sequencial){
      $this->atualizacampos();
     if($this->l19_liclicitemorigem == null ){
       $this->erro_sql = " Campo Item de Origem não informado.";
       $this->erro_campo = "l19_liclicitemorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l19_liclicitemreserva == null ){
       $this->erro_sql = " Campo Item Reservado não informado.";
       $this->erro_campo = "l19_liclicitemreserva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l19_sequencial == "" || $l19_sequencial == null ){
       $result = db_query("select nextval('licitacaoreservacotas_l19_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: licitacaoreservacotas_l19_sequencial_seq do campo: l19_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->l19_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from licitacaoreservacotas_l19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l19_sequencial)){
         $this->erro_sql = " Campo l19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l19_sequencial = $l19_sequencial;
       }
     }
     if(($this->l19_sequencial == null) || ($this->l19_sequencial == "") ){
       $this->erro_sql = " Campo l19_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into licitacaoreservacotas(
                                       l19_sequencial
                                      ,l19_liclicitemorigem
                                      ,l19_liclicitemreserva
                       )
                values (
                                $this->l19_sequencial
                               ,$this->l19_liclicitemorigem
                               ,$this->l19_liclicitemreserva
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reserva de Cotas ($this->l19_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reserva de Cotas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reserva de Cotas ($this->l19_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l19_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21786,'$this->l19_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3922,21786,'','".AddSlashes(pg_result($resaco,0,'l19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3922,21787,'','".AddSlashes(pg_result($resaco,0,'l19_liclicitemorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3922,21788,'','".AddSlashes(pg_result($resaco,0,'l19_liclicitemreserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($l19_sequencial=null) {
      $this->atualizacampos();
     $sql = " update licitacaoreservacotas set ";
     $virgula = "";
     if(trim($this->l19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l19_sequencial"])){
       $sql  .= $virgula." l19_sequencial = $this->l19_sequencial ";
       $virgula = ",";
       if(trim($this->l19_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "l19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l19_liclicitemorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l19_liclicitemorigem"])){
       $sql  .= $virgula." l19_liclicitemorigem = $this->l19_liclicitemorigem ";
       $virgula = ",";
       if(trim($this->l19_liclicitemorigem) == null ){
         $this->erro_sql = " Campo Item de Origem não informado.";
         $this->erro_campo = "l19_liclicitemorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l19_liclicitemreserva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l19_liclicitemreserva"])){
       $sql  .= $virgula." l19_liclicitemreserva = $this->l19_liclicitemreserva ";
       $virgula = ",";
       if(trim($this->l19_liclicitemreserva) == null ){
         $this->erro_sql = " Campo Item Reservado não informado.";
         $this->erro_campo = "l19_liclicitemreserva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l19_sequencial!=null){
       $sql .= " l19_sequencial = $this->l19_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l19_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21786,'$this->l19_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l19_sequencial"]) || $this->l19_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3922,21786,'".AddSlashes(pg_result($resaco,$conresaco,'l19_sequencial'))."','$this->l19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l19_liclicitemorigem"]) || $this->l19_liclicitemorigem != "")
             $resac = db_query("insert into db_acount values($acount,3922,21787,'".AddSlashes(pg_result($resaco,$conresaco,'l19_liclicitemorigem'))."','$this->l19_liclicitemorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l19_liclicitemreserva"]) || $this->l19_liclicitemreserva != "")
             $resac = db_query("insert into db_acount values($acount,3922,21788,'".AddSlashes(pg_result($resaco,$conresaco,'l19_liclicitemreserva'))."','$this->l19_liclicitemreserva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reserva de Cotas não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Reserva de Cotas não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($l19_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l19_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21786,'$l19_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3922,21786,'','".AddSlashes(pg_result($resaco,$iresaco,'l19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3922,21787,'','".AddSlashes(pg_result($resaco,$iresaco,'l19_liclicitemorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3922,21788,'','".AddSlashes(pg_result($resaco,$iresaco,'l19_liclicitemreserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from licitacaoreservacotas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l19_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l19_sequencial = $l19_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reserva de Cotas não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Reserva de Cotas não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:licitacaoreservacotas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($l19_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from licitacaoreservacotas ";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = licitacaoreservacotas.l19_liclicitemorigem ";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l19_sequencial)) {
         $sql2 .= " where licitacaoreservacotas.l19_sequencial = $l19_sequencial ";
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
   public function sql_query_file ($l19_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from licitacaoreservacotas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l19_sequencial)){
         $sql2 .= " where licitacaoreservacotas.l19_sequencial = $l19_sequencial ";
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
