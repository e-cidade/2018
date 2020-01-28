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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordoprogramacaofinanceira
class cl_acordoprogramacaofinanceira {
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
   var $ac34_sequencial = 0;
   var $ac34_programacaofinanceira = 0;
   var $ac34_acordo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ac34_sequencial = int4 = Código Sequencial 
                 ac34_programacaofinanceira = int4 = Programação Financeira 
                 ac34_acordo = int4 = Acordo 
                 ";
   //funcao construtor da classe
   function cl_acordoprogramacaofinanceira() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoprogramacaofinanceira");
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
       $this->ac34_sequencial = ($this->ac34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac34_sequencial"]:$this->ac34_sequencial);
       $this->ac34_programacaofinanceira = ($this->ac34_programacaofinanceira == ""?@$GLOBALS["HTTP_POST_VARS"]["ac34_programacaofinanceira"]:$this->ac34_programacaofinanceira);
       $this->ac34_acordo = ($this->ac34_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac34_acordo"]:$this->ac34_acordo);
     }else{
       $this->ac34_sequencial = ($this->ac34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac34_sequencial"]:$this->ac34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac34_sequencial){
      $this->atualizacampos();
     if($this->ac34_programacaofinanceira == null ){
       $this->erro_sql = " Campo Programação Financeira nao Informado.";
       $this->erro_campo = "ac34_programacaofinanceira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac34_acordo == null ){
       $this->erro_sql = " Campo Acordo nao Informado.";
       $this->erro_campo = "ac34_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac34_sequencial == "" || $ac34_sequencial == null ){
       $result = db_query("select nextval('acordoprogramacaofinanceira_ac34_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoprogramacaofinanceira_ac34_sequencial_seq do campo: ac34_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac34_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordoprogramacaofinanceira_ac34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac34_sequencial)){
         $this->erro_sql = " Campo ac34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac34_sequencial = $ac34_sequencial;
       }
     }
     if(($this->ac34_sequencial == null) || ($this->ac34_sequencial == "") ){
       $this->erro_sql = " Campo ac34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoprogramacaofinanceira(
                                       ac34_sequencial 
                                      ,ac34_programacaofinanceira 
                                      ,ac34_acordo 
                       )
                values (
                                $this->ac34_sequencial 
                               ,$this->ac34_programacaofinanceira 
                               ,$this->ac34_acordo 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordos Programação Financeira ($this->ac34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordos Programação Financeira já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordos Programação Financeira ($this->ac34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17197,'$this->ac34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3040,17197,'','".AddSlashes(pg_result($resaco,0,'ac34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3040,17198,'','".AddSlashes(pg_result($resaco,0,'ac34_programacaofinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3040,17199,'','".AddSlashes(pg_result($resaco,0,'ac34_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ac34_sequencial=null) {
      $this->atualizacampos();
     $sql = " update acordoprogramacaofinanceira set ";
     $virgula = "";
     if(trim($this->ac34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac34_sequencial"])){
       $sql  .= $virgula." ac34_sequencial = $this->ac34_sequencial ";
       $virgula = ",";
       if(trim($this->ac34_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ac34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac34_programacaofinanceira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac34_programacaofinanceira"])){
       $sql  .= $virgula." ac34_programacaofinanceira = $this->ac34_programacaofinanceira ";
       $virgula = ",";
       if(trim($this->ac34_programacaofinanceira) == null ){
         $this->erro_sql = " Campo Programação Financeira nao Informado.";
         $this->erro_campo = "ac34_programacaofinanceira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac34_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac34_acordo"])){
       $sql  .= $virgula." ac34_acordo = $this->ac34_acordo ";
       $virgula = ",";
       if(trim($this->ac34_acordo) == null ){
         $this->erro_sql = " Campo Acordo nao Informado.";
         $this->erro_campo = "ac34_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac34_sequencial!=null){
       $sql .= " ac34_sequencial = $this->ac34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17197,'$this->ac34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac34_sequencial"]) || $this->ac34_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3040,17197,'".AddSlashes(pg_result($resaco,$conresaco,'ac34_sequencial'))."','$this->ac34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac34_programacaofinanceira"]) || $this->ac34_programacaofinanceira != "")
           $resac = db_query("insert into db_acount values($acount,3040,17198,'".AddSlashes(pg_result($resaco,$conresaco,'ac34_programacaofinanceira'))."','$this->ac34_programacaofinanceira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac34_acordo"]) || $this->ac34_acordo != "")
           $resac = db_query("insert into db_acount values($acount,3040,17199,'".AddSlashes(pg_result($resaco,$conresaco,'ac34_acordo'))."','$this->ac34_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordos Programação Financeira nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordos Programação Financeira nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ac34_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac34_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17197,'$ac34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3040,17197,'','".AddSlashes(pg_result($resaco,$iresaco,'ac34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3040,17198,'','".AddSlashes(pg_result($resaco,$iresaco,'ac34_programacaofinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3040,17199,'','".AddSlashes(pg_result($resaco,$iresaco,'ac34_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoprogramacaofinanceira
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac34_sequencial = $ac34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordos Programação Financeira nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordos Programação Financeira nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:acordoprogramacaofinanceira";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ac34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordoprogramacaofinanceira ";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoprogramacaofinanceira.ac34_acordo";
     $sql .= "      inner join programacaofinanceira  on  programacaofinanceira.k117_sequencial = acordoprogramacaofinanceira.ac34_programacaofinanceira";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ac34_sequencial!=null ){
         $sql2 .= " where acordoprogramacaofinanceira.ac34_sequencial = $ac34_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $ac34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordoprogramacaofinanceira ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac34_sequencial!=null ){
         $sql2 .= " where acordoprogramacaofinanceira.ac34_sequencial = $ac34_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  // funcao do sql
  public function sql_query_parcelas ($ac34_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "", $limit = null) {

    $sql  = "select {$campos}";
    $sql .= "  from acordoprogramacaofinanceira  ";
    $sql .= "      inner join programacaofinanceira        on  programacaofinanceira.k117_sequencial = ac34_programacaofinanceira";
    $sql .= "      inner join programacaofinanceiraparcela on  programacaofinanceira.k117_sequencial = programacaofinanceiraparcela.k118_programacaofinanceira";
    $sql .= "      inner join acordo                       on  ac34_acordo  = acordo.ac16_sequencial";


    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ac34_sequencial)) {
        $sql2 .= " where ac34_sequencial = $ac34_sequencial ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }

      if (!empty($limit)) {
          $sql .= " limit {$limit}";
      }

    return $sql;
  }

  // funcao do sql
  public function sql_query_parcelas_lancamento ($ac34_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from acordoprogramacaofinanceira  ";
    $sql .= "      inner join programacaofinanceira        on  programacaofinanceira.k117_sequencial = ac34_programacaofinanceira";
    $sql .= "      inner join programacaofinanceiraparcela on  programacaofinanceira.k117_sequencial = programacaofinanceiraparcela.k118_programacaofinanceira";
    $sql .= "      inner join acordo                       on  ac34_acordo  = acordo.ac16_sequencial";
    $sql .= "      left  join  conlancamprogramacaofinanceiraparcela on k118_sequencial = c118_programacaofinanceiraparcela";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ac34_sequencial)) {
        $sql2 .= " where ac34_sequencial = $ac34_sequencial ";
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
