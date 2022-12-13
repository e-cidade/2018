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

//MODULO: orcamento
//CLASSE DA ENTIDADE orctiporecconveniosolicita
class cl_orctiporecconveniosolicita { 
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
   var $o78_sequencial = 0; 
   var $o78_solicita = 0; 
   var $o78_pactoplano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o78_sequencial = int4 = Sequencial 
                 o78_solicita = int4 = Solicita��o 
                 o78_pactoplano = int4 = C�digo Plano 
                 ";
   //funcao construtor da classe 
   function cl_orctiporecconveniosolicita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orctiporecconveniosolicita"); 
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
       $this->o78_sequencial = ($this->o78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o78_sequencial"]:$this->o78_sequencial);
       $this->o78_solicita = ($this->o78_solicita == ""?@$GLOBALS["HTTP_POST_VARS"]["o78_solicita"]:$this->o78_solicita);
       $this->o78_pactoplano = ($this->o78_pactoplano == ""?@$GLOBALS["HTTP_POST_VARS"]["o78_pactoplano"]:$this->o78_pactoplano);
     }else{
       $this->o78_sequencial = ($this->o78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o78_sequencial"]:$this->o78_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o78_sequencial){ 
      $this->atualizacampos();
     if($this->o78_solicita == null ){ 
       $this->erro_sql = " Campo Solicita��o nao Informado.";
       $this->erro_campo = "o78_solicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o78_pactoplano == null ){ 
       $this->erro_sql = " Campo C�digo Plano nao Informado.";
       $this->erro_campo = "o78_pactoplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o78_sequencial == "" || $o78_sequencial == null ){
       $result = db_query("select nextval('orctiporecconveniosolicita_o78_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orctiporecconveniosolicita_o78_sequencial_seq do campo: o78_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o78_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orctiporecconveniosolicita_o78_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o78_sequencial)){
         $this->erro_sql = " Campo o78_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o78_sequencial = $o78_sequencial; 
       }
     }
     if(($this->o78_sequencial == null) || ($this->o78_sequencial == "") ){ 
       $this->erro_sql = " Campo o78_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orctiporecconveniosolicita(
                                       o78_sequencial 
                                      ,o78_solicita 
                                      ,o78_pactoplano 
                       )
                values (
                                $this->o78_sequencial 
                               ,$this->o78_solicita 
                               ,$this->o78_pactoplano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "orctiporecconveniosolicita ($this->o78_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "orctiporecconveniosolicita j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "orctiporecconveniosolicita ($this->o78_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o78_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o78_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13884,'$this->o78_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2440,13884,'','".AddSlashes(pg_result($resaco,0,'o78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2440,13885,'','".AddSlashes(pg_result($resaco,0,'o78_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2440,13887,'','".AddSlashes(pg_result($resaco,0,'o78_pactoplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o78_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orctiporecconveniosolicita set ";
     $virgula = "";
     if(trim($this->o78_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o78_sequencial"])){ 
       $sql  .= $virgula." o78_sequencial = $this->o78_sequencial ";
       $virgula = ",";
       if(trim($this->o78_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o78_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o78_solicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o78_solicita"])){ 
       $sql  .= $virgula." o78_solicita = $this->o78_solicita ";
       $virgula = ",";
       if(trim($this->o78_solicita) == null ){ 
         $this->erro_sql = " Campo Solicita��o nao Informado.";
         $this->erro_campo = "o78_solicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o78_pactoplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o78_pactoplano"])){ 
       $sql  .= $virgula." o78_pactoplano = $this->o78_pactoplano ";
       $virgula = ",";
       if(trim($this->o78_pactoplano) == null ){ 
         $this->erro_sql = " Campo C�digo Plano nao Informado.";
         $this->erro_campo = "o78_pactoplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o78_sequencial!=null){
       $sql .= " o78_sequencial = $this->o78_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o78_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13884,'$this->o78_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o78_sequencial"]) || $this->o78_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2440,13884,'".AddSlashes(pg_result($resaco,$conresaco,'o78_sequencial'))."','$this->o78_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o78_solicita"]) || $this->o78_solicita != "")
           $resac = db_query("insert into db_acount values($acount,2440,13885,'".AddSlashes(pg_result($resaco,$conresaco,'o78_solicita'))."','$this->o78_solicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o78_pactoplano"]) || $this->o78_pactoplano != "")
           $resac = db_query("insert into db_acount values($acount,2440,13887,'".AddSlashes(pg_result($resaco,$conresaco,'o78_pactoplano'))."','$this->o78_pactoplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orctiporecconveniosolicita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o78_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orctiporecconveniosolicita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o78_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o78_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o78_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o78_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13884,'$o78_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2440,13884,'','".AddSlashes(pg_result($resaco,$iresaco,'o78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2440,13885,'','".AddSlashes(pg_result($resaco,$iresaco,'o78_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2440,13887,'','".AddSlashes(pg_result($resaco,$iresaco,'o78_pactoplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orctiporecconveniosolicita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o78_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o78_sequencial = $o78_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orctiporecconveniosolicita nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o78_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orctiporecconveniosolicita nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o78_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o78_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orctiporecconveniosolicita";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orctiporecconveniosolicita ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = orctiporecconveniosolicita.o78_solicita";
     $sql .= "      inner join pactoplano  on  pactoplano.o74_sequencial = orctiporecconveniosolicita.o78_pactoplano";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join orctiporecconvenio  on  orctiporecconvenio.o16_sequencial = pactoplano.o74_orctiporecconvenio";
     $sql2 = "";
     if($dbwhere==""){
       if($o78_sequencial!=null ){
         $sql2 .= " where orctiporecconveniosolicita.o78_sequencial = $o78_sequencial "; 
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
   function sql_query_file ( $o78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orctiporecconveniosolicita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o78_sequencial!=null ){
         $sql2 .= " where orctiporecconveniosolicita.o78_sequencial = $o78_sequencial "; 
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