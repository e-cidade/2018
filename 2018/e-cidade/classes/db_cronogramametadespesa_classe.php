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

//MODULO: orcamento
//CLASSE DA ENTIDADE cronogramametadespesa
class cl_cronogramametadespesa {
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
   var $o131_sequencial = 0;
   var $o131_cronogramaperspectivadespesa = 0;
   var $o131_mes = 0;
   var $o131_percentual = 0;
   var $o131_valor = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o131_sequencial = int4 = Código Sequencial
                 o131_cronogramaperspectivadespesa = int4 = despesa
                 o131_mes = int4 = Mês
                 o131_percentual = float8 = Percentual Correspondente
                 o131_valor = float8 = Valor
                 ";
   //funcao construtor da classe
   function cl_cronogramametadespesa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramametadespesa");
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
       $this->o131_sequencial = ($this->o131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o131_sequencial"]:$this->o131_sequencial);
       $this->o131_cronogramaperspectivadespesa = ($this->o131_cronogramaperspectivadespesa == ""?@$GLOBALS["HTTP_POST_VARS"]["o131_cronogramaperspectivadespesa"]:$this->o131_cronogramaperspectivadespesa);
       $this->o131_mes = ($this->o131_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o131_mes"]:$this->o131_mes);
       $this->o131_percentual = ($this->o131_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["o131_percentual"]:$this->o131_percentual);
       $this->o131_valor = ($this->o131_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o131_valor"]:$this->o131_valor);
     }else{
       $this->o131_sequencial = ($this->o131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o131_sequencial"]:$this->o131_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o131_sequencial){
      $this->atualizacampos();
     if($this->o131_cronogramaperspectivadespesa == null ){
       $this->erro_sql = " Campo despesa nao Informado.";
       $this->erro_campo = "o131_cronogramaperspectivadespesa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o131_mes == null ){
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "o131_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o131_percentual == null ){
       $this->erro_sql = " Campo Percentual Correspondente nao Informado.";
       $this->erro_campo = "o131_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o131_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o131_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o131_sequencial == "" || $o131_sequencial == null ){
       $result = db_query("select nextval('cronogramametadespesa_o131_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramametadespesa_o131_sequencial_seq do campo: o131_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->o131_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from cronogramametadespesa_o131_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o131_sequencial)){
         $this->erro_sql = " Campo o131_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o131_sequencial = $o131_sequencial;
       }
     }
     if(($this->o131_sequencial == null) || ($this->o131_sequencial == "") ){
       $this->erro_sql = " Campo o131_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramametadespesa(
                                       o131_sequencial
                                      ,o131_cronogramaperspectivadespesa
                                      ,o131_mes
                                      ,o131_percentual
                                      ,o131_valor
                       )
                values (
                                $this->o131_sequencial
                               ,$this->o131_cronogramaperspectivadespesa
                               ,$this->o131_mes
                               ,$this->o131_percentual
                               ,$this->o131_valor
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Metas da despesa ($this->o131_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Metas da despesa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Metas da despesa ($this->o131_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o131_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o131_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15018,'$this->o131_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2640,15018,'','".AddSlashes(pg_result($resaco,0,'o131_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2640,15019,'','".AddSlashes(pg_result($resaco,0,'o131_cronogramaperspectivadespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2640,15020,'','".AddSlashes(pg_result($resaco,0,'o131_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2640,15021,'','".AddSlashes(pg_result($resaco,0,'o131_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2640,15022,'','".AddSlashes(pg_result($resaco,0,'o131_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o131_sequencial=null) {
      $this->atualizacampos();
     $sql = " update cronogramametadespesa set ";
     $virgula = "";
     if(trim($this->o131_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o131_sequencial"])){
       $sql  .= $virgula." o131_sequencial = $this->o131_sequencial ";
       $virgula = ",";
       if(trim($this->o131_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o131_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o131_cronogramaperspectivadespesa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o131_cronogramaperspectivadespesa"])){
       $sql  .= $virgula." o131_cronogramaperspectivadespesa = $this->o131_cronogramaperspectivadespesa ";
       $virgula = ",";
       if(trim($this->o131_cronogramaperspectivadespesa) == null ){
         $this->erro_sql = " Campo despesa nao Informado.";
         $this->erro_campo = "o131_cronogramaperspectivadespesa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o131_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o131_mes"])){
       $sql  .= $virgula." o131_mes = $this->o131_mes ";
       $virgula = ",";
       if(trim($this->o131_mes) == null ){
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o131_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o131_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o131_percentual"])){
       $sql  .= $virgula." o131_percentual = $this->o131_percentual ";
       $virgula = ",";
       if(trim($this->o131_percentual) == null ){
         $this->erro_sql = " Campo Percentual Correspondente nao Informado.";
         $this->erro_campo = "o131_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o131_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o131_valor"])){
       $sql  .= $virgula." o131_valor = $this->o131_valor ";
       $virgula = ",";
       if(trim($this->o131_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o131_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o131_sequencial!=null){
       $sql .= " o131_sequencial = $this->o131_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o131_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15018,'$this->o131_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o131_sequencial"]) || $this->o131_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2640,15018,'".AddSlashes(pg_result($resaco,$conresaco,'o131_sequencial'))."','$this->o131_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o131_cronogramaperspectivadespesa"]) || $this->o131_cronogramaperspectivadespesa != "")
           $resac = db_query("insert into db_acount values($acount,2640,15019,'".AddSlashes(pg_result($resaco,$conresaco,'o131_cronogramaperspectivadespesa'))."','$this->o131_cronogramaperspectivadespesa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o131_mes"]) || $this->o131_mes != "")
           $resac = db_query("insert into db_acount values($acount,2640,15020,'".AddSlashes(pg_result($resaco,$conresaco,'o131_mes'))."','$this->o131_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o131_percentual"]) || $this->o131_percentual != "")
           $resac = db_query("insert into db_acount values($acount,2640,15021,'".AddSlashes(pg_result($resaco,$conresaco,'o131_percentual'))."','$this->o131_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o131_valor"]) || $this->o131_valor != "")
           $resac = db_query("insert into db_acount values($acount,2640,15022,'".AddSlashes(pg_result($resaco,$conresaco,'o131_valor'))."','$this->o131_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Metas da despesa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o131_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Metas da despesa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o131_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o131_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15018,'$o131_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2640,15018,'','".AddSlashes(pg_result($resaco,$iresaco,'o131_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2640,15019,'','".AddSlashes(pg_result($resaco,$iresaco,'o131_cronogramaperspectivadespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2640,15020,'','".AddSlashes(pg_result($resaco,$iresaco,'o131_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2640,15021,'','".AddSlashes(pg_result($resaco,$iresaco,'o131_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2640,15022,'','".AddSlashes(pg_result($resaco,$iresaco,'o131_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cronogramametadespesa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o131_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o131_sequencial = $o131_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Metas da despesa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o131_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Metas da despesa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o131_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o131_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramametadespesa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $o131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cronogramametadespesa ";
     $sql .= "      inner join cronogramaperspectivadespesa  on cronogramaperspectivadespesa.o130_sequencial = cronogramametadespesa.o131_cronogramaperspectivadespesa";
     $sql .= "      inner join orcdotacao                    on orcdotacao.o58_anousu                        = cronogramaperspectivadespesa.o130_anousu";
     $sql .= "                                              and  orcdotacao.o58_coddot                       = cronogramaperspectivadespesa.o130_coddot";
     $sql .= "      inner join orcelemento                   on o56_codele                                   = o58_codele";
     $sql .= "                                              and o56_anousu                                   = o58_anousu";
     $sql .= "      inner join cronogramaperspectiva         on cronogramaperspectiva.o124_sequencial = cronogramaperspectivadespesa.o130_cronogramaperspectiva";
     $sql2 = "";
     if($dbwhere==""){
       if($o131_sequencial!=null ){
         $sql2 .= " where cronogramametadespesa.o131_sequencial = $o131_sequencial ";
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
   function sql_query_file ( $o131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cronogramametadespesa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o131_sequencial!=null ){
         $sql2 .= " where cronogramametadespesa.o131_sequencial = $o131_sequencial ";
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

  public function sql_query_meta_despesa ( $o131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = " select {$campos} ";
    $sql .= "   from cronogramaperspectivadespesa  ";
    $sql .= "        inner join cronogramaperspectiva         on cronogramaperspectiva.o124_sequencial = cronogramaperspectivadespesa.o130_cronogramaperspectiva";
    $sql .= "        inner join orcdotacao                    on orcdotacao.o58_anousu  = cronogramaperspectivadespesa.o130_anousu";
    $sql .= "                                                and  orcdotacao.o58_coddot = cronogramaperspectivadespesa.o130_coddot";
    $sql .= "        left join cronogramametadespesa on cronogramaperspectivadespesa.o130_sequencial = cronogramametadespesa.o131_cronogramaperspectivadespesa ";

    if (empty($dbwhere) && !empty($o131_sequencial)) {
       $sql .= " where cronogramametadespesa.o131_sequencial = $o131_sequencial ";
    } else if (!empty($dbwhere)) {
      $sql .= " where $dbwhere ";
    }

    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }

    return $sql;
  }

  public function sql_query_meta_despesa_anterior ( $o131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = " select {$campos} ";
    $sql .= "   from   cronogramaperspectiva atual ";
    $sql .= "        inner join cronogramaperspectivaacompanhamento on atual.o124_sequencial = o151_cronogramaperspectiva";
    $sql .= "        inner join cronogramaperspectiva origem    on origem.o124_sequencial = o151_cronogramaperspectivaorigem";
    $sql .= "        inner join cronogramaperspectivadespesa  on origem.o124_sequencial = o130_cronogramaperspectiva";
    $sql .= "        inner join cronogramametadespesa         on o130_sequencial = cronogramametadespesa.o131_cronogramaperspectivadespesa ";
    $sql .= "        inner join orcdotacao                    on orcdotacao.o58_anousu  = cronogramaperspectivadespesa.o130_anousu";
    $sql .= "                                                and  orcdotacao.o58_coddot = cronogramaperspectivadespesa.o130_coddot";

    if (empty($dbwhere) && !empty($o131_sequencial)) {

      $sql .= " where cronogramametadespesa.o131_sequencial = $o131_sequencial ";
    } else if (!empty($dbwhere)) {
      $sql .= " where $dbwhere ";
    }

    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }

    return $sql;
  }

  // funcao do sql
  function sql_query_metas ( $o131_sequencial=null,$campos="*",$ordem=null,$dbwhere="", $iMes=''){
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
    $sql .= "     from  cronogramaperspectivadespesa ";
    $sql .= "      inner join orcdotacao                    on orcdotacao.o58_anousu                        = cronogramaperspectivadespesa.o130_anousu";
    $sql .= "                                              and  orcdotacao.o58_coddot                       = cronogramaperspectivadespesa.o130_coddot";
    $sql .= "      inner join orcelemento                   on o56_codele                                   = o58_codele";
    $sql .= "                                              and o56_anousu                                   = o58_anousu";
    $sql .= "      inner join cronogramaperspectiva         on cronogramaperspectiva.o124_sequencial = cronogramaperspectivadespesa.o130_cronogramaperspectiva";
    $sql .= "      left join cronogramametadespesa  on cronogramaperspectivadespesa.o130_sequencial = cronogramametadespesa.o131_cronogramaperspectivadespesa and o131_mes = {$iMes}";
    $sql2 = "";
    if($dbwhere==""){
      if($o131_sequencial!=null ){
        $sql2 .= " where cronogramametadespesa.o131_sequencial = $o131_sequencial ";
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
}
