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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueitemlancconf
class cl_matestoqueitemlancconf { 
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
   var $m96_codlanc = 0; 
   var $m96_id_usuario = 0; 
   var $m96_data_dia = null; 
   var $m96_data_mes = null; 
   var $m96_data_ano = null; 
   var $m96_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m96_codlanc = int8 = Código sequencial do lançamento 
                 m96_id_usuario = int4 = Cod. Usuário 
                 m96_data = date = Data de entrada 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitemlancconf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemlancconf"); 
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
       $this->m96_codlanc = ($this->m96_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m96_codlanc"]:$this->m96_codlanc);
       $this->m96_id_usuario = ($this->m96_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["m96_id_usuario"]:$this->m96_id_usuario);
       if($this->m96_data == ""){
         $this->m96_data_dia = ($this->m96_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m96_data_dia"]:$this->m96_data_dia);
         $this->m96_data_mes = ($this->m96_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m96_data_mes"]:$this->m96_data_mes);
         $this->m96_data_ano = ($this->m96_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m96_data_ano"]:$this->m96_data_ano);
         if($this->m96_data_dia != ""){
            $this->m96_data = $this->m96_data_ano."-".$this->m96_data_mes."-".$this->m96_data_dia;
         }
       }
     }else{
       $this->m96_codlanc = ($this->m96_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m96_codlanc"]:$this->m96_codlanc);
     }
   }
   // funcao para inclusao
   function incluir ($m96_codlanc){ 
      $this->atualizacampos();
     if($this->m96_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "m96_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m96_data == null ){ 
       $this->erro_sql = " Campo Data de entrada nao Informado.";
       $this->erro_campo = "m96_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m96_codlanc = $m96_codlanc; 
     if(($this->m96_codlanc == null) || ($this->m96_codlanc == "") ){ 
       $this->erro_sql = " Campo m96_codlanc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemlancconf(
                                       m96_codlanc 
                                      ,m96_id_usuario 
                                      ,m96_data 
                       )
                values (
                                $this->m96_codlanc 
                               ,$this->m96_id_usuario 
                               ,".($this->m96_data == "null" || $this->m96_data == ""?"null":"'".$this->m96_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens retirados da matestoqueitemlanc ($this->m96_codlanc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens retirados da matestoqueitemlanc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens retirados da matestoqueitemlanc ($this->m96_codlanc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m96_codlanc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m96_codlanc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9649,'$this->m96_codlanc','I')");
       $resac = db_query("insert into db_acount values($acount,1661,9649,'','".AddSlashes(pg_result($resaco,0,'m96_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1661,9650,'','".AddSlashes(pg_result($resaco,0,'m96_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1661,9651,'','".AddSlashes(pg_result($resaco,0,'m96_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m96_codlanc=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitemlancconf set ";
     $virgula = "";
     if(trim($this->m96_codlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m96_codlanc"])){ 
       $sql  .= $virgula." m96_codlanc = $this->m96_codlanc ";
       $virgula = ",";
       if(trim($this->m96_codlanc) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m96_codlanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m96_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m96_id_usuario"])){ 
       $sql  .= $virgula." m96_id_usuario = $this->m96_id_usuario ";
       $virgula = ",";
       if(trim($this->m96_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "m96_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m96_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m96_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m96_data_dia"] !="") ){ 
       $sql  .= $virgula." m96_data = '$this->m96_data' ";
       $virgula = ",";
       if(trim($this->m96_data) == null ){ 
         $this->erro_sql = " Campo Data de entrada nao Informado.";
         $this->erro_campo = "m96_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m96_data_dia"])){ 
         $sql  .= $virgula." m96_data = null ";
         $virgula = ",";
         if(trim($this->m96_data) == null ){ 
           $this->erro_sql = " Campo Data de entrada nao Informado.";
           $this->erro_campo = "m96_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($m96_codlanc!=null){
       $sql .= " m96_codlanc = $this->m96_codlanc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m96_codlanc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9649,'$this->m96_codlanc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m96_codlanc"]))
           $resac = db_query("insert into db_acount values($acount,1661,9649,'".AddSlashes(pg_result($resaco,$conresaco,'m96_codlanc'))."','$this->m96_codlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m96_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1661,9650,'".AddSlashes(pg_result($resaco,$conresaco,'m96_id_usuario'))."','$this->m96_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m96_data"]))
           $resac = db_query("insert into db_acount values($acount,1661,9651,'".AddSlashes(pg_result($resaco,$conresaco,'m96_data'))."','$this->m96_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens retirados da matestoqueitemlanc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m96_codlanc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens retirados da matestoqueitemlanc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m96_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m96_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m96_codlanc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m96_codlanc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9649,'$m96_codlanc','E')");
         $resac = db_query("insert into db_acount values($acount,1661,9649,'','".AddSlashes(pg_result($resaco,$iresaco,'m96_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1661,9650,'','".AddSlashes(pg_result($resaco,$iresaco,'m96_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1661,9651,'','".AddSlashes(pg_result($resaco,$iresaco,'m96_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemlancconf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m96_codlanc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m96_codlanc = $m96_codlanc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens retirados da matestoqueitemlanc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m96_codlanc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens retirados da matestoqueitemlanc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m96_codlanc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m96_codlanc;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemlancconf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m96_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlancconf ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueitemlancconf.m96_id_usuario";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemlancconf.m96_codlanc";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m96_codlanc!=null ){
         $sql2 .= " where matestoqueitemlancconf.m96_codlanc = $m96_codlanc "; 
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
   function sql_query_file ( $m96_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlancconf ";
     $sql2 = "";
     if($dbwhere==""){
       if($m96_codlanc!=null ){
         $sql2 .= " where matestoqueitemlancconf.m96_codlanc = $m96_codlanc "; 
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