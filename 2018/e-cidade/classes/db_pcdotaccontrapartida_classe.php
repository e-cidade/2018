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

//MODULO: compras
//CLASSE DA ENTIDADE pcdotaccontrapartida
class cl_pcdotaccontrapartida { 
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
   var $pc19_sequencial = 0; 
   var $pc19_orctiporec = 0; 
   var $pc19_pcdotac = 0; 
   var $pc19_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc19_sequencial = int4 = C�digo Sequencial 
                 pc19_orctiporec = int4 = Contrapartida 
                 pc19_pcdotac = int4 = Dota��o 
                 pc19_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_pcdotaccontrapartida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcdotaccontrapartida"); 
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
       $this->pc19_sequencial = ($this->pc19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc19_sequencial"]:$this->pc19_sequencial);
       $this->pc19_orctiporec = ($this->pc19_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["pc19_orctiporec"]:$this->pc19_orctiporec);
       $this->pc19_pcdotac = ($this->pc19_pcdotac == ""?@$GLOBALS["HTTP_POST_VARS"]["pc19_pcdotac"]:$this->pc19_pcdotac);
       $this->pc19_valor = ($this->pc19_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["pc19_valor"]:$this->pc19_valor);
     }else{
       $this->pc19_sequencial = ($this->pc19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc19_sequencial"]:$this->pc19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc19_sequencial){ 
      $this->atualizacampos();
     if($this->pc19_orctiporec == null ){ 
       $this->pc19_orctiporec = "0";
     }
     if($this->pc19_pcdotac == null ){ 
       $this->erro_sql = " Campo Dota��o nao Informado.";
       $this->erro_campo = "pc19_pcdotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc19_valor == null ){ 
       $this->pc19_valor = "0";
     }
     if($pc19_sequencial == "" || $pc19_sequencial == null ){
       $result = db_query("select nextval('pcdotaccontrapartida_pc19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcdotaccontrapartida_pc19_sequencial_seq do campo: pc19_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcdotaccontrapartida_pc19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc19_sequencial)){
         $this->erro_sql = " Campo pc19_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc19_sequencial = $pc19_sequencial; 
       }
     }
     if(($this->pc19_sequencial == null) || ($this->pc19_sequencial == "") ){ 
       $this->erro_sql = " Campo pc19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcdotaccontrapartida(
                                       pc19_sequencial 
                                      ,pc19_orctiporec 
                                      ,pc19_pcdotac 
                                      ,pc19_valor 
                       )
                values (
                                $this->pc19_sequencial 
                               ,$this->pc19_orctiporec 
                               ,$this->pc19_pcdotac 
                               ,$this->pc19_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contrapartida da dota��es ($this->pc19_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contrapartida da dota��es j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contrapartida da dota��es ($this->pc19_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc19_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc19_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11916,'$this->pc19_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2059,11916,'','".AddSlashes(pg_result($resaco,0,'pc19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2059,11917,'','".AddSlashes(pg_result($resaco,0,'pc19_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2059,11918,'','".AddSlashes(pg_result($resaco,0,'pc19_pcdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2059,11919,'','".AddSlashes(pg_result($resaco,0,'pc19_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pcdotaccontrapartida set ";
     $virgula = "";
     if(trim($this->pc19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc19_sequencial"])){ 
       $sql  .= $virgula." pc19_sequencial = $this->pc19_sequencial ";
       $virgula = ",";
       if(trim($this->pc19_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "pc19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc19_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc19_orctiporec"])){ 
        if(trim($this->pc19_orctiporec)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc19_orctiporec"])){ 
           $this->pc19_orctiporec = "0" ; 
        } 
       $sql  .= $virgula." pc19_orctiporec = $this->pc19_orctiporec ";
       $virgula = ",";
     }
     if(trim($this->pc19_pcdotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc19_pcdotac"])){ 
       $sql  .= $virgula." pc19_pcdotac = $this->pc19_pcdotac ";
       $virgula = ",";
       if(trim($this->pc19_pcdotac) == null ){ 
         $this->erro_sql = " Campo Dota��o nao Informado.";
         $this->erro_campo = "pc19_pcdotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc19_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc19_valor"])){ 
        if(trim($this->pc19_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc19_valor"])){ 
           $this->pc19_valor = "0" ; 
        } 
       $sql  .= $virgula." pc19_valor = $this->pc19_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc19_sequencial!=null){
       $sql .= " pc19_sequencial = $this->pc19_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc19_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11916,'$this->pc19_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc19_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2059,11916,'".AddSlashes(pg_result($resaco,$conresaco,'pc19_sequencial'))."','$this->pc19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc19_orctiporec"]))
           $resac = db_query("insert into db_acount values($acount,2059,11917,'".AddSlashes(pg_result($resaco,$conresaco,'pc19_orctiporec'))."','$this->pc19_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc19_pcdotac"]))
           $resac = db_query("insert into db_acount values($acount,2059,11918,'".AddSlashes(pg_result($resaco,$conresaco,'pc19_pcdotac'))."','$this->pc19_pcdotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc19_valor"]))
           $resac = db_query("insert into db_acount values($acount,2059,11919,'".AddSlashes(pg_result($resaco,$conresaco,'pc19_valor'))."','$this->pc19_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contrapartida da dota��es nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc19_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contrapartida da dota��es nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc19_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc19_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11916,'$pc19_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2059,11916,'','".AddSlashes(pg_result($resaco,$iresaco,'pc19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2059,11917,'','".AddSlashes(pg_result($resaco,$iresaco,'pc19_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2059,11918,'','".AddSlashes(pg_result($resaco,$iresaco,'pc19_pcdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2059,11919,'','".AddSlashes(pg_result($resaco,$iresaco,'pc19_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcdotaccontrapartida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc19_sequencial = $pc19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contrapartida da dota��es nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc19_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contrapartida da dota��es nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcdotaccontrapartida";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcdotaccontrapartida ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = pcdotaccontrapartida.pc19_orctiporec";
     $sql2 = "";
     if($dbwhere==""){
       if($pc19_sequencial!=null ){
         $sql2 .= " where pcdotaccontrapartida.pc19_sequencial = $pc19_sequencial "; 
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
   function sql_query_file ( $pc19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcdotaccontrapartida ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc19_sequencial!=null ){
         $sql2 .= " where pcdotaccontrapartida.pc19_sequencial = $pc19_sequencial "; 
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