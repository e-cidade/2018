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

//MODULO: projetos
//CLASSE DA ENTIDADE obrasenvioreg
class cl_obrasenvioreg { 
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
   var $ob17_codobrasenvioreg = 0; 
   var $ob17_codobrasenvio = 0; 
   var $ob17_codobra = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob17_codobrasenvioreg = int8 = Código 
                 ob17_codobrasenvio = int8 = Código do envio 
                 ob17_codobra = int4 = Código da obra 
                 ";
   //funcao construtor da classe 
   function cl_obrasenvioreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasenvioreg"); 
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
       $this->ob17_codobrasenvioreg = ($this->ob17_codobrasenvioreg == ""?@$GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvioreg"]:$this->ob17_codobrasenvioreg);
       $this->ob17_codobrasenvio = ($this->ob17_codobrasenvio == ""?@$GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvio"]:$this->ob17_codobrasenvio);
       $this->ob17_codobra = ($this->ob17_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob17_codobra"]:$this->ob17_codobra);
     }else{
       $this->ob17_codobrasenvioreg = ($this->ob17_codobrasenvioreg == ""?@$GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvioreg"]:$this->ob17_codobrasenvioreg);
       $this->ob17_codobrasenvio = ($this->ob17_codobrasenvio == ""?@$GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvio"]:$this->ob17_codobrasenvio);
       $this->ob17_codobra = ($this->ob17_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob17_codobra"]:$this->ob17_codobra);
     }
   }
   // funcao para inclusao
   function incluir ($ob17_codobrasenvioreg){ 
      $this->atualizacampos();
     if($ob17_codobrasenvioreg == "" || $ob17_codobrasenvioreg == null ){
       $result = db_query("select nextval('obrasenvioreg_ob17_codobrasenvioreg_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrasenvioreg_ob17_codobrasenvioreg_seq do campo: ob17_codobrasenvioreg"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob17_codobrasenvioreg = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrasenvioreg_ob17_codobrasenvioreg_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob17_codobrasenvioreg)){
         $this->erro_sql = " Campo ob17_codobrasenvioreg maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob17_codobrasenvioreg = $ob17_codobrasenvioreg; 
       }
     }
     if(($this->ob17_codobrasenvioreg == null) || ($this->ob17_codobrasenvioreg == "") ){ 
       $this->erro_sql = " Campo ob17_codobrasenvioreg nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasenvioreg(
                                       ob17_codobrasenvioreg 
                                      ,ob17_codobrasenvio 
                                      ,ob17_codobra 
                       )
                values (
                                $this->ob17_codobrasenvioreg 
                               ,$this->ob17_codobrasenvio 
                               ,$this->ob17_codobra 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro das obras enviadas ($this->ob17_codobrasenvioreg) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro das obras enviadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro das obras enviadas ($this->ob17_codobrasenvioreg) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob17_codobrasenvioreg;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob17_codobrasenvioreg));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6432,'$this->ob17_codobrasenvioreg','I')");
       $resac = db_query("insert into db_acount values($acount,1056,6432,'','".AddSlashes(pg_result($resaco,0,'ob17_codobrasenvioreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1056,6431,'','".AddSlashes(pg_result($resaco,0,'ob17_codobrasenvio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1056,6433,'','".AddSlashes(pg_result($resaco,0,'ob17_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob17_codobrasenvioreg=null) { 
      $this->atualizacampos();
     $sql = " update obrasenvioreg set ";
     $virgula = "";
     if(trim($this->ob17_codobrasenvioreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvioreg"])){ 
       $sql  .= $virgula." ob17_codobrasenvioreg = $this->ob17_codobrasenvioreg ";
       $virgula = ",";
       if(trim($this->ob17_codobrasenvioreg) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ob17_codobrasenvioreg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob17_codobrasenvio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvio"])){ 
       $sql  .= $virgula." ob17_codobrasenvio = $this->ob17_codobrasenvio ";
       $virgula = ",";
       if(trim($this->ob17_codobrasenvio) == null ){ 
         $this->erro_sql = " Campo Código do envio nao Informado.";
         $this->erro_campo = "ob17_codobrasenvio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob17_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob17_codobra"])){ 
       $sql  .= $virgula." ob17_codobra = $this->ob17_codobra ";
       $virgula = ",";
       if(trim($this->ob17_codobra) == null ){ 
         $this->erro_sql = " Campo Código da obra nao Informado.";
         $this->erro_campo = "ob17_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ob17_codobrasenvioreg!=null){
       $sql .= " ob17_codobrasenvioreg = $this->ob17_codobrasenvioreg";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob17_codobrasenvioreg));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6432,'$this->ob17_codobrasenvioreg','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvioreg"]))
           $resac = db_query("insert into db_acount values($acount,1056,6432,'".AddSlashes(pg_result($resaco,$conresaco,'ob17_codobrasenvioreg'))."','$this->ob17_codobrasenvioreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob17_codobrasenvio"]))
           $resac = db_query("insert into db_acount values($acount,1056,6431,'".AddSlashes(pg_result($resaco,$conresaco,'ob17_codobrasenvio'))."','$this->ob17_codobrasenvio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob17_codobra"]))
           $resac = db_query("insert into db_acount values($acount,1056,6433,'".AddSlashes(pg_result($resaco,$conresaco,'ob17_codobra'))."','$this->ob17_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro das obras enviadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob17_codobrasenvioreg;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro das obras enviadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob17_codobrasenvioreg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob17_codobrasenvioreg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob17_codobrasenvioreg=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob17_codobrasenvioreg));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6432,'$ob17_codobrasenvioreg','E')");
         $resac = db_query("insert into db_acount values($acount,1056,6432,'','".AddSlashes(pg_result($resaco,$iresaco,'ob17_codobrasenvioreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1056,6431,'','".AddSlashes(pg_result($resaco,$iresaco,'ob17_codobrasenvio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1056,6433,'','".AddSlashes(pg_result($resaco,$iresaco,'ob17_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrasenvioreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob17_codobrasenvioreg != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob17_codobrasenvioreg = $ob17_codobrasenvioreg ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro das obras enviadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob17_codobrasenvioreg;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro das obras enviadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob17_codobrasenvioreg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob17_codobrasenvioreg;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasenvioreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ob17_codobrasenvioreg=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasenvioreg ";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasenvioreg.ob17_codobra";
     $sql .= "      inner join obrasenvio  on  obrasenvio.ob16_codobrasenvio = obrasenvioreg.ob17_codobrasenvio";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = obrasenvio.ob16_login";
     $sql2 = "";
     if($dbwhere==""){
       if($ob17_codobrasenvioreg!=null ){
         $sql2 .= " where obrasenvioreg.ob17_codobrasenvioreg = $ob17_codobrasenvioreg "; 
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
   function sql_query_file ( $ob17_codobrasenvioreg=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasenvioreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob17_codobrasenvioreg!=null ){
         $sql2 .= " where obrasenvioreg.ob17_codobrasenvioreg = $ob17_codobrasenvioreg "; 
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