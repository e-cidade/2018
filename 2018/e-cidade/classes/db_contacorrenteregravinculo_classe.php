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

//MODULO: contabilidade
//CLASSE DA ENTIDADE contacorrenteregravinculo
class cl_contacorrenteregravinculo { 
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
   var $c27_sequencial = 0; 
   var $c27_contacorrente = 0; 
   var $c27_estrutural = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c27_sequencial = int4 = Sequencial vinculo conta corrente regra 
                 c27_contacorrente = int4 = Conta corrente 
                 c27_estrutural = varchar(15) = Estrutural para vinculo 
                 ";
   //funcao construtor da classe 
   function cl_contacorrenteregravinculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contacorrenteregravinculo"); 
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
       $this->c27_sequencial = ($this->c27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c27_sequencial"]:$this->c27_sequencial);
       $this->c27_contacorrente = ($this->c27_contacorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["c27_contacorrente"]:$this->c27_contacorrente);
       $this->c27_estrutural = ($this->c27_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["c27_estrutural"]:$this->c27_estrutural);
     }else{
       $this->c27_sequencial = ($this->c27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c27_sequencial"]:$this->c27_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c27_sequencial){ 
      $this->atualizacampos();
     if($this->c27_contacorrente == null ){ 
       $this->erro_sql = " Campo Conta corrente nao Informado.";
       $this->erro_campo = "c27_contacorrente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c27_estrutural == null ){ 
       $this->erro_sql = " Campo Estrutural para vinculo nao Informado.";
       $this->erro_campo = "c27_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c27_sequencial == "" || $c27_sequencial == null ){
       $result = db_query("select nextval('contacorrenteregravinculo_c27_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contacorrenteregravinculo_c27_sequencial_seq do campo: c27_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c27_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from contacorrenteregravinculo_c27_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c27_sequencial)){
         $this->erro_sql = " Campo c27_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c27_sequencial = $c27_sequencial; 
       }
     }
     if(($this->c27_sequencial == null) || ($this->c27_sequencial == "") ){ 
       $this->erro_sql = " Campo c27_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contacorrenteregravinculo(
                                       c27_sequencial 
                                      ,c27_contacorrente 
                                      ,c27_estrutural 
                       )
                values (
                                $this->c27_sequencial 
                               ,$this->c27_contacorrente 
                               ,'$this->c27_estrutural' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo regra conta corrente ($this->c27_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo regra conta corrente j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo regra conta corrente ($this->c27_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c27_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c27_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19662,'$this->c27_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3493,19662,'','".AddSlashes(pg_result($resaco,0,'c27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3493,19663,'','".AddSlashes(pg_result($resaco,0,'c27_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3493,19664,'','".AddSlashes(pg_result($resaco,0,'c27_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c27_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update contacorrenteregravinculo set ";
     $virgula = "";
     if(trim($this->c27_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c27_sequencial"])){ 
       $sql  .= $virgula." c27_sequencial = $this->c27_sequencial ";
       $virgula = ",";
       if(trim($this->c27_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo conta corrente regra nao Informado.";
         $this->erro_campo = "c27_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c27_contacorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c27_contacorrente"])){ 
       $sql  .= $virgula." c27_contacorrente = $this->c27_contacorrente ";
       $virgula = ",";
       if(trim($this->c27_contacorrente) == null ){ 
         $this->erro_sql = " Campo Conta corrente nao Informado.";
         $this->erro_campo = "c27_contacorrente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c27_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c27_estrutural"])){ 
       $sql  .= $virgula." c27_estrutural = '$this->c27_estrutural' ";
       $virgula = ",";
       if(trim($this->c27_estrutural) == null ){ 
         $this->erro_sql = " Campo Estrutural para vinculo nao Informado.";
         $this->erro_campo = "c27_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c27_sequencial!=null){
       $sql .= " c27_sequencial = $this->c27_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c27_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19662,'$this->c27_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c27_sequencial"]) || $this->c27_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3493,19662,'".AddSlashes(pg_result($resaco,$conresaco,'c27_sequencial'))."','$this->c27_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c27_contacorrente"]) || $this->c27_contacorrente != "")
           $resac = db_query("insert into db_acount values($acount,3493,19663,'".AddSlashes(pg_result($resaco,$conresaco,'c27_contacorrente'))."','$this->c27_contacorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c27_estrutural"]) || $this->c27_estrutural != "")
           $resac = db_query("insert into db_acount values($acount,3493,19664,'".AddSlashes(pg_result($resaco,$conresaco,'c27_estrutural'))."','$this->c27_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo regra conta corrente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c27_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo regra conta corrente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c27_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c27_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c27_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c27_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19662,'$c27_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3493,19662,'','".AddSlashes(pg_result($resaco,$iresaco,'c27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3493,19663,'','".AddSlashes(pg_result($resaco,$iresaco,'c27_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3493,19664,'','".AddSlashes(pg_result($resaco,$iresaco,'c27_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contacorrenteregravinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c27_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c27_sequencial = $c27_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo regra conta corrente nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c27_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo regra conta corrente nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c27_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c27_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:contacorrenteregravinculo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c27_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contacorrenteregravinculo ";
     $sql .= "      inner join contacorrente  on  contacorrente.c17_sequencial = contacorrenteregravinculo.c27_contacorrente";
     $sql2 = "";
     if($dbwhere==""){
       if($c27_sequencial!=null ){
         $sql2 .= " where contacorrenteregravinculo.c27_sequencial = $c27_sequencial "; 
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
   function sql_query_file ( $c27_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contacorrenteregravinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c27_sequencial!=null ){
         $sql2 .= " where contacorrenteregravinculo.c27_sequencial = $c27_sequencial "; 
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