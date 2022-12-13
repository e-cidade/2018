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

//MODULO: compras
//CLASSE DA ENTIDADE pcsugforn
class cl_pcsugforn { 
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
   var $pc40_solic = 0; 
   var $pc40_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc40_solic = int4 = numero da solicitacao 
                 pc40_numcgm = int4 = numero do cgm do fornecedor 
                 ";
   //funcao construtor da classe 
   function cl_pcsugforn() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcsugforn"); 
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
       $this->pc40_solic = ($this->pc40_solic == ""?@$GLOBALS["HTTP_POST_VARS"]["pc40_solic"]:$this->pc40_solic);
       $this->pc40_numcgm = ($this->pc40_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc40_numcgm"]:$this->pc40_numcgm);
     }else{
       $this->pc40_solic = ($this->pc40_solic == ""?@$GLOBALS["HTTP_POST_VARS"]["pc40_solic"]:$this->pc40_solic);
       $this->pc40_numcgm = ($this->pc40_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc40_numcgm"]:$this->pc40_numcgm);
     }
   }
   // funcao para inclusao
   function incluir ($pc40_solic,$pc40_numcgm){ 
      $this->atualizacampos();
       $this->pc40_solic = $pc40_solic; 
       $this->pc40_numcgm = $pc40_numcgm; 
     if(($this->pc40_solic == null) || ($this->pc40_solic == "") ){ 
       $this->erro_sql = " Campo pc40_solic nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc40_numcgm == null) || ($this->pc40_numcgm == "") ){ 
       $this->erro_sql = " Campo pc40_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcsugforn(
                                       pc40_solic 
                                      ,pc40_numcgm 
                       )
                values (
                                $this->pc40_solic 
                               ,$this->pc40_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fornecedores sugeridos ($this->pc40_solic."-".$this->pc40_numcgm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fornecedores sugeridos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fornecedores sugeridos ($this->pc40_solic."-".$this->pc40_numcgm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc40_solic."-".$this->pc40_numcgm;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc40_solic,$this->pc40_numcgm));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2122,'$this->pc40_solic','I')");
       $resac = db_query("insert into db_acountkey values($acount,2123,'$this->pc40_numcgm','I')");
       $resac = db_query("insert into db_acount values($acount,343,2122,'','".AddSlashes(pg_result($resaco,0,'pc40_solic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,343,2123,'','".AddSlashes(pg_result($resaco,0,'pc40_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc40_solic=null,$pc40_numcgm=null) { 
      $this->atualizacampos();
     $sql = " update pcsugforn set ";
     $virgula = "";
     if(trim($this->pc40_solic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc40_solic"])){ 
       $sql  .= $virgula." pc40_solic = $this->pc40_solic ";
       $virgula = ",";
       if(trim($this->pc40_solic) == null ){ 
         $this->erro_sql = " Campo numero da solicitacao nao Informado.";
         $this->erro_campo = "pc40_solic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc40_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc40_numcgm"])){ 
        if(trim($this->pc40_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc40_numcgm"])){ 
           $this->pc40_numcgm = "0" ; 
        } 
       $sql  .= $virgula." pc40_numcgm = $this->pc40_numcgm ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc40_solic!=null){
       $sql .= " pc40_solic = $this->pc40_solic";
     }
     if($pc40_numcgm!=null){
       $sql .= " and  pc40_numcgm = $this->pc40_numcgm";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc40_solic,$this->pc40_numcgm));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2122,'$this->pc40_solic','A')");
         $resac = db_query("insert into db_acountkey values($acount,2123,'$this->pc40_numcgm','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc40_solic"]))
           $resac = db_query("insert into db_acount values($acount,343,2122,'".AddSlashes(pg_result($resaco,$conresaco,'pc40_solic'))."','$this->pc40_solic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc40_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,343,2123,'".AddSlashes(pg_result($resaco,$conresaco,'pc40_numcgm'))."','$this->pc40_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fornecedores sugeridos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc40_solic."-".$this->pc40_numcgm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fornecedores sugeridos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc40_solic."-".$this->pc40_numcgm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc40_solic."-".$this->pc40_numcgm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc40_solic=null,$pc40_numcgm=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc40_solic,$pc40_numcgm));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2122,'$pc40_solic','E')");
         $resac = db_query("insert into db_acountkey values($acount,2123,'$pc40_numcgm','E')");
         $resac = db_query("insert into db_acount values($acount,343,2122,'','".AddSlashes(pg_result($resaco,$iresaco,'pc40_solic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,343,2123,'','".AddSlashes(pg_result($resaco,$iresaco,'pc40_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcsugforn
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc40_solic != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc40_solic = $pc40_solic ";
        }
        if($pc40_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc40_numcgm = $pc40_numcgm ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fornecedores sugeridos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc40_solic."-".$pc40_numcgm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fornecedores sugeridos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc40_solic."-".$pc40_numcgm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc40_solic."-".$pc40_numcgm;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcsugforn";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc40_solic=null,$pc40_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcsugforn ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcsugforn.pc40_numcgm";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = pcsugforn.pc40_solic";
     $sql .= "      left  join solicitem  on  solicitem.pc11_numero = solicita.pc10_numero";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left  join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc40_solic!=null ){
         $sql2 .= " where pcsugforn.pc40_solic = $pc40_solic "; 
       } 
       if($pc40_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcsugforn.pc40_numcgm = $pc40_numcgm "; 
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
   function sql_query_file ( $pc40_solic=null,$pc40_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcsugforn ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc40_solic!=null ){
         $sql2 .= " where pcsugforn.pc40_solic = $pc40_solic "; 
       } 
       if($pc40_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcsugforn.pc40_numcgm = $pc40_numcgm "; 
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
  
  public function sql_query_dados_fornecedor( $pc40_solic=null,$pc40_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcsugforn ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcsugforn.pc40_numcgm";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = pcsugforn.pc40_solic";
     $sql2 = "";
     if($dbwhere==""){
       if($pc40_solic!=null ){
         $sql2 .= " where pcsugforn.pc40_solic = $pc40_solic "; 
       } 
       if($pc40_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcsugforn.pc40_numcgm = $pc40_numcgm "; 
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