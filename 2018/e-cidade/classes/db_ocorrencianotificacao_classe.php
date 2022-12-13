<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE ocorrencianotificacao
class cl_ocorrencianotificacao { 
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
   var $ed105_sequencial = 0; 
   var $ed105_ocorrencia = 0; 
   var $ed105_mensagemnotificacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed105_sequencial = int4 = Código Ocorrência Notificação 
                 ed105_ocorrencia = int4 = Ocorrência 
                 ed105_mensagemnotificacao = int4 = Mensagem Notificação 
                 ";
   //funcao construtor da classe 
   function cl_ocorrencianotificacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ocorrencianotificacao"); 
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
       $this->ed105_sequencial = ($this->ed105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed105_sequencial"]:$this->ed105_sequencial);
       $this->ed105_ocorrencia = ($this->ed105_ocorrencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed105_ocorrencia"]:$this->ed105_ocorrencia);
       $this->ed105_mensagemnotificacao = ($this->ed105_mensagemnotificacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed105_mensagemnotificacao"]:$this->ed105_mensagemnotificacao);
     }else{
       $this->ed105_sequencial = ($this->ed105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed105_sequencial"]:$this->ed105_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed105_sequencial){ 
      $this->atualizacampos();
     if($this->ed105_ocorrencia == null ){ 
       $this->erro_sql = " Campo Ocorrência nao Informado.";
       $this->erro_campo = "ed105_ocorrencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed105_mensagemnotificacao == null ){ 
       $this->erro_sql = " Campo Mensagem Notificação nao Informado.";
       $this->erro_campo = "ed105_mensagemnotificacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed105_sequencial == "" || $ed105_sequencial == null ){
       $result = db_query("select nextval('ocorrencianotificacao_ed105_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ocorrencianotificacao_ed105_sequencial_seq do campo: ed105_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed105_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ocorrencianotificacao_ed105_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed105_sequencial)){
         $this->erro_sql = " Campo ed105_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed105_sequencial = $ed105_sequencial; 
       }
     }
     if(($this->ed105_sequencial == null) || ($this->ed105_sequencial == "") ){ 
       $this->erro_sql = " Campo ed105_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ocorrencianotificacao(
                                       ed105_sequencial 
                                      ,ed105_ocorrencia 
                                      ,ed105_mensagemnotificacao 
                       )
                values (
                                $this->ed105_sequencial 
                               ,$this->ed105_ocorrencia 
                               ,$this->ed105_mensagemnotificacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ocorrencianotificacao ($this->ed105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ocorrencianotificacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ocorrencianotificacao ($this->ed105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed105_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed105_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19254,'$this->ed105_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3422,19254,'','".AddSlashes(pg_result($resaco,0,'ed105_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3422,19255,'','".AddSlashes(pg_result($resaco,0,'ed105_ocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3422,19268,'','".AddSlashes(pg_result($resaco,0,'ed105_mensagemnotificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed105_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ocorrencianotificacao set ";
     $virgula = "";
     if(trim($this->ed105_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed105_sequencial"])){ 
       $sql  .= $virgula." ed105_sequencial = $this->ed105_sequencial ";
       $virgula = ",";
       if(trim($this->ed105_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Ocorrência Notificação nao Informado.";
         $this->erro_campo = "ed105_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed105_ocorrencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed105_ocorrencia"])){ 
       $sql  .= $virgula." ed105_ocorrencia = $this->ed105_ocorrencia ";
       $virgula = ",";
       if(trim($this->ed105_ocorrencia) == null ){ 
         $this->erro_sql = " Campo Ocorrência nao Informado.";
         $this->erro_campo = "ed105_ocorrencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed105_mensagemnotificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed105_mensagemnotificacao"])){ 
       $sql  .= $virgula." ed105_mensagemnotificacao = $this->ed105_mensagemnotificacao ";
       $virgula = ",";
       if(trim($this->ed105_mensagemnotificacao) == null ){ 
         $this->erro_sql = " Campo Mensagem Notificação nao Informado.";
         $this->erro_campo = "ed105_mensagemnotificacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed105_sequencial!=null){
       $sql .= " ed105_sequencial = $this->ed105_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed105_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19254,'$this->ed105_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed105_sequencial"]) || $this->ed105_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3422,19254,'".AddSlashes(pg_result($resaco,$conresaco,'ed105_sequencial'))."','$this->ed105_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed105_ocorrencia"]) || $this->ed105_ocorrencia != "")
           $resac = db_query("insert into db_acount values($acount,3422,19255,'".AddSlashes(pg_result($resaco,$conresaco,'ed105_ocorrencia'))."','$this->ed105_ocorrencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed105_mensagemnotificacao"]) || $this->ed105_mensagemnotificacao != "")
           $resac = db_query("insert into db_acount values($acount,3422,19268,'".AddSlashes(pg_result($resaco,$conresaco,'ed105_mensagemnotificacao'))."','$this->ed105_mensagemnotificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ocorrencianotificacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ocorrencianotificacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed105_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed105_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19254,'$ed105_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3422,19254,'','".AddSlashes(pg_result($resaco,$iresaco,'ed105_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3422,19255,'','".AddSlashes(pg_result($resaco,$iresaco,'ed105_ocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3422,19268,'','".AddSlashes(pg_result($resaco,$iresaco,'ed105_mensagemnotificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ocorrencianotificacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed105_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed105_sequencial = $ed105_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ocorrencianotificacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ocorrencianotificacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed105_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ocorrencianotificacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ocorrencianotificacao ";
     $sql .= "      inner join ocorrencia  on  ocorrencia.ed103_sequencial = ocorrencianotificacao.ed105_ocorrencia";
     $sql .= "      inner join mensagemnotificacao  on  mensagemnotificacao.db134_sequencial = ocorrencianotificacao.ed105_mensagemnotificacao";
     $sql .= "      inner join ocorrenciatipo  on  ocorrenciatipo.ed102_sequencial = ocorrencia.ed103_ocorrenciatipo";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = ocorrencia.ed103_matricula";
     $sql .= "      inner join mensagemnotificacaotipo  on  mensagemnotificacaotipo.db133_sequencial = mensagemnotificacao.db134_mensagemnotificacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed105_sequencial!=null ){
         $sql2 .= " where ocorrencianotificacao.ed105_sequencial = $ed105_sequencial "; 
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
   function sql_query_file ( $ed105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ocorrencianotificacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed105_sequencial!=null ){
         $sql2 .= " where ocorrencianotificacao.ed105_sequencial = $ed105_sequencial "; 
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
?>