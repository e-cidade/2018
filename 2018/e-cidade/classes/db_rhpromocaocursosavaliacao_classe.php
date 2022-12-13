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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhpromocaocursosavaliacao
class cl_rhpromocaocursosavaliacao { 
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
   var $h75_sequencial = 0; 
   var $h75_rhpromocaocurso = 0; 
   var $h75_rhavaliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h75_sequencial = int4 = sequencial 
                 h75_rhpromocaocurso = int4 = Curso da promoção 
                 h75_rhavaliacao = int4 = Avaliação 
                 ";
   //funcao construtor da classe 
   function cl_rhpromocaocursosavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpromocaocursosavaliacao"); 
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
       $this->h75_sequencial = ($this->h75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h75_sequencial"]:$this->h75_sequencial);
       $this->h75_rhpromocaocurso = ($this->h75_rhpromocaocurso == ""?@$GLOBALS["HTTP_POST_VARS"]["h75_rhpromocaocurso"]:$this->h75_rhpromocaocurso);
       $this->h75_rhavaliacao = ($this->h75_rhavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h75_rhavaliacao"]:$this->h75_rhavaliacao);
     }else{
       $this->h75_sequencial = ($this->h75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h75_sequencial"]:$this->h75_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h75_sequencial){ 
      $this->atualizacampos();
     if($this->h75_rhpromocaocurso == null ){ 
       $this->erro_sql = " Campo Curso da promoção nao Informado.";
       $this->erro_campo = "h75_rhpromocaocurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h75_rhavaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação nao Informado.";
       $this->erro_campo = "h75_rhavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h75_sequencial == "" || $h75_sequencial == null ){
       $result = db_query("select nextval('rhpromocaocursosavaliacao_h75_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpromocaocursosavaliacao_h75_sequencial_seq do campo: h75_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h75_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpromocaocursosavaliacao_h75_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h75_sequencial)){
         $this->erro_sql = " Campo h75_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h75_sequencial = $h75_sequencial; 
       }
     }
     if(($this->h75_sequencial == null) || ($this->h75_sequencial == "") ){ 
       $this->erro_sql = " Campo h75_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpromocaocursosavaliacao(
                                       h75_sequencial 
                                      ,h75_rhpromocaocurso 
                                      ,h75_rhavaliacao 
                       )
                values (
                                $this->h75_sequencial 
                               ,$this->h75_rhpromocaocurso 
                               ,$this->h75_rhavaliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação dos cursos ($this->h75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação dos cursos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação dos cursos ($this->h75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h75_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h75_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18724,'$this->h75_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3318,18724,'','".AddSlashes(pg_result($resaco,0,'h75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3318,18725,'','".AddSlashes(pg_result($resaco,0,'h75_rhpromocaocurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3318,18726,'','".AddSlashes(pg_result($resaco,0,'h75_rhavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h75_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhpromocaocursosavaliacao set ";
     $virgula = "";
     if(trim($this->h75_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h75_sequencial"])){ 
       $sql  .= $virgula." h75_sequencial = $this->h75_sequencial ";
       $virgula = ",";
       if(trim($this->h75_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "h75_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h75_rhpromocaocurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h75_rhpromocaocurso"])){ 
       $sql  .= $virgula." h75_rhpromocaocurso = $this->h75_rhpromocaocurso ";
       $virgula = ",";
       if(trim($this->h75_rhpromocaocurso) == null ){ 
         $this->erro_sql = " Campo Curso da promoção nao Informado.";
         $this->erro_campo = "h75_rhpromocaocurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h75_rhavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h75_rhavaliacao"])){ 
       $sql  .= $virgula." h75_rhavaliacao = $this->h75_rhavaliacao ";
       $virgula = ",";
       if(trim($this->h75_rhavaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação nao Informado.";
         $this->erro_campo = "h75_rhavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h75_sequencial!=null){
       $sql .= " h75_sequencial = $this->h75_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h75_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18724,'$this->h75_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h75_sequencial"]) || $this->h75_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3318,18724,'".AddSlashes(pg_result($resaco,$conresaco,'h75_sequencial'))."','$this->h75_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h75_rhpromocaocurso"]) || $this->h75_rhpromocaocurso != "")
           $resac = db_query("insert into db_acount values($acount,3318,18725,'".AddSlashes(pg_result($resaco,$conresaco,'h75_rhpromocaocurso'))."','$this->h75_rhpromocaocurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h75_rhavaliacao"]) || $this->h75_rhavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,3318,18726,'".AddSlashes(pg_result($resaco,$conresaco,'h75_rhavaliacao'))."','$this->h75_rhavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação dos cursos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação dos cursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h75_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h75_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18724,'$h75_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3318,18724,'','".AddSlashes(pg_result($resaco,$iresaco,'h75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3318,18725,'','".AddSlashes(pg_result($resaco,$iresaco,'h75_rhpromocaocurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3318,18726,'','".AddSlashes(pg_result($resaco,$iresaco,'h75_rhavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpromocaocursosavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h75_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h75_sequencial = $h75_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação dos cursos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação dos cursos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h75_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpromocaocursosavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocaocursosavaliacao ";
     $sql .= "      inner join rhavaliacao  on  rhavaliacao.h73_sequencial = rhpromocaocursosavaliacao.h75_rhavaliacao";
     $sql .= "      inner join rhpromocaocurso  on  rhpromocaocurso.h74_sequencial = rhpromocaocursosavaliacao.h75_rhpromocaocurso";
     $sql .= "      inner join rhpromocao  as a on   a.h72_sequencial = rhavaliacao.h73_rhpromocao";
     $sql .= "      inner join curric  on  curric.h03_seq = rhpromocaocurso.h74_rhcurso";
     $sql .= "      inner join rhpromocao  as b on   b.h72_sequencial = rhpromocaocurso.h74_rhpromocao";
     $sql2 = "";
     if($dbwhere==""){
       if($h75_sequencial!=null ){
         $sql2 .= " where rhpromocaocursosavaliacao.h75_sequencial = $h75_sequencial "; 
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
   function sql_query_file ( $h75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocaocursosavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h75_sequencial!=null ){
         $sql2 .= " where rhpromocaocursosavaliacao.h75_sequencial = $h75_sequencial "; 
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