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

class cl_aguacortesituacao { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $x43_codsituacao = 0; 
   var $x43_descr = null; 
   var $x43_regra = 0; 
   var $x43_realizacobranca = '1';
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x43_codsituacao = int4 = Situação 
                 x43_descr = varchar(40) = Descrição 
                 x43_regra = int4 = Regra 
                 x43_realizacobranca = bool = Realiza Cobrança 
                 ";
   //funcao construtor da classe 
   function cl_aguacortesituacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacortesituacao"); 
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
       $this->x43_codsituacao = ($this->x43_codsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x43_codsituacao"]:$this->x43_codsituacao);
       $this->x43_descr = ($this->x43_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["x43_descr"]:$this->x43_descr);
       $this->x43_regra = ($this->x43_regra == ""?@$GLOBALS["HTTP_POST_VARS"]["x43_regra"]:$this->x43_regra);
       $this->x43_realizacobranca = ($this->x43_realizacobranca == "1"?@$GLOBALS["HTTP_POST_VARS"]["x43_realizacobranca"]:$this->x43_realizacobranca);
     }else{
       $this->x43_codsituacao = ($this->x43_codsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x43_codsituacao"]:$this->x43_codsituacao);
     }
   }
   // funcao para inclusao
   function incluir ($x43_codsituacao){ 
      $this->atualizacampos();
     if($this->x43_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "x43_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x43_regra == null ){ 
       $this->erro_sql = " Campo Regra nao Informado.";
       $this->erro_campo = "x43_regra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x43_realizacobranca == null ){ 
       $this->x43_realizacobranca = "1";
     }
     if($x43_codsituacao == "" || $x43_codsituacao == null ){
       $result = @db_query("select nextval('aguacorte_x43_codsituacao_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacorte_x43_codsituacao_seq do campo: x43_codsituacao"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x43_codsituacao = pg_result($result,0,0); 
     }else{
       $result = @db_query("select last_value from aguacorte_x43_codsituacao_seq");
       if(($result != false) && (pg_result($result,0,0) < $x43_codsituacao)){
         $this->erro_sql = " Campo x43_codsituacao maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x43_codsituacao = $x43_codsituacao; 
       }
     }
     if(($this->x43_codsituacao == null) || ($this->x43_codsituacao == "") ){ 
       $this->erro_sql = " Campo x43_codsituacao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @db_query("insert into aguacortesituacao(
                                       x43_codsituacao 
                                      ,x43_descr 
                                      ,x43_regra 
                                      ,x43_realizacobranca 
                       )
                values (
                                $this->x43_codsituacao 
                               ,'$this->x43_descr' 
                               ,$this->x43_regra 
                               ,'$this->x43_realizacobranca' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Situação do Corte ($this->x43_codsituacao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Situação do Corte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Situação do Corte ($this->x43_codsituacao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->x43_codsituacao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,8554,'$this->x43_codsituacao','I')");
       $resac = db_query("insert into db_acount values($acount,1457,8554,'','".pg_result($resaco,0,'x43_codsituacao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1457,8555,'','".pg_result($resaco,0,'x43_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1457,8573,'','".pg_result($resaco,0,'x43_regra')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1457,22399,'','".pg_result($resaco,0,'x43_realizacobranca')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x43_codsituacao=null) {
      $this->atualizacampos();
     $sql = " update aguacortesituacao set ";
     $virgula = "";
     if(trim($this->x43_codsituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x43_codsituacao"])){ 
        if(trim($this->x43_codsituacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x43_codsituacao"])){ 
           $this->x43_codsituacao = "0" ; 
        } 
       $sql  .= $virgula." x43_codsituacao = $this->x43_codsituacao ";
       $virgula = ",";
       if(trim($this->x43_codsituacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "x43_codsituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x43_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x43_descr"])){ 
       $sql  .= $virgula." x43_descr = '$this->x43_descr' ";
       $virgula = ",";
       if(trim($this->x43_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "x43_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x43_regra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x43_regra"])){ 
        if(trim($this->x43_regra)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x43_regra"])){ 
           $this->x43_regra = "0" ; 
        } 
       $sql  .= $virgula." x43_regra = $this->x43_regra ";
       $virgula = ",";
       if(trim($this->x43_regra) == null ){ 
         $this->erro_sql = " Campo Regra nao Informado.";
         $this->erro_campo = "x43_regra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x43_realizacobranca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x43_realizacobranca"])){ 
       $sql  .= $virgula." x43_realizacobranca = '$this->x43_realizacobranca' ";
       $virgula = ",";
     }
     $sql .= " where  x43_codsituacao = $this->x43_codsituacao
";


     $resaco = $this->sql_record($this->sql_query_file($this->x43_codsituacao));
     if($this->numrows>0){       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,8554,'$this->x43_codsituacao','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["x43_codsituacao"]))
         $resac = db_query("insert into db_acount values($acount,1457,8554,'".pg_result($resaco,0,'x43_codsituacao')."','$this->x43_codsituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["x43_descr"]))
         $resac = db_query("insert into db_acount values($acount,1457,8555,'".pg_result($resaco,0,'x43_descr')."','$this->x43_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["x43_regra"]))
         $resac = db_query("insert into db_acount values($acount,1457,8573,'".pg_result($resaco,0,'x43_regra')."','$this->x43_regra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["x43_realizacobranca"]))
         $resac = db_query("insert into db_acount values($acount,1457,22399,'".pg_result($resaco,0,'x43_realizacobranca')."','$this->x43_realizacobranca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }

     $result = @db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situação do Corte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situação do Corte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x43_codsituacao=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->x43_codsituacao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,8554,'$this->x43_codsituacao','E')");
       $resac = db_query("insert into db_acount values($acount,1457,8554,'','".pg_result($resaco,0,'x43_codsituacao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1457,8555,'','".pg_result($resaco,0,'x43_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1457,8573,'','".pg_result($resaco,0,'x43_regra')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1457,22399,'','".pg_result($resaco,0,'x43_realizacobranca')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from aguacortesituacao
                    where ";
     $sql2 = "";
      if($this->x43_codsituacao != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " x43_codsituacao = $this->x43_codsituacao ";
}
     $result = @db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situação do Corte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situação do Corte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x43_codsituacao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @db_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x43_codsituacao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortesituacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($x43_codsituacao!=null ){
         $sql2 .= " where aguacortesituacao.x43_codsituacao = $x43_codsituacao "; 
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
   function sql_query_file ( $x43_codsituacao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortesituacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($x43_codsituacao!=null ){
         $sql2 .= " where aguacortesituacao.x43_codsituacao = $x43_codsituacao "; 
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