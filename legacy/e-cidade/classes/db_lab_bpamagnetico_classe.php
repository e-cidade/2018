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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_bpamagnetico
class cl_lab_bpamagnetico {
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
   var $la55_i_codigo = 0;
   var $la55_i_fechamento = 0;
   var $la55_i_usuario = 0;
   var $la55_d_data_dia = null;
   var $la55_d_data_mes = null;
   var $la55_d_data_ano = null;
   var $la55_d_data = null;
   var $la55_c_hora = null;
   var $la55_t_arquivo = null;
   var $la55_o_arquivo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 la55_i_codigo = int4 = Código
                 la55_i_fechamento = int4 = Fechamento
                 la55_i_usuario = int4 = Usuário
                 la55_d_data = date = Data
                 la55_c_hora = char(5) = Hora
                 la55_t_arquivo = text = Arquivo
                 la55_o_arquivo = oid = Arquivo
                 ";
   //funcao construtor da classe
   function cl_lab_bpamagnetico() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_bpamagnetico");
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
       $this->la55_i_codigo = ($this->la55_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_i_codigo"]:$this->la55_i_codigo);
       $this->la55_i_fechamento = ($this->la55_i_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_i_fechamento"]:$this->la55_i_fechamento);
       $this->la55_i_usuario = ($this->la55_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_i_usuario"]:$this->la55_i_usuario);
       if($this->la55_d_data == ""){
         $this->la55_d_data_dia = ($this->la55_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_d_data_dia"]:$this->la55_d_data_dia);
         $this->la55_d_data_mes = ($this->la55_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_d_data_mes"]:$this->la55_d_data_mes);
         $this->la55_d_data_ano = ($this->la55_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_d_data_ano"]:$this->la55_d_data_ano);
         if($this->la55_d_data_dia != ""){
            $this->la55_d_data = $this->la55_d_data_ano."-".$this->la55_d_data_mes."-".$this->la55_d_data_dia;
         }
       }
       $this->la55_c_hora = ($this->la55_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_c_hora"]:$this->la55_c_hora);
       $this->la55_t_arquivo = ($this->la55_t_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_t_arquivo"]:$this->la55_t_arquivo);
       $this->la55_o_arquivo = ($this->la55_o_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["la55_o_arquivo"]:$this->la55_o_arquivo);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->la55_i_codigo == null ){
       $la55_i_codigo="";
     }
     if($this->la55_i_fechamento == null ){
       $this->erro_sql = " Campo Fechamento nao Informado.";
       $this->erro_campo = "la55_i_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la55_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "la55_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la55_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "la55_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la55_c_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "la55_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la55_t_arquivo == null ){
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "la55_t_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la55_o_arquivo == null ){
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "la55_o_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la55_i_codigo == "" || $la55_i_codigo == null ){
       $result = db_query("select nextval('lab_bpamagnetico_la55_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_bpamagnetico_la55_i_codigo_seq do campo: la55_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->la55_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from lab_bpamagnetico_la55_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la55_i_codigo)){
         $this->erro_sql = " Campo la55_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la55_i_codigo = $la55_i_codigo;
       }
     }
     $sql = "insert into lab_bpamagnetico(
                                       la55_i_codigo
                                      ,la55_i_fechamento
                                      ,la55_i_usuario
                                      ,la55_d_data
                                      ,la55_c_hora
                                      ,la55_t_arquivo
                                      ,la55_o_arquivo
                       )
                values (
                                $this->la55_i_codigo
                               ,$this->la55_i_fechamento
                               ,$this->la55_i_usuario
                               ,".($this->la55_d_data == "null" || $this->la55_d_data == ""?"null":"'".$this->la55_d_data."'")."
                               ,'$this->la55_c_hora'
                               ,'$this->la55_t_arquivo'
                               ,$this->la55_o_arquivo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "registra a geração do BPA () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "registra a geração do BPA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "registra a geração do BPA () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   }
   // funcao para alteracao
   function alterar ( $oid=null ) {
      $this->atualizacampos();
     $sql = " update lab_bpamagnetico set ";
     $virgula = "";
     if(trim($this->la55_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_i_codigo"])){
       $sql  .= $virgula." la55_i_codigo = $this->la55_i_codigo ";
       $virgula = ",";
       if(trim($this->la55_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la55_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la55_i_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_i_fechamento"])){
       $sql  .= $virgula." la55_i_fechamento = $this->la55_i_fechamento ";
       $virgula = ",";
       if(trim($this->la55_i_fechamento) == null ){
         $this->erro_sql = " Campo Fechamento nao Informado.";
         $this->erro_campo = "la55_i_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la55_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_i_usuario"])){
       $sql  .= $virgula." la55_i_usuario = $this->la55_i_usuario ";
       $virgula = ",";
       if(trim($this->la55_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "la55_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la55_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la55_d_data_dia"] !="") ){
       $sql  .= $virgula." la55_d_data = '$this->la55_d_data' ";
       $virgula = ",";
       if(trim($this->la55_d_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "la55_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["la55_d_data_dia"])){
         $sql  .= $virgula." la55_d_data = null ";
         $virgula = ",";
         if(trim($this->la55_d_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "la55_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la55_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_c_hora"])){
       $sql  .= $virgula." la55_c_hora = '$this->la55_c_hora' ";
       $virgula = ",";
       if(trim($this->la55_c_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "la55_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la55_t_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_t_arquivo"])){
       $sql  .= $virgula." la55_t_arquivo = '$this->la55_t_arquivo' ";
       $virgula = ",";
       if(trim($this->la55_t_arquivo) == null ){
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "la55_t_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la55_o_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la55_o_arquivo"])){
       $sql  .= $virgula." la55_o_arquivo = $this->la55_o_arquivo ";
       $virgula = ",";
       if(trim($this->la55_o_arquivo) == null ){
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "la55_o_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registra a geração do BPA nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registra a geração do BPA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ( $oid=null ,$dbwhere=null) {
     $sql = " delete from lab_bpamagnetico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registra a geração do BPA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registra a geração do BPA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_bpamagnetico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $oid = null,$campos="lab_bpamagnetico.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_bpamagnetico ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_bpamagnetico.la55_i_usuario";
     $sql .= "      inner join lab_fechamento  on  lab_fechamento.la54_i_codigo = lab_bpamagnetico.la55_i_fechamento";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where lab_bpamagnetico.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_bpamagnetico ";
     $sql2 = "";
     if($dbwhere==""){
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

/**
 * Função que retorna o sql da produção do BPA
 * @param object $dados
 */
function sql_querry_prd_bpa($oDados) {

  $sSql2  = " select ";
  $sSql2 .= "    la02_i_cnes as prd_ups, ";
  $sSql2 .= "    lpad(sd63_c_procedimento::text, 10, '0') as prd_pa, ";
  $sSql2 .= "    (select count(*)  from sau_proccid ";
  $sSql2 .= "       where sd72_i_procedimento = sd63_i_codigo ";
  $sSql2 .= "         and sd72_i_anocomp = sd63_i_anocomp ";
  $sSql2 .= "         and sd72_i_mescomp = sd63_i_mescomp) as proc_quant_cid, ";
  $sSql2 .= "    lpad(rh70_estrutural::text,6,'0') as prd_cbo,";

  if ($oDados->sTipo == "02" || $oDados->sTipo == "03") {

    $sSql2 .= "    la22_i_codigo||la08_i_codigo as cod_faa, ";
    $sSql2 .= "    '$oDados->iCompano".str_pad ($oDados->iCompmes,2, "0", STR_PAD_LEFT )."' as prd_cmp, ";
    $sSql2 .= "    la32_d_data as prd_dtaten, ";
    $sSql2 .= "    (select  s115_c_cartaosus from cgs_cartaosus ";
    $sSql2 .= "     where s115_i_cgs=cgs.z01_i_numcgs ";
    $sSql2 .= "     order by  s115_c_tipo asc limit 1) as prd_cnspac, ";
    $sSql2 .= "    lpad(z01_v_sexo::text,1,' ') as prd_sexo, ";
    $sSql2 .= "    lpad('$oDados->iCidade',6,' ') as prd_ibge, ";
    $sSql2 .= "    lpad(sd70_c_cid::text,4,' ') as prd_cid, ";
    $sSql2 .= "    lpad(la21_i_quantidade,6,0) as prd_qt, ";
    $sSql2 .= "    '01' as prd_caten, ";
    $sSql2 .= "    '             ' as prd_naut, ";
    $sSql2 .= "    'BPA' as prd_org, ";
    $sSql2 .= "    lpad(z01_v_nome::text,30,' ') as prd_nmpac, ";
    $sSql2 .= "    z01_d_nasc as prd_dtnasc, ";
    $sSql2 .= "    '99' as prd_raca, ";
    $sSql2 .= "    null as prd_flh, ";
    $sSql2 .= "    null as prd_seq, ";
    $sSql2 .= "    la06_c_cns as prd_cnsmed,";
    $sSql2 .= "    'I' as prd_orig, ";
    $sSql2 .= "    case when fc_idade(z01_d_nasc,la32_d_data) > 99 then 40 ";
    $sSql2 .= "     else fc_idade(z01_d_nasc,la32_d_data) ";
    $sSql2 .= "    end as prd_idade, ";
    $sSql2 .= "    z01_nome as nome_med, ";
    $sSql2 .= "    la22_i_codigo as cod_requi, ";
    $sSql2 .= "    la08_i_codigo as cod_exame, ";
    $sSql2 .= "    la06_i_cgm    as cod_prof, ";
    $sSql2 .= "    z01_i_cgsund  as cod_pac, ";
    $sSql2 .= "    case when (select  s115_c_cartaosus from cgs_cartaosus";
    $sSql2 .= "               where s115_i_cgs=cgs.z01_i_numcgs order by  s115_c_tipo asc limit 1)";
    $sSql2 .= "    is null then false ";
    $sSql2 .= "    else fc_valida_cns( (select  s115_c_cartaosus from cgs_cartaosus ";
    $sSql2 .= "                         where s115_i_cgs=cgs.z01_i_numcgs order by  s115_c_tipo asc limit 1) ) ";
    $sSql2 .= "    end as valida_cns_cgs,";
    $sSql2 .= "    case when la06_c_cns is null then false ";
    $sSql2 .= "         else fc_valida_cns(la06_c_cns)  ";
    $sSql2 .= "    end as valida_cns_med";

  } else {

    $sSql2 .= "    'C' as prd_orig, ";
    $sSql2 .= "    ' ' as cod_faa, ";
    $sSql2 .= "    rh70_estrutural, ";
    $sSql2 .= "       case when ( select sd73_c_detalhe ";
    $sSql2 .= "                   from sau_procdetalhe ";
    $sSql2 .= "                   inner join sau_detalhe on sau_detalhe.sd73_i_codigo = sau_procdetalhe.sd74_i_detalhe";
    $sSql2 .= "                   where sau_procdetalhe.sd74_i_procedimento  = sau_procedimento.sd63_i_codigo ";
    $sSql2 .= "                   and sd73_c_detalhe = '012' ";
    $sSql2 .= "                   limit 1 ";
    $sSql2 .= "                 ) = '012' then  ";
    $sSql2 .= "            case when fc_idade(z01_d_nasc,la32_d_data) > 99 then 40 ";
    $sSql2 .= "            else fc_idade(z01_d_nasc,la32_d_data)  ";
    $sSql2 .= "            end  ";
    $sSql2 .= "       else '999'  ";
    $sSql2 .= "       end as prd_idade, ";
    $sSql2 .= "    lpad(sum(la21_i_quantidade),6,0) as prd_qt";

  }

  $sSql2 .= "    from lab_requiitem ";
  $sSql2 .= "      left join lab_coletaitem     on lab_coletaitem.la32_i_requiitem  = lab_requiitem.la21_i_codigo ";
  $sSql2 .= "      left join lab_requisicao     on lab_requisicao.la22_i_codigo     = lab_requiitem.la21_i_requisicao ";
  $sSql2 .= "      left join cgs                on cgs.z01_i_numcgs                 = lab_requisicao.la22_i_cgs ";
  $sSql2 .= "      left join cgs_und            on cgs_und.z01_i_cgsund             = cgs.z01_i_numcgs ";
  $sSql2 .= "      left  join (select distinct on (s115_i_cgs) s115_i_cgs, ";
  $sSql2 .= "                    s115_c_cartaosus, ";
  $sSql2 .= "                    s115_c_tipo ";
  $sSql2 .= "               from cgs_cartaosus ";
  $sSql2 .= "              order by s115_i_cgs, s115_c_tipo asc) as cgs_cartaosus";
  $sSql2 .= "                                   on cgs_cartaosus.s115_i_cgs         = cgs.z01_i_numcgs ";
  $sSql2 .= "      left join lab_setorexame     on lab_setorexame.la09_i_codigo     = lab_requiitem.la21_i_setorexame ";
  $sSql2 .= "      left join lab_exame          on lab_exame.la08_i_codigo          = lab_setorexame.la09_i_exame ";
  $sSql2 .= "      inner join (select distinct on (la47_i_requiitem) lab_conferencia.*  from lab_conferencia";
  $sSql2 .= "                 order by la47_i_requiitem,la47_d_data desc,la47_c_hora desc)";
  $sSql2 .= "             as lab_conferencia    on lab_conferencia.la47_i_requiitem = lab_requiitem.la21_i_codigo ";
  $sSql2 .= "      left join sau_procedimento   on sau_procedimento.sd63_i_codigo   = lab_conferencia.la47_i_procedimento ";
  $sSql2 .= "      left join sau_financiamento  on sau_financiamento.sd65_i_codigo  = sau_procedimento.sd63_i_financiamento ";
  $sSql2 .= "      left join lab_labsetor       on lab_labsetor.la24_i_codigo       = lab_setorexame.la09_i_labsetor ";
  $sSql2 .= "      left join lab_laboratorio    on lab_laboratorio.la02_i_codigo    = lab_labsetor.la24_i_laboratorio ";
  $sSql2 .= "      left join lab_labresp        on lab_labresp.la06_i_codigo        = lab_labsetor.la24_i_resp ";
  $sSql2 .= "      left join rhcbo              on rhcbo.rh70_sequencial            = lab_labresp.la06_i_cbo ";
  $sSql2 .= "      left join lab_medico         on lab_medico.la38_i_requisicao     = lab_requisicao.la22_i_codigo ";
  $sSql2 .= "      left join medicos            on medicos.sd03_i_codigo            = lab_medico.la38_i_medico ";
  $sSql2 .= "      left join cgm m              on m.z01_numcgm                     = lab_labresp.la06_i_cgm ";
  $sSql2 .= "      left join sau_cid            on sau_cid.sd70_i_codigo            = lab_conferencia.la47_i_cid ";
  $sSql2 .= "    where lab_coletaitem.la32_d_data between '$oDados->dIni' and '$oDados->dFim' ";
  if ($oDados->sTipo != '03') {

    $sSql2 .= "   and exists ( select *  ";
    $sSql2 .= "                  from sau_procregistro ";
    $sSql2 .= "                inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro";
    $sSql2 .= "                                         and sau_registro.sd84_c_registro = '$oDados->sTipo'  ";
    $sSql2 .= "                 where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
    $sSql2 .= "              ) ";

  }
  if($oDados->iUnidade != ''){
    $sSql2 .= " and la24_i_laboratorio in ($oDados->iUnidade) ";
  }
  if ($oDados->financiamento != 0) {
    $sSql2 .= " and  sd65_c_financiamento = (select sd65_c_financiamento from ";
    $sSql2 .= " sau_financiamento where sd65_i_codigo=$oDados->financiamento) ";
  }
  if($oDados->sTipo == "01"){
    $sSql2 .= " group by prd_ups, sd63_i_anocomp, sd63_i_mescomp, prd_pa, rh70_estrutural, sd63_c_procedimento, ";
    $sSql2 .= "sd63_i_codigo, z01_d_nasc, la32_d_data ";
  }
  $sSql2 .= " order by la02_i_cnes, rh70_estrutural, sd63_c_procedimento ";

  return $sSql2;

}

/**
 * Função que retorna o sql da produção do BPA
 * @param object $dados
 */
function sql_querry_cbr_bpa($oDados, $sSql) {

  $sSql1  = " select ";
  $sSql1 .= "   '#BPA#' as cbc_hdr, ";
  $sSql1 .= "   lpad($oDados->iCompano,4,'0')||lpad($oDados->iCompmes,2,'0')  as cbc_mvm,";
  $sSql1 .= "   lpad($oDados->iLinhas,6,'0')  as cbc_lin,";
  $sSql1 .= "   lpad(ceil($oDados->iLinhas/20),6,'0')  as cbc_flh,";
  $sSql1 .= "   '$oDados->sOrgResp'  as cbc_rsp, ";
  $sSql1 .= "   lpad('$oDados->sSigla',6,' ')  as cbc_sgl, ";
  $sSql1 .= "   (select cgc from db_config where codigo = ".db_getsession ( "DB_instit" ).") as cbc_cgccpf, ";
  $sSql1 .= "   lpad('$oDados->sDestino',40,' ')  as cbc_dst, ";
  $sSql1 .= "   'M' as cbc_dst_in, ";
  $sSql1 .= "   lpad('$oDados->sVersao',10,' ') as cbc_versao, ";
  $sSql1 .= "   (sum(prd_pa::bigint)+sum(prd_qt::bigint))%1111+1111 as cbc_smt_vrf ";
  $sSql1 .= " from ($sSql) as a ";

  return $sSql1;

}

}

?>