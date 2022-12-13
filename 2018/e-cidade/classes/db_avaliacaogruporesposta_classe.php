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

//MODULO: Habitacao
//CLASSE DA ENTIDADE avaliacaogruporesposta
class cl_avaliacaogruporesposta {
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
   var $db107_sequencial = 0;
   var $db107_usuario = 0;
   var $db107_datalancamento_dia = null;
   var $db107_datalancamento_mes = null;
   var $db107_datalancamento_ano = null;
   var $db107_datalancamento = null;
   var $db107_hora = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db107_sequencial = int4 = Sequencial
                 db107_usuario = int4 = Usuário
                 db107_datalancamento = date = Data Lançamento
                 db107_hora = char(5) = Hora
                 ";
   //funcao construtor da classe
   function cl_avaliacaogruporesposta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaogruporesposta");
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
       $this->db107_sequencial = ($this->db107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_sequencial"]:$this->db107_sequencial);
       $this->db107_usuario = ($this->db107_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_usuario"]:$this->db107_usuario);
       if($this->db107_datalancamento == ""){
         $this->db107_datalancamento_dia = ($this->db107_datalancamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_datalancamento_dia"]:$this->db107_datalancamento_dia);
         $this->db107_datalancamento_mes = ($this->db107_datalancamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_datalancamento_mes"]:$this->db107_datalancamento_mes);
         $this->db107_datalancamento_ano = ($this->db107_datalancamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_datalancamento_ano"]:$this->db107_datalancamento_ano);
         if($this->db107_datalancamento_dia != ""){
            $this->db107_datalancamento = $this->db107_datalancamento_ano."-".$this->db107_datalancamento_mes."-".$this->db107_datalancamento_dia;
         }
       }
       $this->db107_hora = ($this->db107_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_hora"]:$this->db107_hora);
     }else{
       $this->db107_sequencial = ($this->db107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db107_sequencial"]:$this->db107_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db107_sequencial){
      $this->atualizacampos();
     if($this->db107_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "db107_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db107_datalancamento == null ){
       $this->erro_sql = " Campo Data Lançamento nao Informado.";
       $this->erro_campo = "db107_datalancamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db107_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "db107_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db107_sequencial == "" || $db107_sequencial == null ){
       $result = db_query("select nextval('avaliacaogruporesposta_db107_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogruporesposta_db107_sequencial_seq do campo: db107_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->db107_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from avaliacaogruporesposta_db107_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db107_sequencial)){
         $this->erro_sql = " Campo db107_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db107_sequencial = $db107_sequencial;
       }
     }
     if(($this->db107_sequencial == null) || ($this->db107_sequencial == "") ){
       $this->erro_sql = " Campo db107_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporesposta(
                                       db107_sequencial
                                      ,db107_usuario
                                      ,db107_datalancamento
                                      ,db107_hora
                       )
                values (
                                $this->db107_sequencial
                               ,$this->db107_usuario
                               ,".($this->db107_datalancamento == "null" || $this->db107_datalancamento == ""?"null":"'".$this->db107_datalancamento."'")."
                               ,'$this->db107_hora'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação Grupo Resposta ($this->db107_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação Grupo Resposta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação Grupo Resposta ($this->db107_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db107_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {

       $resaco = $this->sql_record($this->sql_query_file($this->db107_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16927,'$this->db107_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2987,16927,'','".AddSlashes(pg_result($resaco,0,'db107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2987,16928,'','".AddSlashes(pg_result($resaco,0,'db107_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2987,16929,'','".AddSlashes(pg_result($resaco,0,'db107_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2987,16930,'','".AddSlashes(pg_result($resaco,0,'db107_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($db107_sequencial=null) {
      $this->atualizacampos();
     $sql = " update avaliacaogruporesposta set ";
     $virgula = "";
     if(trim($this->db107_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db107_sequencial"])){
       $sql  .= $virgula." db107_sequencial = $this->db107_sequencial ";
       $virgula = ",";
       if(trim($this->db107_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db107_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db107_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db107_usuario"])){
       $sql  .= $virgula." db107_usuario = $this->db107_usuario ";
       $virgula = ",";
       if(trim($this->db107_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "db107_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db107_datalancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db107_datalancamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db107_datalancamento_dia"] !="") ){
       $sql  .= $virgula." db107_datalancamento = '$this->db107_datalancamento' ";
       $virgula = ",";
       if(trim($this->db107_datalancamento) == null ){
         $this->erro_sql = " Campo Data Lançamento nao Informado.";
         $this->erro_campo = "db107_datalancamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["db107_datalancamento_dia"])){
         $sql  .= $virgula." db107_datalancamento = null ";
         $virgula = ",";
         if(trim($this->db107_datalancamento) == null ){
           $this->erro_sql = " Campo Data Lançamento nao Informado.";
           $this->erro_campo = "db107_datalancamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db107_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db107_hora"])){
       $sql  .= $virgula." db107_hora = '$this->db107_hora' ";
       $virgula = ",";
       if(trim($this->db107_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "db107_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db107_sequencial!=null){
       $sql .= " db107_sequencial = $this->db107_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {

       $resaco = $this->sql_record($this->sql_query_file($this->db107_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16927,'$this->db107_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db107_sequencial"]) || $this->db107_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2987,16927,'".AddSlashes(pg_result($resaco,$conresaco,'db107_sequencial'))."','$this->db107_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db107_usuario"]) || $this->db107_usuario != "")
             $resac = db_query("insert into db_acount values($acount,2987,16928,'".AddSlashes(pg_result($resaco,$conresaco,'db107_usuario'))."','$this->db107_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db107_datalancamento"]) || $this->db107_datalancamento != "")
             $resac = db_query("insert into db_acount values($acount,2987,16929,'".AddSlashes(pg_result($resaco,$conresaco,'db107_datalancamento'))."','$this->db107_datalancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db107_hora"]) || $this->db107_hora != "")
             $resac = db_query("insert into db_acount values($acount,2987,16930,'".AddSlashes(pg_result($resaco,$conresaco,'db107_hora'))."','$this->db107_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Grupo Resposta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Grupo Resposta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($db107_sequencial=null,$dbwhere=null) {

     if (!isset($_SESSION["DB_usaAccount"])) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($db107_sequencial));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16927,'$db107_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,2987,16927,'','".AddSlashes(pg_result($resaco,$iresaco,'db107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2987,16928,'','".AddSlashes(pg_result($resaco,$iresaco,'db107_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2987,16929,'','".AddSlashes(pg_result($resaco,$iresaco,'db107_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2987,16930,'','".AddSlashes(pg_result($resaco,$iresaco,'db107_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporesposta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db107_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db107_sequencial = $db107_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Grupo Resposta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Grupo Resposta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db107_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporesposta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $db107_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from avaliacaogruporesposta ";
     $sql2 = "";
     if($dbwhere==""){
       if($db107_sequencial!=null ){
         $sql2 .= " where avaliacaogruporesposta.db107_sequencial = $db107_sequencial ";
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
   function sql_query_file ( $db107_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from avaliacaogruporesposta ";
     $sql2 = "";
     if($dbwhere==""){
       if($db107_sequencial!=null ){
         $sql2 .= " where avaliacaogruporesposta.db107_sequencial = $db107_sequencial ";
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

  public function sql_query_respostas($iCodigoPergunta = null, $codigoAvaliacao, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from avaliacaogruporesposta ";
    $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
    $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
    $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
    $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
    $sql2 = "";

    if (empty($dbwhere)) {

      $sql2 .=" where ";
      $aWhere = array();

      if (!empty($iCodigoPergunta)) {
        $aWhere[] = " db103_sequencial = {$iCodigoPergunta} ";
      }
      if (!empty($codigoAvaliacao)) {
        $aWhere[] = " db107_sequencial = {$codigoAvaliacao} ";
      }
      $sql2 .= implode("and ", $aWhere);
    } else if (!empty($dbwhere)) {
      $sql2 = " where {$dbwhere}";
    }

    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

    public function sql_avaliacao_preenchida( $db107_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" )
    {
        $sql  = "select {$campos} ";
        $sql .= "  from avaliacaogruporesposta ";
        $sql .= "  join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial ";
        $sql .= "  join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta on db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao on db102_avaliacao = db101_sequencial ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($db107_sequencial)){
                $sql2 .= " where avaliacaogruporesposta.db107_sequencial = {$db107_sequencial} ";
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

    public function busca_resposta_preenchimento($preenchimentoId = null, $campos = "*")
    {
        $sql  = "select {$campos} ";
        $sql .= "  from avaliacaogruporesposta ";
        $sql .= "  join avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial ";
        $sql .= "  join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao ON db102_avaliacao = db101_sequencial ";

        $sql .= " where db107_sequencial = {$preenchimentoId} ";
        return $sql;
    }
}
