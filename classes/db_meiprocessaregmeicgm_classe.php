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
//CLASSE DA ENTIDADE meiprocessaregmeicgm
class cl_meiprocessaregmeicgm { 
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
   var $q118_sequencial = 0; 
   var $q118_meicgm = 0; 
   var $q118_meiprocessareg = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q118_sequencial = int4 = Sequencial 
                 q118_meicgm = int4 = MEI 
                 q118_meiprocessareg = int4 = Registro de Processamento do MEI 
                 ";
   //funcao construtor da classe 
   function cl_meiprocessaregmeicgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiprocessaregmeicgm"); 
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
       $this->q118_sequencial = ($this->q118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q118_sequencial"]:$this->q118_sequencial);
       $this->q118_meicgm = ($this->q118_meicgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q118_meicgm"]:$this->q118_meicgm);
       $this->q118_meiprocessareg = ($this->q118_meiprocessareg == ""?@$GLOBALS["HTTP_POST_VARS"]["q118_meiprocessareg"]:$this->q118_meiprocessareg);
     }else{
       $this->q118_sequencial = ($this->q118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q118_sequencial"]:$this->q118_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q118_sequencial){ 
      $this->atualizacampos();
     if($this->q118_meicgm == null ){ 
       $this->erro_sql = " Campo MEI nao Informado.";
       $this->erro_campo = "q118_meicgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q118_meiprocessareg == null ){ 
       $this->erro_sql = " Campo Registro de Processamento do MEI nao Informado.";
       $this->erro_campo = "q118_meiprocessareg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q118_sequencial == "" || $q118_sequencial == null ){
       $result = db_query("select nextval('meiprocessaregmeicgm_q118_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiprocessaregmeicgm_q118_sequencial_seq do campo: q118_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q118_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiprocessaregmeicgm_q118_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q118_sequencial)){
         $this->erro_sql = " Campo q118_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q118_sequencial = $q118_sequencial; 
       }
     }
     if(($this->q118_sequencial == null) || ($this->q118_sequencial == "") ){ 
       $this->erro_sql = " Campo q118_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiprocessaregmeicgm(
                                       q118_sequencial 
                                      ,q118_meicgm 
                                      ,q118_meiprocessareg 
                       )
                values (
                                $this->q118_sequencial 
                               ,$this->q118_meicgm 
                               ,$this->q118_meiprocessareg 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros de processamento de inclusão do MEI ($this->q118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros de processamento de inclusão do MEI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros de processamento de inclusão do MEI ($this->q118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q118_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q118_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16649,'$this->q118_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2947,16649,'','".AddSlashes(pg_result($resaco,0,'q118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2947,16650,'','".AddSlashes(pg_result($resaco,0,'q118_meicgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2947,16651,'','".AddSlashes(pg_result($resaco,0,'q118_meiprocessareg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q118_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiprocessaregmeicgm set ";
     $virgula = "";
     if(trim($this->q118_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q118_sequencial"])){ 
       $sql  .= $virgula." q118_sequencial = $this->q118_sequencial ";
       $virgula = ",";
       if(trim($this->q118_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q118_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q118_meicgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q118_meicgm"])){ 
       $sql  .= $virgula." q118_meicgm = $this->q118_meicgm ";
       $virgula = ",";
       if(trim($this->q118_meicgm) == null ){ 
         $this->erro_sql = " Campo MEI nao Informado.";
         $this->erro_campo = "q118_meicgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q118_meiprocessareg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q118_meiprocessareg"])){ 
       $sql  .= $virgula." q118_meiprocessareg = $this->q118_meiprocessareg ";
       $virgula = ",";
       if(trim($this->q118_meiprocessareg) == null ){ 
         $this->erro_sql = " Campo Registro de Processamento do MEI nao Informado.";
         $this->erro_campo = "q118_meiprocessareg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q118_sequencial!=null){
       $sql .= " q118_sequencial = $this->q118_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q118_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16649,'$this->q118_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q118_sequencial"]) || $this->q118_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2947,16649,'".AddSlashes(pg_result($resaco,$conresaco,'q118_sequencial'))."','$this->q118_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q118_meicgm"]) || $this->q118_meicgm != "")
           $resac = db_query("insert into db_acount values($acount,2947,16650,'".AddSlashes(pg_result($resaco,$conresaco,'q118_meicgm'))."','$this->q118_meicgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q118_meiprocessareg"]) || $this->q118_meiprocessareg != "")
           $resac = db_query("insert into db_acount values($acount,2947,16651,'".AddSlashes(pg_result($resaco,$conresaco,'q118_meiprocessareg'))."','$this->q118_meiprocessareg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros de processamento de inclusão do MEI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros de processamento de inclusão do MEI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q118_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q118_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16649,'$q118_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2947,16649,'','".AddSlashes(pg_result($resaco,$iresaco,'q118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2947,16650,'','".AddSlashes(pg_result($resaco,$iresaco,'q118_meicgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2947,16651,'','".AddSlashes(pg_result($resaco,$iresaco,'q118_meiprocessareg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiprocessaregmeicgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q118_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q118_sequencial = $q118_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros de processamento de inclusão do MEI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros de processamento de inclusão do MEI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q118_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiprocessaregmeicgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiprocessaregmeicgm ";
     $sql .= "      inner join meiprocessareg  on  meiprocessareg.q112_sequencial = meiprocessaregmeicgm.q118_meiprocessareg";
     $sql .= "      inner join meicgm  on  meicgm.q115_sequencial = meiprocessaregmeicgm.q118_meicgm";
     $sql .= "      inner join meiimportameireg  on  meiimportameireg.q111_sequencial = meiprocessareg.q112_meiimportameireg";
     $sql .= "      inner join meiprocessa  on  meiprocessa.q113_sequencial = meiprocessareg.q112_meiprocessa";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = meicgm.q115_numcgm";
     $sql .= "      inner join meisituacao  on  meisituacao.q116_sequencial = meicgm.q115_meisitucao";
     $sql2 = "";
     if($dbwhere==""){
       if($q118_sequencial!=null ){
         $sql2 .= " where meiprocessaregmeicgm.q118_sequencial = $q118_sequencial "; 
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
   function sql_query_file ( $q118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiprocessaregmeicgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($q118_sequencial!=null ){
         $sql2 .= " where meiprocessaregmeicgm.q118_sequencial = $q118_sequencial "; 
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