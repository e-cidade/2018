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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meiimportalinha
class cl_meiimportalinha { 
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
   var $q105_sequencial = 0; 
   var $q105_meiimporta = 0; 
   var $q105_recibosolicitacao = null; 
   var $q105_cnpj = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q105_sequencial = int4 = Sequencial 
                 q105_meiimporta = int4 = Importação do MEI 
                 q105_recibosolicitacao = varchar(10) = Recibo de Solicitação 
                 q105_cnpj = varchar(14) = CNPJ Mei 
                 ";
   //funcao construtor da classe 
   function cl_meiimportalinha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportalinha"); 
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
       $this->q105_sequencial = ($this->q105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q105_sequencial"]:$this->q105_sequencial);
       $this->q105_meiimporta = ($this->q105_meiimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["q105_meiimporta"]:$this->q105_meiimporta);
       $this->q105_recibosolicitacao = ($this->q105_recibosolicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q105_recibosolicitacao"]:$this->q105_recibosolicitacao);
       $this->q105_cnpj = ($this->q105_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["q105_cnpj"]:$this->q105_cnpj);
     }else{
       $this->q105_sequencial = ($this->q105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q105_sequencial"]:$this->q105_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q105_sequencial){ 
      $this->atualizacampos();
     if($this->q105_meiimporta == null ){ 
       $this->erro_sql = " Campo Importação do MEI nao Informado.";
       $this->erro_campo = "q105_meiimporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q105_sequencial == "" || $q105_sequencial == null ){
       $result = db_query("select nextval('meiimportalinha_q105_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportalinha_q105_sequencial_seq do campo: q105_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q105_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportalinha_q105_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q105_sequencial)){
         $this->erro_sql = " Campo q105_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q105_sequencial = $q105_sequencial; 
       }
     }
     if(($this->q105_sequencial == null) || ($this->q105_sequencial == "") ){ 
       $this->erro_sql = " Campo q105_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportalinha(
                                       q105_sequencial 
                                      ,q105_meiimporta 
                                      ,q105_recibosolicitacao 
                                      ,q105_cnpj 
                       )
                values (
                                $this->q105_sequencial 
                               ,$this->q105_meiimporta 
                               ,'$this->q105_recibosolicitacao' 
                               ,'$this->q105_cnpj' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Importação por Linha do Arquivo MEI ($this->q105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Importação por Linha do Arquivo MEI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Importação por Linha do Arquivo MEI ($this->q105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q105_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q105_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16235,'$this->q105_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2848,16235,'','".AddSlashes(pg_result($resaco,0,'q105_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2848,16236,'','".AddSlashes(pg_result($resaco,0,'q105_meiimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2848,16237,'','".AddSlashes(pg_result($resaco,0,'q105_recibosolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2848,16238,'','".AddSlashes(pg_result($resaco,0,'q105_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q105_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportalinha set ";
     $virgula = "";
     if(trim($this->q105_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q105_sequencial"])){ 
       $sql  .= $virgula." q105_sequencial = $this->q105_sequencial ";
       $virgula = ",";
       if(trim($this->q105_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q105_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q105_meiimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q105_meiimporta"])){ 
       $sql  .= $virgula." q105_meiimporta = $this->q105_meiimporta ";
       $virgula = ",";
       if(trim($this->q105_meiimporta) == null ){ 
         $this->erro_sql = " Campo Importação do MEI nao Informado.";
         $this->erro_campo = "q105_meiimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q105_recibosolicitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q105_recibosolicitacao"])){ 
       $sql  .= $virgula." q105_recibosolicitacao = '$this->q105_recibosolicitacao' ";
       $virgula = ",";
     }
     if(trim($this->q105_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q105_cnpj"])){ 
       $sql  .= $virgula." q105_cnpj = '$this->q105_cnpj' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q105_sequencial!=null){
       $sql .= " q105_sequencial = $this->q105_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q105_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16235,'$this->q105_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q105_sequencial"]) || $this->q105_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2848,16235,'".AddSlashes(pg_result($resaco,$conresaco,'q105_sequencial'))."','$this->q105_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q105_meiimporta"]) || $this->q105_meiimporta != "")
           $resac = db_query("insert into db_acount values($acount,2848,16236,'".AddSlashes(pg_result($resaco,$conresaco,'q105_meiimporta'))."','$this->q105_meiimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q105_recibosolicitacao"]) || $this->q105_recibosolicitacao != "")
           $resac = db_query("insert into db_acount values($acount,2848,16237,'".AddSlashes(pg_result($resaco,$conresaco,'q105_recibosolicitacao'))."','$this->q105_recibosolicitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q105_cnpj"]) || $this->q105_cnpj != "")
           $resac = db_query("insert into db_acount values($acount,2848,16238,'".AddSlashes(pg_result($resaco,$conresaco,'q105_cnpj'))."','$this->q105_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação por Linha do Arquivo MEI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação por Linha do Arquivo MEI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q105_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q105_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16235,'$q105_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2848,16235,'','".AddSlashes(pg_result($resaco,$iresaco,'q105_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2848,16236,'','".AddSlashes(pg_result($resaco,$iresaco,'q105_meiimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2848,16237,'','".AddSlashes(pg_result($resaco,$iresaco,'q105_recibosolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2848,16238,'','".AddSlashes(pg_result($resaco,$iresaco,'q105_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportalinha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q105_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q105_sequencial = $q105_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação por Linha do Arquivo MEI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação por Linha do Arquivo MEI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q105_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportalinha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportalinha ";
     $sql .= "      inner join meiimporta  on  meiimporta.q104_sequencial = meiimportalinha.q105_meiimporta";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = meiimporta.q104_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($q105_sequencial!=null ){
         $sql2 .= " where meiimportalinha.q105_sequencial = $q105_sequencial "; 
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
   function sql_query_file ( $q105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportalinha ";
     $sql2 = "";
     if($dbwhere==""){
       if($q105_sequencial!=null ){
         $sql2 .= " where meiimportalinha.q105_sequencial = $q105_sequencial "; 
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