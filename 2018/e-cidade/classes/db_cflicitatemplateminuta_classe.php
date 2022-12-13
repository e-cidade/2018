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

//MODULO: licitacao
//CLASSE DA ENTIDADE cflicitatemplateminuta
class cl_cflicitatemplateminuta { 
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
   var $l41_sequencial = 0; 
   var $l41_cflicita = 0; 
   var $l41_db_documentotemplate = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l41_sequencial = int4 = Código Sequencial 
                 l41_cflicita = int4 = Código Licitação 
                 l41_db_documentotemplate = int4 = Documento Template 
                 ";
   //funcao construtor da classe 
   function cl_cflicitatemplateminuta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cflicitatemplateminuta"); 
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
       $this->l41_sequencial = ($this->l41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l41_sequencial"]:$this->l41_sequencial);
       $this->l41_cflicita = ($this->l41_cflicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l41_cflicita"]:$this->l41_cflicita);
       $this->l41_db_documentotemplate = ($this->l41_db_documentotemplate == ""?@$GLOBALS["HTTP_POST_VARS"]["l41_db_documentotemplate"]:$this->l41_db_documentotemplate);
     }else{
       $this->l41_sequencial = ($this->l41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l41_sequencial"]:$this->l41_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l41_sequencial){ 
      $this->atualizacampos();
     if($this->l41_cflicita == null ){ 
       $this->erro_sql = " Campo Código Licitação nao Informado.";
       $this->erro_campo = "l41_cflicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l41_db_documentotemplate == null ){ 
       $this->erro_sql = " Campo Documento Template nao Informado.";
       $this->erro_campo = "l41_db_documentotemplate";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l41_sequencial == "" || $l41_sequencial == null ){
       $result = db_query("select nextval('cflicitatemplateminuta_l41_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cflicitatemplateminuta_l41_sequencial_seq do campo: l41_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l41_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cflicitatemplateminuta_l41_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l41_sequencial)){
         $this->erro_sql = " Campo l41_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l41_sequencial = $l41_sequencial; 
       }
     }
     if(($this->l41_sequencial == null) || ($this->l41_sequencial == "") ){ 
       $this->erro_sql = " Campo l41_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cflicitatemplateminuta(
                                       l41_sequencial 
                                      ,l41_cflicita 
                                      ,l41_db_documentotemplate 
                       )
                values (
                                $this->l41_sequencial 
                               ,$this->l41_cflicita 
                               ,$this->l41_db_documentotemplate 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cflicitatemplateminuta ($this->l41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cflicitatemplateminuta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cflicitatemplateminuta ($this->l41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l41_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l41_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17276,'$this->l41_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3058,17276,'','".AddSlashes(pg_result($resaco,0,'l41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3058,17277,'','".AddSlashes(pg_result($resaco,0,'l41_cflicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3058,17278,'','".AddSlashes(pg_result($resaco,0,'l41_db_documentotemplate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l41_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cflicitatemplateminuta set ";
     $virgula = "";
     if(trim($this->l41_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l41_sequencial"])){ 
       $sql  .= $virgula." l41_sequencial = $this->l41_sequencial ";
       $virgula = ",";
       if(trim($this->l41_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "l41_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l41_cflicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l41_cflicita"])){ 
       $sql  .= $virgula." l41_cflicita = $this->l41_cflicita ";
       $virgula = ",";
       if(trim($this->l41_cflicita) == null ){ 
         $this->erro_sql = " Campo Código Licitação nao Informado.";
         $this->erro_campo = "l41_cflicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l41_db_documentotemplate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l41_db_documentotemplate"])){ 
       $sql  .= $virgula." l41_db_documentotemplate = $this->l41_db_documentotemplate ";
       $virgula = ",";
       if(trim($this->l41_db_documentotemplate) == null ){ 
         $this->erro_sql = " Campo Documento Template nao Informado.";
         $this->erro_campo = "l41_db_documentotemplate";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l41_sequencial!=null){
       $sql .= " l41_sequencial = $this->l41_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l41_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17276,'$this->l41_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l41_sequencial"]) || $this->l41_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3058,17276,'".AddSlashes(pg_result($resaco,$conresaco,'l41_sequencial'))."','$this->l41_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l41_cflicita"]) || $this->l41_cflicita != "")
           $resac = db_query("insert into db_acount values($acount,3058,17277,'".AddSlashes(pg_result($resaco,$conresaco,'l41_cflicita'))."','$this->l41_cflicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l41_db_documentotemplate"]) || $this->l41_db_documentotemplate != "")
           $resac = db_query("insert into db_acount values($acount,3058,17278,'".AddSlashes(pg_result($resaco,$conresaco,'l41_db_documentotemplate'))."','$this->l41_db_documentotemplate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cflicitatemplateminuta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cflicitatemplateminuta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l41_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l41_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17276,'$l41_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3058,17276,'','".AddSlashes(pg_result($resaco,$iresaco,'l41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3058,17277,'','".AddSlashes(pg_result($resaco,$iresaco,'l41_cflicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3058,17278,'','".AddSlashes(pg_result($resaco,$iresaco,'l41_db_documentotemplate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cflicitatemplateminuta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l41_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l41_sequencial = $l41_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cflicitatemplateminuta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cflicitatemplateminuta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l41_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cflicitatemplateminuta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $l41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cflicitatemplateminuta ";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = cflicitatemplateminuta.l41_cflicita";
     $sql .= "      inner join db_documentotemplate  on  db_documentotemplate.db82_sequencial = cflicitatemplateminuta.l41_db_documentotemplate";
     $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
     $sql .= "      inner join db_config  as a on   a.codigo = db_documentotemplate.db82_instit";
     $sql .= "      inner join db_documentotemplatetipo  on  db_documentotemplatetipo.db80_sequencial = db_documentotemplate.db82_templatetipo";
     $sql2 = "";
     if($dbwhere==""){
       if($l41_sequencial!=null ){
         $sql2 .= " where cflicitatemplateminuta.l41_sequencial = $l41_sequencial "; 
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
   function sql_query_file ( $l41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cflicitatemplateminuta ";
     $sql2 = "";
     if($dbwhere==""){
       if($l41_sequencial!=null ){
         $sql2 .= " where cflicitatemplateminuta.l41_sequencial = $l41_sequencial "; 
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