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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_devfechaaplica
class cl_vac_devfechaaplica { 
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
   var $vc24_i_codigo = 0; 
   var $vc24_i_fechaaplica = 0; 
   var $vc24_i_devolucao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc24_i_codigo = int4 = código 
                 vc24_i_fechaaplica = int4 = fechamento aplicação 
                 vc24_i_devolucao = int4 = Devolução 
                 ";
   //funcao construtor da classe 
   function cl_vac_devfechaaplica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_devfechaaplica"); 
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
       $this->vc24_i_codigo = ($this->vc24_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc24_i_codigo"]:$this->vc24_i_codigo);
       $this->vc24_i_fechaaplica = ($this->vc24_i_fechaaplica == ""?@$GLOBALS["HTTP_POST_VARS"]["vc24_i_fechaaplica"]:$this->vc24_i_fechaaplica);
       $this->vc24_i_devolucao = ($this->vc24_i_devolucao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc24_i_devolucao"]:$this->vc24_i_devolucao);
     }else{
       $this->vc24_i_codigo = ($this->vc24_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc24_i_codigo"]:$this->vc24_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc24_i_codigo){ 
      $this->atualizacampos();
     if($this->vc24_i_fechaaplica == null ){ 
       $this->erro_sql = " Campo fechamento aplicação nao Informado.";
       $this->erro_campo = "vc24_i_fechaaplica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc24_i_devolucao == null ){ 
       $this->erro_sql = " Campo Devolução nao Informado.";
       $this->erro_campo = "vc24_i_devolucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc24_i_codigo == "" || $vc24_i_codigo == null ){
       $result = db_query("select nextval('vac_devfechaaplica_vc24_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_devfechaaplica_vc24_i_codigo_seq do campo: vc24_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc24_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_devfechaaplica_vc24_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc24_i_codigo)){
         $this->erro_sql = " Campo vc24_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc24_i_codigo = $vc24_i_codigo; 
       }
     }
     if(($this->vc24_i_codigo == null) || ($this->vc24_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc24_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_devfechaaplica(
                                       vc24_i_codigo 
                                      ,vc24_i_fechaaplica 
                                      ,vc24_i_devolucao 
                       )
                values (
                                $this->vc24_i_codigo 
                               ,$this->vc24_i_fechaaplica 
                               ,$this->vc24_i_devolucao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "devolução fecha aplicação ($this->vc24_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "devolução fecha aplicação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "devolução fecha aplicação ($this->vc24_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc24_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc24_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17574,'$this->vc24_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3104,17574,'','".AddSlashes(pg_result($resaco,0,'vc24_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3104,17575,'','".AddSlashes(pg_result($resaco,0,'vc24_i_fechaaplica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3104,17576,'','".AddSlashes(pg_result($resaco,0,'vc24_i_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc24_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_devfechaaplica set ";
     $virgula = "";
     if(trim($this->vc24_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc24_i_codigo"])){ 
       $sql  .= $virgula." vc24_i_codigo = $this->vc24_i_codigo ";
       $virgula = ",";
       if(trim($this->vc24_i_codigo) == null ){ 
         $this->erro_sql = " Campo código nao Informado.";
         $this->erro_campo = "vc24_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc24_i_fechaaplica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc24_i_fechaaplica"])){ 
       $sql  .= $virgula." vc24_i_fechaaplica = $this->vc24_i_fechaaplica ";
       $virgula = ",";
       if(trim($this->vc24_i_fechaaplica) == null ){ 
         $this->erro_sql = " Campo fechamento aplicação nao Informado.";
         $this->erro_campo = "vc24_i_fechaaplica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc24_i_devolucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc24_i_devolucao"])){ 
       $sql  .= $virgula." vc24_i_devolucao = $this->vc24_i_devolucao ";
       $virgula = ",";
       if(trim($this->vc24_i_devolucao) == null ){ 
         $this->erro_sql = " Campo Devolução nao Informado.";
         $this->erro_campo = "vc24_i_devolucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc24_i_codigo!=null){
       $sql .= " vc24_i_codigo = $this->vc24_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc24_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17574,'$this->vc24_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc24_i_codigo"]) || $this->vc24_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3104,17574,'".AddSlashes(pg_result($resaco,$conresaco,'vc24_i_codigo'))."','$this->vc24_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc24_i_fechaaplica"]) || $this->vc24_i_fechaaplica != "")
           $resac = db_query("insert into db_acount values($acount,3104,17575,'".AddSlashes(pg_result($resaco,$conresaco,'vc24_i_fechaaplica'))."','$this->vc24_i_fechaaplica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc24_i_devolucao"]) || $this->vc24_i_devolucao != "")
           $resac = db_query("insert into db_acount values($acount,3104,17576,'".AddSlashes(pg_result($resaco,$conresaco,'vc24_i_devolucao'))."','$this->vc24_i_devolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "devolução fecha aplicação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc24_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "devolução fecha aplicação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc24_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc24_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17574,'$vc24_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3104,17574,'','".AddSlashes(pg_result($resaco,$iresaco,'vc24_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3104,17575,'','".AddSlashes(pg_result($resaco,$iresaco,'vc24_i_fechaaplica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3104,17576,'','".AddSlashes(pg_result($resaco,$iresaco,'vc24_i_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_devfechaaplica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc24_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc24_i_codigo = $vc24_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "devolução fecha aplicação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc24_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "devolução fecha aplicação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc24_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc24_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_devfechaaplica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_devfechaaplica ";
     $sql .= "      inner join vac_fechaaplica  on  vac_fechaaplica.vc21_i_codigo = vac_devfechaaplica.vc24_i_fechaaplica";
     $sql .= "      inner join vac_devolucao  on  vac_devolucao.vc26_i_codigo = vac_devfechaaplica.vc24_i_devolucao";
     $sql .= "      inner join vac_aplicalote  on  vac_aplicalote.vc17_i_codigo = vac_fechaaplica.vc21_i_aplicalote";
     $sql .= "      inner join vac_fechamento  on  vac_fechamento.vc20_i_codigo = vac_fechaaplica.vc21_i_fechamento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_devolucao.vc26_i_usuario";
     $sql .= "      inner join vac_fechamento  as a on   a.vc20_i_codigo = vac_devolucao.vc26_i_fechamento";
     $sql2 = "";
     if($dbwhere==""){
       if($vc24_i_codigo!=null ){
         $sql2 .= " where vac_devfechaaplica.vc24_i_codigo = $vc24_i_codigo "; 
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
   function sql_query_file ( $vc24_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_devfechaaplica ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc24_i_codigo!=null ){
         $sql2 .= " where vac_devfechaaplica.vc24_i_codigo = $vc24_i_codigo "; 
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