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

//MODULO: patrim
//CLASSE DA ENTIDADE benscomissaousu
class cl_benscomissaousu { 
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
   var $t61_codcom = 0; 
   var $t61_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t61_codcom = int8 = Código da comissão 
                 t61_id_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_benscomissaousu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benscomissaousu"); 
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
       $this->t61_codcom = ($this->t61_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["t61_codcom"]:$this->t61_codcom);
       $this->t61_id_usuario = ($this->t61_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t61_id_usuario"]:$this->t61_id_usuario);
     }else{
       $this->t61_codcom = ($this->t61_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["t61_codcom"]:$this->t61_codcom);
       $this->t61_id_usuario = ($this->t61_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t61_id_usuario"]:$this->t61_id_usuario);
     }
   }
   // funcao para inclusao
   function incluir ($t61_codcom,$t61_id_usuario){ 
      $this->atualizacampos();
       $this->t61_codcom = $t61_codcom; 
       $this->t61_id_usuario = $t61_id_usuario; 
     if(($this->t61_codcom == null) || ($this->t61_codcom == "") ){ 
       $this->erro_sql = " Campo t61_codcom nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->t61_id_usuario == null) || ($this->t61_id_usuario == "") ){ 
       $this->erro_sql = " Campo t61_id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benscomissaousu(
                                       t61_codcom 
                                      ,t61_id_usuario 
                       )
                values (
                                $this->t61_codcom 
                               ,$this->t61_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Usuários da comissão ($this->t61_codcom."-".$this->t61_id_usuario) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Usuários da comissão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Usuários da comissão ($this->t61_codcom."-".$this->t61_id_usuario) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t61_codcom."-".$this->t61_id_usuario;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t61_codcom,$this->t61_id_usuario));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5799,'$this->t61_codcom','I')");
       $resac = db_query("insert into db_acountkey values($acount,5800,'$this->t61_id_usuario','I')");
       $resac = db_query("insert into db_acount values($acount,921,5799,'','".AddSlashes(pg_result($resaco,0,'t61_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,921,5800,'','".AddSlashes(pg_result($resaco,0,'t61_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t61_codcom=null,$t61_id_usuario=null) { 
      $this->atualizacampos();
     $sql = " update benscomissaousu set ";
     $virgula = "";
     if(trim($this->t61_codcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t61_codcom"])){ 
       $sql  .= $virgula." t61_codcom = $this->t61_codcom ";
       $virgula = ",";
       if(trim($this->t61_codcom) == null ){ 
         $this->erro_sql = " Campo Código da comissão nao Informado.";
         $this->erro_campo = "t61_codcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t61_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t61_id_usuario"])){ 
       $sql  .= $virgula." t61_id_usuario = $this->t61_id_usuario ";
       $virgula = ",";
       if(trim($this->t61_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t61_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t61_codcom!=null){
       $sql .= " t61_codcom = $this->t61_codcom";
     }
     if($t61_id_usuario!=null){
       $sql .= " and  t61_id_usuario = $this->t61_id_usuario";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t61_codcom,$this->t61_id_usuario));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5799,'$this->t61_codcom','A')");
         $resac = db_query("insert into db_acountkey values($acount,5800,'$this->t61_id_usuario','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t61_codcom"]))
           $resac = db_query("insert into db_acount values($acount,921,5799,'".AddSlashes(pg_result($resaco,$conresaco,'t61_codcom'))."','$this->t61_codcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t61_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,921,5800,'".AddSlashes(pg_result($resaco,$conresaco,'t61_id_usuario'))."','$this->t61_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuários da comissão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t61_codcom."-".$this->t61_id_usuario;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usuários da comissão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t61_codcom."-".$this->t61_id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t61_codcom."-".$this->t61_id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t61_codcom=null,$t61_id_usuario=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t61_codcom,$t61_id_usuario));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5799,'$t61_codcom','E')");
         $resac = db_query("insert into db_acountkey values($acount,5800,'$t61_id_usuario','E')");
         $resac = db_query("insert into db_acount values($acount,921,5799,'','".AddSlashes(pg_result($resaco,$iresaco,'t61_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,921,5800,'','".AddSlashes(pg_result($resaco,$iresaco,'t61_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benscomissaousu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t61_codcom != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t61_codcom = $t61_codcom ";
        }
        if($t61_id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t61_id_usuario = $t61_id_usuario ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuários da comissão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t61_codcom."-".$t61_id_usuario;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usuários da comissão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t61_codcom."-".$t61_id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t61_codcom."-".$t61_id_usuario;
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
        $this->erro_sql   = "Record Vazio na Tabela:benscomissaousu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t61_codcom=null,$t61_id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscomissaousu ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benscomissaousu.t61_id_usuario";
     $sql .= "      inner join benscomissao  on  benscomissao.t60_codcom = benscomissaousu.t61_codcom";
     $sql .= "      inner join db_usuarios as a  on  a.id_usuario = benscomissao.t60_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($t61_codcom!=null ){
         $sql2 .= " where benscomissaousu.t61_codcom = $t61_codcom "; 
       } 
       if($t61_id_usuario!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " benscomissaousu.t61_id_usuario = $t61_id_usuario "; 
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
   function sql_query_file ( $t61_codcom=null,$t61_id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscomissaousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($t61_codcom!=null ){
         $sql2 .= " where benscomissaousu.t61_codcom = $t61_codcom "; 
       } 
       if($t61_id_usuario!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " benscomissaousu.t61_id_usuario = $t61_id_usuario "; 
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