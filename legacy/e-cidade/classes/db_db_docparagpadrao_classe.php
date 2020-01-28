<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_docparagpadrao
class cl_db_docparagpadrao { 
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
   var $db62_coddoc = 0; 
   var $db62_codparag = 0; 
   var $db62_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db62_coddoc = int4 = Código Documento 
                 db62_codparag = int4 = Cod. Parágrafo 
                 db62_ordem = int4 = Ordem do Parágrafo 
                 ";
   //funcao construtor da classe 
   function cl_db_docparagpadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_docparagpadrao"); 
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
       $this->db62_coddoc = ($this->db62_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["db62_coddoc"]:$this->db62_coddoc);
       $this->db62_codparag = ($this->db62_codparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db62_codparag"]:$this->db62_codparag);
       $this->db62_ordem = ($this->db62_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["db62_ordem"]:$this->db62_ordem);
     }else{
       $this->db62_coddoc = ($this->db62_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["db62_coddoc"]:$this->db62_coddoc);
       $this->db62_codparag = ($this->db62_codparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db62_codparag"]:$this->db62_codparag);
     }
   }
   // funcao para inclusao
   function incluir ($db62_coddoc,$db62_codparag){ 
      $this->atualizacampos();
     if($this->db62_ordem == null ){ 
       $this->erro_sql = " Campo Ordem do Parágrafo nao Informado.";
       $this->erro_campo = "db62_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db62_coddoc = $db62_coddoc; 
       $this->db62_codparag = $db62_codparag; 
     if(($this->db62_coddoc == null) || ($this->db62_coddoc == "") ){ 
       $this->erro_sql = " Campo db62_coddoc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->db62_codparag == null) || ($this->db62_codparag == "") ){ 
       $this->erro_sql = " Campo db62_codparag nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_docparagpadrao(
                                       db62_coddoc 
                                      ,db62_codparag 
                                      ,db62_ordem 
                       )
                values (
                                $this->db62_coddoc 
                               ,$this->db62_codparag 
                               ,$this->db62_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ligação entre o documento e os paragrafos padrões ($this->db62_coddoc."-".$this->db62_codparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ligação entre o documento e os paragrafos padrões já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ligação entre o documento e os paragrafos padrões ($this->db62_coddoc."-".$this->db62_codparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db62_coddoc."-".$this->db62_codparag;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db62_coddoc,$this->db62_codparag));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9167,'$this->db62_coddoc','I')");
       $resac = db_query("insert into db_acountkey values($acount,9168,'$this->db62_codparag','I')");
       $resac = db_query("insert into db_acount values($acount,1570,9167,'','".AddSlashes(pg_result($resaco,0,'db62_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1570,9168,'','".AddSlashes(pg_result($resaco,0,'db62_codparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1570,9169,'','".AddSlashes(pg_result($resaco,0,'db62_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db62_coddoc=null,$db62_codparag=null) { 
      $this->atualizacampos();
     $sql = " update db_docparagpadrao set ";
     $virgula = "";
     if(trim($this->db62_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db62_coddoc"])){ 
       $sql  .= $virgula." db62_coddoc = $this->db62_coddoc ";
       $virgula = ",";
       if(trim($this->db62_coddoc) == null ){ 
         $this->erro_sql = " Campo Código Documento nao Informado.";
         $this->erro_campo = "db62_coddoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db62_codparag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db62_codparag"])){ 
       $sql  .= $virgula." db62_codparag = $this->db62_codparag ";
       $virgula = ",";
       if(trim($this->db62_codparag) == null ){ 
         $this->erro_sql = " Campo Cod. Parágrafo nao Informado.";
         $this->erro_campo = "db62_codparag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db62_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db62_ordem"])){ 
       $sql  .= $virgula." db62_ordem = $this->db62_ordem ";
       $virgula = ",";
       if(trim($this->db62_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem do Parágrafo nao Informado.";
         $this->erro_campo = "db62_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db62_coddoc!=null){
       $sql .= " db62_coddoc = $this->db62_coddoc";
     }
     if($db62_codparag!=null){
       $sql .= " and  db62_codparag = $this->db62_codparag";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db62_coddoc,$this->db62_codparag));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9167,'$this->db62_coddoc','A')");
         $resac = db_query("insert into db_acountkey values($acount,9168,'$this->db62_codparag','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db62_coddoc"]))
           $resac = db_query("insert into db_acount values($acount,1570,9167,'".AddSlashes(pg_result($resaco,$conresaco,'db62_coddoc'))."','$this->db62_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db62_codparag"]))
           $resac = db_query("insert into db_acount values($acount,1570,9168,'".AddSlashes(pg_result($resaco,$conresaco,'db62_codparag'))."','$this->db62_codparag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db62_ordem"]))
           $resac = db_query("insert into db_acount values($acount,1570,9169,'".AddSlashes(pg_result($resaco,$conresaco,'db62_ordem'))."','$this->db62_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ligação entre o documento e os paragrafos padrões nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db62_coddoc."-".$this->db62_codparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ligação entre o documento e os paragrafos padrões nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db62_coddoc."-".$this->db62_codparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db62_coddoc."-".$this->db62_codparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db62_coddoc=null,$db62_codparag=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db62_coddoc,$db62_codparag));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9167,'$db62_coddoc','E')");
         $resac = db_query("insert into db_acountkey values($acount,9168,'$db62_codparag','E')");
         $resac = db_query("insert into db_acount values($acount,1570,9167,'','".AddSlashes(pg_result($resaco,$iresaco,'db62_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1570,9168,'','".AddSlashes(pg_result($resaco,$iresaco,'db62_codparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1570,9169,'','".AddSlashes(pg_result($resaco,$iresaco,'db62_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_docparagpadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db62_coddoc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db62_coddoc = $db62_coddoc ";
        }
        if($db62_codparag != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db62_codparag = $db62_codparag ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ligação entre o documento e os paragrafos padrões nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db62_coddoc."-".$db62_codparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ligação entre o documento e os paragrafos padrões nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db62_coddoc."-".$db62_codparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db62_coddoc."-".$db62_codparag;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_docparagpadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db62_coddoc=null,$db62_codparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_docparagpadrao ";
     $sql .= "      inner join db_documentopadrao  on  db_documentopadrao.db60_coddoc = db_docparagpadrao.db62_coddoc";
     $sql .= "      inner join db_paragrafopadrao  on  db_paragrafopadrao.db61_codparag = db_docparagpadrao.db62_codparag";
     $sql .= "      inner join db_config  on  db_config.codigo = db_documentopadrao.db60_instit";
     $sql .= "      inner join db_tipodoc  on  db_tipodoc.db08_codigo = db_documentopadrao.db60_tipodoc";
     $sql2 = "";
     if($dbwhere==""){
       if($db62_coddoc!=null ){
         $sql2 .= " where db_docparagpadrao.db62_coddoc = $db62_coddoc "; 
       } 
       if($db62_codparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_docparagpadrao.db62_codparag = $db62_codparag "; 
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
   function sql_query_file ( $db62_coddoc=null,$db62_codparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_docparagpadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db62_coddoc!=null ){
         $sql2 .= " where db_docparagpadrao.db62_coddoc = $db62_coddoc "; 
       } 
       if($db62_codparag!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_docparagpadrao.db62_codparag = $db62_codparag "; 
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