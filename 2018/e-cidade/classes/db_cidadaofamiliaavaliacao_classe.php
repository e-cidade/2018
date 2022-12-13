<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: social
//CLASSE DA ENTIDADE cidadaofamiliaavaliacao
class cl_cidadaofamiliaavaliacao { 
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
   var $as06_sequencial = 0; 
   var $as06_cidadaofamilia = 0; 
   var $as06_avaliacaogruporesposta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as06_sequencial = int4 = Código 
                 as06_cidadaofamilia = int4 = Cidadão Família 
                 as06_avaliacaogruporesposta = int4 = Avaliação 
                 ";
   //funcao construtor da classe 
   function cl_cidadaofamiliaavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaofamiliaavaliacao"); 
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
       $this->as06_sequencial = ($this->as06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as06_sequencial"]:$this->as06_sequencial);
       $this->as06_cidadaofamilia = ($this->as06_cidadaofamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["as06_cidadaofamilia"]:$this->as06_cidadaofamilia);
       $this->as06_avaliacaogruporesposta = ($this->as06_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["as06_avaliacaogruporesposta"]:$this->as06_avaliacaogruporesposta);
     }else{
       $this->as06_sequencial = ($this->as06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as06_sequencial"]:$this->as06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as06_sequencial){ 
      $this->atualizacampos();
     if($this->as06_cidadaofamilia == null ){ 
       $this->erro_sql = " Campo Cidadão Família nao Informado.";
       $this->erro_campo = "as06_cidadaofamilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as06_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Avaliação nao Informado.";
       $this->erro_campo = "as06_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as06_sequencial == "" || $as06_sequencial == null ){
       $result = db_query("select nextval('cidadaofamiliaavaliacao_as06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaofamiliaavaliacao_as06_sequencial_seq do campo: as06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaofamiliaavaliacao_as06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as06_sequencial)){
         $this->erro_sql = " Campo as06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as06_sequencial = $as06_sequencial; 
       }
     }
     if(($this->as06_sequencial == null) || ($this->as06_sequencial == "") ){ 
       $this->erro_sql = " Campo as06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaofamiliaavaliacao(
                                       as06_sequencial 
                                      ,as06_cidadaofamilia 
                                      ,as06_avaliacaogruporesposta 
                       )
                values (
                                $this->as06_sequencial 
                               ,$this->as06_cidadaofamilia 
                               ,$this->as06_avaliacaogruporesposta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaofamiliaavaliacao ($this->as06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaofamiliaavaliacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaofamiliaavaliacao ($this->as06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->as06_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19087,'$this->as06_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3396,19087,'','".AddSlashes(pg_result($resaco,0,'as06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3396,19088,'','".AddSlashes(pg_result($resaco,0,'as06_cidadaofamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3396,19089,'','".AddSlashes(pg_result($resaco,0,'as06_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as06_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaofamiliaavaliacao set ";
     $virgula = "";
     if(trim($this->as06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as06_sequencial"])){ 
       $sql  .= $virgula." as06_sequencial = $this->as06_sequencial ";
       $virgula = ",";
       if(trim($this->as06_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as06_cidadaofamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as06_cidadaofamilia"])){ 
       $sql  .= $virgula." as06_cidadaofamilia = $this->as06_cidadaofamilia ";
       $virgula = ",";
       if(trim($this->as06_cidadaofamilia) == null ){ 
         $this->erro_sql = " Campo Cidadão Família nao Informado.";
         $this->erro_campo = "as06_cidadaofamilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as06_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as06_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." as06_avaliacaogruporesposta = $this->as06_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->as06_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Avaliação nao Informado.";
         $this->erro_campo = "as06_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as06_sequencial!=null){
       $sql .= " as06_sequencial = $this->as06_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {
      
       $resaco = $this->sql_record($this->sql_query_file($this->as06_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19087,'$this->as06_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as06_sequencial"]) || $this->as06_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3396,19087,'".AddSlashes(pg_result($resaco,$conresaco,'as06_sequencial'))."','$this->as06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as06_cidadaofamilia"]) || $this->as06_cidadaofamilia != "")
             $resac = db_query("insert into db_acount values($acount,3396,19088,'".AddSlashes(pg_result($resaco,$conresaco,'as06_cidadaofamilia'))."','$this->as06_cidadaofamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as06_avaliacaogruporesposta"]) || $this->as06_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,3396,19089,'".AddSlashes(pg_result($resaco,$conresaco,'as06_avaliacaogruporesposta'))."','$this->as06_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaofamiliaavaliacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaofamiliaavaliacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as06_sequencial=null,$dbwhere=null) { 
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($as06_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19087,'$as06_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3396,19087,'','".AddSlashes(pg_result($resaco,$iresaco,'as06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3396,19088,'','".AddSlashes(pg_result($resaco,$iresaco,'as06_cidadaofamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3396,19089,'','".AddSlashes(pg_result($resaco,$iresaco,'as06_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaofamiliaavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as06_sequencial = $as06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaofamiliaavaliacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaofamiliaavaliacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaofamiliaavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofamiliaavaliacao ";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = cidadaofamiliaavaliacao.as06_avaliacaogruporesposta";
     $sql .= "      inner join cidadaofamilia  on  cidadaofamilia.as04_sequencial = cidadaofamiliaavaliacao.as06_cidadaofamilia";
     $sql2 = "";
     if($dbwhere==""){
       if($as06_sequencial!=null ){
         $sql2 .= " where cidadaofamiliaavaliacao.as06_sequencial = $as06_sequencial "; 
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
   function sql_query_file ( $as06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofamiliaavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($as06_sequencial!=null ){
         $sql2 .= " where cidadaofamiliaavaliacao.as06_sequencial = $as06_sequencial "; 
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